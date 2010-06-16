<?php
/*
Plugin Name: Malaria No More Plugin
Plugin URI: http://www.guyro.com/malaria-no-more-plugin
Description: The Malaria No More Plugin adds a customizable widget which displays the latest posts and updates from MalariaNoMore.org. It can be integrated anywhere in the blog. This Newsticker shows up the last five or more posts and is a great solution to help spread the word about Malaria and what people can do to help, as well as what others are doing about it.
Version: 1.0
Author: Guy Roman
Author URI: http://www.guyro.com
License: GPL3
*/

function malariaupdate()
{
  $options = get_option("widget_malariaupdate");
  if (!is_array($options)){
    $options = array(
      'title' => 'Malaria News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://malarianomore.org/blog/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_malariaupdate($args)
{
  extract($args);
  
  $options = get_option("widget_malariaupdate");
  if (!is_array($options)){
    $options = array(
      'title' => 'Malaria News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  malariaupdate();
  echo $after_widget;
}

function malariaupdate_control()
{
  $options = get_option("widget_malariaupdate");
  if (!is_array($options)){
    $options = array(
      'title' => 'Malaria News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['malariaupdate-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['malariaupdate-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['malariaupdate-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['malariaupdate-CharCount']);
    update_option("widget_malariaupdate", $options);
  }
?> 
  <p>
    <label for="malariaupdate-WidgetTitle">Widget Title: </label>
    <input type="text" id="malariaupdate-WidgetTitle" name="malariaupdate-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="malariaupdate-NewsCount">Max. News: </label>
    <input type="text" id="malariaupdate-NewsCount" name="malariaupdate-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="malariaupdate-CharCount">Max. Characters: </label>
    <input type="text" id="malariaupdate-CharCount" name="malariaupdate-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="malariaupdate-Submit"  name="malariaupdate-Submit" value="1" />
  </p>
  
<?php
}

function malariaupdate_init()
{
  register_sidebar_widget(__('Malaria News'), 'widget_malariaupdate');    
  register_widget_control('Malaria News', 'malariaupdate_control', 300, 200);
}
add_action("plugins_loaded", "malariaupdate_init");
?>