<?php /*~~~*/

class lct_features_action {
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


		add_action( 'init', [ $this, 'do_shortcode' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'front_css' ], 999 );

		add_action( 'lct_jq_doc_ready_add', [ $this, 'jq_doc_ready_add' ], 10, 2 );

		add_action( 'lct_wp_footer_style_add', [ $this, 'wp_footer_style_add' ] );

		add_action( 'lct_jq_autosize', [ $this, 'jq_autosize' ] );
		add_action( 'lct_jquery_autosize_min_js', [ $this, 'jq_autosize' ] ); //old version that may still be in use
	}


	/**
	 * Make sure the shortcodes get processed
	 */
	public function do_shortcode() {
		add_filter( 'the_content', 'do_shortcode' );
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'widget_execphp', 'do_shortcode' );
	}


	/**
	 * ADD custom stylesheet to front-end
	 */
	public function front_css() {
		//We can only process this action is acf is also installed and running
		if ( ! class_exists( 'acf' ) )
			return;

		if ( ! get_field( "{$this->zxzp->zxza_acf}enable_front_css", lct_o() ) )
			return;


		wp_enqueue_style( "{$this->zxzp->zxzu}front", $this->zxzp->plugin_dir_url . 'assets/css/front.min.css', null );
	}


	/**
	 * Print out any custom document ready items that have been queued
	 * *
	 * Called as an action in jq_doc_ready_add()
	 */
	public function jq_doc_ready() {
		$current_action = current_action();

		global ${"{$this->zxzp->zxzu}jq_doc_ready_{$current_action}"};

		$jq = ${"{$this->zxzp->zxzu}jq_doc_ready_{$current_action}"};


		if ( $jq ) {
			$jq = array_unique( $jq );

			echo sprintf( '<script>jQuery(document).ready( function() {%s});</script>', implode( '', $jq ) );
		}
	}


	/**
	 * Add a document ready item
	 *
	 * @param        $item
	 * @param string $location
	 */
	public function jq_doc_ready_add( $item, $location = 'wp_footer' ) {
		global ${"{$this->zxzp->zxzu}jq_doc_ready_{$location}"};


		if ( ! ${"{$this->zxzp->zxzu}jq_doc_ready_{$location}"} )
			${"{$this->zxzp->zxzu}jq_doc_ready_{$location}"} = [ ];

		${"{$this->zxzp->zxzu}jq_doc_ready_{$location}"}[] = $item;


		add_action( $location, [ $this, 'jq_doc_ready' ], 999 );
	}


	/**
	 * Print out any custom CSS items that have been queued
	 * *
	 * Called as an action in wp_footer_style_add()
	 */
	public function wp_footer_style() {
		global ${"{$this->zxzp->zxzu}wp_footer_style"};

		$style = ${"{$this->zxzp->zxzu}wp_footer_style"};


		if ( $style ) {
			$style = array_unique( $style );

			echo sprintf( '<style>%s</style>', implode( '', $style ) );
		}
	}


	/**
	 * Add a CSS item
	 *
	 * @param $item
	 */
	public function wp_footer_style_add( $item ) {
		global ${"{$this->zxzp->zxzu}wp_footer_style"};


		if ( ! ${"{$this->zxzp->zxzu}wp_footer_style"} )
			${"{$this->zxzp->zxzu}wp_footer_style"} = [ ];

		${"{$this->zxzp->zxzu}wp_footer_style"}[] = $item;


		add_action( 'wp_footer', [ $this, 'wp_footer_style' ], 998 );
	}


	/**
	 * ADD autosize.js assets when they are needed
	 */
	public function jq_autosize() {
		if ( lct_is_dev() )
			wp_enqueue_script( "{$this->zxzp->zxzu}jq_autosize", $this->zxzp->plugin_dir_url . 'includes/autosize/autosize.js', [ 'jquery' ] );
		else
			wp_enqueue_script( "{$this->zxzp->zxzu}jq_autosize", $this->zxzp->plugin_dir_url . 'includes/autosize/autosize.min.js', [ 'jquery' ] );
	}
}
