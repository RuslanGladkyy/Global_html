<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}gforms_shortcode", 'init' ], 100000 );

class lct_gforms_shortcode {
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


		add_shortcode( "{$this->zxzp->zxzu}gf_submit", [ $this, 'submit_button_anywhere' ] );
	}


	/**
	 * Shortcode to put a submit button anywhere you feel like it should go
	 *
	 * @param $a
	 *
	 * @return string
	 */
	function submit_button_anywhere( $a ) {
		if ( empty( $a['id'] ) )
			return false;


		$html = [ ];


		if ( empty( $a['text'] ) )
			$a['text'] = 'Send';


		$class = " class='{$this->zxzp->zxzu}gf_submit_{$a['id']} gform_button button";

		if ( ! empty( $a['class'] ) )
			$class .= ' ' . $a['class'];

		//We can only process this function is acf is also installed and running
		if ( class_exists( 'acf' ) )
			$class .= ' ' . get_field( "{$this->zxzp->zxza_acf}gform_button_custom_class", lct_o() );

		$class .= "'";


		if ( ! empty( $a['style'] ) )
			$style = " style='{$a['style']}'";
		else
			$style = '';


		if ( ! empty( $a['live'] ) ) {
			switch ( $a['live'] ) {
				case 'hide':
					do_action( 'lct_wp_footer_style_add', "#gform_submit_button_{$a['id']}{display: none !important;}" );
					break;


				default:
			}
		}


		$html[] = "<a href='#' {$class}{$style}>{$a['text']}</a>";


		$jq = "jQuery('.{$this->zxzp->zxzu}gf_submit_{$a['id']}').click( function(e) {
			jQuery('#gform_submit_button_{$a['id']}').click();
			e.preventDefault();
		});";

		do_action( 'lct_jq_doc_ready_add', $jq );


		return implode( '', $html );
	}
}
