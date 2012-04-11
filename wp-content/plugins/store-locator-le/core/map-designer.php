<?php 
/******************************************************************************
 * file: map-designer.php
 *
 * provide the map designer admin interface
 ******************************************************************************/
 
//===========================================================================
// Supporting Functions
//===========================================================================

/**************************************
 ** function: choose_units
 **
 ** Display the map size units pulldown (%,px,em,pt)
 **
 **/
function choose_units($unit, $input_name) {   
	$unit_arr     = array('%','px','em','pt');
	$select_field = "<select name='$input_name'>";	
	foreach ($unit_arr as $value) {
		$selected=($value=="$unit")? " selected='selected' " : "" ;
        $select_field.="\n<option value='$value' $selected>$value</option>";
	}
	$select_field.="</select>";
	return $select_field;
}

/**************************************
 ** function: SaveCheckboxToDB
 **
 ** Update the checkbox setting in the database.
 **
 ** Parameters:
 **  $boxname (string, required) - the name of the checkbox (db option name)
 **  $prefix (string, optional) - defaults to SLPLUS_PREFIX, can be '' 
 **/
function SaveCheckboxToDB($boxname,$prefix = SLPLUS_PREFIX) {
    $whichbox = $prefix.$boxname; 
    $_POST[$whichbox] = isset($_POST[$whichbox])?1:0;  
    update_option($whichbox,$_POST[$whichbox]); 
}

/**************************************
** function: CreateCheckboxDiv
 **
 ** Update the checkbox setting in the database.
 **
 ** Parameters:
 **  $boxname (string, required) - the name of the checkbox (db option name)
 **  $label (string, optional) - default '', the label to go in front of the checkbox
 **  $message (string, optional) - default '', the help message 
 **  $prefix (string, optional) - defaults to SLPLUS_PREFIX, can be ''  
 **/
function CreateCheckboxDiv($boxname,$label='',$msg='',$prefix=SLPLUS_PREFIX) {
    $whichbox = $prefix.$boxname; 
    return 
        "<div class='form_entry'>".
            "<label  for='$whichbox'>$label:</label>".
            "<input name='$whichbox' value='1' type='checkbox' ".((get_option($whichbox) ==1)?' checked':'').">".
            slp_createhelpdiv($boxname,$msg).
        "</div>"
        ;
}



//===========================================================================
// Main Processing
//===========================================================================
$update_msg ='';

if (!$_POST) {
    move_upload_directories();
    
} else {
    if (isset($_POST['sl_language'])) { 
    	    update_option('sl_language', $_POST['sl_language']);
    }
    $sl_google_map_arr=explode(":", $_POST['google_map_domain']);
    update_option('sl_google_map_country', $sl_google_map_arr[0]);
    update_option('sl_google_map_domain', $sl_google_map_arr[1]);
    update_option('sl_map_character_encoding', $_POST['sl_map_character_encoding']);
    
    $_POST['height']=ereg_replace("[^0-9]", "", $_POST['height']);
    $_POST['width']=ereg_replace("[^0-9]", "", $_POST['width']);
    update_option('sl_map_height', $_POST['height']);
    update_option('sl_map_width', $_POST['width']);
    update_option('sl_map_radii', $_POST['radii']);
    update_option('sl_map_height_units', $_POST['height_units']);
    update_option('sl_map_width_units', $_POST['width_units']);
    update_option('sl_map_home_icon', $_POST['icon']);
    update_option('sl_map_end_icon', $_POST['icon2']);
    update_option('sl_search_label', $_POST['search_label']);
    update_option('sl_radius_label', $_POST['sl_radius_label']);
    update_option('sl_website_label', $_POST['sl_website_label']);
    update_option('sl_instruction_message', $_POST['sl_instruction_message']);
    update_option('sl_zoom_level', $_POST['zoom_level']);
    update_option('sl_zoom_tweak', $_POST['zoom_tweak']);
    update_option('sl_map_type', $_POST['sl_map_type']);
    update_option('sl_num_initial_displayed', $_POST['sl_num_initial_displayed']);    
    update_option('sl_distance_unit', $_POST['sl_distance_unit']);

    if (function_exists('execute_and_output_plustemplate')) {
        update_option('sl_starting_image', $_POST['sl_starting_image']);
        update_option(SLPLUS_PREFIX.'_search_tag_label',        $_POST[SLPLUS_PREFIX.'_search_tag_label']);
        update_option(SLPLUS_PREFIX.'_tag_search_selections',   $_POST[SLPLUS_PREFIX.'_tag_search_selections']);
        update_option(SLPLUS_PREFIX.'_state_pd_label',          $_POST[SLPLUS_PREFIX.'_state_pd_label']);
        update_option(SLPLUS_PREFIX.'_map_center',              $_POST[SLPLUS_PREFIX.'_map_center']);        
    }    
    
    # Checkbox settings - can set to issset and save that because the
    # post variable is only set if it is checked, if not checked it is
    # false (0).
    #
    $_POST['sl_use_city_search']=isset($_POST['sl_use_city_search'])?1:0;
    update_option('sl_use_city_search',$_POST['sl_use_city_search']);
            
    $_POST['slplus_show_state_pd']=isset($_POST['slplus_show_state_pd'])?1:0;
    update_option('slplus_show_state_pd',$_POST['slplus_show_state_pd']);
    
    $_POST['sl_use_country_search']=isset($_POST['sl_use_country_search'])?1:0;
    update_option('sl_use_country_search',$_POST['sl_use_country_search']);
       
    $_POST['sl_remove_credits']=isset($_POST['sl_remove_credits'])?1:0; 
    update_option('sl_remove_credits',$_POST['sl_remove_credits']);
    
    $_POST['sl_load_locations_default']=isset($_POST['sl_load_locations_default'])?1:0;
    update_option('sl_load_locations_default',$_POST['sl_load_locations_default']);

    $_POST['sl_map_overview_control'] = isset($_POST['sl_map_overview_control'])?1:0;  
    update_option('sl_map_overview_control',$_POST['sl_map_overview_control']);
    
    $BoxesToHit = array(
        '_show_tag_search',
        '_show_tag_any',
        '_email_form',
        '_show_tags',
        '_disable_scrollwheel',
        '_disable_initialdirectory',
        '_disable_largemapcontrol3d',
        '_disable_scalecontrol',
        '_disable_maptypecontrol',
        '_hide_radius_selections',
        '_hide_address_entry',
        '_disable_search'
        );
    foreach ($BoxesToHit as $JustAnotherBox) {        
        SaveCheckBoxToDB($JustAnotherBox);
    }
       
    $update_msg = "<div class='highlight'>".__("Successful Update", SLPLUS_PREFIX).'</div>';
}

//---------------------------
//
initialize_variables();

$the_domain = array(    
    "United States"=>"maps.google.com",
    "Argentina"=>"maps.google.com.ar",
    "Australia"=>"maps.google.com.au",
    "Austria"=>"maps.google.at",
    "Belgium"=>"maps.google.be",
    "Brazil"=>"maps.google.com.br",
    "Canada"=>"maps.google.ca",
    "Chile"=>"maps.google.cl", 
    "China"=>"ditu.google.com",
    "Czech Republic"=>"maps.google.cz",
    "Denmark"=>"maps.google.dk",
    "Finland"=>"maps.google.fi",
    "France"=>"maps.google.fr",
    "Germany"=>"maps.google.de",
    "Hong Kong"=>"maps.google.com.hk",
    "India"=>"maps.google.co.in", 
    "Republic of Ireland"=>"maps.google.ie",
    "Italy"=>"maps.google.it",
    "Japan"=>"maps.google.co.jp", 
    "Liechtenstein"=>"maps.google.li", 
    "Mexico"=>"maps.google.com.mx", 
    "Netherlands"=>"maps.google.nl",
    "New Zealand"=>"maps.google.co.nz",
    "Norway"=>"maps.google.no",
    "Poland"=>"maps.google.pl",
    "Portugal"=>"maps.google.pt", 
    "Russia"=>"maps.google.ru",
    "Singapore"=>"maps.google.com.sg", 
    "South Korea"=>"maps.google.co.kr", 
    "Spain"=>"maps.google.es",
    "Sweden"=>"maps.google.se",
    "Switzerland"=>"maps.google.ch",
    "Taiwan"=>"maps.google.com.tw", 
    "United Kingdom"=>"maps.google.co.uk",
    );

$char_enc["Default (UTF-8)"]="utf-8";
$char_enc["Western European (ISO-8859-1)"]="iso-8859-1";
$char_enc["Western/Central European (ISO-8859-2)"]="iso-8859-2";
$char_enc["Western/Southern European (ISO-8859-3)"]="iso-8859-3";
$char_enc["Western European/Baltic Countries (ISO-8859-4)"]="iso-8859-4";
$char_enc["Russian (Cyrillic)"]="iso-8859-5";
$char_enc["Arabic (ISO-8859-6)"]="iso-8859-6";
$char_enc["Greek (ISO-8859-7)"]="iso-8859-7";
$char_enc["Hebrew (ISO-8859-8)"]="iso-8859-8";
$char_enc["Western European w/amended Turkish (ISO-8859-9)"]="iso-8859-9";
$char_enc["Western European w/Nordic characters (ISO-8859-10)"]="iso-8859-10";
$char_enc["Thai (ISO-8859-11)"]="iso-8859-11";
$char_enc["Baltic languages & Polish (ISO-8859-13)"]="iso-8859-13";
$char_enc["Celtic languages (ISO-8859-14)"]="iso-8859-14";
$char_enc["Japanese (Shift JIS)"]="shift_jis";
$char_enc["Simplified Chinese (China)(GB 2312)"]="gb2312";
$char_enc["Traditional Chinese (Taiwan)(Big 5)"]="big5";
$char_enc["Hong Kong (HKSCS)"]="hkscs";
$char_enc["Korea (EUS-KR)"]="eus-kr";


//-- Set Checkboxes
//
$checked2   	    = (isset($checked2)  ?$checked2  :'');
$city_checked	    = (get_option('sl_use_city_search')             ==1)?' checked ':'';
$checked3	        = (get_option('sl_remove_credits')              ==1)?' checked ':'';

$map_type_options=(isset($map_type_options)?$map_type_options:'');
$map_type["".__("Normal", SLPLUS_PREFIX).""]="G_NORMAL_MAP";
$map_type["".__("Satellite", SLPLUS_PREFIX).""]="G_SATELLITE_MAP";
$map_type["".__("Hybrid", SLPLUS_PREFIX).""]="G_HYBRID_MAP";
$map_type["".__("Physical", SLPLUS_PREFIX).""]="G_PHYSICAL_MAP";


$zl[]=0;$zl[]=1;$zl[]=2;$zl[]=3;$zl[]=4;$zl[]=5;$zl[]=6;$zl[]=7;$zl[]=8;
$zl[]=9;$zl[]=10;$zl[]=11;$zl[]=12;$zl[]=13;$zl[]=14;$zl[]=15;$zl[]=16;
$zl[]=17;$zl[]=18;$zl[]=19;


// Zoom Level
//
$slp_current_setting = get_option('sl_zoom_level');
if ($slp_current_setting == '') { $slp_current_setting = 4; }
$zoom="<select name='zoom_level'>";
foreach ($zl as $value) {
	$zoom.="<option value='$value' ";
	if ($slp_current_setting==$value){ $zoom.=" selected ";}
	$zoom.=">$value</option>";
}
$zoom.="</select>";

// Zoom Adjustment
//
$slp_current_setting = get_option('sl_zoom_tweak');
if ($slp_current_setting == '') { $slp_current_setting = 4; }
$zoom_adj="<select name='zoom_tweak'>";
foreach ($zl as $value) {
	$zoom_adj.="<option value='$value' ";
	if ($slp_current_setting==$value){ $zoom_adj.=" selected ";}
	$zoom_adj.=">$value</option>";
}
$zoom_adj.="</select>";

// Map Type
//
$slp_current_setting = get_option('sl_map_type');
foreach($map_type as $key=>$value) {
	$selected2=($slp_current_setting==$value)? " selected " : "";
	$map_type_options.="<option value='$value' $selected2>$key</option>\n";
}

//---- ICONS ----

$icon_str   =(isset($icon_str)  ?$icon_str  :'');
$icon2_str  =(isset($icon2_str) ?$icon2_str :'');
$icon_dir=opendir(SLPLUS_ICONDIR);

// List icons
while (false !== ($an_icon=readdir($icon_dir))) {
	if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {
		$icon_str.=
		"<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' 
		     src='".SLPLUS_ICONURL.$an_icon."'
		     onclick='document.forms[0].icon.value=this.src;document.getElementById(\"prev\").src=this.src;'
		     onmouseover='style.borderColor=\"red\";' 
		     onmouseout='style.borderColor=\"white\";'
		     >";
	}
}
// Custom icon directory?
if (is_dir($sl_upload_path."/custom-icons/")) {
	$icon_upload_dir=opendir($sl_upload_path."/custom-icons/");
	while (false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {
			$icon_str.=
			"<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' 
			src='$sl_upload_base/custom-icons/$an_icon' 
			onclick='document.forms[\"mapDesigner\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' 
			onmouseover='style.borderColor=\"red\";' 
			onmouseout='style.borderColor=\"white\";'
			>";
		}
	}
}

$icon2_str = preg_replace('/\.icon\.value/','.icon2.value',$icon_str);
$icon2_str = preg_replace('/getElementById\("prev"\)/','getElementById("prev2")',$icon2_str);

// Icon is the old path, notify them to re-select
//
$icon_notification_msg=
(
    ( !ereg("/core/images/icons/", get_option('sl_map_home_icon')) 
        && 
      !ereg("/custom-icons/", get_option('sl_map_home_icon'))
    )
        || 
    ( !ereg("/core/images/icons/", get_option('sl_map_end_icon')) 
        && 
      !ereg("/custom-icons/", get_option('sl_map_end_icon'))
    )
)
    ? 
"<div class='highlight' style='background-color:LightYellow;color:red'><span style='color:red'>".
__("Please re-select your <b>Home Icon</b> and <b>Destination Icon</b> below, so that they show up properly on your map.", SLPLUS_PREFIX).
"</span></div>" : 
"" ;


// Instantiate the form rendering object
//
global $slpMapSettings;
$slpMapSettings = new wpCSL_settings__slplus(
    array(
            'no_license'        => true,
            'prefix'            => $slplus_plugin->prefix,
            'url'               => $slplus_plugin->url,
            'name'              => $slplus_plugin->name . ' - Map Settings',
            'plugin_url'        => $slplus_plugin->plugin_url,
            'render_csl_blocks' => false,
            'form_action'       => SLPLUS_ADMINPAGE.'map-designer.php',
            'save_text'         => 'Save Settings'
        )
 ); 

//-------------------------
// Navbar Section
//-------------------------    
$slpMapSettings->add_section(
    array(
        'name' => 'Navigation',
        'div_id' => 'slplus_navbar',
        'description' => get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php'),
        'is_topmenu' => true,
        'auto' => false
    )
);

//------------------------------------
// Create The Search Form Settings Panel
//  
$slpDescription = get_string_from_phpexec(SLPLUS_COREDIR.'/templates/settings_searchform.php');
$slpMapSettings->add_section(
    array(
            'name'          => __('Search Form',SLPLUS_PREFIX),
            'description'   => $slpDescription,
            'auto'          => true
        )
 );
   
//------------------------------------
// Create The Map Settings Panel
//  
$slpDescription = get_string_from_phpexec(SLPLUS_COREDIR.'/templates/settings_mapform.php');
$slpMapSettings->add_section(
    array(
            'name'          => __('Map',SLPLUS_PREFIX),
            'description'   => $slpDescription,
            'auto'          => true
        )
 );
    

//------------------------------------
// Info Panel
//
$slpDescription = 
    "Product Information: <a href='$slplus_plugin->url' target='cybersprocket'>$slplus_plugin->url</a><br/>";
if ($slplus_plugin->debugging) {
$slpDescription .= 
        "Basename:  ".SLPLUS_BASENAME    ."<br/>" .
        "Core Directory:   ".SLPLUS_COREDIR     ."<br/>" .
        "Plugin Directory: ".SLPLUS_PLUGINDIR   ."<br/>" .
        "Core URL: ".SLPLUS_COREURL   ."<br/>" .
        "Plugin URL: ".SLPLUS_PLUGINURL   ."<br/>" .
        "Admin Page: ".SLPLUS_ADMINPAGE   ."<br/>" .
        ""
        ;    
}        
$slpMapSettings->add_section(
    array(
            'name'          => __('Plugin Info',SLPLUS_PREFIX),
            'description'   => $slpDescription,
            'auto'          => true
        )
 );
    
//------------------------------------
// Render It 
//
print $update_msg;
$slpMapSettings->render_settings_page();    