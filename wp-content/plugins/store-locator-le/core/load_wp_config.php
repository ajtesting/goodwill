<?php

$DebugOutput = (basename($_SERVER['SCRIPT_FILENAME']) == 'load_wp_config.php');

/*
 * function: InstallType
 *
 * returns the type of WordPress install for the given path:
 *
 * 'standard' : the config and settings file are in the given path
 * 'secured'  : the config file is one directory up, the settings is here
 * ''         : config and/or settings file is missing
 * 
 */
function InstallType($path) {
    $upPath = dirname($path);
    
    // Check Standard Setup
    //
    if (file_exists($path.'/wp-config.php') && file_exists($path.'/wp-settings.php')) {
        return 'standard';
    
    
    // Check for secured setup
    //
    } elseif (
         file_exists($upPath.'/wp-config.php') && 
        !file_exists($upPath.'/wp-settings.php') &&
         file_exists($path.'/wp-settings.php')
        ) {
        return 'secured';
        
    // Fail
    //
    } else {
        return '';
    }
}


// What paths do we want to look at for an install of WordPress?
//
//


// Seed the list with the main directory of our script name
//
$possible_path = preg_replace('/\/wp-content\/.*/','',$_SERVER['SCRIPT_FILENAME']);
$PathsToTry = array($possible_path);

// Check DOCUMENT_ROOT from Apache
//
if (isset($_SERVER['DOCUMENT_ROOT'])) { 
    if (!in_array($_SERVER['DOCUMENT_ROOT'],$PathsToTry)) { 
        array_push($PathsToTry,$_SERVER['DOCUMENT_ROOT']); 
    } 
}

// Check SUBDOMAIN_DOCUMENT_ROOT from Apache
//
if (isset($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'])) { 
    if (!in_array($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'],$PathsToTry)) { 
        array_push($PathsToTry,$_SERVER['SUBDOMAIN_DOCUMENT_ROOT']); 
    } 
}

// Check a few paths up from here
//

// back up from wordpress/wp-content/plugins/core/js dir
$thisFileDir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if (file_exists($thisFileDir)) {
    if (!in_array($thisFileDir,$PathsToTry)) { 
        array_push($PathsToTry,$thisFileDir); 
    } 
}
// ...and one higher than that
$thisFileDir = dirname($thisFileDir);
if (file_exists($thisFileDir)) {
    if (!in_array($thisFileDir,$PathsToTry)) { 
        array_push($PathsToTry,$thisFileDir); 
    } 
}
// ...and one higher than that
$thisFileDir = dirname($thisFileDir);
if (file_exists($thisFileDir)) {
    if (!in_array($thisFileDir,$PathsToTry)) { 
        array_push($PathsToTry,$thisFileDir); 
    } 
}


// Look for WordPress config info in the given directory list
// Test both the normal and secured setup
//
$installtype = '';
while ((list(, $thisPath) = each($PathsToTry)) && ($installtype == '')) {
    
    // Check for an install at the given path
    //
    $installtype = InstallType($thisPath);
    
    if ($DebugOutput) { print "Path: $thisPath type is *$installtype*<br/>"; }
    
    // If we have a valid install, load the config
    //
    if ($installtype == 'standard') {
        include($thisPath.'/wp-config.php');    
    } elseif ($installtype == 'secured') {
        define(ABSPATH,$thisPath.'/');
        include(dirname($thisPath).'/wp-config.php');
    }
}


