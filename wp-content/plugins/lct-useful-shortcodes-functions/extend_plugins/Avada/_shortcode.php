<?php /*~~~*/

class lct_Avada_shortcode {
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
		//global $g_zxza;
		//$this->zxzp = $g_zxza;


		add_shortcode( 'Avada_clear', [ $this, 'Avada_clear' ] );
		add_shortcode( 'avada_clear', [ $this, 'Avada_clear' ] );
	}


	/**
	 * [avada_clear style=""]
	 * Add an fusion-clearfix clear div anywhere you want
	 *
	 * @param $a
	 *
	 * @return bool|string
	 */
	public function Avada_clear( $a ) {
		return Avada_clear( $a );
	}
}
