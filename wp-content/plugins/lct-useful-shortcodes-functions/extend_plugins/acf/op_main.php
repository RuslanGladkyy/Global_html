<?php /*~~~*/
if ( function_exists( 'acf_add_options_page' ) ) :
	$dir = dirname( __FILE__ ) . '/';

	acf_add_options_page( [
		'page_title' => strtoupper( $this->zxza ) . ' Useful',
		'menu_title' => strtoupper( $this->zxza ) . ' Useful',
		'menu_slug'  => $this->zxzu . 'acf_op_main',
		'capability' => 'activate_plugins',
		'redirect'   => true
	] );


	acf_add_options_sub_page( [
		'title'      => 'Main Settings',
		'menu'       => 'Main Settings',
		'slug'       => $this->zxzu . 'acf_op_main_settings',
		'parent'     => $this->zxzu . 'acf_op_main',
		'capability' => 'activate_plugins'
	] );

	include( 'op_main_settings_groups.php' );

	//$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_op_main_settings", '', "{$dir}op_main_settings.php", 'every' );


	acf_add_options_sub_page( [
		'title'      => 'Fixes and Cleanups',
		'menu'       => 'Fixes and Cleanups',
		'slug'       => $this->zxzu . 'acf_op_main_fixes_cleanups',
		'parent'     => $this->zxzu . 'acf_op_main',
		'capability' => 'activate_plugins'
	] );

	include( 'op_main_fixes_cleanups_groups.php' );

	$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_op_main_fixes_cleanups", '', "{$dir}op_main_fixes_cleanups.php", 'every' );


	include( 'op_main_page.php' );

endif;
