=== Store Locator Plus ===
Plugin Name: Store Locator Plus
Contributors: cybersprocket
Donate link: http://www.cybersprocket.com/products/store-locator-plus/
Tags: store locator, store locater, google, google maps, dealer locator, dealer locater, zip code search, shop locator, shop finder, zipcode, location finder, places, stores, maps, mapping, mapper, plugin, posts, post, page, coordinates, latitude, longitude, geo, geocoding, shops, ecommerce, e-commerce, business locations, store locator plus, store locater plus
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 2.4

This plugin puts a search form and an interactive Google map on your site so you can show visitors your store locations.    

== Description ==

This plugin puts a search form and an interactive Google map on your site so you 
can show visitors your store locactions.  Users search for stores within a 
specified radius.  Full admin panel data entry and management of stores from a few to
a few thousand.

http://www.youtube.com/watch?v=ni_CrSoNr38

= Features =

* You can use it for a variety of countries, as supported by Google Maps.
* Supports international languages and character sets.
* Allows you to use unique map icons or your own custom map icons.
* Change default map settings via the admin panel including:
* Map type (terrain, satellite, street, etc.)
* Inset map show/hide
* Starting zoom level
* You can use miles or kilometers
* Pulldown list of cities and/or countries on search form can be toggled on/off.
* Bulk upload your locations via the CSV loader.
* Location search tracking and reporting, find out what your visitors are looking for.
* Popup email form.

== Optional Plus Pack Available ==

The plugin has an optional plus pack available that adds advanced features to the Store Locator Plus product.
Some of the added features include:

* CSV Bulk Uploads - using the CSV bulk loader
* Extended Map Settings - Control more details about how the map looks, disable the scale, the zoom, and more.
* Extended Manage Location Features - more controls for managing locations.
* Reporting - information on the reporting system and how it works.
* Tag Search - how to use tag based location searches.
* Custom Themes (Plus Pack v2.4) - easily add your own custom styles that stay in place during patches and upgrades.

Learn More about these features at the [Store Locator Plus Support Pages](http://redmine.cybersprocket.com/projects/mc-closeststore/wiki)

== Upgrades ==

We will offer upgrade versions from time-to-time when special new features are added to the product.  
If you do not purchase the upgrade you will still receive bug fixes and minor feature additions, however
some of the newest features may not be available to you.   

The main product will always remain fully functional and free.  If you'd like to have some of the latest
"bells & whistles" purchasing the upgrades is a great way to help support us and encourage us to add even
more items the next time around.

= Looking For Customized WordPress Plugins? =

If you are looking for custom WordPress development for your own plugins, give 
us a call.   Not only can we offer competitive rates but we can also leverage 
our existing framework for WordPress applications which reduces development time 
and costs.

Learn more at: http://www.cybersprocket.com/services/wordpress-developers/

= Related Links =

* [Store Locator Plus Product Info](http://redmine.cybersprocket.com/products/store-locator-plus/)
* [Store Locator Plus Support Pages](http://redmine.cybersprocket.com/projects/mc-closeststore/wiki)
* [Other Cyber Sprocket Plugins](http://wordpress.org/extend/plugins/profile/cybersprocket/) 
* [Custom WordPress Development](http://www.cybersprocket.com/services/wordpress-developers/)
* [Our Facebook Page](http://www.facebook.com/cyber.sprocket.labs)

== Installation ==

= Requirements =

* PHP 5.1+

= Main Plugin =

1. Upload the `store-locator-plus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Sign up for a Google Maps API Key for your domain at http://code.google.com/apis/maps/signup.html
4. Add your locations through the 'Add Locations' page in the Store Locator admin panel
5. Place the code '[STORE-LOCATOR]' (case-sensitive) in the body of a page or a post to display your store locator

= Icons =

1. There are some default icons in the `/wp-content/plugins/store-locator/icons` directory. 
2. Add your own custom icons in to `wp-content/uploads/sl-uploads/custom-icons`.

= Custom CSS (Stylesheet) =

You can modify the default style sheet included with the plugin at 
./css/csl-slplus.css' and place it under `/wp-content/uploads/sl-uploads/custom-css/`. 
The store locator will give priority to the 'csl-slplus.css' in the 'custom-css/' 
folder over the default 'csl-slplus.css' file that is included.  This allows you 
to upgrade the main store locator plugin without worrying about losing your 
custom styling. 

== Frequently Asked Questions ==

= What are the terms of the license? =

The license is based on GPL.  You get the code, feel free to modify it as you
wish.  We prefer that our customers pay us because they like what we do and 
want to support our efforts to bring useful software to market.  Learn more
on our [CSL License Terms page](http://redmine.cybersprocket.com/projects/commercial-products/wiki/Cyber_Sprocket_Labs_Licensing_Terms "CSL License Terms page").

= How can i translate the plugin into my language? =

* Find on internet the free program POEDIT, and learn how it works.
* Use the .pot file located in the languages directory of this plugin to create or update the .po and .mo files.
* Place these file in the languages subdirectory.
* If everything is ok, email the files to lobbyjones@cybersprocket.com and we will add them to the next release.
* For more information on POT files, domains, gettext and i18n have a look at the I18n for WordPress developers Codex page and more specifically at the section about themes and plugins.

= What browsers are supported? =

All major browsers should work, however Cyber Sprocket Labs only officially supports the current and prior releases of Internet Explorer, Firefox, Chrome, and Safari.

As of June, 2011 this includes:

* Internet Explorer (IE) 8/9
* Firefox 4/5
* Chrome 11/12
* Safari 4/5

= What happened to the LE version? =

The light edition (LE) and Plus versions have been merged back into a single product.   The LE version remains fully functional and now has a lot of the main features that were only available in the Plus Pack.

= What is the Plus Pack? =

The Plus Pack is a paid add-on for the main Store Locator Plus product.  When you purchase the Plus Pack the system will automatically install a variety of new features in the base product.  The features that are added by the Plus Pack change on a regular basis.  Please see the [support pages](http://redmine.cybersprocket.com/projects/mc-closeststore/wiki) for the latest list.

Some of the features in the Plus Pack include:

* A searched-locations report.
* Ability to input addresses in bulk via a CSV upload.
* Ability to associate tags with locations and filter search results by those tags.
* Additional search form elements.

== Screenshots ==

1. Location Details
2. Basic Address Search
3. All Options Search
4. Tag Filter, Pulldown (any with zip)
5. Tag Filter, PUlldown (green with zip)
6. Admin Map Settings
7. Admin Add Locations
8. Admin Manage Locations

More screenshots are available via our [online documentation](http://redmine.cybersprocket.com/projects/mc-closeststore/wiki).

== Changelog ==

= 2.4 (February 8th, 2012) =

* Force height on icon sizes.
* Fixed problem with menu/button links on admin panel for subdirectory installs.
* Fixed problem with certain international characters stopping the initial map zoom/search.
* PLUS PACK v2.4 : Integrated custom themes system. (paid upgrade)
* PLUS PACK v2.4 : You can now set your center location for the map. (paid upgrade)

= 2.3.1 (January 25th, 2012) =

* Fix missing CSS files.

= 2.3 (January 19th, 2012) =

* PLUS PACK Feature #7165: Manage Locations : Filter To Uncoded Only
* PLUS PACK Feature #7166: Recode locations that failed to geocode.
* PLUS PACK Feature #7231: Add label to State Pulldown.
* Fix missing map marker.
* Feature #7230: Change default radius selection.
* Feature #7229: Fix typo in zoom level instruction.
* Manage Locations Updates
** Feature #7233: Clean up action bar header.
** Feature #7235: Update expand/normal view interface.
** Feature #7236: Manage locations: Stylize the page length setting.
** Feature #7237: Manage locations: use icon sprites v. text for edit/delete. 


= 2.2.5 (December 26th, 2011) =

* Fix address lookup for address search with comma or space.
* Retain ability to search for addresses with UTF8 characters like ü in für.
* Added extended debugging messages to address search.

= 2.2.4 (December 2011) = 

* Store Locator Plus and LE are now merged, allowing for direct-from-WordPress upgrades.
* General performance improvements via reduced memory usage while running searches.
* Address search now can process special characters like: ü
* Better lookup and testing for loading wp_config, the source of "unable to load JavaScript errors"
* Description field can now hold > 255 characters.
* If only 1 location is returned the map no longer auto-zooms onto that location, is uses the zoom level setting.
* Added zoom level adjustment for how tight to zoom in on results.
* Increase performance & reduce disk I/O when building map settings page.
* Language file updated (/core/languages/store-locator-plus.pot)
* Plus Pack: state pulldown now available
* Plus Pack: tags with spaces can be filtered and searched
* Plus Pack: allow tags in table and bubble
* Plus Pack: tags in table and bubble now wrapped with div and spans with unique classes to allow for icon displays
* patch 2.2.4 - update language maps for better language support

= 2.1 (October 2011) =

* Fix error when debug mode is enabled.
* Updated administrative pages header with new menu button bar.

= 2.0.3 (September 9th 2011) =

* Fix syntax error in view locations.


= 2.0.2 (September 2011) =

* Minor edits.

= 2.0.1 (August 2011) =

* Elminate errors on servers with exec() disabled on php.

= 2.0 (June 2011) =

* Feature: Added tracking and reporting system.
* Feature: Multiple retries available for better geocoding() on bulk or single-item uploads.
* Feature: Improved failed goecode reporting.
* Feature: Scroll wheel zoom can be disabled via a map settings checkbox.
* Feature: Search form address, radius, and search buttons can be hidden.
* Feature: Google maps scale, 3d controls, type of map overlays can be hidden.
* Update: If the search-by-tags box is shown it takes precedence over the only_with attribute.
* Update: Re-factored the code to share components with the light edition (LE) version.
* Update: Icon paths have changed - make sure you reset your icons via the map designer.
* Update: Added Republic of Ireland to the countries list.
* Fix: conflict with copyr() with other plugins.
* Fix: language file loading.
* Fix: Custom icons are back for Internet Explorer.

= 1.9 (May 11th 2011) =

* Add email contact via forms option.
* Better reporting of failed PHP connector loading.
* More checking & user reporting on failed map interface loading.
* Fix problem with multisite installs where plugin was only installed in parent.
* Updated language file.

= 1.8.2 (April 22 2011) =

* Fix broken paths in the config loader.

= 1.8.1 (Easter Weekend 2011) =

* Short open tag fix.
* Look for wp-config in secure location (one level up) for secured installs

= 1.8 (April 2011) =

* Can now override the search form tag list pulldown via the shortcode (tags_for_pulldown).
* Can now specify a search form only produce results for a single tag via the shortcode (only_with_tag).
* Set search form input font to black, the background is currently forced white in the CSS.
* Added new email field to store locator data.
* Fix errors on javascript processing on some systems with no subdomain support.
* [Shortcode documentation](http://redmine.cybersprocket.com/projects/mc-closeststore/wiki/How_To_Implement)


= 1.7.6 (March 26th 2011) =

* Better path processing in javascript files to find wp-config.php (fixes missing maps on some installs)
* Added author name to main plugin source.


= 1.7.5 (March 21st 2011) =

* Rename base php file to prevent "not a valid header" messages.
* Update various links to prevent double-slash and possible URL issues on WAMP systems.
* Strip extra whitespace around tags to improve search
* Updated CSV loader to detect and process Mac based line endings.
* CSV loader checks number of columns and reports error message if too many are found.
* Debugging mode turns on debugging in store-locator-js.php for JavaScript issues.

= 1.7.4 (March 14th 2011) =

* Force CSL-helper class into distribution kit.

= 1.7.3 (March 12th 2011) =

* Better checking if admin user logged in / Admin demo mode works on more sites now.
* Extended debugging output.
* Change menu to read "Add Locations" v. "SLP Locations"

= 1.7.2 (March 3rd 2011) =

* Add pulldown selection for tag searches.

= 1.7.1 (March 2nd 2011) =

* Fix search when tag searches are not enabled.

= 1.7 (March 2011) =

* Can now search locations by tags.
* Cleaned up map settings page.
* Fixed bulk upload record count.
* Added CSV mime type for some WP installs that blocked it on bulk uploads.

= 1.6.5 (February 15th 2011) =

* Allows purchased license key to be saved after the DB key holding the purchase flag has been mangled. 

= 1.6.4 (February 11th 2011) =

* Set the SLPLUS CSS for images to have visibility & display to !important. 
* Some themes & plugins force javascript images to be hidden, causing the map not to display.


= 1.6.3 (February 10th 2011) =

* Fix conflict errors when replacing Store Locator with SLPLUS.
* The conflict errors would cause the Google Map to not display on some installs.


= 1.6.2 (February 8th 2011) =

* Re-distribute 1.6.1 patch, full kit did not make it to the WordPress system.

= 1.6.1 (February 7th 2011) =

* Fix problem with Map API key not saving.

= 1.6 (February 5th 2011) =

* Fix problem with subdomain installs not finding store locations.

= 1.5 (February 2011) =

* Added bulk upload feature via CSV files.
* Fixed problem with map display on subdomain installs.
* Fixed a problem with map not showing up in v1.4 release.
* Fixed paging problem on view locations.
* Various performance tweaks for page loads:
* ... built-in shortcode processor v. custom regex processor
* ... removed customization backups on each page load
* ... admin panel helper info setup only on settings page call

= 1.4  (January 2011) =

* City/County pulldown only shown if checked of on admin panel.
* Updated layout of search form, using more CSS for easier layout changes
* Add locations form cleaned up
* Manage/view locations form cleaned up
* Make search work with address 2 field
* Make map and search results output show address 2 field
* Revamp manage locations header 
* More warnings in the main codebase have been fixed
* Removed Store Locator Plugin addons support, addons support causing problems.

= 1.3  (December 2010) =

* Add country field to address data.
* Clean up various coding errors since WordPress 3.0 release
* Initial release based on Google Maps Store Locator for WordPress v1.2.39.3

== Upgrade Notice ==

Plus Edition users should record their license key and use it to license the Plus Pack.

