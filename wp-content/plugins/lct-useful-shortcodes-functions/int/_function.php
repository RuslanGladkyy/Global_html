<?php /*~~~*/
/**
 * Append note to text if the site is a dev or sandbox
 *
 * @param       $text
 * @param array $appension
 *
 * @return string
 */
function lct_i_append_dev_sb( $text, $appension = [ 'dev' => '::DEV::', 'sb' => '::DEV_SB::' ] ) {
	if ( lct_is_dev_or_sb() ) {
		if ( lct_is_sandbox() )
			$appension = $appension['sb'];
		else
			$appension = $appension['dev'];

		$text = sprintf( '%s %s', $appension, $text );
	}


	return $text;
}


/**
 * modify the gaTracker category before we echo it
 *
 * @param $category
 *
 * @return string
 */
function lct_i_get_gaTracker_category( $category ) {
	$category = lct_i_append_dev_sb( $category, [ 'dev' => '::DEV::', 'sb' => '::DEV_SB::' ] );


	return $category;
}
