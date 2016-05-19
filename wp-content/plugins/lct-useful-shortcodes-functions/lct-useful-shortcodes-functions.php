<?php /**
 * Plugin Name: LCT Useful Shortcodes & Functions
 * Plugin URI: http://lookclassy.com/wordpress-plugins/useful-shortcodes-functions/
 * Version: 5.40.14
 * Text Domain: TD_LCT
 * Author: Look Classy Technologies
 * Author URI: http://lookclassy.com/
 * License: GPLv3 (http://opensource.org/licenses/GPL-3.0)
 * Description: Shortcodes & Functions that will help make your life easier.
 * Copyright 2016 Look Classy Technologies  (email : info@lookclassy.com)
 */

/*
Copyright (C) 2016 Look Classy Technologies

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/


//Start your engines
$g_lct = new g_lct;

class g_lct {
	public $version;
	public $zxza = 'lct';
	public $zxzu = 'lct_';
	public $zxza_acf = 'lct:::';
	public $zxzd = ':::';
	public $zxzp = 'parent';
	public $dash = 'lct-useful-shortcodes-functions';
	public $us = 'lct_useful_shortcodes_functions';
	public $plugin_file;
	public $plugin_dir_url;
	public $plugin_dir_path;
	public $Avada = false;
	public $Avada_version = 0.0;
	public $Yoast_GA = false;
	public $Yoast_GA_option_yst_ga = false;
	public $Yoast_GA_universal = false;
	public $post_types;
	public $post_types_monitored;
	public $taxonomies;
	public $doing_ajax;
	public $pre = 'lct_'; //Just in case it is being used on another plugin or theme
	public $lct_dash = 'lct-useful-shortcodes-functions'; //Just in case it is being used on another plugin or theme
	public $lct_us = 'lct_useful_shortcodes_functions'; //Just in case it is being used on another plugin or theme


	public function __construct() {
		$this->set_globals();
		$this->very_very_first();


		register_activation_hook( $this->plugin_file, [ $this, 'activate' ] );
		//register_deactivation_hook( $this->plugin_file, [ $this, 'deactivate' ] );
		register_uninstall_hook( $this->plugin_file, [ "g_{$this->zxza}", 'uninstall' ] );
		add_action( 'load-update-core.php', [ $this, 'update_core_load' ] );
		add_action( 'upgrader_process_complete', [ $this, 'update' ] );


		$this->load_class( 'plugins_loaded', "{$this->zxzu}post_types", 5, 'admin/plugins_loaded/post_types.php', 'every' );
		$this->load_class( 'plugins_loaded', "{$this->zxzu}taxonomies", 5, 'admin/plugins_loaded/taxonomies.php', 'every' );


		add_action( 'plugins_loaded', [ $this, 'plugins_loaded_first' ], 5 );

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded_first_set_globals' ], 5 );

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 5 );

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded_extend_plugins' ], 5 );


		add_action( 'admin_init', [ $this, 'set_version' ] );
	}


	/**
	 * Include a file and load up the class(es) that are in it
	 *
	 * @param        $tag
	 * @param        $class
	 * @param int    $priority
	 * @param        $include
	 * @param string $callback
	 * @param string $load_type
	 *
	 * @return bool
	 */
	public function load_class( $tag, $class, $priority = 10, $include = '', $load_type = 'non_ajax', $callback = 'init' ) {
		$load_r_up = false;


		if ( ! $priority )
			$priority = 10;


		switch ( $load_type ) {
			case 'every':
			case 'everywhere':
				$load_r_up = true;
				break;


			case 'all':
			case 'always':
				if ( ! is_admin() )
					$load_r_up = true;
				break;


			case 'all_admin':
			case 'always_admin':
				if ( ! is_admin() )
					$load_r_up = true;
				break;


			case 'ajax':
				if ( $this->doing_ajax )
					$load_r_up = true;
				break;


			case 'non_ajax':
				if ( ! $this->doing_ajax && ! is_admin() )
					$load_r_up = true;
				break;


			case 'non_ajax_admin':
				if ( ! $this->doing_ajax && is_admin() )
					$load_r_up = true;
				break;


			case 'dev':
				if ( lct_is_dev() )
					$load_r_up = true;
				break;


			case 'sb':
				if ( lct_is_sandbox() )
					$load_r_up = true;
				break;


			case 'dev_or_sb':
				if ( lct_is_dev_or_sb() )
					$load_r_up = true;
				break;
		}


		if ( $load_r_up ) {
			if ( $include ) {
				/** @noinspection PhpIncludeInspection */
				include_once( $include );
			}

			add_action( $tag, [ $class, $callback ], $priority );
		}


		return $load_r_up;
	}


	/**
	 * Set us some globals
	 */
	public function set_globals() {
		//Set default vars
		$this->plugin_file     = __FILE__;
		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );


		//Set Constants
		if ( ! defined( 'LCT_VALUE_EMPTY' ) )
			define( 'LCT_VALUE_EMPTY', '---empty---' );
	}


	/**
	 * Very very first to load
	 */
	public function very_very_first() {
		//Load up our plugin_reliant class
		include_once( 'plugin_reliant.php' );
		$plugin_reliant = "{$this->zxzu}plugin_reliant";
		/** @noinspection PhpUndefinedMethodInspection */
		$plugin_reliant::init( $this );
	}


	/**
	 * Only runs when the plugin is activated
	 */
	public function activate() {
		$this->set_version();
		$this->check_version();
	}


	/**
	 * Only runs when the plugin is deactivated
	 */
	public function deactivate() {
	}


	/**
	 * Only runs when the plugin is uninstalled
	 */
	public static function uninstall() {
		delete_option( 'lct_useful_shortcodes_functions_version' );
	}


	public function update_core_load() {
		do_action( 'lct_ws_menu_editor', true );

		//TODO: cs - We need to find a better way to do this, but we only have a few queries so this will work for now. - 3/9/2016 6:23 PM
		do_action( 'lct_database_check' );
	}


	/**
	 * Only runs when the plugin is updated
	 */
	public function update() {
		$this->set_version();
		$this->check_version();
	}


	/**
	 * Let's get the important stuff going first
	 */
	public function plugins_loaded_first() {
		include_once( 'admin/plugins_loaded/_function.php' );
	}


	/**
	 * Let's get the important stuff going first
	 */
	public function plugins_loaded_first_set_globals() {
		//Set the post_types
		$class = "{$this->zxzu}post_types";
		global ${$class};
		$class = ${$class};
		/** @noinspection PhpUndefinedMethodInspection */
		$tmp = $class->get_post_types();

		if ( $tmp )
			$this->post_types = $tmp;


		//Set the post_types, included controlled ones
		/** @noinspection PhpUndefinedMethodInspection */
		$tmp = $class->get_post_types_all_monitored();

		if ( $tmp )
			$this->post_types_monitored = $tmp;


		//Set the taxonomies
		$class = "{$this->zxzu}taxonomies";
		global ${$class};
		$class = ${$class};
		/** @noinspection PhpUndefinedMethodInspection */
		$tmp = $class->get_taxonomies();

		if ( $tmp )
			$this->taxonomies = $tmp;
	}


	/**
	 * Load up everything else
	 */
	public function plugins_loaded() {
		include( 'admin/__init.php' );

		include( 'int/__init.php' );

		include( 'features/__init.php' );

		include_once( 'deprecated.php' );
	}


	/**
	 * Load us some items that are dependant on other plugins
	 */
	public function plugins_loaded_extend_plugins() {
		if ( ! defined( 'DISABLE_BAW' ) )
			include_once( 'extend_plugins/lct_baw_force_plugin_updates/index.php' );

		if ( defined( 'ENABLE_DDSG' ) )
			include_once( 'extend_plugins/lct_sitemap_generator/index.php' );

		if ( ! defined( 'DISABLE_LTLS' ) )
			include_once( 'extend_plugins/lct_textimage_linking_shortcode/index.php' );


		include( 'extend_plugins/acf/__init.php' );
		include( 'extend_plugins/Avada/__init.php' );
		include( 'extend_plugins/better-wp-security/__init.php' );
		include( 'extend_plugins/gforms/__init.php' );
		include( 'extend_plugins/maintenance/__init.php' );
		include( 'extend_plugins/redirection/__init.php' );
		include( 'extend_plugins/w3-total-cache/__init.php' );
		include( 'extend_plugins/woocommerce/__init.php' );
		include( 'extend_plugins/wordpress-seo/__init.php' );
	}


	/**
	 * Set the current version of this plugin
	 */
	public function set_version() {
		$plugin        = get_plugin_data( $this->plugin_file );
		$this->version = $plugin['Version'];
	}


	/**
	 * Check if the version has never been set
	 */
	public function check_version() {
		if ( $this->version != get_option( $this->us . '_version' ) )
			$this->update_version();
	}


	/**
	 * Update the DB when the plugin files are updated
	 */
	public function update_version() {
		apply_filters( 'lct_editzz_update_files', false, false );

		do_action( 'lct_ws_menu_editor', true );

		//TODO: cs - We need to find a better way to do this, but we only have a few queries so this will work for now. - 3/9/2016 6:23 PM
		do_action( 'lct_database_check' );

		update_option( $this->us . '_version', $this->version );
	}
}


//TODO: cs - UD get_terms() when you think everyone is running v4.5 - 04/18/2016 10:54 PM
