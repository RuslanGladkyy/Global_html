<?php /*~~~*/
if ( ! function_exists( 'P_R_SERVER' ) ) {
	/**
	 * Print All _SERVER vars
	 *
	 * @param bool $return
	 *
	 * @return bool|string
	 */
	function P_R_SERVER( $return = false ) {
		if ( $return )
			return P_R( $_SERVER, '$_SERVER', true );


		P_R( $_SERVER, '$_SERVER' );


		return false;
	}
}


add_shortcode( 'P_R_SERVER', 'lct_sc_P_R_SERVER' );
/**
 * [P_R_SERVER]
 *
 * @return bool|string
 */
function lct_sc_P_R_SERVER() {
	return P_R_SERVER( true );
}


if ( ! function_exists( 'P_R_POST' ) ) {
	/**
	 * Print All _POST vars
	 *
	 * @param bool $return
	 *
	 * @return bool|string
	 */
	function P_R_POST( $return = false ) {
		if ( $return )
			return P_R( $_POST, '$_POST', true );


		P_R( $_POST, '$_POST' );


		return false;
	}
}


add_shortcode( 'P_R_POST', 'lct_sc_P_R_POST' );
/**
 * [P_R_POST]
 *
 * @return bool|string
 */
function lct_sc_P_R_POST() {
	return P_R_POST( true );
}
