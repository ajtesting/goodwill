<?php

/***********************************************************************
* Class: wpCSL_themes
*
* Manage the theme system for WordPress plugins.
*
************************************************************************/

class wpCSL_themes__slplus {
    


    /*-------------------------------------
     * method: __construct
     *
     * Overload of the default class instantiation.
     *
     */
    function __construct($params) {
        
        // Properties with default values
        //
        $this->columns = 1;                 // How many columns/row in our display output.
        $this->css_dir = 'css/';
        
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }

        // Remember the base directory path, then
        // Append plugin path to the directories
        //
        $this->css_url = $this->plugin_url . '/'. $this->css_dir;
        $this->css_dir = $this->plugin_path . 
            $this->css_dir;       
    }
    
    /*-------------------------------------
     * method: add_admin_settings
     *
     * Add the theme settings to the admin panel.
     *
     */
    function add_admin_settings($settingsObj = null) {
        if ($settingsObj == null) {
            $settingsObj = $this->settings;
        }

        // The Themes
        // No themes? Force the default at least
        //
        $themeArray = get_option($this->prefix.'-theme_array');
        if (count($themeArray, COUNT_RECURSIVE) < 2) {
            $themeArray = array('Default MP Layout' => 'mp-white-1up');
        } 
    
        // Check for theme files
        //
        $lastNewThemeDate = get_option($this->prefix.'-theme_lastupdated');
        $newEntry = array();
        if ($dh = opendir($this->css_dir)) {
            while (($file = readdir($dh)) !== false) {
                
                // If not a hidden file
                //
                if (!preg_match('/^\./',$file)) {                
                    $thisFileModTime = filemtime($this->css_dir.$file);
                    
                    // We have a new theme file possibly...
                    //
                    if ($thisFileModTime > $lastNewThemeDate) {
                        $newEntry = $this->GetThemeInfo($this->css_dir.$file);
                        $themeArray = array_merge($themeArray, array($newEntry['label'] => $newEntry['file']));                                        
                        update_option($this->prefix.'-theme_lastupdated', $thisFileModTime);
                    }
                }
            }
            closedir($dh);
        }

        // Delete the default theme if we have specific ones
        //
        $resetDefault = false;
        
        if ((count($themeArray, COUNT_RECURSIVE) > 1) && isset($themeArray['Default MP Layout'])){        
            unset($themeArray['Default MP Layout']);
            $resetDefault = true;
        }
        

        // We added at least one new theme
        //
        if ((count($newEntry, COUNT_RECURSIVE) > 1) || $resetDefault) {
            update_option($this->prefix.'-theme_array',$themeArray);
        }  
                            
        $settingsObj->add_item(
            __('Display Settings',$this->prefix), 
            __('Select A Theme',$this->prefix),   
            'theme',    
            'list', 
            false, 
            __('How should the plugin UI elements look?  Check the <a href="'.$this->support_url.'" target="Cyber Sprocket">documentation</a> for more info.',$this->prefix),
            $themeArray
        );        
    }    
    
    /**************************************
     ** method: GetThemeInfo
     ** 
     ** Extract the label & key from a CSS file header.
     **
     **/
    function GetThemeInfo ($filename) {    
        $dataBack = array();
        if ($filename != '') {
           $default_headers = array(
                'label' => 'label',
                'file' => 'file',
                'columns' => 'columns'
               );
            
           $dataBack = get_file_data($filename,$default_headers,'');
           $dataBack['file'] = preg_replace('/.css$/','',$dataBack['file']);       
        }
        
        return $dataBack;
     }    

 
    /**************************************
     ** method: configure_theme
     ** 
     ** Configure the plugin theme drivers based on the theme file meta data.
     **
     **/
     function configure_theme($themeFile) {
        $newEntry = $this->GetThemeInfo($this->css_dir.$themeFile);
        $this->products->columns = $newEntry['columns'];
     }
     
    /**************************************
     ** function: assign_user_stylesheet
     **
     ** Set the user stylesheet to what we selected.
     **/
    function assign_user_stylesheet() {
        $themeFile = get_option($this->prefix.'-theme') . '.css';
        if ($themeFile == '.css') { $themeFile='mp-white-1up.css'; }
        
        if ( file_exists($this->css_dir.$themeFile)) {
            wp_deregister_style($this->prefix.'_user_header_css');             
            wp_dequeue_style($this->prefix.'_user_header_css');             
            wp_register_style($this->prefix.'_user_header_css', $this->css_url .$themeFile); 
            wp_enqueue_style ($this->prefix.'_user_header_css');
            $this->configure_theme($themeFile);
        }      
    }     
}
