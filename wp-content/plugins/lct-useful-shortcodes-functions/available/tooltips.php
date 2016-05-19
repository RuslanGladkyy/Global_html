<?php /*~~~*/
add_action( 'init', [ "lct_available_tooltips", 'init' ], 999999 );

class lct_available_tooltips {
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


		wp_register_script( "{$this->zxzp->zxzu}tooltips", "{$this->zxzp->plugin_dir_url}assets/js/tooltips.min.js", [ 'jquery-ui-tooltip' ], false );

		add_action( 'wp_print_scripts', [ $this, 'wp_print_scripts' ] );
	}


	/**
	 * Prints required tooltip scripts
	 */
	public function wp_print_scripts() {
		wp_enqueue_script( "{$this->zxzp->zxzu}tooltips" );


		wp_enqueue_style( "{$this->zxzp->zxzu}tooltip", "{$this->zxzp->plugin_dir_url}assets/css/tooltip.min.css", null );

		if ( file_exists( get_template_directory() . '/assets/fonts/fontawesome/font-awesome.css' ) )
			wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/fonts/fontawesome/font-awesome.css', [ ] );
		else
			wp_enqueue_style( 'fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css', [ ] );
	}
}
