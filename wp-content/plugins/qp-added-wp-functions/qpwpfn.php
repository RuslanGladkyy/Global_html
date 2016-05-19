<?php
    
/*
Plugin Name: Added WP Functions by QP
Plugin URL: http://www.quatipixels.pw
Description: Easily add more functionality to WordPress function.php.
Author: Quati Pixels
Version: 1.03
Author URL: http://www.quatipixels.pw
*/

//use jquery and ajax

	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/qpwpfn_functions.php");
	require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/qpwpfn_deactivate.php");

	function qpwpfn_admin() {
		include('qpwpfn_admin.php');
	}

	function qpwpfn_admin_actions() {
		add_options_page("Added WP Functions", "Added WP Functions",'manage_options', "added-wp-functions", "qpwpfn_admin");
	}

	add_action('admin_menu', 'qpwpfn_admin_actions');

	register_deactivation_hook(__FILE__, 'qpwpfn_deactivate');

?>