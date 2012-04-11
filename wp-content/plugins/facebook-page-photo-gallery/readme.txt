===Facebook Page Photo Gallery ===

Contributors: classicon
Tags: facebook page gallery, facebook walleria, facebook page photo, gallery, embed facebook, facebook albums, image gallery, photo gallery, fancybox, lightbox
Donate link: http://zoxion.com/donate
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 2.0.2


Showcase your Facebook Page photos in a fancy gallery that surpasses even Facebook's.

== Description ==
This Plugin fetches a Facebook Page Photo Album as a JSON object and uses [Fancybox](href="http://fancybox.net "Fancybox" ) to render the gallery in wordpress
It is specifically for public photos particularly those belonging to a Facebook Page if you would like to show Photos and albums belonging to users and friends then consider [Facebook Walleria Plugin](http://zoxion.com). You do not need special
permissions, just the album ID and boom!

Version 2.0 was completely rewritten and makes use of wordpress shortcodes. 

= How to use =
* In your post just add the following  **[fbphotos id=x]** where x is the album ID
* To specify number of photos to show use **[fbphotos id=x limit=y]** where y is the number to be shown
* To show your album in a random manner **[fbphotos id=x rand=1]**
* To show a  specified number of random photos from an album **[fbphotos id=x limit=y rand=1]**
* To use in your template pages in theme development you can call the following function
**fppg_get_photos($x,$n)** where $x is Album ID and $n is the $n of photos you want to display.

= Pro Features =
If you want to showcase any private albums you may consider **[Facebook Walleria Plugin](http://zoxion.com/walleria "Facebook Walleria")** with features like

* Facebook Events
* Facebook Albums
* Facebook Wall
* Facebook Photos

= Requirements =
* Facebook Page Photo Gallery requires PHP5!
* jQuery 1.4.4 and higher
* WordPress 3.2 or higher

= Settings =
* You can customise the gallery WP Admin Panel » Settings » Facebook Page Photo

= Language =

* Facebook Page Photo Gallery is currently available in English

= Questions =

* Please visit [our blog](http://zoxion.com/facebook-page-photo-gallery) and leave a comment

= Demo & Instructions = 

[visit blog](http://zoxion.com/facebook-page-photo-gallery/)


= Donate =
It is hard to continue development and support for this plugin without contributions from users like you.
If you enjoy using Facebook Page Photo Gallery and find it useful, please consider [__making a donation__](http://zoxion.com/donate/).
Your donation will help encourage and support the plugin's continued development and better user support.


== Installation ==

1. Unzip the plugin and Upload the directory 'facebook-page-photo-gallery' to '/wp-content/plugins/' on your site

2. Activate the plugin through the 'Plugins' menu in WordPress

3. That's all for it to work! You can customise the settings though under WP Admin Panel » Settings » Facebook Page Photo

== Screenshots ==

1. The Facebook Page Photo Album
2. The Facebook Page Photo Gallery with active fancybox
3. The Facebook Page Photo settings Page

== Frequently Asked Questions ==
= How do I get an album ID =
The album id is the number on the facebook after a. up to the next dot. Copy that number and place it between the braces.

* For example, http://www.facebook.com/media/set/?set=a.209112642458743.46995.123369051033103 the ID is 209112642458743

= Does FPPG 2.0 support the shortcode used in previous versions i.e FB-Album[] =
No. It now uses Wordpress shortcode [fbphotos]

== Changelog ==
= 2.0.2 =
* Fixed a bug affecting SSL thanx to Josh
= 2.0.1 =
* Fixed a bug that limited photos to 25

= 2.0 =
* Completely rewritten- its faster!
* Implemented Wordpress shortcodes
* Deprecated use of regular expressions
* Added random showcasing
* Deprecated previous shortcode (FB-Album[]) in favor of [fbphotos]
* Moved CSS from main file to allow easy user customisation
* General code clean-up

= 1.0.3 =
* Added an option to select the number of photos to show a specified number of photos. This is especially handy for huge albums. All you need is FB-Album[album-id, number] eg to show 10 photos
FB-Album[55464664,10]
* Fixed a brocken link to facebook.
* Fixed possible function conflict with other plugins
* Fixed possible option conflict

= 1.0.2 =
* fixed potential conflict with other plugins that use JSON like tweetblender

= 1.0.1 =
* fixed wrongly fetched thumbnails from older Facebook albums
* general code cleaning

= 1.0 =
* first public release