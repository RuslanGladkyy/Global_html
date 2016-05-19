<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}features_shortcode_static", 'init' ] );

class lct_features_shortcode_static {
	/**
	 * Get the class running
	 */
	public static function init() {
		$class = __CLASS__;
		//global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 */
	public function __construct() {
		global $g_lct;
		$this->zxzp = $g_lct;


		add_shortcode( 'up_path', [ $this, 'function_passthru' ] );
		add_shortcode( 'path_up', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_site_wp', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_theme', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_plugin', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_theme_parent', [ $this, 'function_passthru' ] );


		add_shortcode( 'up', [ $this, 'function_passthru' ] );
		add_shortcode( 'url_up', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_root_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_site_wp', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_theme', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_plugin', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_theme_parent', [ $this, 'function_passthru' ] );
	}


	/**
	 * Get the site's upload directory URL
	 *
	 * @return mixed
	 */
	public function function_passthru() {
		$caller   = debug_backtrace();
		$function = $caller[0]['args'][2];


		if ( function_exists( $function = $this->zxzp->zxzu . $function ) )
			return $function();
		else if ( function_exists( $function ) )
			return $function();


		return false;
	}
}
