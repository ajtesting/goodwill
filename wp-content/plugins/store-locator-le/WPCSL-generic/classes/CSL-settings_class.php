<?php

/****************************************************************************
 **
 ** class: wpCSL_settings__slplus
 **
 ** The main settings class.
 ** 
 **/
class wpCSL_settings__slplus {

    /**------------------------------------
     ** method: __construct
     **
     ** Overload of the default class instantiation.
     **
     **/
    function __construct($params) {
        // Default Params
        //
        $this->render_csl_blocks = true;        // Display the CSL info blocks
        $this->form_action = 'options.php';     // The form action for this page
        $this->save_text =__('Save Changes');
        $this->css_prefix = '';  
        $this->has_packages = false;
        
        // Passed Params
        //        
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }

        // Only show the license section if the plugin settings
        // wants a license module
        if (!$this->no_license) {
            $this->license_section_title = 'Plugin License';
            $this->add_section(array(
                    'name' => $this->license_section_title,
                    'description' => "<p>To obtain a key, please purchase this plugin " .
                        "from <a href=\"{$this->url}\" target=\"_new\">{$this->url}</a>.</p>",
                    'auto' => false,
                    'div_id' => 'csl_license_block'
                )
            );
            
        // We don't have a main license but we have paid option
        // packages
        } else if ($this->has_packages) {
            $this->license_section_title = 'Premium Options';
            $this->add_section(array(
                    'name' => $this->license_section_title,
                    'description' => "<p>{$this->name} has premium options available.<br/>" .
                        "Visit <a href=\"{$this->url}\" target=\"_new\">{$this->url}</a> to " .
                        "learn more about the available add-on packages.<br/> After you purchase " .
                        "an add-on package come back here to activate your add-on packages.</p>",
                    'auto' => false,
                    'div_id' => 'csl_license_block'
                )
            );
        }

        // Render CSL Blocks - if set false we don't need this overhead
        //
        if ($this->render_csl_blocks) {        
            $this->csl_php_modules = get_loaded_extensions();
            natcasesort($this->csl_php_modules);
            global $wpdb;
            $this->add_section(
                array(
                    'name' => 'Plugin Environment',
                    'description' =>
                        '<p>Here are the technical details about your plugin:<br />
                           <div style="border: solid 1px #E0E0E0; padding: 6px; margin: 6px;
                               background-color: #F4F4F4;">
                               
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">Active WPCSL:</div>
                               <div style="float: left;">' . plugin_dir_path(__FILE__) . '</div>
                             </div>                                
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">Site URL:</div>
                               <div style="float: left;">' . get_option('siteurl') . '</div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">Encryption Key:</div>
                               <div style="float: left;">' . md5(get_option($this->prefix.'-license_key')) . '</div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">License Key:</div>
                               <div style="float: left;">' . (get_option($this->prefix.'-purchased')?'licensed':'unlicensed') . '</div>
                             </div>
                             
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">WPCSL Version:</div>
                               <div style="float: left;">' . WPCSL__slplus__VERSION . '
                               </div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">WordPress Version:</div>
                               <div style="float: left;">' . $GLOBALS['wp_version'] . '
                               </div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">MySQL Version:</div>
                               <div style="float: left;">' . $wpdb->db_version() . '
                               </div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">PHP Version:</div>
                               <div style="float: left;">' . phpversion() .'</div>
                             </div>
                             <div style="clear:left;">
                               <div style="width:150px; float:left; text-align: right;
                                   padding-right: 6px;">PHP Modules:</div>
                               <div style="float: left;">' .
                                 implode('<br/>',$this->csl_php_modules) . '
                               </div>
                             </div>
                             <div style="clear:left;">&nbsp;</div>
                           </div>
                         </p>',
                    'auto' => false,
                    'start_collapsed' => true
                )
            );
    
            $this->add_item(
                'Plugin Environment', 
                'Enable Debugging Output: ',   
                'debugging',    
                'checkbox'
            );
    
            $this->add_section(array(
                    'name' => 'Plugin Info',
                    'description' =>
                        '<div class="cybersprocket-cslbox">
                        <div class="cybersprocket-csllogo">
                        <a href="http://www.cybersprocket.com/" target="cslinfo"><img src="'. $this->plugin_url .'/images/CSL_banner_logo.png"/></a>
                         </div>
                         <div class="cybersprocket-cslinfo">
                         <h4>This plugin has been brought to you by <a href="http://www.cybersprocket.com"
                                target="_new">Cyber Sprocket Labs</a></h4>
                         <p>Cyber Sprocket Labs is a custom software development company.  
                            We develop desktop, mobile, and web applications for clients large and small  
                            from all around the world. We hope our plugin brings you closer to the perfect site.
                            If there is anything we can do to improve our work or if you wish to hire us to customize
                            this plugin please call our Charleston South Carolina headquarters or 
                            <a href="http://www.cybersprocket.com/contact-us/" target="cyber-sprocket-labs">email us</a>
                            and let us know.<br/>
                            <br>
                            <strong>Cyber Sprocket Is...</strong><br/>
                            Lance Cleveland, Paul Grimes, Chris Rasys, Lobby Jones, Seth Hayward<br/>
                            <br/>
                            <strong>For more information:</strong><br/>
                            <a href="http://www.cybersprocket.com" target="cyber-sprocket-labs">Please visit our website at www.cybersprocket.com</a>.<br/>
                         </p>
                         </div>
                         </div>
                         ',
                    'auto' => false
                )
            );
        }       
    }

    /**------------------------------------
     ** method: add_section
     **
     **/
    function add_section($params) {
        if (!isset($this->sections[$params['name']])) {
            $this->sections[$params['name']] = new wpCSL_settings_section__slplus(
                array_merge(
                    $params,
                    array('plugin_url' => $this->plugin_url,
                          'css_prefix' => $this->css_prefix,                       
                            )
                )
            );
        }            
    }

    /**------------------------------------
     ** Class: WPCSL_Settings
     **------------------------------------
     ** Method: add_item 
     ** 
     ** Parameters:
     **    section name
     **    display name, the label that shows before the input field
     **    name, the database key for the setting
     **    type (default: text, list, checkbox, textarea)
     **    required setting? (default: false, true)
     **    description (default: null) - this is what shows via the expand/collapse setting
     **    custom (default: null, name/value pair if list
     **    value (default: null), the value to use if not using get_option
     **
     **/
    function add_item($section, $display_name, $name, $type = 'text',
            $required = false, $description = null, $custom = null,
            $value = null
            ) {

        $name = $this->prefix .'-'.$name;
    
        $this->sections[$section]->add_item(
            array(
                'prefix' => $this->prefix,
                'css_prefix' => $this->css_prefix,                
                'display_name' => $display_name,
                'name' => $name,
                'type' => $type,
                'required' => $required,
                'description' => $description,
                'custom' => $custom,
                'value' => $value
            )
        );

        if ($required) {
            if (get_option($name) == '') {
                if (isset($this->notifications)) {
                    $this->notifications->add_notice(
                        1,
                        "Please provide a value for <em>$display_name</em>",
                        "options-general.php?page={$this->prefix}-options#".
                            strtolower(strtr($display_name,' ', '_'))
                    );
                }
            }
        }
    }

    /**------------------------------------
     ** Method: register
     ** 
     ** This function should be used via an admin_init action 
     **
     **/
    function register() {
        if (isset($this->license)) {
            $this->license->initialize_options();
        }
        if (isset($this->cache)) {
            $this->cache->initialize_options();
        }

        foreach ($this->sections as $section) {
            $section->register($this->prefix);
        }
    }

    /**------------------------------------
     ** method: render_settings_page
     **
     ** Create the HTML for the plugin settings page on the admin panel
     **/
    function render_settings_page() {
        $this->header();
        
        // Redner all top menus first.
        //
        foreach ($this->sections as $section) {
            if (isset($section->is_topmenu) && ($section->is_topmenu)) {
                $section->display();
            }
        }        

        // Only render license section if plugin settings
        // asks for it
        if ($this->has_packages || !$this->no_license) {
            $this->sections[$this->license_section_title]->header();
            $this->show_plugin_settings();
            $this->sections[$this->license_section_title]->footer();
        }

        // Draw each settings section as defined in the plugin config file
        //
        foreach ($this->sections as $section) {
            if ($section->auto) {
                $section->display();
            }
        }

        // Show the plugin environment and info section on every plugin
        //
        if ($this->render_csl_blocks) {
            $this->sections['Plugin Environment']->display();
            $this->sections['Plugin Info']->display();
        }
        $this->render_javascript();
        $this->footer();
    }

    /**------------------------------------
     ** method: show_plugin_settings
     **
     ** This is a function specifically for showing the licensing stuff,
     ** should probably be moved over to the licensing submodule
     **/
    function show_plugin_settings() {
       $license_ok =(  (get_option($this->prefix.'-purchased') == '1')   &&
                      (get_option($this->prefix.'-license_key') != '')            	    	    
                          );     
        
        // If has_packages is true that means we have an unlicensed product
        // so we don't want to show the license box
        //
        if (!$this->has_packages) {
            $content = "<tr valign=\"top\">\n";
            $content .= "  <th  class=\"input_label\" scope=\"row\">License Key *</th>";
            $content .= "    <td>";
            $content .= "<input type=\"text\"".
                ((!$license_ok) ?
                    "name=\"{$this->prefix}-license_key\"" :
                    '') .
                " value=\"". get_option($this->prefix.'-license_key') .
                "\"". ($license_ok?'disabled' :'') .
                " />";
    
            if ($license_ok) {
                $content .= "<input type=\"hidden\" name=\"{$this->prefix}-license_key\" value=\"".
                    get_option($this->prefix.'-license_key')."\"/>";
                $content .= '<span><img src="'. $this->plugin_url .
                    '/images/check_green.png" border="0" style="padding-left: 5px;" ' .
                    'alt="License validated!" title="License validated!"></span>';
            }
            
            $content .= (!$license_ok) ?
                ('<span><font color="red"><br/>Without a license key, this plugin will ' .
                    'only function for Admins</font></span>') :
                '';
            $content .= (!(get_option($this->prefix.'-license_key') == '') &&
                        !get_option($this->prefix.'-purchased')) ?
                ('<span><font color="red">Your license key could not be verified</font></span>') :
                '';
    
            if (!$license_ok) {
                $content .= $this->MakePayPalButton($this->paypal_button_id);
            }
            
            $content .= '<div id="prodsku">sku: ';
            if (isset($this->sku) && ($this->sku != '')) {
                $content .= $this->sku;
            } else {
                $content .= 'not set';            
            }        
            $content .= '</div>';
            

            
        // If we are using has_packages we need to seed our content string
        //
        } else {
            $content ='';
        }            
      
        // List the packages
        //
        if (isset($this->parent->license->packages) && ($this->parent->license->packages > 0)) {
            $content .='<tr><td colspan="2" class="optionpack_topline">'.__('The following optional add-ons are available').':</td></tr>';
            $content .= '<tr valign="top">';
            foreach ($this->parent->license->packages as $package) {
                $content .= '<th class="input_label optionpack">'.$package->name.'</th>';
                $content .= '<td class="optionpack">'.$this->EnabledOrBuymeString($license_ok,$package).'</td>';
            }

            $content .= '</tr>';
        }
        
        // If the main product or packages show the license box
        // Then show a save button here
        //
       $license_ok =(  (get_option($this->prefix.'-purchased') == '1')   &&
                      (get_option($this->prefix.'-license_key') != '')            	    	    
                          );            
        if (!$license_ok) {
            $content .= '<tr><td colspan="2">' .
                $this->generate_save_button_string().
                '</td></tr>';
        }

        echo $content;                
    }
    
    /**------------------------------------
     ** method: EnabledOrBuymeString
     **
     **/
    function EnabledOrBuymeString($mainlicenseOK, $package) {
        $content = '';
        
        // If the main product is licensed or we want to force
        // the packages list, show the checkbox or buy/validate button. 
        //
        if ($mainlicenseOK || $this->has_packages) {
            
            // Check if package is licensed now.
            //

            $package->isenabled =
                $package->parent->check_license_key(
                    $package->sku,
                    true,
                    ($this->has_packages ? $package->license_key : '')
                );

            $installed_version = get_option($this->prefix.'-'.$package->sku.'-version');
            $latest_version = get_option($this->prefix.'-'.$package->sku.'-latest-version');

            // Upgrade is available if the current package version < the latest available
            // -AND- the current package version is has been set
            $upgrade_available = (
                        ($installed_version != '') &&                
                        (   get_option($this->prefix.'-'.$package->sku.'-version-numeric') <
                            get_option($this->prefix.'-'.$package->sku.'-latest-version-numeric')
                        )                        
                    );

            // Package is enabled, just show that
            //
            if ($package->isenabled) {
                $packString = $package->name . ' is enabled!';

                $content .=
                    '<div><img src="'. $this->plugin_url .
                    '/images/check_green.png" border="0" style="padding-left: 5px;" ' .
                    'alt="'.$packString.'" title="'.$packString.'">' .
                    'Version ' . $installed_version .'</div>'.
                    '<input type="hidden" '.
                            'name="'.$package->lk_option_name.'" '.
                            ' value="'.$package->license_key.'" '.
                            ' />';
                    ;
                    
                // OK - the license was verified, this package is valid
                // but the mainlicense was not set...
                // go set it.
                if (!$mainlicenseOK && ($package->license_key != '')) {
                    update_option($this->prefix.'-purchased',true);   
                    update_option($this->prefix.'-license_key',$package->license_key);
                }                      
                    
            // Package not enabled, show buy button
            //
            }

            if (!$package->isenabled || $upgrade_available) {
                if ($package->isenabled && $upgrade_available) {
                    $content .= '<b>There is a new version available: ' . $latest_version . '</b><br>';
                    $content .= $this->MakePayPalButton($package->paypal_upgrade_button_id, $package->help_text);
                    $content .= "Once you've made your purchase, the plugin will automatically re-validate with the latest version.";
                } else {
                    $content .= $this->MakePayPalButton($package->paypal_button_id, $package->help_text);
                }

                // Show license entry box if we need to
                //
                if ($this->has_packages && !$upgrade_available) {
                    $content .= "{$package->sku} Activation Key: <input type='text' ".
                            "name='{$package->lk_option_name}'" .
                            " value='' ".
                            " />";                     
                    if ($package->license_key != '') {
                        $content .= 
                            "<br/><span class='csl_info'>".
                            "The key {$package->license_key} could not be validated.".
                            "</span>";
                    }
                }
            }
            
        // Main product not licensed, tell them.
        //
        } else {
            $content .= '<span>You must license the product before you can purchase add-on packages.</span>';
        }
        
        return $content;
    }
    
    /**------------------------------------
     ** method: MakePayPalButton
     **
     **/
    function MakePayPalButton($buttonID, $helptext = '') {
        
        // Set default help text
        //
        if ($helptext == '') {
            $helptext = 'Your license key is emailed within minutes of your purchase.<br/>'. 
                  'If you do not receive your license check your spam '.
                     'folder then <a href="http://www.cybersprocket.com/contact-us/" '.
                     'target="Cyber Sprocket">Contact us</a>.';
        }
        
        // PayPal Form String
        $ppFormString = 
                    "<form action='https://www.paypal.com/cgi-bin/webscr' target='_blank' method='post'>".
                    "<input type='hidden' name='cmd' value='_s-xclick'>".
                    "<input type='hidden' name='hosted_button_id' value='$buttonID'>".
                    "<input type='hidden' name='on0' value='Main License Key'>".
                    "<input type='hidden' name='os0' value='" . get_option($this->prefix.'-license_key') . "'>".                    "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='Lobby says buy more sprockets!'>".
                    "<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>".                    
                    "</form>"
                ;
        
        // Modal Form Helpers
        //
        // 
        //
        $modalFormSetup = '
            <script>
            jQuery(function() {
                jQuery( "#ppform_iframe_'.$buttonID.'" ).contents().find("body").html("'.$ppFormString.'");                                
            });
            </script>        
            ';
            
        // Build paypal form and send it back
        //
        return $modalFormSetup .
        '<div><iframe height="70" scrolling="no" id="ppform_iframe_'.$buttonID.'" name="ppform_iframe_'.$buttonID.'" src=""></iframe></div>'.                
                '<div>'.
                  '<p>'.$helptext.'</p>'.
                '</div>';
    }
    
    

    /**------------------------------------
     ** method: header
     **
     **/
    function header() {
        echo "<div class='wrap'>\n";
        screen_icon(preg_replace('/\s/','_',$this->name));
        echo "<h2>{$this->name}</h2>\n";
        echo "<form method='post' action='".$this->form_action."'>\n";
        echo settings_fields($this->prefix.'-settings');

        echo "\n<div id=\"poststuff\" class=\"metabox-holder\">
     <div class=\"meta-box-sortables\">
       <script type=\"text/javascript\">
         jQuery(document).ready(function($) {
             $('.postbox').children('h3, .handlediv').click(function(){
                 $(this).siblings('.inside').toggle();
             });
         });         
         jQuery(document).ready(function($) {
             $('.".$this->css_prefix."-moreicon').click(function(){
                 $(this).siblings('.".$this->css_prefix."-moretext').toggle();
             });
         });         
       </script>\n";
    }

    /**------------------------------------
     ** method: footer
     **
     **/
    function footer() {
        print '</div></div>' .
              $this->generate_save_button_string() .
             '</form></div>';
    }
        
    /**------------------------------------
     ** method: generate_save_button_string
     **
     **/
    function generate_save_button_string() {
        return sprintf('<input type="submit" class="button-primary" value="%s" />',
         $this->save_text
         );                    
    }

    /**------------------------------------
     ** method: render_javascript
     **
     **/
    function render_javascript() {
        echo "<script type=\"text/javascript\">
            function swapVisibility(id) {
              var item = document.getElementById(id);
              item.style.display = (item.style.display == 'block') ? 'none' : 'block';
            }
          </script>";
    }

    /**------------------------------------
     ** method: check_required
     **
     **/
    function check_required($section = null) {
        if ($section == null) {
            foreach ($this->sections as $section) {
                foreach ($section->items as $item) {
                    if ($item->required && get_option($item->name) == '') return false;
                }
            }
        } else {
            
            // The requested section does not exist yet.
            if (!isset($this->sections[$section])) { return false; }
            
            // Check the required items
            //
            foreach ($this->sections[$section]->items as $item) {
                if ($item->required && get_option($item->name) == '') return false;
            }
        }

        return true;
    }

}

/****************************************************************************
 **
 ** class: wpCSL_settings_section__slplus
 **
 **/
class wpCSL_settings_section__slplus {

    /**------------------------------------
     **/
    function __construct($params) {
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }

        if (!isset($this->auto)) $this->auto = true;
    }

    /**------------------------------------
     ** Class: wpCSL_settings_section
     ** Method: add_item
     **
     **/
    function add_item($params) {
        $this->items[] = new wpCSL_settings_item__slplus(
            array_merge(
                $params,
                array('plugin_url' => $this->plugin_url,
                      'css_prefix' => $this->css_prefix,
                      )
            )
        );
    }

    /**------------------------------------
     **/
    function register($prefix) {
        if (!isset($this->items)) return false;
        foreach ($this->items as $item) {
            $item->register($prefix);
        }
    }

    /**------------------------------------
     **/
    function display() {
        $this->header();

        if (isset($this->items)) {
            foreach ($this->items as $item) {
                $item->display();
            }
        }

        $this->footer();
    }

    /**------------------------------------
     **/
    function header() {
        echo "<div class=\"postbox\" " . (isset($this->div_id) ?  "id='$this->div_id'" : '') . ">
         <div class=\"handlediv\" title=\"Click to toggle\"><br/></div>
         <h3 class=\"hndle\">
           <span>{$this->name}</span>
           <a name=\"".strtolower(strtr($this->name, ' ', '_'))."\"></a>
         </h3>
         <div class=\"inside\" " . (isset($this->start_collapsed) && $this->start_collapsed ? 'style="display:none;"' : '') . ">
            <div class='section_description'>{$this->description}</div>
    <table class=\"form-table\" style=\"margin-top: 0pt;\">\n";

    }

    /**------------------------------------
     **/
    function footer() {
        echo "</table>
         </div>
       </div>\n";
    }

}

/****************************************************************************
 **
 ** class: wpCSL_settings_item__slplus
 **
 ** Settings Page : Items Class
 ** This class manages individual settings on the admin panel settings page.
 **
 **/
class wpCSL_settings_item__slplus {

    /**------------------------------------
     **/
    function __construct($params) {
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
    }

    /**------------------------------------
     **/
    function register($prefix) {
        register_setting( $prefix.'-settings', $this->name );
    }

    /**------------------------------------
     **/
    function display() {
        $this->header();
        if (isset($this->value)) {
            $showThis = $this->value;
        } else {
            $showThis = get_option($this->name);
        }
        $showThis = htmlspecialchars($showThis);
        
        echo '<div class="'.$this->css_prefix.'-input">';
        
        switch ($this->type) {
            case 'textarea':
                echo "<textarea name=\"{$this->name}\" cols=\"50\" rows=\"5\">".
                    $showThis ."</textarea>";
                break;

            case 'text':
                echo "<input type=\"text\" name=\"{$this->name}\" value=\"". $showThis ."\" />";
                break;

            case "checkbox":
                echo "<input type=\"checkbox\" name=\"{$this->name}\"".
                    (($showThis) ? ' checked' : '').">";
                break;

            case "list":
                echo $this->create_option_list();
                break;
                
            case "submit_button":
                echo '<input class="button-primary" type="submit" value="'.$showThis.'">';
                break;                

            default:
                echo $this->custom;
                break;

        }
        echo '</div>';

        if ($this->description != null) {
            $this->display_description_icon();
        }

        if ($this->required) {
            echo ((get_option($this->name) == '') ?
                '<div class="'.$this->css_prefix.'-reqbox">'.
                    '<div class="'.$this->css_prefix.'-reqicon"></div>'.
                    '<div class="'.$this->css_prefix.'-reqtext">This field is required.</div>'.
                '</div>'
                : ''
                );
        }
        
        if ($this->description != null) {
            $this->display_description_text($this->description);
        }

        
        
        $this->footer();
    }

    /**------------------------------------
     * If $type is 'list' then $custom is a hash used to make a <select>
     * drop-down representing the setting.  This function returns a
     * string with the markup for that list.
     */
    function create_option_list() {
        $output_list = array("<select class='csl_select' name=\"{$this->name}\">\n");

        foreach ($this->custom as $key => $value) {
            if (get_option($this->name) === $value) {
                $output_list[] = "<option class='csl_option' value=\"$value\" " .
                    "selected=\"selected\">$key</option>\n";
            }
            else {
                $output_list[] = "<option class='csl_option'  value=\"$value\">$key</option>\n";
            }
        }

        $output_list[] = "</select>\n";
        
        return implode('', $output_list);
    }

    /**------------------------------------
     **/
    function header() {
        echo "<tr><th class='input_label' scope='row'>" .
        "<a name='" .
        strtolower(strtr($this->display_name, ' ', '_')).
            "'></a>{$this->display_name}".
            (($this->required) ? ' *' : '').
            '</th><td>';

    }

    /**------------------------------------
     **/
    function footer() {
        echo '</td></tr>';
    }

    /**------------------------------------
     **/
    function display_description_icon() {
        echo '<div class="'.$this->css_prefix.'-moreicon" title="click for more info"><br/></div>';        
    }    
    
    /**------------------------------------
     **/
    function display_description_text($content) {
        echo '<div class="'.$this->css_prefix.'-moretext">';
        echo $content;
        echo '</div>';
    }
}
