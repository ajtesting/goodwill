<?php
/************************************************************************
*
* file: CSL-license_class.php
*
* Handle the license management subsystem for WPCSL-Generic.
*
* Process the license keys, validating them against the license server.
*
************************************************************************/

class wpCSL_license__slplus {    

    /**------------------------------------
     ** CONSTRUCTOR
     **/
    function __construct($params) {
        
        // Defaults
        //

        // Set by incoming parameters
        //
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
        
        // Override incoming parameters
        
    }

    /**------------------------------------
     ** method: check_license_key()
     **
     ** Currently only checks for an existing license key (PayPal
     ** transaction ID).
     **/
    function check_license_key($theSKU='', $isa_package=false, $usethis_license='') {

        // The SKU
        //
        if ($theSKU == '') {
            $theSKU = $this->sku;
        }
        
        // The forced license
        // needed for plugins with no main license 
        // but licensed packages
        //
        if ($usethis_license == '') {
            $usethis_license = get_option($this->prefix . '-license_key');
        }

        // Don't check to see if the license is valid if there is no supplied license key
        if ($usethis_license == '') {
            return false;
        }

        // HTTP Handler is not set fail the license check
        //
        if (!isset($this->http_handler)) { return false; }

        // Build our query string from the options provided
        //  
        $query_string = http_build_query(
            array(
                'id' => $usethis_license,
                'siteurl' => get_option('siteurl'),
                'sku' => $theSKU,
                'checkpackage' => $isa_package ? 'true' : 'false',
                'advanced' => 'true'
            )
        );
        
        // Places we check the license
        //
        $csl_urls = array(
            'http://cybersprocket.com/paypal/valid_transaction.php?',
            'http://license.cybersprocket.com/paypal/valid_transaction.php?',
            );

        // Check each server until all fail or ONE passes
        //  
        foreach ($csl_urls as $csl_url) {            
            $response = false;
            $result = $this->http_handler->request( 
                            $csl_url . $query_string, 
                            array('timeout' => 60) 
                            ); 
            if ($this->http_result_is_ok($result) ) {
                $response = json_decode($result['body']);
            }

            // If we get a true response record it in the DB and exit
            //
            if ($response->result) {
                
                //.............
                // Licensed
                // main product
                if (!$isa_package) { 
                    update_option($this->prefix.'-purchased',true); 
            
                // add on package
                } else {
                    update_option($this->prefix.'-'.$theSKU.'-isenabled',true);
                    
                    // Local version info for this package is empty, set it
                    //
                    if (get_option($this->prefix.'-'.$theSKU.'-version') == '') {                        
                            update_option($this->prefix.'-'.$theSKU.'-version',$response->latest_version);
                            update_option($this->prefix.'-'.$theSKU.'-version-numeric',$response->latest_version_numeric);
                            
                    // Local version is not empty,                         
                    // Make sure we never downgrade the user's version
                    //
                    } else if ($response->effective_version_numeric > (int)get_option($this->prefix.'-'.$theSKU.'-version-numeric')) {
                            update_option($this->prefix.'-'.$theSKU.'-version',$response->effective_version);
                            update_option($this->prefix.'-'.$theSKU.'-version-numeric',$response->effective_version_numeric);
                    }             
                }

                update_option($this->prefix.'-'.$theSKU.'-latest-version',$response->latest_version);
                update_option($this->prefix.'-'.$theSKU.'-latest-version-numeric',$response->latest_version_numeric);
                return true;
            }
        }
                
        //.............
        // Not licensed
        // main product
        if (!$isa_package) { 
            update_option($this->prefix.'-purchased',false);
            
        // add on package
        } else {
            update_option($this->prefix.'-'.$theSKU.'-isenabled',false);            
        }
        
        
        return false;
    }

    /**------------------------------------
     ** method: check_product_key()
     **
     **/
    function check_product_key() {
        
        // If main product is not licensed (denoted by has_package=true)
        // and we are not checking a package, pretend we are licensed
        // and get out of here.
        //
        if ($this->has_packages) {
            return true;
        }
        
        if (get_option($this->prefix.'-purchased') != '1') {
            if (get_option($this->prefix.'-license_key') != '') {
                update_option($this->prefix.'-purchased', $this->check_license_key());
            }

            if (get_option($this->prefix.'-purchased') != '1') {
                if (isset($this->notifications)) {
                    $this->notifications->add_notice(
                        2,
                        "You have not provided a valid license key for this plugin. " .
                            "Until you do so, it will only display content for Admin users.",
                        "options-general.php?page={$this->prefix}-options#product_settings"
                    );
                }
            }
        }

        return (isset($notices)) ? $notices : false;
    }

    /**------------------------------------
     ** method: initialize_options()
     **
     **/
    function initialize_options() {
        register_setting($this->prefix.'-settings', $this->prefix.'-license_key');
        register_setting($this->prefix.'-Settings', $this->prefix.'-purchased');
        
        if ($this->has_packages) {
            foreach ($this->packages as $aPackage) {
                $aPackage->initialize_options_for_admin();
            }
        }            
    }

    /**-----------------------------------
     * method: http_result_is_ok()
     *
     * Determine if the http_request result that came back is valid.
     *
     * params:
     *  $result (required, object) - the http result
     *
     * returns:
     *   (boolean) - true if we got a result, false if we got an error
     */
    private function http_result_is_ok($result) {

        // Yes - we can make a very long single logic check
        // on the return, but it gets messy as we extend the
        // test cases. This is marginally less efficient but
        // easy to read and extend.
        //
        if ( is_a($result,'WP_Error') ) { return false; }
        if ( !isset($result['body'])  ) { return false; }
        if ( $result['body'] == ''    ) { return false; }

        return true;
    }
    
    
    /**------------------------------------
     ** method: add_licensed_package()
     **
     ** Add a package object to the license object.
     **
     ** Packages are components that have their own license keys to be
     ** activated, but are always related to a parent product with a valid
     ** license.
     **
     **/
    function add_licensed_package($params) {
        
        // If we don't have a package name or SKU get outta here
        //
        if (!isset($params['name']) || !isset($params['sku'])) return;
        
        // Setup the new package only if it was not setup before
        //
        if (!isset($this->packages[$params['name']])) {
            $this->packages[$params['name']] = new wpCSL_license_package__slplus(
                array_merge(
                    $params,
                    array(
                        'prefix' => $this->prefix,
                        'parent' => $this
                        )
                    )
            );
        } 
   }
    
}


/****************************************************************************
 **
 ** class: wpCSL_license_package__slplus
 **
 **/
class wpCSL_license_package__slplus {

    public $active_version = 0;
    
    /**------------------------------------
     **/
    function __construct($params) {
        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
        
        // Register these settings
        //
        $this->enabled_option_name = $this->prefix.'-'.$this->sku.'-isenabled';
        $this->lk_option_name      = $this->prefix.'-'.$this->sku.'-lk';
         
        // If the isenabled flag is not explicitly passed in,
        // set this package to the pre-saved enabled/disabled setting from wp_options
        // which will return false if never set before
        //
        $this->isenabled = get_option($this->enabled_option_name);        
        
        // Set our license key property
        //
        $this->license_key = get_option($this->lk_option_name);
        
        // Set our active version (what we are licensed for)
        //
        $this->active_version =  get_option($this->prefix.'-'.$this->sku.'-latest-version-numeric'); 
    }
    
    
    /**------------------------------------
     ** method: initialize_options_for_admin
     **
     ** Initialize the admin option settings.
     **/
    function initialize_options_for_admin() {
        register_setting($this->prefix.'-settings', $this->enabled_option_name);                        
        register_setting($this->prefix.'-settings', $this->lk_option_name);        
    }
    
    function isenabled_after_forcing_recheck() {
        if (!$this->isenabled) {
            $this->parent->check_license_key($this->sku, true, get_option($this->lk_option_name));
            $this->isenabled = get_option($this->enabled_option_name);
            $this->active_version =  get_option($this->prefix.'-'.$this->sku.'-latest-version-numeric');             
        }
        return $this->isenabled;
    }
}
