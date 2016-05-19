<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}admin_shortcode", 'init' ] );

class lct_admin_shortcode {
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


		add_shortcode( "{$this->zxzp->zxzu}admin_onetime_script_run", [ $this, 'admin_onetime_script_run' ] );
	}


	/**
	 * Run a one-time script from an action hook in the theme folder's functions.php file
	 * Use~~~~~
	 * add_action( "{$this->zxzp->zxzu}admin_onetime_script_run", 'lca_admin_onetime_script_run' );
	 * function lca_admin_onetime_script_run() {
	 * echo 'works';
	 * return;
	 * }
	 *
	 * @param $a
	 */
	function admin_onetime_script_run( $a ) {
		do_action( "{$this->zxzp->zxzu}admin_onetime_script_run", $a );


		return;
	}
}
