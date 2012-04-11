<?php

global $sl_dir, $sl_base, $sl_path, $sl_upload_path, $sl_upload_base;

$text_domain=SLPLUS_PREFIX;
$prefix = SLPLUS_PREFIX;

$sl_dir =SLPLUS_PLUGINDIR;  //plugin absolute server directory name
$sl_base=SLPLUS_PLUGINURL;  //URL to plugin directory
$sl_path=ABSPATH.'wp-content/plugins/'.$sl_dir; //absolute server path to plugin directory
$sl_upload_path=ABSPATH.'wp-content/uploads/sl-uploads'; //absolute server path to store locator uploads directory

$view_link="| <a href='".SLPLUS_ADMINPAGE."view-locations.php'>".
    __("Manage Locations", SLPLUS_PREFIX)."</a>";
    

$web_domain=$_SERVER['HTTP_HOST'];
$slp_enc_key=md5(get_option(SLPLUS_PREFIX.'-license_key'));
$map_character_encoding=(get_option('sl_map_character_encoding')!="")? 
    "&amp;oe=".get_option('sl_map_character_encoding') : 
    "";
$sl_upload_base=get_option('siteurl')."/wp-content/uploads/sl-uploads"; //URL to store locator uploads directory

