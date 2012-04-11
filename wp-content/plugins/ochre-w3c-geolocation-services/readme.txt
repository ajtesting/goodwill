=== Ochre W3C Geolocation Services ===
Contributors: ochrelabs
Tags: geolocation, w3c, geo location, location, ochre, ochre labs, ochre development labs
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: trunk

Geolocation Services attempts to retrieve a visitor's physical location, allowing for geographically relevant content to be delivered.

== Description ==
[Ochre's Geolocation Services plugin for
WordPress](http://www.ochrelabs.com/wordpress-plugins/ochre-geolocation "Ochre's Geolocation Services plugin for Wordpress")  utilizes the [W3C Geolocation API](http://dev.w3.org/geo/api/spec-source.html "Geolocation API Specification") to retrieve a visitor's physical location, enabling a
WordPress website to present content relevant to a visitors current physical location such as local maps, event listings, branch and franchise locations, social media and other information that is geographically
interesting.

The plugin provides three "back end" action hooks for use by your or third party plugins or theme actions, and AJAX actions for "front end" customization such as executing javascript with the resolved Location information, redirecting to a new page, or refreshing the current page.

== Installation ==

1. Using the Wordpress plugin manager, install this plugin.

or

1. Download from http://wordpress.org/extend/plugins/ochre-w3c-geolocation-services/
2. Using the Wordpress plugin manager, upload the archive you just downloaded.
3. Activate

or

1. Download from http://wordpress.org/extend/plugins/ochre-w3c-geolocation-services/
2. Extract, and upload contents of archive to wp-content/plugins/ochre-w3c-geolocation-services
3. Using the Wordpress plugin manager, activate the plugin

or

[Get us to do it](http://www.ochrelabs.com/contact/ "Contact Ochre Labs for installation services"), or your web developer.

== Configuration ==
Global Configuration is available from the Wordpress settings->Ochre Geolocation page:

* Global or per-post/per page geolocation behavour

Per-page and per-post configurations include:

* Executing custom javascript
* Redirecting to a URL
* Performing a page refresh
* Firing off the ochregeo actions but doing nothing on the front end
* Disabling completely

== Actions ==

The following actions for do_action() are fired when a Geolocation update is received:

* ochregeo_received_nosupport : No Geolocation support in the device/browser
* ochregeo_received_unknownpos : A position could not be retrieved
* ochregeo_received_location : Position was received.  This action is passed the OCHRELABS_WP_Geolocation object as its only argument

== AJAX actions ==

The following AJAX actions are implemented:

* (nopriv) ochregeo_ochregeos : Transmits location information and executes an action based on a per-post/page or global setting.
* (nopriv) ochregeo_get_coordinates : Retrieves last received location information. (we haven't tested this)

== Executing custom javascript after a succesful Geolocation update ==

Javascript executed on a per-page/post or global basis has access to a `res` object
containing information from the Geolocation Service plugin.

Properties of this object are:

`
res.la; // latitude
res.ll; // longitude
res.ev; // elevation (not always available - do not rely on)
res.ac  // accuracy (not always available - do not rely on)

If reverse geo coding is enabled, the object may also contain:

res.country  // country
res.countryc // country code
res.state    // state
res.statec   // state code
res.city     // city
`

== El Quickie API Reference ==
`
// This is the Ochre Geo object instantiated by the plugin.
$ochre_geo = new OCHRELABS_WP_Geolocation();	

// Get status of the current geolocation request.  $ochre_geo::STATUS_UPDATED means you have "valid" coordinate data.
// Constants are:
  $ochre_geo::STATUS_QUERY;        // waiting for update from client
  $ochre_geo::STATUS_UPDATED;      // received coordinate update from client
  $ochre_geo::STATUS_NOTSUPPORTED; // client does not support geo location
  $ochre_geo::STATUS_ERROR;        // an error was returned from the client
  $ochre_geo::STATUS_UNKNOWNPOS;   // location was unknown
  $ochre_geo::STATUS_DISABLED;     // module has been told not to present geolocation query

$ochre_geo->get_status(); 	

// Set the location information manually (not normally needed)
$ochre_geo->set_coordinates($latitude,$longitude,$elevation=0,$accuracy=0);

// get the current resolved location information - check get_status() first before relying on this data!
// the returned array will be something like: array("latitude"=>49.1234, "longitude"=>"-123.1234","elevation"=>0,"accuracy"=>0);
$ochre_geo->get_coordinates();  

// geocodes the current resolved location information and returns it as an array like array("city"=>"Vancouver","country"=>"Canada","state"=>"British Columbia","statec"=>"BC","country"=>"Canada","countryc"=>"CA")
$ochre_geo->geocode();

`

== Reverse Geo Coder ==
We've thrown in Yahoo! place finder Geocoding support.  The geocoder()
returns an array consisting of something like:

` array(
	"city"=>"Vancouver","
	"country"=>"Canada",
	"state"=>"British Columbia",
	"statec"=>"BC",
	"country"=>"Canada",
	"countryc"=>"CA"
	)
`

Geo Coding support is still young and subject to change.  It's not hooked into the Javascript API, and since there are at least a few geo coder javascript api's already out there we haven't decided whether to do this or not.

== Frequently Asked Questions ==

There are none yet.

== Changelog ==
= 0.02 =
First release.  There may be bugs and other problems!  Try it out and let us know if you run into problems or have suggestions for improvement!

= 0.03 =
Miscellaneous fixes.

= 0.04 =

* Add option to reverse geo code results 
* Add city, state, statec, country and countryc to redirect templates and returned javascript object
* Fixes for PHP < 5.3 compatability

== Upgrade Notice ==

All users are advised to upgrade to this latest release.


