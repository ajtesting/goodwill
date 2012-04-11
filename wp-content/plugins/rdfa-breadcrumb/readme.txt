=== RDFa Breadcrumb ===
Contributors: yawalkarm
Plugin URI: http://digitalfair.tk/development/plugins/
Donate link: https://www.paypal.com/cgi-bin/webscr&business=nitiny892@gmail.com&cmd=_donations&item_name=Friends+of+Mallikarjun+Yawalkar
Tags: navigation, menu, breadcrumb, breadcrumbs, bbpress, forum, RDFa, google rich snippet
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.0

An easy template tag for showing a breadcrumb menu on your site and on google search results with built in RDFa Markup.

== Description ==

*RDFa Breadcrumb* provide links back to each previous page the user navigated through to get to the current page or - in hierarchical site structures - the parent pages of the current one.

It gives you a new template tag called `rdfa_breadcrumb()` that you can place anywhere in your template files. It has inbuilt RDFa content markup, so this will help Google to recognize and display the breadcrumbs on search results as well.

Check out the screenshots.

== Screenshots ==

1. The RDFa Breadcrumbs on your website.
2. RDFa Breadcrumbs displayed by Google Webmasters rich snippet testing tool.
3. Breadcrumbs on google search results.

= Frameworks =

* Genesis
* Hybrid
* Nifty
* Thematic
* Thesis

= Plugins =

* bbPress

= Localization =

* English - (en_EN)

== Installation ==

1. Upload `rdfa-breadcrumb` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the following code to your template files : `<?php if(function_exists('rdfa_breadcrumb')){ rdfa_breadcrumb(); } ?> `

== Frequently Asked Questions ==

= How do I add it to my theme? =

You would use this call to replace the default navigation with RDFa Breadcrumb:
`<?php if ( function_exists( 'rdfa_breadcrumb' ) ) { rdfa_breadcrumb();  } ?>`


== Changelog ==

=1.0=
* Added support for WordPress 3.3
* Added support for Hybrid, Thematic and Thesis theme
* Added support for Google's RDFa markup

= 0.4 =
* Added support for bbPress Plugin
* Cleaned up the code

= 0.3 =
* Added support for Genesis Theme
* Added support for custom post types and taxonomies
* Added support for hierarchies
* Added support for WordPress 3.1's post type archives
* Added readme.html file

= 0.2 =
* Added support for Date (Day, Month and Year)
* Added CSS for Style Title, Homelink and Separator
* Cleaned up the code

= 0.1 =
* First public release
