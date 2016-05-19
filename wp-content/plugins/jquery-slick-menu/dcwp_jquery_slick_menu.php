<?php
/*
		Plugin Name: jQuery Slick Menu
		Plugin URI: http://www.designchemical.com/blog/index.php/wordpress-plugins/wordpress-plugin-jquery-slick-menu-widget/
		Tags: jquery, flyout, menu, vertical, animated, css, navigation, widget, slider
		Description: Creates a widget, which adds a sticky sliding menu from any Wordpress custom menu.
		Author: Lee Chestnutt
		Version: 1.3.2
		Author URI: http://www.designchemical.com
*/

global $registered_skins;

class dc_jqslickmenu {

	function dc_jqslickmenu(){
		global $registered_skins;
	
		if(!is_admin()){
			// Header styles
			add_action( 'wp_head', array('dc_jqslickmenu', 'header') );
			// Scripts
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'dcjqslickmenu', dc_jqslickmenu::get_plugin_directory() . '/js/jquery.slick.menu.2.1.js', array('jquery') );
			// Shortcodes
			add_shortcode( 'dcsl-link', 'dcsl_menu_link_shortcode' );
		}
		add_action( 'wp_footer', array('dc_jqslickmenu', 'footer') );
		
		$registered_skins = array();
	}

	function header(){
		echo "\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".dc_jqslickmenu::get_plugin_directory()."/css/dcslickmenu.css\" media=\"screen\" />";
	}
	
	function footer(){
		//echo "\n\t";
	}
	
	function options(){}

	function get_plugin_directory(){
		return WP_PLUGIN_URL . '/jquery-slick-menu';	
	}

};

// Include the widget
include_once('dcwp_jquery_slick_menu_widget.php');

// Initialize the plugin.
$dcjqslickmenu = new dc_jqslickmenu();

// Register the widget
add_action('widgets_init', create_function('', 'return register_widget("dc_jqslickmenu_widget");'));

/**
* Create a link shortcode for opening/closing the menu
*/
function dcsl_menu_link_shortcode($atts){
	
	extract(shortcode_atts( array(
		'text' => 'Click Here',
		'action' => 'link'
	), $atts));

	return "<a href='#' class='dcsl-$action'>$text</a>";

}

?>