<?php

/***********************************************************************
* Class: wpCSL_helper
*
* Contains various helper, but non-critical methods to assist in making
* WordPress Plugins easier to build.
*
************************************************************************/

class wpCSL_helper__slplus {

    /**************************************
     ** method: get_string_from_phpexec()
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
        }
    }
    
    
     
    /**************************************
     ** method: execute_and_output_template()
     ** 
     ** Executes the included php (or html) file and prints out the results.
     ** Makes for easy include templates that depend on processing logic to be
     ** dumped mid-stream into a WordPress page.  A plugin in a plugin sorta.
     **
     ** Parameters:
     **  $file (string, required) - name of the file in the plugin/templates dir
     **/
    function execute_and_output_template($file) {
        $file = SLPLUS_PLUGINDIR.'/templates/'.$file;
        print get_string_from_phpexec($file);
    }
    
    
    
    /**************************************
     ** method: convert_text_to_html
     ** 
     ** Convert text in the WP readme file format (wiki markup) to basic HTML
     **
     ** Parameters:
     **  $file (string, required) - name of the file in the plugin dir
     **/
    function convert_text_to_html($file='readme.txt') {
        ob_start();
        include(SLPLUS_PLUGINDIR.$file);
        $content=ob_get_contents();
        ob_end_clean();
        $content=ereg_replace("\=\=\= ", "<h2>", $content);
        $content=ereg_replace(" \=\=\=", "</h2>", $content);
        $content=ereg_replace("\=\= ", "<div id='wphead' style='color:white'><h1 id='site-heading'><span id='site-title'>", $content);
        $content=ereg_replace(" \=\=", "</h1></span></div>", $content);
        $content=ereg_replace("\= ", "<b><u>", $content);
        $content=ereg_replace(" \=", "</u></b>", $content);
        $content=do_hyperlink($content);
        return nl2br($content);
    }    

}
