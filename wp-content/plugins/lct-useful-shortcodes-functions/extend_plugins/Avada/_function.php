<?php /*~~~*/
/**
 * This is used as a bug fix in Avada v3.8.7
 *
 * @param $url
 *
 * @return mixed
 */
function lct_Avada_get_url_with_correct_scheme( $url ) {
	return $url;
}


/**
 * Add an fusion-clearfix clear div anywhere you want
 *
 * @param      $a
 * @param bool $echo
 *
 * @return string
 */
function Avada_clear( $a = [ ], $echo = false ) {
	$r = '<div class="fusion-clearfix"></div>';


	if (
		isset( $a['style'] ) &&
		$a['style']
	) {
		$r = sprintf( '<div class="fusion-clearfix" style="%s"></div>', $a['style'] );
	}


	if ( $echo )
		echo $r;


	return $r;
}
