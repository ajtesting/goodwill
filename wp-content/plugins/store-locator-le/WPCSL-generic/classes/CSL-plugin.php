<?php
/************************************************************************
*
* file: CSL-plugin.php
*
* The main Cyber Sprocket library for communicating effectively with 
* WordPress.   This class manages the related helper classes so we can 
* share a code libary and reduce code redundancy.
* 
************************************************************************/
define('WPCSL__slplus__VERSION', '1.5.1');

// (LC) 
// These helper files should only be loaded if needed by the plugin
// that is asking for WPCSL-Generic services.
//
// Wrap inside the init and check the class properties first?
// 
require_once('CSL-cache_class.php');
require_once('CSL-helper_class.php');
require_once('CSL-license_class.php');
require_once('CSL-notifications_class.php');
require_once('CSL-products_class.php');
require_once('CSL-settings_class.php');
require_once('CSL-themes_class.php');


/*****************************************************************************
* Class: WPCSL_plugin
*
* This class does most of the heavy lifting for creating a plugin.
* It takes a hash as its one constructor argument, which can have the
* following keys and values:
*
*     * 'basefile' :: Path and filename of main plugin file. Needed so wordpress
*               can tell which plugin is calling some of it's generic hooks.
*
*     * 'css_prefix' :: The prefix to add to CSS classes, use 'csl_theme' to
*               enable generic themes.
*
*     * 'driver_defaults' :: A hash where the keys are the names of
*       support options for a Panhandler driver, and the values are
*       the names of Wordpress settings which will provide the
*       default values for those driver options.  See the method
*       'get_supported_options()' in the Panhandler code for a
*       description of driver options.  The names of the settings
*       should not include the prefix, i.e. write:
*
*           'driver_defaults' => array(
*               'keywords' => 'keywords'
*           )
*
*       instead of
*
*           'driver_defaults' => array(
*               'keywords' => 'csl-mp-ebay-keywords'
*           )
*
*     * 'name' :: The name of the plugin.
*
*     * 'prefix' :: A string used to prefix all of the Wordpress
*       settings for the plugin.
*
*     * 'support_url' :; The URL for the support page at Cyber Sprocket Labs
*
*     * 'purchase_url' :: The URL for purchasing the plugin
*
*     * 'url' :: The URL for the product page at Cyber Sprocket Labs.
*
*     * 'has_packages' :: defaults to false, if true that means the main product is
*       not licensed but we still need the license class to manage add-ons.
*
*/
class wpCSL_plugin__slplus {

    /**-------------------------------------
     **/
    function __construct($params) {

        // These settings can be overridden
        //
        $this->no_license       = false;
        $this->themes_enabled   = false;
        $this->columns          = 1;
        $this->driver_type      = 'Panhandler';
        $this->css_prefix       = '';
        $this->sku              = '';
        $this->uses_money       = true;
        $this->has_packages     = false;

        // Do the setting override or initial settings.
        //
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
        
        // Debugging Flag
        $this->debugging = (get_option($this->prefix.'-debugging') == 'on');
        
        // What prefix do we add to the CSS elements?
        if ($this->css_prefix == '') {
            $this->css_prefix = $this->prefix;
        }

        // Store the license option here to prevent
        // multiple DB lookups
        $this->purchased = false;

        // Determine whether or not we need to have a valid license
        // this will disable all license checking/presentation
        //
        if (!isset($this->paypal_button_id)) { $this->paypal_button_id = ''; }
        $this->no_license = ($this->paypal_button_id == '');

        // Make sure we have WP_Http for http posts
        // then instatiate it here in the http_handler property
        // of this class.
        //
        if( !class_exists( 'WP_Http' ) ) {
            include_once( ABSPATH . WPINC. '/class-http.php' );
        }
        if ( class_exists( 'WP_Http' ) ) {
            $this->http_handler = new WP_Http;
        } else if ($this->debugging) {
            print "WordPress HTTP Handler is not available.<br/>\n";
        }

        // Debugging Flag
        $this->debugging = (get_option($this->prefix.'-debugging') == 'on');

        $this->notifications_config = array(
            'prefix' => $this->prefix,
            'name' => $this->name,
            'url' => 'options-general.php?page='.$this->prefix.'-options',
        );
        
        if ($this->driver_type != 'none') {
            $this->products_config = array(
                'prefix'            => $this->prefix,
                'css_prefix'        => $this->css_prefix,
                'columns'           => $this->columns,
             );
        }            

        $this->settings_config = array(
            'prefix'            => $this->prefix,
            'css_prefix'        => $this->css_prefix,
            'plugin_url'        => $this->plugin_url,
            'name'              => $this->name,
            'url'               => $this->url,
            'paypal_button_id'  => $this->paypal_button_id,
            'no_license'        => $this->no_license,
            'sku'               => $this->sku,
            'has_packages'      => $this->has_packages,
            'parent'            => $this
            
        );

        $this->cache_config = array(
            'prefix' => $this->prefix,
            'path' => $this->cache_path
        );
        
        if ($this->has_packages || !$this->no_license) {
            $this->license_config = array(
                'prefix'        => $this->prefix,
                'http_handler'  => $this->http_handler,
                'sku'           => $this->sku,
                'has_packages'  => $this->has_packages
            );
        }            

        $this->themes_config = array(
            'prefix'        => $this->prefix,
            'plugin_path'   => $this->plugin_path,
            'plugin_url'    => $this->plugin_url,  
            'support_url'   => $this->support_url
        );

        $this->initialize();
    }

    /**-------------------------------------
     ** method: ok_to_show
     **
     ** returns true if... 
     **
     ** the plugin has been purchased
     ** the user is an admin
     **
     **/
    function ok_to_show() {
        global $current_user;

        // this instantiation already knows we're licensed
        if ($this->purchased) { 
            return true; // Short circuit, no need to set this again below

        // purchase already recorded
        } else if (get_option($this->prefix.'-purchased') == '1')  { 
            $this->purchased = true;
            return true;

        // user is an admin
        } else if (current_user_can('administrator')) {
            $this->purchased = true;
            return true;

        // purchase not recorded - recheck it on the server
        } else if ($this->no_license || $this->license->check_license_key())      { 
            $this->purchased = true;
            return true;
        }

        // We are not running a licensed copy
        // show the reason via debugging        
        if ($this->debugging) {
            print "Purchased flag: " . get_option($this->prefix.'-purchased') . "<br/>\n";
            if (!isset($current_user)) {
                print "Current user is not set.<br/>\n";
            } else {
                print "Current User ID: " . $current_user->ID . "<br/>\n";
                if ($current_user->ID > 0) {
                    print "Capabilities:<pre>\n";
                    print_r($current_user->wp_capabilities);
                    print "</pre>\n";
                } else {
                    print "You are not logged in.<br/>\n";
                }                    
            }
        }

        return false;                 // And tell our "callers"    
    }

    /**-------------------------------------
     ** Method: CSL_ARRAY_FILL_KEYS
     ** Our own version of the php5.2 array_fill_keys
     ** So we can hopefully stay with php5.1 compatability
     **/
    function csl_array_fill_keys($target,$value='') {
        if(is_array($target)) {
            foreach($target as $key => $val) {
                $filledArray[$val] = is_array($value) ? $value[$key] : $value;
            }
        }
        return $filledArray;
    }
    

    /**-------------------------------------
     ** method: create_helper
     **
     ** Instantiates the helper class and attaches it to an instantiation
     ** of this class.
     **
     **/
    function create_helper($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_helper__slplus':
            case 'default':
            default:
                $this->helper = new wpCSL_helper__slplus();

        }
    }    

    /**-------------------------------------
     ** method: create_notifications
     **/
    function create_notifications($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_notifications__slplus':
            case 'default':
            default:
                $this->notifications = 
                    new wpCSL_notifications__slplus($this->notifications_config);
        }
    }
    
   
    /**-------------------------------------
     ** method: create_products
     **/
    function create_products($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_products__slplus':
            case 'default':
            default:
                $this->products = new wpCSL_products__slplus($this->products_config);

        }
    }
    


    /***********************************************
     ** method: create_settings
     **/
    function create_settings($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_settings__slplus':
            case 'default':
            default:
                $this->settings = new wpCSL_settings__slplus($this->settings_config);

        }
    }

   
    /**-------------------------------------
     ** method: create_themes
     **/
    function create_themes($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_products__slplus':
            case 'default':
            default:
                $this->themes = new wpCSL_themes__slplus($this->themes_config);

        }
    }    
    

    /**-------------------------------------
     ** method: create_license
     **/
    function create_license($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_license__slplus':
            case 'default':
            default:
                if ($this->has_packages || !$this->no_license) {
                    $this->license = new wpCSL_license__slplus($this->license_config);
                }

        }
    }

    /**-------------------------------------
     ** method: create_cache
     **/
    function create_cache($class = 'none') {
        switch ($class) {
            case 'none':
                break;

            case 'wpCSL_cache__slplus':
            case 'default':
            default:
                $this->cache = new wpCSL_cache__slplus($this->cache_config);

        }
    }

    /**-------------------------------------
     ** method: create_options_page
     **/
    function create_options_page() {
        add_options_page(
            $this->name . ' Options',
            $this->name,
            'administrator',
            $this->prefix . '-options',
            array(
                $this->settings,
                'render_settings_page'
            )
        );
    }

    /**-------------------------------------
     ** method: create_objects
     **/
    function create_objects() {
        
        // use_obj_defaults is set, use the invoke the default 
        // set of wpCSL objects
        //
        if (isset($this->use_obj_defaults) && $this->use_obj_defaults) {
            $this->create_helper('default');
            $this->create_notifications('default');
            $this->create_products('default');
            $this->create_settings('default');
            if ($this->has_packages || !$this->no_license) { $this->create_license('default'); }
            $this->create_cache('default');
            $this->create_themes('default');
            
        // Custom objects are in place
        //
        } else {
            if (isset($this->helper_obj_name))
                $this->create_helper($this->helper_obj_name);
            if (isset($this->notifications_obj_name))
                $this->create_notifications($this->notifications_obj_name);
            if (isset($this->products_obj_name))
                $this->create_products($this->products_obj_name);
            if (isset($this->settings_obj_name))
                $this->create_settings($this->settings_obj_name);
            if (($this->has_packages || !$this->no_license) && isset($this->license_obj_name))
                $this->create_license($this->license_obj_name);
            if (isset($this->cache_obj_name))
                $this->create_cache($this->cache_obj_name);
            if (isset($this->themes_obj_name))
                $this->create_themes($this->themes_obj_name);
        }
    }

    /***********************************************
     ** method: add_refs
     ** What did you say? Refactoring what now? I don't know what that is
     **
     ** This connects the instantiated objects of other classes that are
     ** properties of the main CSL-plugin class to each other.  For example
     ** it ensures each of the other classes can access the notification
     ** object for the main plugin.
     **
     ** settings    <= notifications, license, cache, themes
     ** themes      <= settings, notifications, products
     ** cache       <= settings, notifications
     ** helper      <= notifications
     ** license     <= notifications
     ** products    <= notifications
     **
     **/
    function add_refs() {
        // Notifications doesn't require any other objects yet

        // Settings
        if (isset($this->settings)) {
            if (isset($this->notifications) && !isset($this->settings->notifications))
                $this->settings->notifications = &$this->notifications;
            if (isset($this->license) && !isset($this->settings->license))
                $this->settings->license = &$this->license;
            if (isset($this->cache) && !isset($this->settings->cache))
                $this->settings->cache = &$this->cache;
            if (isset($this->themes) && !isset($this->settings->themes))
                $this->settings->themes = &$this->themes;
        }

        // Cache
        if (isset($this->cache)) {
            if (isset($this->settings) && !isset($this->cache->settings))
                $this->cache->settings = &$this->settings;
            if (isset($this->notifications) && !isset($this->cache->notifications))
                $this->cache->notifications = &$this->notifications;
        }

        // Helper
        if (isset($this->helper)) {
            if (isset($this->helper) && !isset($this->helper->notifications))
                $this->helper->notifications = &$this->notifications;
        }
        
        // License
        if ($this->has_packages || !$this->no_license) { 
            if (isset($this->license)) {
                if (isset($this->notifications) && !isset($this->license->notifications))
                    $this->license->notifications = &$this->notifications;
            }
        }

        // Products
        if (isset($this->products)) {
            if (isset($this->products) && !isset($this->products->notifications))
                $this->products->notifications = &$this->notifications;
        }
        
        // Themes
        if (isset($this->themes)) {
            if (isset($this->themes) && !isset($this->themes->notifications))
                $this->themes->notifications = &$this->notifications;
            if (isset($this->settings) && !isset($this->themes->settings))            
                $this->themes->settings = &$this->settings;
            if (isset($this->products) && !isset($this->themes->products))            
                $this->themes->products = &$this->products;
        }
    }

    /**-------------------------------------
     ** method: initialize
     **/
    function initialize() {
        $this->create_objects();
        $this->add_refs();
        if (isset($this->driver_name))
            $this->load_driver();
        $this->add_wp_actions();
    }

    /**-------------------------------------
     ** method: add_wp_actions
     **
     ** What we do when WordPress is initializing actions
     **
     ** Note: admin_menu is not called on every admin page load
     ** Reference: http://codex.wordpress.org/Plugin_API/Action_Reference
     **/
    function add_wp_actions() {
        if ( is_admin() ) {
            add_action('admin_menu', array($this, 'create_options_page'));
            add_action('admin_init', array($this, 'admin_init'),50);
            add_action('admin_notices', array($this->notifications, 'display'));          
        } else {
            if (!$this->themes_enabled) {
                // non-admin enqueues, actions, and filters
                add_action('wp_head', array($this, 'checks'));
                add_filter('wp_print_scripts', array($this, 'user_header_js'));
                add_filter('wp_print_styles', array($this, 'user_header_css'));
            }
        }

        add_filter('plugin_row_meta', array($this, 'add_meta_links'), 10, 2);

        // Only add shortcodes if there is a driver to use
        if (isset($this->driver)) {
            // Custom shortcodes
            if (isset($this->shortcodes)) {
                if (is_array($this->shortcodes)) {
                    foreach ($this->shortcodes as $shortcode) {
                        $shortcode_lc = strtolower($shortcode);
                        $shortcode_uc = strtoupper($shortcode);
                        add_shortcode($shortcode, array($this, 'shortcode_show_items'));
                        add_shortcode($shortcode_lc, array($this, 'shortcode_show_items'));
                        add_shortcode($shortcode_uc, array($this, 'shortcode_show_items'));
                    }
                } else {
                        $shortcode_lc = strtolower($shortcode);
                        $shortcode_uc = strtoupper($shortcode);
                        add_shortcode($shortcode, array($this, 'shortcode_show_items'));
                        add_shortcode($shortcode_lc, array($this, 'shortcode_show_items'));
                        add_shortcode($shortcode_uc, array($this, 'shortcode_show_items'));
                }
            } 

            // Automatic shortcodes
            // This should cover any basic typos involving dashes or underscores
            add_shortcode($this->prefix.'_show-items', array($this, 'shortcode_show_items'));
            add_shortcode($this->prefix.'_show_items', array($this, 'shortcode_show_items'));
            add_shortcode($this->prefix.'-show-items', array($this, 'shortcode_show_items'));
            add_shortcode($this->prefix.'-show_items', array($this, 'shortcode_show_items'));
            
        // No Driver
        //
        } else {
            if (($this->debugging) && ($this->driver_type != 'none')) {
                print __('DEBUG: No driver found.', $this->prefix);
            }
        }
    }

    /**-------------------------------------
     ** method: add_meta_links
     **/
    function add_meta_links($links, $file) {

        if ($file == $this->basefile) {
            if (isset($this->support_url)) {
                $links[] = '<a href="'.$this->support_url.'" title="'.__('Support') . '">'.
                            __('Support') . '</a>';
            }
            if (isset($this->purchase_url)) {
                $links[] = '<a href="'.$this->purchase_url.'" title="'.__('Purchase') . '">'.
                            __('Buy Now') . '</a>';
            }
            $links[] = '<a href="options-general.php?page='.$this->prefix.'-options" title="'.
                            __('Settings') . '">'.__('Settings') . '</a>';
        }
        return $links;
    }

    /**-------------------------------------
     ** method: admin_init
     **
     ** What we do whenever an admin page is initialized.
     ** This is called by Wordpress.
     **
     **/
    function admin_init() {
        $this->add_display_settings();
        $this->settings->register();
        $this->checks();
    }

    /**-------------------------------------
     ** method: checks
     **/
    function checks() {
        if (isset($this->cache)) {
            $this->cache->check_cache();
        }

        if (!$this->has_packages && isset($this->license)) {
            $this->license->check_product_key();
        }
    }


    /**-------------------------------------
     ** method: load_driver
     **
     **
     ** This function loads the data driver for this plugin.
     ** The legacy code was very Panhandler centric, so that is
     ** still in place with new hooks to load other "Custom" drivers
     **/
    function load_driver() {

        // Load Panhandler class and drivers if not already loaded
        //
        if ( 
            ($this->driver_type == 'Panhandler') && 
            file_exists($this->plugin_path . 'Panhandler/Panhandler.php')
            ) {
                if (!class_exists('PanhandlerProduct')) {
                    require_once($this->plugin_path . 'Panhandler/Panhandler.php');
                }

                try {
                    require_once($this->plugin_path . 'Panhandler/Drivers/'. 
                                    $this->driver_name .'.php');
                }
                catch (PanhandlerError $e) {
                    $this->notifications->add_notice(1, $e->getMessage());
                }

        // Load Custom class and drivers if not already loaded
        //
        } else {
            if (file_exists($this->plugin_path . 'Custom/Drivers/'. $this->driver_name .'.php')) {
                if (!class_exists($this->driver_name . 'Driver')) {
                    try {
                        require_once($this->plugin_path . 'Custom/Drivers/'. $this->driver_name .'.php');
                    }
                    catch (Exception $e) {
                        $this->notifications->add_notice(1, $e->getMessage());
                    }
                }

            // No Driver Found
            //
            } else {
                if ($this->debugging) {
                    print __('DEBUG: could not locate driver:', $this->prefix) . 
                        $this->plugin_path . 'Custom/Drivers/'. $this->driver_name .'.php' .
                        "<br/>\n";                                        
                }
            }                
        }

        // The driver class should now exist, let's load it's definition
        //
        if (class_exists($this->driver_name.'Driver')) {
            try {
                // Add http_handler to driver_args array
                //
                if ( isset($this->driver_args) )  {
                    $this->driver_args = array_merge(
                            array(
                                'http_handler'  => $this->http_handler,
                                'debugging'     => $this->debugging,
                                'prefix'        => $this->prefix,
                                'parent'        => $this
                                ),
                            $this->driver_args
                            );
                } else {
                    $this->driver_args = 
                            array(
                                'http_handler'  => $this->http_handler,
                                'debugging'     => $this->debugging,
                                'prefix'        => $this->prefix,
                                'parent'        => $this
                                );
                }

                // Invoke the driver via reflection classes
                //
                $reflectionDriver = new ReflectionClass($this->driver_name . 'Driver');
                $this->driver = $reflectionDriver->newInstanceArgs(array($this->driver_args));
            }
            catch (Exception $e) {
                $this->notifications->add_notice(1, $e->getMessage());
            }
        }
    }

    /**-------------------------------------
     * method: add_display_settings
     *
     * Add the display settings section to the admin panel.
     *
     **/
    function add_display_settings() {         
        $this->settings->add_section(array(
                'name' => __('Display Settings',$this->prefix),
                'description' => '',
                'start_collapsed' => true
            )
        );
        
        if ($this->themes_enabled) {
            $this->themes->add_admin_settings();
        }
        
        
        if (get_option($this->prefix.'-locale')) {
            setlocale(LC_MONETARY, get_option($this->prefix.'-locale'));
        }

        // If we have an exec function and get locales, show the pulldown.
        //        
        if (function_exists('exec')) {
            if (exec('locale -a', $locales)) {
                $locale_custom = array();
    
                foreach ($locales as $locale) {
                    $locale_custom[$locale] = $locale;
                }
    
                $this->settings->add_item(
                    'Display Settings', 
                    'Locale', 
                    'locale', 
                    'list', 
                    false, 
                    __('Sets the locale for PHP program processing, affects time and currency processing. '.
                        'If you change this, save settings and then select money format.',$this->prefix),
                    $locale_custom
                );
            }
        } else {
                $this->settings->add_item(
                    'Display Settings', 
                    'Locale', 
                    'locale', 
                    null, 
                    false, 
                    __('Your PHP settings have disabled exec(), your locale list cannot be determined.',$this->prefix),
                    '&nbsp;'
                );
        }

        // Show money pulldown if we are using Panhandler or have set the uses_money flag
        //
        if  (
            (($this->driver_type == 'Panhandler') || $this->uses_money) && 
            (function_exists('money_format')) 
            ) {
                $this->settings->add_item(
                    'Display Settings', 
                    'Money Format', 
                    'money_format', 
                    'list', 
                    false, 
                    __('This is based on your current locale, which is set to ',$this->prefix).
                        '<code>'. setlocale(LC_MONETARY, 0) .'</code>',
                    array(
                        money_format('%!i', 1234.56)            => '%!i',
                        money_format('%!^i', 1234.56)           => '%!^i',
                        money_format('%!=*(#10.2n', 1234.56)    => '%!=*(#10.2n',
                        money_format('%!=*^-14#8.2i', 1234.56)  => '%!=*^-14#8.2i'
                        )
                    );
        }
    }


    /**-------------------------------------
     * method: display_objects
     *
     * This method generates the HTML that will be used to display
     * the HTML output for this plugin.
     *
     * Parameters:
     * $objectlist    (named array) - an array of the objects to render
     *
     * Returns:
     * A basic error message string if the render class is missing, otherwise
     * the HTML that was returned from the render_objects_to_HTML method in
     * the driver class.
     *
     **/
    function display_objects($objectlist = NULL) {
        $HTML_to_display = 'Could not figure out how to display the data for this shortcode.';
        if ( is_callable(array($this->driver,'render_objects_to_HTML'), true)) {
            $HTML_to_display = $this->driver->render_objects_to_HTML($objectlist);
        }
        return $HTML_to_display;
    }
    
    /**-------------------------------------
     ** method: render_shortcode
     **
     ** process the shortcode for custom data drivers
     ** should get back an HTML string to replace the shortcode with
     **
     **/
    function render_shortcode($atts) {
        $HTML_to_display = 'Could not figure out how to display this shortcode.';
        if ( is_callable(array($this->driver,'render_shortcode_as_HTML'), true)) {
            $HTML_to_display = $this->driver->render_shortcode_as_HTML($atts);
        }
        return $HTML_to_display;
    }
    
    
    /**-------------------------------------
     * Method: SHORTCODE_SHOW_ITEMS
     *
     * Shows the products in a formatted output on the page wherever the shortcode appears.
     * This is the default output, custom shortcodes and functions can be put in the main
     * calling function.
     *
     */
    function shortcode_show_items($atts, $content = NULL) {
        if ( $this->ok_to_show() ) {
            $content = '';

            // Debugging
            //
            if ($this->debugging) {
                if (is_array($atts)) {
                    print __('DEBUG: Shortcode called with attributes:',$this->prefix) . "<br/>\n";
                    foreach ($atts as $name=>$value) {
                        print $name.':'.$value."<br/>\n";
                    }
                } else {
                    print __('DEBUG: Shortcode called with no attributes.',$this->prefix) . "<br/>\n";
                }
            }            
            
            // Filter out erroneous attributes
            if (is_array($atts)) {
                $atts = array_intersect_key( $atts, 
                            $this->csl_array_fill_keys( $this->driver->get_supported_options(), 
                                                        'temp' ) 
                        );
            }

            // We need some user defaults

            // If there's a custom array set, use that to populate the list
            if (isset($this->driver_defaults) && is_array($this->driver_defaults)) {
                $defaults = $this->apply_driver_defaults($this->driver_defaults);
            } else {
                // Otherwise, grab all of the user defaults from wordpress
                foreach($this->driver->get_supported_options() as $key) {
                    if (get_option($this->prefix .'-'. $key)) {
                        $defaults[$key] = get_option($this->prefix .'-'. $key);
                    }
                }
            }

            // Send them to the driver (if they exist)
            if (isset($defaults)) {
                $this->driver->set_default_option_values($defaults);
            }

            // Render a list of objects to HTML (usually products)
            //
            if (
                ($this->driver_type == 'Panhandler') ||
                ($this->driver_type == 'product')
                ) {
                $content = $this->render_object_list($atts);

            // Custom data driver
            //
            } elseif ($this->driver_type == 'custom') {
                $content = $this->render_shortcode($atts);            
            }

            
        // Not OK TO Show
        } else {
            if ($this->debugging) {
                $content = __('DEBUG: Not OK To Show',$this->prefix);
            }
        }
        return $content;
    }
    
    /**-------------------------------------
     ** method: render_object_list
     **
     ** Show products via the shortcode processor.
     **
     ** This is legacy code that came out of shortcode_show_items.
     ** It was separated to continue the generalization of wpCSL.
     **
     ** returns: a string that represents the product info in HTML format
     **
     **/
    function render_object_list($atts) {
        // Fetch the products
        // Check the cache first, then go direct to the source
        //
        if (isset($this->cache) && get_option($this->prefix.'-cache_enable')) {
            if (!($products = $this->cache->load(md5(implode(',',(array)$atts)))) ) {
                $products = $this->driver->get_products($atts);
            }
        } else {
            try {
                $products = $this->driver->get_products($atts);
            }

            // Deal with errors
            // These should probably be posted to the notifications system...
            catch (PanhandlerError $error) {
                return $error->message;
            }
        }

        // If there was an error show that and exit,
        // otherwise save the returned data to the cache if it is enabled
        //
        if (is_a($products, 'PanhandlerError')) return $products->message;
        else {
            if (isset($this->cache) && get_option($this->prefix.'-cache_enable')) {
                $this->cache->save(md5(implode(',', (array)$atts)), $products);
            }
        }

        // If there are products, return the HTML that will display them
        // otherwise return the simple "No products found" message.
        //
        if (count($products) > 0) {

            // Legacy Panhandler Stuff
            //
            if (is_a($products[0], 'PanhandlerProduct')) {
                $content = $this->products->display_products($products);

                // Object Display, yes Panhandler appendages
                // still abound leaving us with a $products var name
                // for now.
                //
            } else {
                $content = $this->display_objects($products);
            }   
            
        // No products, show an error message as the output
        //
        } else {
            $content= __('No products found', $this->prefix);
        }

        return $content;            
    }

    /**-------------------------------------
     ** method: user_header_js
     **/
    function user_header_js() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
    }

    /**-------------------------------------
     ** method: user_header_css
     **/
    function user_header_css() {

        if (isset($this->css_url)) {
            wp_register_style($this->prefix.'css', $this->css_url);
        } else if (isset($this->plugin_url)) {
            wp_register_style($this->prefix.'css', $this->plugin_url . '/css/'.$this->prefix.'.css');
        }
        wp_enqueue_style($this->prefix.'css');
        wp_enqueue_style('thickbox');
    }

    /**-------------------------------------
     ** method: apply_driver_defaults
     **
     ** Populate an array with values from wordpress if they exist, will
     ** propogate through an array structure recursively
     **/
    function apply_driver_defaults(&$defaults) {
        $results = array();
        foreach ($defaults as $key => $value) {
            if (is_array($value)) {
                $results[$key] = $this->apply_driver_defaults($value);
            }
            else {
                if (get_option($this->prefix .'-'.$value)) {
                    $results[$value] = get_option($this->prefix .'-'.$value);
                }
            }
        }

        return $results;
    }
}

