=== Constant Contact WordPress Widget ===
Contributors: katzwebdesign, jamesbenson
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=Constant%20Contact%20WordPress%20Widget&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: constant contact, widget, newsletter, form, signup, newsletter widget, email newsletter form, newsletter form, newsletter signup, email widget, email marketing, newsletter, form, signup
Requires at least: 2.8
Tested up to: 3.1.2
Stable tag: trunk

Easily add Constant Contact signup forms to your website (sidebar or content) and configure how they look.

== Description ==

> __This plugin requires a <a href="http://www.constantcontact.com/index.jsp?pn=katzwebservices" title="Sign up for Constant Contact" rel="nofollow">Constant Contact account</a>.__ Constant Contact is the best email marketing provider; give them a try!<br /><small>The link above is an affiliate link that supports development of this plugin.</small>

__The Constant Contact Widget__ adds Constant Contact signup forms to your sidebar without touching code. Includes options for Title, Button Text, Tag Wrapper, Form ID Code, Intro Paragraph, and much more.

<h3>Shortcode newsletter form support</h3>
Embed a form in your website using shortcakes: `[constantcontact id="3"]` will embed form #3 in your page or post content. It's that simple!

<h3>Widget options:</h3>

The widget has tons of configuration options:

* `title`  -  Widget title
* `preface`  -  Text shown inside the widget form
* `button`  -  The text of the submit button
* `style`  -  Choose from three different Constant Contact styles, or unstyled
* `safe_subscribe`  -  Choose from three colors of the SafeSubscribe logo - Constant Contact only provides 1 by default!
* `email_image`  -  Add some flair to your widget with an email icon: choose from 5 options
* `width`  -  Customize the width of your widget
* `input_size`  -  Customize the width of the email input field
* and more!

[Learn more on the Official Plugin Page](http://www.seodenver.com/constant-contact-wordpress-widget/).

####Note

If your using WordPress version 2.9 or above, consider using the <a href="http://wordpress.org/extend/plugins/constant-contact-api/">Constant Contact for WordPress</a> plugin. The <a href="http://wordpress.org/extend/plugins/constant-contact-api//9LL8kt">Constant Contact for WordPress</a> plugin has more features, but is currently lacking the styling options available with this plugin. We are working to bring you the best of both plugins.

== Installation ==

Follow the following steps to install this plugin.

__In your sidebar:__

1. Download plugin to the `/wp-content/plugins/` folder
1. Upload the plugin to your web host
1. Activate the plugin through the 'Appearance > Plugins' menu in WordPress
1. Go to the Widgets page in WordPress (Appearance > Widgets)
1. Drag `Constant Contact Widget 2.0` into a sidebar.
1. Paste the HTML Code from Constant Contact (Contacts > Join My Mailing List > Start Wizard to generate HTML code). <a href="http://www.constantcontact.com/display_media.jsp?id=15t" target="_blank">View tutorial video</a>.</p>
1. Save the widget, then configure the rest of the options.
1. Save once more, and you're all set!

__On a page:__
Configure the widget as you would in your sidebar (see above). Then, you can either:

* use `<?php do_shortcode('[constantcontact id="#"]'); ?>` in your template code
* or write `[constantcontact id="#"]` in your post's or page's content.

== Frequently Asked Questions ==

= How do I use the new `apply_filters()` functionality? (Added 1.7) =
If you want to change some code in the widget, you can use the WordPress `add_filter()` function to achieve this.

You can add code to your theme's `functions.php` file that will modify the widget output. Here's an example:
<pre>
function my_example_function($widget) { 
	// The $widget variable is the output of the widget
	// This will replace 'this word' with 'that word' in the widget output.
	$widget = str_replace('this word', 'that word', $widget);
	// Make sure to return the $widget variable, or it won't work!
	return $widget;
}
add_filter('cc_signup_form_widget', 'my_example_function');
</pre>

= How do I create a Constant Contact form using this widget? =

Go to 'Appearance' > 'Widgets,' click "Add" next to "Constant Contact," click "Edit" to configure your form and follow the on-screen instruction.

= Do I need a Constant Contact account for this widget? =
This plugin requires a <a href="http://www.constantcontact.com/features/signup.jsp?pn=katzwebservices" title="Sign up for Constant Contact" rel="nofollow">Constant Contact account</a> (affiliate link).

Constant Contact is a great email marketing company -- their rates are determined by the number of contacts in your list, not how many emails you send. This means you can send unlimited emails per month for one fixed rate! <a href="http://www.constantcontact.com/features/signup.jsp?pn=katzwebservices" rel="nofollow" title="Try out Constant Contact today">Give it a test run</a> (affiliate link).

= How do use this as a PHP function and not have it in the sidebar? =

In the Widget options, fill out the Widget settings as you would normally. Set 'Show the Widget' to 'No, Hide Widget', which will save the widget settings, but will not display it.  Then you can use the following PHP to show the widget: `do_shortcode('[constantcontact id="#"]');`. 

= Can I call Constant Contact if this form does not work? =

No, this form is not created by Constant Contact, so please do not call them with questions. Instead, ["leave a message on the widget blog page"](http://www.seodenver.com/constant-contact-wordpress-widget/).

= What is the license for this plugin? = 

* This plugin is released under a GPL license.

== Screenshots ==

1. Before the widget is configured
1. Widget Text & Input settings
1. Widget Design Preset settings
1. Widget Visual settings
1. Widget Form settings
1. How the widget displays in the twentyten theme

== Changelog ==

= 2.0.3 =
* Fixed issue where upon form submission, users are taken to Constant Contact page that says "An unexpected error has occurred. Please try again."
* Fixed PHP notices being displayed if `WP_DEBUG` is turned on
* Fixed issue where background color may have been overwritten when using the Basic Design preset.
* Improved CSS output to only output once when `wp_print_scripts` is called multiple times
* Changed CSS generation for Style 1 to allow for multiple widgets with different style configurations

= 2.0.2 = 
* Added backward compatibility from Version 1.7 - all previous settings will now be imported.

= 2.0.1 =
* Fixed bug where widget would display the form before the widget had been properly configured
* Fixed a bug where the "Give thanks" link would show up even if "Give thanks" option had not been checked. Sorry about that!

= 2.0 =
* Converted the plugin to use the (not-so-new) <a href="http://codex.wordpress.org/Widget_API" rel="nofollow">Widgets API</a> introduced in WordPress 2.8. If use an earlier version, do not upgrade!
* Widget CSS is now shown in website `head` instead of inline
	* Filters were added to easily modify CSS: `cc_widget_style`, `cc_widget_style_{widget #}`
* Widget layout completely re-written to be much easier to use.
* All images used in the widget styles are now included in the plugin - this will reduce load time of each page
* New settings:
	* Email icons - Add nice-looking email icons to your widget
	* Form target - Choose whether or not forms open in new window
* This version has many large changes - please <a href="http://www.seodenver.com/constant-contact-wordpress-widget/">report any issues here</a> - you may need to re-configure some settings if the form is not displaying as it used to.
* Shortcode use has been updated - you must now use the widget ID (found under the widget's Form Settings section) in the shortcode. Before, it was `[constantcontact]`; now it is `[constantcontact id="#"]`, where # is your widget number.

= 1.7 =

* Moved from .17 to 1.7
* Added `apply_filters('cc_signup_form_widget')` code to allow for simple widget modification
* Improved layout, wording, and grouping of widget configuration options
* Improved readme.txt file

= .161 & .162 =

* Updated plugin to include GPL license information
* Added `function_exists` check to fix reported issue

= .16 =

* Added different SafeSubscribe(sm) image options for different background colors
* Combined the SafeSubscribe image chooser with Display SafeSubscribe option
* Improved <label>s in widget
* Improved Stylish email image display
* Added a proper Changelog to readme.txt
* Fixed widget installation "White Screen" error

= .154 & .155  =

* Added #go to submit button in all Styles to add more CSS customization
* Added shortcode functionality

= .153 =

* Fixed unexpected $end issue on some servers, by changing <? to <?php

= .151 =

* Added custom title to Stylish design
* Added custom email input label, and option to turn off label

= .15 =

* Added option to change size of email input
* Added option to hide the `<fieldset>` in the No Style form
* Fixed Internet Explorer issue where adding widget would cause active widgets list to disappear.
* Added options to change border and background color in the Basic Style form

= .146 =

* Stylish form now has custom button text
* Added custom width option for forms
* Improved Stylish form email image layout

= .144 & .145 =

* Email input javascript text toggle now works properly
* Added option to give widget author credit
* Made Constant Contact informational links nofollow
* Added option to rename or remove `<legend>` using the No Style form

= .143 =

* Updated CSS for Stylish design to prevent background of email image from showing through

= .142 =

* Fixed code for CSS-only and Stylish design

= .141 =

* Fixed code for Stylish design if javascript was turned off

= .14 =

* Added option to not show widget, but use with plugin-like functionality by calling widget_cc()
* Added option to define custom default input text toggle
* Added method to turn off input text toggle and remove the javascript
* Fixed improper <label for=""> code

= .131 =

* Fixed small coding error with closing input tag
* Added wptexturize for widget title

= .13 =

* Added option to show Constant Contact link
* Added Basic, Bubble, and Stylish style options from Constant Contact's default options (don't blame ME!)

== Upgrade Notice ==

= 2.0.3 =
* Fixed issue where upon form submission, users are taken to Constant Contact page that says "An unexpected error has occurred. Please try again."
* Fixed PHP notices being displayed if `WP_DEBUG` is turned on
* Fixed issue where background color may have been overwritten when using the Basic Design preset.
* Improved CSS output to only output once when `wp_print_scripts` is called multiple times
* Changed CSS generation for Style 1 to allow for multiple widgets with different style configurations

= 2.0.2 = 
* Added backward compatibility from Version 1.7 - all previous settings will now be imported.

= 2.0.1 =
* Fixed bug where widget would display the form before the widget had been properly configured
* Fixed a bug where the "Give thanks" link would show up even if "Give thanks" option had not been checked. Sorry about that!

= 2.0 =
* Converted the plugin to use the (not-so-new) <a href="http://codex.wordpress.org/Widget_API" rel="nofollow">Widgets API</a> introduced in WordPress 2.8. If use an earlier version, do not upgrade!
* This version has many large changes - please <a href="http://www.seodenver.com/constant-contact-wordpress-widget/">report any issues here</a>
* Shortcode use has been updated - you must now use the widget ID (found under the widget's Form Settings section) in the shortcode. Before, it was `[constantcontact]`; now it is `[constantcontact id="#"]`, where # is your widget number.
= 1.7 =

* If you want to modify widget output, there's now an `add_filters` method to do so.
* There's better layout of the widget control

= .162 =

* If you experienced a fatal error `Cannot redeclare cc_default_string()`, this update fixes that issue