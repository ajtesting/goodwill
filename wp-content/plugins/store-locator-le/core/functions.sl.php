<?php
/****************************************************************************
 ** file: functions.sl.php
 **
 ** The collection of main core functions for Store Locator Plus
 ***************************************************************************/


/* -----------------*/
function move_upload_directories() {
	global $sl_upload_path, $sl_path;
	
	if (!is_dir(ABSPATH . "wp-content/uploads")) {
		mkdir(ABSPATH . "wp-content/uploads", 0755);
	}
	if (!is_dir($sl_upload_path)) {
		mkdir($sl_upload_path, 0755);
	}
	if (!is_dir($sl_upload_path . "/custom-icons")) {
		mkdir($sl_upload_path . "/custom-icons", 0755);
	}
	if (!is_dir($sl_upload_path . "/custom-css")) {
		mkdir($sl_upload_path . "/custom-css", 0755);
	}
	
	if (is_dir($sl_path . "/languages") && !is_dir($sl_upload_path . "/languages")) {
		csl_copyr($sl_path . "/languages", $sl_upload_path . "/languages");
	}
	if (is_dir($sl_path . "/images") && !is_dir($sl_upload_path . "/images")) {
		csl_copyr($sl_path . "/images", $sl_upload_path . "/images");
	}
}

/*-----------------*/

function initialize_variables() {
    global $height, $width, $width_units, $height_units, $radii;
    global $icon, $icon2, $google_map_domain, $google_map_country, $theme, $sl_base, $sl_upload_base, $location_table_view;
    global $search_label, $zoom_level, $zoom_tweak, $sl_use_city_search, $sl_use_name_search, $sl_default_map;
    global $sl_radius_label, $sl_website_label, $sl_num_initial_displayed, $sl_load_locations_default;
    global $sl_distance_unit, $sl_map_overview_control, $sl_admin_locations_per_page, $sl_instruction_message;
    global $sl_map_character_encoding, $sl_use_country_search, $slplus_show_state_pd;
    
    $sl_map_character_encoding=get_option('sl_map_character_encoding');
    if (empty($sl_map_character_encoding)) {
        $sl_map_character_encoding="";
        add_option('sl_map_character_encoding', $sl_map_character_encoding);
        }
    $sl_instruction_message=get_option('sl_instruction_message');
    if (empty($sl_instruction_message)) {
        $sl_instruction_message="Enter Your Address or Zip Code Above.";
        add_option('sl_instruction_message', $sl_instruction_message);
        }
    $sl_admin_locations_per_page=get_option('sl_admin_locations_per_page');
    if (empty($sl_admin_locations_per_page)) {
        $sl_admin_locations_per_page="100";
        add_option('sl_admin_locations_per_page', $sl_admin_locations_per_page);
        }
    $sl_map_overview_control=get_option('sl_map_overview_control');
    if (empty($sl_map_overview_control)) {
        $sl_map_overview_control="0";
        add_option('sl_map_overview_control', $sl_map_overview_control);
        }
    $sl_distance_unit=get_option('sl_distance_unit');
    if (empty($sl_distance_unit)) {
        $sl_distance_unit="miles";
        add_option('sl_distance_unit', $sl_distance_unit);
        }
    $sl_load_locations_default=get_option('sl_load_locations_default');
    if (empty($sl_load_locations_default)) {
        $sl_load_locations_default="1";
        add_option('sl_load_locations_default', $sl_load_locations_default);
        }
    $sl_num_initial_displayed=get_option('sl_num_initial_displayed');
    if (empty($sl_num_initial_displayed)) {
        $sl_num_initial_displayed="25";
        add_option('sl_num_initial_displayed', $sl_num_initial_displayed);
        }
    $sl_website_label=get_option('sl_website_label');
    if (empty($sl_website_label)) {
        $sl_website_label="Website";
        add_option('sl_website_label', $sl_website_label);
        }
    $sl_radius_label=get_option('sl_radius_label');
    if (empty($sl_radius_label)) {
        $sl_radius_label="Radius";
        add_option('sl_radius_label', $sl_radius_label);
        }
    $sl_map_type=get_option('sl_map_type');
    if (empty($sl_map_type)) {
        $sl_map_type='G_NORMAL_MAP';
        add_option('sl_map_type', $sl_map_type);
        }
    $sl_remove_credits=get_option('sl_remove_credits');
    if (empty($sl_remove_credits)) {
        $sl_remove_credits="0";
        add_option('sl_remove_credits', $sl_remove_credits);
        }
    $sl_use_name_search=get_option('sl_use_name_search');
    if (empty($sl_use_name_search)) {
        $sl_use_name_search="0";
        add_option('sl_use_name_search', $sl_use_name_search);
        }
    $sl_use_city_search=get_option('sl_use_city_search');
    if (empty($sl_use_city_search)) {
        $sl_use_city_search="1";
        add_option('sl_use_city_search', $sl_use_city_search);
        }
    $sl_use_country_search=get_option('sl_use_country_search');
    if (empty($sl_use_country_search)) {
        $sl_use_country_search="1";
        add_option('sl_use_country_search', $sl_use_country_search);
        }
    $slplus_show_state_pd=get_option('slplus_show_state_pd');
    if (empty($slplus_show_state_pd)) {
        $slplus_show_state_pd="1";
        add_option('slplus_show_state_pd', $slplus_show_state_pd);
        }
    $zoom_level=get_option('sl_zoom_level');
    if (empty($zoom_level)) {
        $zoom_level="4";
        add_option('sl_zoom_level', $zoom_level);
        }
    $zoom_tweak=get_option('sl_zoom_tweak');
    if (empty($zoom_tweak)) {
        $zoom_tweak="1";
        add_option('sl_zoom_tweak', $zoom_tweak);
        }
    $search_label=get_option('sl_search_label');
    if (empty($search_label)) {
        $search_label="Address";
        add_option('sl_search_label', $search_label);
        }
    $location_table_view=get_option('sl_location_table_view');
    if (empty($location_table_view)) {
        $location_table_view="Normal";
        add_option('sl_location_table_view', $location_table_view);
        }
    $theme=get_option('sl_map_theme');
    if (empty($theme)) {
        $theme="";
        add_option('sl_map_theme', $theme);
        }
    $google_map_country=get_option('sl_google_map_country');
    if (empty($google_map_country)) {
        $google_map_country="United States";
        add_option('sl_google_map_country', $google_map_country);
    }
    $google_map_domain=get_option('sl_google_map_domain');
    if (empty($google_map_domain)) {
        $google_map_domain="maps.google.com";
        add_option('sl_google_map_domain', $google_map_domain);
    }
    $icon2=get_option('sl_map_end_icon');
    if (empty($icon2)) {
        add_option('sl_map_end_icon', SLPLUS_COREURL . 'images/icons/marker.png');
        $icon2=get_option('sl_map_end_icon');
    }
    $icon=get_option('sl_map_home_icon');
    if (empty($icon)) {
        add_option('sl_map_home_icon', SLPLUS_COREURL . 'images/icons/arrow.png');
        $icon=get_option('sl_map_home_icon');
    }
    $height=get_option('sl_map_height');
    if (empty($height)) {
        add_option('sl_map_height', '350');
        $height=get_option('sl_map_height');
        }
    
    $height_units=get_option('sl_map_height_units');
    if (empty($height_units)) {
        add_option('sl_map_height_units', "px");
        $height_units=get_option('sl_map_height_units');
        }	
    
    $width=get_option('sl_map_width');
    if (empty($width)) {
        add_option('sl_map_width', "100");
        $width=get_option('sl_map_width');
        }
    
    $width_units=get_option('sl_map_width_units');
    if (empty($width_units)) {
        add_option('sl_map_width_units', "%");
        $width_units=get_option('sl_map_width_units');
        }	
    
    $radii=get_option('sl_map_radii');
    if (empty($radii)) {
        add_option('sl_map_radii', "10,25,50,100,(200),500");
        $radii=get_option('sl_map_radii');
        }
}




/*----------------------------*/
function do_geocoding($address,$sl_id='') {    
    global $wpdb, $slplus_plugin;    
    if (!defined('MAPS_HOST')) { define("MAPS_HOST", get_option('sl_google_map_domain')); }
    if (!defined('KEY')) { define('KEY', $slplus_plugin->driver_args['api_key']); }
    
    // Initialize delay in geocode speed
    $delay = 0;
    $base_url = "http://" . MAPS_HOST . "/maps/geo?output=csv&key=" . KEY;
    
    //Adding ccTLD (Top Level Domain) to help perform more accurate geocoding according to selected Google Maps Domain - 12/16/09
    $ccTLD_arr=explode(".", MAPS_HOST);
    $ccTLD=$ccTLD_arr[count($ccTLD_arr)-1];
    if ($ccTLD!="com") {
        $base_url .= "&gl=".$ccTLD;
    }
    
    //Map Character Encoding
    if (get_option("sl_map_character_encoding")!="") {
        $base_url .= "&oe=".get_option("sl_map_character_encoding");
    }
    
    // Loop through for X retries
    //
    $iterations = get_option(SLPLUS_PREFIX.'-goecode_retries');
    if ($iterations <= 0) { $iterations = 1; }
    while($iterations){
    	$iterations--;     
    
        // Iterate through the rows, geocoding each address
        $request_url = $base_url . "&q=" . urlencode($address);
        if (extension_loaded("curl") && function_exists("curl_init")) {
                $cURL = curl_init();
                curl_setopt($cURL, CURLOPT_URL, $request_url);
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
                $csv = curl_exec($cURL);
                curl_close($cURL);  
        }else{
             $csv = file_get_contents($request_url) or die("url not loading");
        }
    
        $csvSplit = split(",", $csv);
        $status = $csvSplit[0];
        $lat = $csvSplit[2];
        $lng = $csvSplit[3];
        
        // Geocode completed successfully
        //
        if (strcmp($status, "200") == 0) {
            $iterations = 0;      // Break out of retry loop if we are OK
            
            // successful geocode
            $geocode_pending = false;
            $lat = $csvSplit[2];
            $lng = $csvSplit[3];
            
            // Update newly inserted address
            //
            if ($sl_id=='') {
                $query = sprintf("UPDATE " . $wpdb->prefix ."store_locator " .
                       "SET sl_latitude = '%s', sl_longitude = '%s' " .
                       "WHERE sl_id = ".mysql_insert_id() .
                       " LIMIT 1;", 
                       mysql_real_escape_string($lat), 
                       mysql_real_escape_string($lng)
                       );
            }
            
            // Update an existing address
            //
            else {
                $query = sprintf("UPDATE " . $wpdb->prefix ."store_locator SET sl_latitude = '%s', sl_longitude = '%s' WHERE sl_id = $sl_id LIMIT 1;", mysql_real_escape_string($lat), mysql_real_escape_string($lng));
            }
            
            
            // Run insert/update
            //
            $update_result = mysql_query($query);
            if (!$update_result) {
                echo sprintf(__("Could not add/update address.  Error: %s.", SLPLUS_PREFIX),mysql_error())."\n<br>";
            }

        // Geocoding done too quickly
        //
        } else if (strcmp($status, "620") == 0) {
            
          // No iterations left, tell user of failure
          //
	      if(!$iterations){
            echo sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
            echo sprintf(__("Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
	      }                       
          $delay += 100000;

        // Invalid address
        //
        } else if (strcmp($status, '602') == 0) {
	    	$iterations = 0; 
	    	echo sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
	      	echo sprintf(__("Unknown Address! Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
          
        // Could Not Geocode
        //
        } else {
            $geocode_pending = false;
            echo sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
            echo sprintf(__("Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
        }
        usleep($delay);
    }
}    


/***********************************
 ** Run install/update activation routines
 **
 ** [LE/PLUS]
 **/

function activate_slplus() {
    global $slplus_plugin;
    
   
    // Data Updates
    //
    global $sl_db_version, $sl_installed_ver;
	$sl_db_version='2.2';     //***** CHANGE THIS ON EVERY STRUCT CHANGE
    $sl_installed_ver = get_option( SLPLUS_PREFIX."-db_version" );

	install_main_table();
	if (function_exists('install_reporting_tables')) {
	    install_reporting_tables();
    }
    
    
    // Update the version
    //
    if ($sl_installed_ver == '') {
        add_option(SLPLUS_PREFIX."-db_version", $sl_db_version);
    } else {
        update_option(SLPLUS_PREFIX."-db_version", $sl_db_version);
    }
    
    
    if (function_exists('add_slplus_roles_and_caps')) {
        add_slplus_roles_and_caps();
    }        
	move_upload_directories();
}


/***********************************
 ** function: install_main_table
 **
 ** Install/update the main locations table.
 **
 **/
function install_main_table() {
	global $wpdb, $sl_installed_ver;
    
	
	//*****
	//***** CHANGE sl_db_version IN activate_slplus() 
	//***** ANYTIME YOU CHANGE THIS STRUCTURE
	//*****	
	$charset_collate = '';
    if ( ! empty($wpdb->charset) )
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    if ( ! empty($wpdb->collate) )
        $charset_collate .= " COLLATE $wpdb->collate";	
	$table_name = $wpdb->prefix . "store_locator";
	$sql = "CREATE TABLE $table_name (
			sl_id mediumint(8) unsigned NOT NULL auto_increment,
			sl_store varchar(255) NULL,
			sl_address varchar(255) NULL,
			sl_address2 varchar(255) NULL,
			sl_city varchar(255) NULL,
			sl_state varchar(255) NULL,
			sl_zip varchar(255) NULL,
			sl_country varchar(255) NULL,
			sl_latitude varchar(255) NULL,
			sl_longitude varchar(255) NULL,
			sl_tags mediumtext NULL,
			sl_description text NULL,
			sl_email varchar(255) NULL,
			sl_url varchar(255) NULL,
			sl_hours varchar(255) NULL,
			sl_phone varchar(255) NULL,
			sl_image varchar(255) NULL,
			sl_private varchar(1) NULL,
			sl_neat_title varchar(255) NULL,
			sl_lastupdated  timestamp NOT NULL default current_timestamp,			
			PRIMARY KEY  (sl_id)
			) 
			$charset_collate
			";
						
    // If we updated an existing DB, do some mods to the data
    //
    if (slplus_dbupdater($sql,$table_name) === 'updated') {
        
        // We are upgrading from something less than 2.0
        //
	    if (floatval($sl_installed_ver) < 2.0) {
            dbDelta("UPDATE $table_name SET sl_lastupdated=current_timestamp " . 
                "WHERE sl_lastupdated < '2011-06-01'"
                );
        }   
	    if (floatval($sl_installed_ver) < 2.2) {
            dbDelta("ALTER $table_name MODIFY sl_description text ");
        }   
    }         
}

/***********************************
 ** function: slplus_dbupdater
 ** 
 ** Update the data structures on new db versions.
 **
 **/ 
function slplus_dbupdater($sql,$table_name) {
    global $wpdb, $sl_db_version, $sl_installed_ver;
        
    // New installation
    //
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		return 'new';
		
    // Installation upgrade
    //
	} else {        
        if( $sl_installed_ver != $sl_db_version ) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            return 'updated';    
        }
    }   
}


/***********************************
 ** function: head_scripts
 **
 ** Create the javascript elements needed for the google map for pages that use
 ** the plugin only.   This was inherited and needs to be cleaned up a bit.
 ** 
 ** We'll still want to ensure we only load up scripts (and CSS, etc.) on pages
 ** where the map will be displayed.
 **/
function head_scripts() {
	global $sl_dir, $sl_base, $sl_upload_base, $sl_path, $sl_upload_path, $wpdb, $pagename, $map_character_encoding;
	global $slplus_plugin;
	
	//Check if currently on page with shortcode
	$pageID = isset($_GET['p'])         ? $_GET['p']       : 
	          (isset($_GET['page_id'])   ? $_GET['page_id'] : '');
	$on_sl_page=$wpdb->get_results("SELECT post_name FROM ".$wpdb->prefix."posts ".
	        "WHERE (post_content LIKE '%[STORE-LOCATOR%' OR post_content LIKE '%[SLPLUS%') AND " .
	        "post_status IN ('publish', 'draft') AND ".
	        "(post_name='$pagename' OR ID='$pageID')", 
	        ARRAY_A);
	
	//Checking if code used in posts	
	$sl_code_is_used_in_posts=$wpdb->get_results(
	    "SELECT post_name FROM ".$wpdb->prefix."posts ".
	    "WHERE (post_content LIKE '%[STORE-LOCATOR%' OR post_content LIKE '%[SLPLUS%') AND post_type='post'"
	    );
	
	//If shortcode used in posts, get post IDs, and put into array of numbers
	if ($sl_code_is_used_in_posts) {
		$sl_post_ids=$wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[STORE-LOCATOR%' AND post_type='post'", ARRAY_A);
		foreach ($sl_post_ids as $val) { $post_ids_array[]=$val['ID'];}
	} else {			    
	     //post number that'll never be reached
		$post_ids_array=array(9999999999999999999999999999999999999);
	}
	
	// If on page with store locator shortcode, on an archive, search, or 404 page 
    // while shortcode has been used in a post, on the front page, or a specific 
    // post with shortcode, display code, otherwise, don't
	if ($on_sl_page || is_search() || 
        ((is_archive() || is_404()) && $sl_code_is_used_in_posts) || 
        is_front_page() || is_single($post_ids_array)
        ) {
        if (isset($slplus_plugin) && $slplus_plugin->ok_to_show()) {
            $api_key=$slplus_plugin->driver_args['api_key'];
            $google_map_domain=(get_option('sl_google_map_domain')!="")? 
                    get_option('sl_google_map_domain') : 
                    "maps.google.com";
            
            print  "<script src='http://$google_map_domain/maps?file=api&amp;v=2&amp;key=$api_key&amp;sensor=false{$map_character_encoding}' type='text/javascript'></script>
                    <script src='".SLPLUS_PLUGINURL."/core/js/store-locator-js.php' type='text/javascript'></script>
                    <script src='".SLPLUS_PLUGINURL."/core/js/store-locator.js' type='text/javascript'></script>
                    <script src='".SLPLUS_PLUGINURL."/core/js/functions.js' type='text/javascript'></script>\n";
            
                    
                
            // CSL Theme System
            //
            if (get_option(SLPLUS_PREFIX . '-theme' ) != '') {
                    setup_stylesheet_for_slplus();
                
            // Legacy Custom CSS
            //
            } else {
                $has_custom_css=(file_exists($sl_upload_path."/custom-css/csl-slplus.css"))? 
                    $sl_upload_base."/custom-css" : 
                    $sl_base; 
                print "<link  href='".$has_custom_css."/core/css/csl-slplus.css' type='text/css' rel='stylesheet'/>";
            }
            
            
            
            $theme=get_option('sl_map_theme');
            if ($theme!="") {print "\n<link  href='".$sl_upload_base."/themes/$theme/style.css' rel='stylesheet' type='text/css'/>";}
            $zl=(trim(get_option('sl_zoom_level'))!="")? get_option('sl_zoom_level') : 4;		            
            $ztweak=(trim(get_option('sl_zoom_tweak'))!="")? get_option('sl_zoom_tweak') : 1;		            
            }
        } else {
            if ($slplus_plugin->debugging) {
                $sl_page_ids=$wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[STORE-LOCATOR%' AND post_status='publish'", ARRAY_A);
                print "<!-- No store locator on this page, so no unnecessary scripts for better site performance. (";
                if ($sl_page_ids) {
                    foreach ($sl_page_ids as $value) { print "$value[ID],";}
                }
                print ")-->";
            }
        }
}


/**************************************
 ** function: store_locator_shortcode
 **
 ** Process the store locator shortcode.
 **
 **/
 function store_locator_shortcode($attributes, $content = null) {
    // Variables this function uses and passes to the template
    // we need a better way to pass vars to the template parser so we don't
    // carry around the weight of these global definitions.
    // the other option is to unset($GLOBAL['<varname>']) at then end of this    
    // function call.
    //
    // Let's start using a SINGLE named array called "fnvars" to pass along anything
    // we want.
    //
    global  $sl_dir, $sl_base, $sl_upload_base, $sl_path, $sl_upload_path, $text_domain, $wpdb,
	    $slplus_plugin, $prefix,	        
	    $search_label, $width, $height, $width_units, $height_units, $hide,
	    $sl_radius, $sl_radius_label, $r_options, $button_style,
	    $sl_instruction_message, $cs_options, 
	    $country_options, $slplus_state_options, $fnvars;	 	    
    $fnvars = array();

    //----------------------
    // Attribute Processing
    // [LE/PLUS]
    //    
    if (function_exists('slplus_shortcode_atts')) {
        slplus_shortcode_atts($attributes);
    }
                   
    $height=(get_option('sl_map_height'))? 
    get_option('sl_map_height') : "500" ;
    
    $width=(get_option('sl_map_width'))? 
    get_option('sl_map_width') : "100" ;
        
    $radii=(get_option('sl_map_radii'))? 
    get_option('sl_map_radii') : "1,5,10,(25),50,100,200,500" ;
    
    $height_units=(get_option('sl_map_height_units'))? 
    get_option('sl_map_height_units') : "px";
    
    $width_units=(get_option('sl_map_width_units'))? 
    get_option('sl_map_width_units') : "%";
    
    $sl_instruction_message=(get_option('sl_instruction_message'))? 
    get_option('sl_instruction_message') : 
    "Enter Your Address or Zip Code Above.";

    $r_array=explode(",", $radii);
    $search_label=(get_option('sl_search_label'))? 
    get_option('sl_search_label') : "Address" ;
    
    $unit_display=(get_option('sl_distance_unit')=="km")? 
    "km" : "mi";

    $r_options      =(isset($r_options)         ?$r_options      :'');
    $cs_options     =(isset($cs_options)        ?$cs_options     :'');
    $country_options=(isset($country_options)   ?$country_options:'');
    $slplus_state_options=(isset($slplus_state_options)   ?$slplus_state_options:'');

    foreach ($r_array as $value) {
        $s=(ereg("\(.*\)", $value))? " selected='selected' " : "" ;
        
        // Hiding Radius?
        if (get_option(SLPLUS_PREFIX.'_hide_radius_selections') == 1) {
            if ($s == " selected='selected' ") {
                $value=ereg_replace("[^0-9]", "", $value);
                $r_options = "<input type='hidden' id='radiusSelect' name='radiusSelect' value='$value'>";
            }
            
        // Not hiding radius, build pulldown.
        } else {
            $value=ereg_replace("[^0-9]", "", $value);
            $r_options.="<option value='$value' $s>$value $unit_display</option>";
        }
    }
        
    //-------------------
    // Show City Search option is checked
    // setup the pulldown list
    //
    if (get_option('sl_use_city_search')==1) {
        $cs_array=$wpdb->get_results(
            "SELECT CONCAT(TRIM(sl_city), ', ', TRIM(sl_state)) as city_state " .
                "FROM ".$wpdb->prefix."store_locator " .
                "WHERE sl_city<>'' AND sl_state<>'' AND sl_latitude<>'' " .
                    "AND sl_longitude<>'' " .
                "GROUP BY city_state " .
                "ORDER BY city_state ASC", 
            ARRAY_A);
    
        if ($cs_array) {
            foreach($cs_array as $value) {
        $cs_options.="<option value='$value[city_state]'>$value[city_state]</option>";
            }
        }
    }

    //----------------------
    // Create Country Pulldown
    // [LE/PLUS]
    //    
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {                    
        $country_options = slplus_create_country_pd();    
        $slplus_state_options = slplus_create_state_pd();
    } else {
        $country_options = '';    
        $slplus_state_options = '';
    }
        
    $theme_base=$sl_upload_base."/images";
    $theme_path=$sl_upload_path."/images";
    if (!file_exists($theme_path."/search_button.png")) {
        $theme_base=$sl_base."/images";
        $theme_path=$sl_path."/images";
    }
    $sub_img=$theme_base."/search_button.png";
    $mousedown=(file_exists($theme_path."/search_button_down.png"))? 
        "onmousedown=\"this.src='$theme_base/search_button_down.png'\" onmouseup=\"this.src='$theme_base/search_button.png'\"" : 
        "";
    $mouseover=(file_exists($theme_path."/search_button_over.png"))? 
        "onmouseover=\"this.src='$theme_base/search_button_over.png'\" onmouseout=\"this.src='$theme_base/search_button.png'\"" : 
        "";
    $button_style=(file_exists($theme_path."/search_button.png"))? 
        "type='image' src='$sub_img' $mousedown $mouseover" : 
        "type='submit'";
    $hide=(get_option('sl_remove_credits')==1)? 
        "style='display:none;'" : 
        "";

    $columns = 1;
    $columns += (get_option('sl_use_city_search')!=1) ? 1 : 0;
    $columns += (get_option('sl_use_country_search')!=1) ? 1 : 0; 	    
    $columns += (get_option('slplus_show_state_pd')!=1) ? 1 : 0; 	    
    $sl_radius_label=get_option('sl_radius_label');
    $file = SLPLUS_COREDIR . 'templates/search_form.php';

    // Prep fnvars for passing to our template
    //
    $fnvars = array_merge($fnvars,(array) $attributes);       // merge in passed attributes

    return get_string_from_phpexec($file); 
}

/**************************************
 ** function: csl_slplus_add_options_page()
 **
 ** Add the Store Locator panel to the admin sidebar.
 **
 **/
function csl_slplus_add_options_page() {
	global $slplus_plugin;
	
	if ( 
	    (trim($slplus_plugin->driver_args['api_key'])!="") &&
	    (!function_exists('add_slplus_roles_and_caps') || current_user_can('manage_slp'))
	    )
	{
        add_menu_page(
            __($slplus_plugin->name, SLPLUS_PREFIX),  
            __($slplus_plugin->name, SLPLUS_PREFIX), 
            'administrator', 
            SLPLUS_COREDIR.'add-locations.php'
            );	
		add_submenu_page(
    	    SLPLUS_COREDIR.'add-locations.php',
		    __("Add Locations", SLPLUS_PREFIX), 
		    __("Add Locations", SLPLUS_PREFIX), 
		    'administrator', 
		    SLPLUS_COREDIR.'add-locations.php'
		    );
		add_submenu_page(
    	    SLPLUS_COREDIR.'add-locations.php',
		    __("Manage Locations", SLPLUS_PREFIX), 
		    __("Manage Locations", SLPLUS_PREFIX), 
		    'administrator', 
		    SLPLUS_COREDIR.'view-locations.php'
		    );
		add_submenu_page(
    	    SLPLUS_COREDIR.'add-locations.php',
		    __("Map Settings", SLPLUS_PREFIX), 
		    __("Map Settings", SLPLUS_PREFIX), 
		    'administrator', 
		    SLPLUS_COREDIR.'map-designer.php'
		    );
		
		// Plus Reporting
		//
		if ($slplus_plugin->license->packages['Plus Pack']->isenabled) { 		
            if (function_exists('slplus_add_report_settings')) {
                add_submenu_page(
                    SLPLUS_COREDIR.'add-locations.php',
                    __("Reports", SLPLUS_PREFIX), 
                    __("Reports", SLPLUS_PREFIX), 
                    'administrator', 
                    SLPLUS_PLUGINDIR.'reporting.php'
                    );		    
            }
        }            
	}

}


/*---------------------------------*/
function add_admin_javascript() {
        global $sl_base, $sl_upload_base, $sl_dir, $google_map_domain, $sl_path, 
            $sl_upload_path, $map_character_encoding, $slplus_plugin;
        wp_enqueue_script('jquery-ui-dialog');               
		$api=$slplus_plugin->driver_args['api_key'];
        print "<script src='".SLPLUS_PLUGINURL."/core/js/functions.js'></script>\n
        <script type='text/javascript'>
        var sl_dir='".SLPLUS_PLUGINDIR."';
        var sl_google_map_country='".get_option('sl_google_map_country')."';
        </script>\n";
        if (ereg("add-locations", (isset($_GET['page'])?$_GET['page']:''))) {
            $google_map_domain=(get_option('sl_google_map_domain')!="")? get_option('sl_google_map_domain') : "maps.google.com";			
            print "<script src='http://$google_map_domain/maps?file=api&amp;v=2&amp;key=$api&amp;sensor=false{$map_character_encoding}' type='text/javascript'></script>\n";
        }
}


/*---------------------------------*/
function set_query_defaults() {
	global $where, $o, $d;
	
	$qry = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
	$where=($qry!='')? 
	        " WHERE ".
	        "sl_store    LIKE '%$qry%' OR ".
	        "sl_address  LIKE '%$qry%' OR ".
	        "sl_address2 LIKE '%$qry%' OR ".
	        "sl_city     LIKE '%$qry%' OR ".
	        "sl_state    LIKE '%$qry%' OR ".
	        "sl_zip      LIKE '%$qry%' OR ".
	        "sl_tags     LIKE '%$qry%' " 
	        : 
	        '' ;
	$o= (isset($_GET['o']) && (trim($_GET['o']) != ''))
	    ? $_GET['o'] : "sl_store";
	$d= (isset($_GET['d']) && (trim($_GET['d'])=='DESC')) 
	    ? "DESC" : "ASC";
}

/*----------------------------------*/
function match_imported_data($the_array) {
    print "<h3>".__("Choose Heading That Matches Columns You Want to Import", SLPLUS_PREFIX).":</h3>(".__("Leave headings for undesired columns unchanged", SLPLUS_PREFIX).")<br><br>
    <form method='post'>
    <input type='button' value='".__("Cancel", SLPLUS_PREFIX)."' class='button' onclick='history.go(-1)'>&nbsp;<input type='submit' value='".__("Import Locations", SLPLUS_PREFIX)."' class='button'>
    <table class='widefat'><thead><tr style='/*background-color:black*/'>";
    
    $array_to_be_counted=(is_array($the_array[0]))? $the_array[0] : $the_array[1] ; //needed for the csv import (where first line is usually skipped)  vs the point-click-add import (where there's only the first line)
    for ($ctr=1; $ctr<=count($array_to_be_counted); $ctr++) {
    print "<td><select name='field_map[]'>";
    print "<option value=''>".__("Choose")."</option>
            <option value='sl_store'>".__("Name", SLPLUS_PREFIX)."</option>
                <option value='sl_address'>".__("Street(Line1)", SLPLUS_PREFIX)."</option>
                <option value='sl_address2'>".__("Street(Line2)", SLPLUS_PREFIX)."</option>
                <option value='sl_city'>".__("City", SLPLUS_PREFIX)."</option>
                <option value='sl_state'>".__("State", SLPLUS_PREFIX)."</option>
                <option value='sl_zip'>".__("Zip", SLPLUS_PREFIX)."</option>
                <option value='sl_tags'>".__("Tags", SLPLUS_PREFIX)."</option>
                <option value='sl_description'>".__("Description", SLPLUS_PREFIX)."</option>
                <option value='sl_hours'>".__("Hours", SLPLUS_PREFIX)."</option>
                <option value='sl_url'>".__("URL", SLPLUS_PREFIX)."</option>
                <option value='sl_phone'>".__("Phone", SLPLUS_PREFIX)."</option>
                <option value='sl_image'>".__("Image", SLPLUS_PREFIX)."</option>
                <option value='sl_private'>".__("Is Private?", SLPLUS_PREFIX)."</option>";
    print "</select></td>";
    }
    print "</tr></thead>";
    
    foreach ($the_array as $key=>$value) {
    print "<tr style='border-bottom:solid silver 1px'>";
    $bgcolor="#ddd";
    $ctr2=0;
    foreach ($value as $key2=>$value2) {
        $bgcolor=($bgcolor=="#fff" || empty($bgcolor))? "#ddd" : "#fff";
        print "<td style='background-color:$bgcolor'>$value2<input type='hidden' value='$value2' name='column{$ctr2}[]'></td>\n";
        $ctr2++;
    }
    print "</tr>\n";
    }
    print "</table><input type='hidden' name='finish_import' value='1'>
    <input type='hidden' name='total_entries' value='".(count($the_array))."'>
    <input type='button' value='".__("Cancel", SLPLUS_PREFIX)."' class='button' onclick='history.go(-1)'>&nbsp;<input type='submit' value='".__("Import Locations", SLPLUS_PREFIX)."' class='button'></form>";
}
/*--------------------------------------------------------------*/

function do_hyperlink(&$text, $target="'_blank'")
{
   // match protocol://address/path/
   $text = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/?&amp;%20,=-\+-])*", "<a href=\"\\0\" target=$target>\\0</a>", $text);
   $text = ereg_replace("(^| )(www([.]?[a-zA-Z0-9_/=-\+-])*)", "\\1<a href=\"http://\\2\" target=$target>\\2</a>", $text);
   return $text;
}


/*-------------------------------------------------------------*/
function comma($a) {
	$a=ereg_replace('"', "&quot;", $a);
	$a=ereg_replace("'", "&#39;", $a);
	$a=ereg_replace(">", "&gt;", $a);
	$a=ereg_replace("<", "&lt;", $a);
	$a=ereg_replace(" & ", " &amp; ", $a);
	return ereg_replace("," ,"&#44;" ,$a);
	
}


/*-----------------------------------------------------------*/
function url_test($url) {
	return (strtolower(substr($url,0,7))=="http://");
}

/************************************************************
 * Copy a file, or recursively copy a folder and its contents
 */
function csl_copyr($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, 0755);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        csl_copyr("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}

