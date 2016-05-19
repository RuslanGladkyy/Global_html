<?php /*~~~*/
if ( class_exists( 'acf' ) ) {
	$dir = dirname( __FILE__ ) . '/';

	include( '_function.php' );

	include( 'op_main.php' );

	$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_action", '', "{$dir}_action.php", 'every' );
	$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_filter", '', "{$dir}_filter.php", 'every' );
	$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_shortcode", '', "{$dir}_shortcode.php", 'every' );

	$this->load_class( 'plugins_loaded', "{$this->zxzu}acf_instant_save", '', "{$dir}ajax/instant_save.php", 'every' );
}
