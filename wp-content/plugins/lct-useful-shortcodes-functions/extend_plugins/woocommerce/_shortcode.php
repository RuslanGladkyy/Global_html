<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}woocommerce_shortcode", 'init' ], 1000000 );

class lct_woocommerce_shortcode {
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


		add_shortcode( "{$this->zxzp->zxzu}wc_login_form", [ $this, 'wc_login_form' ] );
	}


	/**
	 * Generate the WooCommerce login form
	 */
	public function wc_login_form() {
		global $wp;


		ob_start();


		if ( ! is_user_logged_in() ) {
			$message = apply_filters( 'woocommerce_my_account_message', '' );

			if ( ! empty( $message ) )
				wc_add_notice( $message );

			if ( isset( $wp->query_vars['lost-password'] ) ) {
				WC_Shortcode_My_Account::lost_password();
			} else {
				wc_get_template( 'myaccount/form-login.php' );
			}
		}


		$output = ob_get_clean();


		return $output;
	}
}
