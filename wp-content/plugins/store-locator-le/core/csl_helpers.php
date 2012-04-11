<?php
/****************************************************************************
 ** file: csl_helpers.php
 **
 ** Generic helper functions.  May live in WPCSL-Generic soon.
 ***************************************************************************/

/**************************************
 ** function: csl_slplus_setup_admin_interface
 **
 ** Builds the interface elements used by WPCSL-generic for the admin interface.
 **/
function csl_slplus_setup_admin_interface() {
    global $slplus_plugin;
    
    // Don't have what we need? Leave.
    if (!isset($slplus_plugin)) { return; }

    // Already been here?  Get out.
    if (isset($slplus_plugin->settings->sections['How to Use'])) { return; }

    //-------------------------
    // Navbar Section
    //-------------------------    
    $slplus_plugin->settings->add_section(
        array(
            'name' => 'Navigation',
            'div_id' => 'slplus_navbar',
            'description' => get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php'),
            'is_topmenu' => true,
            'auto' => false
        )
    );
    
    //-------------------------
    // Option Packages
    //-------------------------
    if (!$slplus_plugin->no_license) {    
        $slplus_plugin->license->add_licensed_package(
                array(
                    'name'              => 'Widget Pack',
                    'help_text'         => 'Click the buy now button to purchase this add-on.  When done, refresh this page.',
                    'sku'               => 'SLPLUS-WIDGETS',
                    'paypal_button_id'  => 'FA99CZBPNZJGG'
                )            
            );
    }        
  
    //-------------------------
    // How to Use Section
    //-------------------------    
    $slplus_plugin->settings->add_section(
        array(
            'name' => 'How to Use',
            'description' => get_string_from_phpexec(SLPLUS_PLUGINDIR.'/how_to_use.txt'),
            'start_collapsed' => true
        )
    );

    //-------------------------
    // Google Communiations
    //-------------------------    
    $slplus_plugin->settings->add_section(
        array(
            'name'        => 'Google Communication',
            'description' => 'These settings affect how the plugin communicates with Google to create your map.'.
                                '<br/><br/>'
        )
    );
    
    $slplus_plugin->settings->add_item(
        'Google Communication', 
        'Google API Key', 
        'api_key', 
        'text', 
        false,
        'Your Google API Key.  You will need to ' .
        '<a href="http://code.google.com/apis/maps/signup.html" target="newinfo">'.
        'go to Google</a> to get your Google Maps API Key.'
    );


    $slplus_plugin->settings->add_item(
        'Google Communication', 
        'Geocode Retries', 
        'goecode_retries', 
        'list', 
        false,
        'How many times should we try to set the latitude/longitude for a new address. ' .
        'Higher numbers mean slower bulk uploads ('.
        '<a href="http://www.cybersprocket.com/products/store-locator-plus/">plus version</a>'.
        '), lower numbers makes it more likely the location will not be set during bulk uploads.',
        array (
              'None' => 0,
              '1' => '1',
              '2' => '2',
              '3' => '3',
              '4' => '4',
              '5' => '5',
              '6' => '6',
              '7' => '7',
              '8' => '8',
              '9' => '9',
              '10' => '10',
            )
    );
    
    //-------------------------
    // Reporting
    //-------------------------   
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {          
        $slp_rep_desc = __('These settings affect how the reporting system behaves. ', SLPLUS_PREFIX);
        if (!function_exists('slplus_add_report_settings')) {
            $slp_rep_desc .= '<br/><br/>'.
                __('This is a <a href="http://www.cybersprocket.com/products/store-locator-plus/">plus version</a>'.
                ' feature.  It provides a way to generate reports on what locations' .
                ' people have searched for and what results they received back. ', SLPLUS_PREFIX);
        }
        $slp_rep_desc .= '<br/><br/>'; 
            
        $slplus_plugin->settings->add_section(
            array(
                'name'        => 'Reporting',
                'description' => $slp_rep_desc
            )
        );
        
        if (function_exists('slplus_add_report_settings')) {
            slplus_add_report_settings();
        }
    }        
}
 
 
/**************************************
 ** function: get_string_from_phpexec()
 ** 
 ** Executes the included php (or html) file and returns the output as a string.
 **
 ** Parameters:
 **  $file (string, required) - name of the file 
 **/
function get_string_from_phpexec($file) {
    if (file_exists($file)) {
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    } else {
    	    print "No file: $file in ".getcwd()."<br/>";
    }
}
 
 
/**************************************
 ** function: execute_and_output_template()
 ** 
 ** Executes the included php (or html) file and prints out the results.
 ** Makes for easy include templates that depend on processing logic to be
 ** dumped mid-stream into a WordPress page.  A plugin in a plugin sorta.
 **
 ** Parameters:
 **  $file (string, required) - name of the file in the plugin/templates dir
 **/
function execute_and_output_template($file) {
    $file = SLPLUS_COREDIR.'/templates/'.$file;
    print get_string_from_phpexec($file);
}

/**************************************
 ** function: slp_createhelpdiv()
 ** 
 ** Generate the string that displays the help icon and the expandable div
 ** that mimics the WPCSL-Generic forms more info buttons.
 **
 ** Parameters:
 **  $divname (string, required) - the name of the div to toggle
 **  $msg (string, required) - the message to display
 **/
function slp_createhelpdiv($divname,$msg) {
    return "<a onclick=\"swapVisibility('".SLPLUS_PREFIX."-help$divname');\" href=\"javascript:;\">".
        "<img class='helpicon' border='0' title='More info' alt='More info' src='".SLPLUS_COREURL."images/help-icon-18x20.png'>".
        "</a>".
        "<div id='".SLPLUS_PREFIX."-help$divname' class='input_note' style='display: none;'>".
            $msg. 
        "</div>"
        ;
}


/**************************************
 ** function: setup_stylesheet_for_slplus
 **
 ** Setup the CSS for the product pages.
 **/
function setup_stylesheet_for_slplus() {
    global $slplus_plugin;
    $slplus_plugin->themes->assign_user_stylesheet();    
}

/**************************************
 ** function: setup_ADMIN_stylesheet_for_slplus
 **
 ** Setup the CSS for the admin page.
 **/
function setup_ADMIN_stylesheet_for_slplus() {
    if ( file_exists(SLPLUS_PLUGINDIR.'css/admin.css')) {
        wp_register_style('csl_slplus_admin_css', SLPLUS_PLUGINURL .'/css/admin.css'); 
        wp_enqueue_style ('csl_slplus_admin_css');
    }
}

