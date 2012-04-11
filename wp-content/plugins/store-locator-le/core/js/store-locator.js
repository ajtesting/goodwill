/*****************************************************************************
 * File: store-locator.js
 * 
 * Check that our PHP connector works, if so load map stuff.
 *
 *****************************************************************************/

// Check the WordPress environment was loaded
//
if (typeof add_base == 'undefined') {
    alert('SLPLUS: The PHP JavaScript connector did not load.');
} else if (typeof GLatLng == 'undefined' ) {    
    alert('SLPLUS: Google Map Interface did not load.');
    
// Load the map script if no errors
//
} else {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = add_base + '/js/store-locator-map.js';
    head.appendChild(script);
  
    // Load the email form script if we want it
    //
    if (slp_use_email_form) {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = add_base + '/js/store-locator-emailform.js';
        head.appendChild(script);
    }
}
