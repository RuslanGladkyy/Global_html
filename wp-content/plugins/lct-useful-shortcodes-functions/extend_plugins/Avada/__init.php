<?php /*~~~*/
if ( $this->Avada ) {
	$dir = dirname( __FILE__ ) . '/';

	include( '_function.php' );

	include( 'override/_function.php' );

	$this->load_class( 'plugins_loaded', "{$this->zxzu}Avada_override", 6, "{$dir}override/__override.php", 'every' );

	$this->load_class( 'plugins_loaded', "{$this->zxzu}Avada_action", '', "{$dir}_action.php", 'every' );
	$this->load_class( 'plugins_loaded', "{$this->zxzu}Avada_shortcode", '', "{$dir}_shortcode.php" );
}
