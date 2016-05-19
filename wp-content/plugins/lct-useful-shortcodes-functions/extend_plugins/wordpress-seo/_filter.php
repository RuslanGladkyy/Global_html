<?php /*~~~*/
add_filter( 'wpseo_opengraph_site_name', 'lct_opengraph_site_name' );
/**
 * Disable Yoast SEO's wpseo_opengraph_site_name when the lct setting is checked
 *
 * @param $title
 *
 * @return bool
 */
function lct_opengraph_site_name( $title ) {
	if ( get_field( 'lct:::hide_og_site_name', lct_o() ) )
		$title = false;


	return $title;
}
