=== JQuery Slick Menu Widget ===
Contributors: remix4
Donate link: http://www.designchemical.com/blog/index.php/wordpress-plugins/wordpress-plugin-jquery-slick-menu-widget/#form-donate
Tags: jquery, flyout, sliding, menu, vertical, horizontal, animated, navigation, widget
Requires at least: 3.0
Tested up to: 3.12
Stable tag: 1.3.2

jQuery Slick Menu creates a sticky, sliding menu widget from any Wordpress custom menu.

== Description ==

Creates a widget, which adds a sticky, sliding menu from any standard Wordpress custom menu using jQuery. Can handle multiple slick menus on each page and the location of each menu can be easily set from the widget control panel using a combination of the "location" option and the "offset". The plugin also includes shortcodes, which allow you to add external text links to activate the sliding menu.

= Menu Options =

The widget has several parameters that can be configured to help cutomise the sliding menu:

* Tab Text - Enter the text that you would like to use for the menu tab.
* Location - This sets the location of the slide out menu in the browser window. There are 6 different locations to choose from:
	* Top Left
	* Top Right
	* Bottom Left
	* Bottom Right
	* Left
	* Right
* Offset - The number of pixels from the edge of the browser window.
* Animation Speed - The speed at which the slide out menu will open/close
* Auto-Close Menu - If checked, the menu will automatically slide closed when the user clicks anywhere in the browser
* Skin - 12 different sample skins for styling the slick container are available to give examples of css that can be used to style your own slick menu.

= Shortcodes =

The plugin includes the feature to add text links within your site content that will open/close the floating tab.

1. [dcsl-link] - default link, which will toggle the menu open/closed with the link text "Click Here".
2. [dcsl-link text="Slick Menu"] - toggle the menu open/closed with the link text "Slick Menu".
3. [dcsl-link action="open"] - open the menu with the default link text.
4. [dcsl-link action="close"] - close the menu with the default link text.

[__See demo__](http://www.designchemical.com/lab/demo-wordpress-jquery-slick-menu-plugin/)

== Installation ==

1. Upload the plugin through `Plugins > Add New > Upload` interface or upload `jquery-slick-menu` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the widgets section, select the jQuery Slick Menu widget and add to one of your widget areas
4. Select one of the WP menus, set the required settings and save your widget

== Frequently Asked Questions ==

= The menu appears on the page but does not work. Why? =

One main reason for this is that the plugin adds the required jQuery code to your template footer. Make sure that your template files contain the wp_footer() function.

Another likely cause is due to other non-functioning plugins, which may have errors and cause the plugin javascript to not load. Remove any unwanted plugins and try again. Checking with Firebug will show where these error are occuring.

[__Also check out our jquery slick menu faq page__](http://www.designchemical.com/blog/index.php/frequently-asked-questions/jquery-slick-menu/)

== Screenshots ==

1. JQuery Slick Menu widget in edit mode
2. Locations For Menu Settings

== Changelog ==

= 1.3.2 = 
* Update: Update to jquery.slick.2.1.js

= 1.3.1 = 
* Fixed: Validation error

= 1.3 = 
* Added: Shortcodes to open/close menu using external links
* Added: New skins

= 1.2 = 
* Fixed: Bug with Safari left/right location

= 1.1 = 
* Fixed: Bug with "no theme" option

= 1.0 = 
* First release

== Upgrade Notice ==
