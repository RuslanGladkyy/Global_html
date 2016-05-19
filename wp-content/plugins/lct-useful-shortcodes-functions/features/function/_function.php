<?php /*~~~*/
/**
 * Checks if the value is in the page's permalink
 * Created for use in widget logic plugin, but you can use anywhere
 *
 * @param $search_this_in_url
 *
 * @return bool
 */
function lct_is_in_url( $search_this_in_url ) {
	if ( empty( $search_this_in_url ) )
		return false;


	if ( strpos( get_permalink(), $search_this_in_url ) !== false )
		return true;
	else
		return false;
}


/**
 * Check if the page you are on is a page or subpage of the specified $page_id
 *
 * @param $page_id
 *
 * @return bool
 */
function lct_is_in_page( $page_id ) {
	global $post;

	$post_parent = $post->post_parent;


	if ( empty( $page_id ) )
		return false;

	if ( is_page( $page_id ) )
		return true;

	while( $post_parent != 0 ) {
		if ( is_page() && $post_parent == $page_id )
			return true;

		$new_post    = get_post( $post_parent );
		$post_parent = $new_post->post_parent;
	}


	return false;
}


/**
 * List of only role and cap prefixes
 *
 * @return array
 */
function lct_get_role_cap_prefixes_only() {
	return [
		'lct_role_',
		'lct_cap_',
	];
}


/**
 * List of any prefixed we are using for role/cap filtering
 *
 * @return array
 */
function lct_get_role_cap_prefixes() {
	return array_merge(
		lct_get_role_cap_prefixes_only(),
		[
			'lct_ch_',
		]
	);
}


/**
 * Get the parent of a single term
 *
 * @param      $term_id
 * @param      $taxonomy
 * @param bool $get_all_parents
 *
 * @return array
 */
function lct_get_term_parent( $term_id, $taxonomy, $get_all_parents = false ) {
	$parent = [ ];
	$term   = get_term( $term_id, $taxonomy );


	if ( ! is_wp_error( $term ) ) {
		if ( $term->parent !== 0 )
			$parent[] = $term->parent;

		if ( $get_all_parents && $term->parent !== 0 ) {
			$term = get_term( $term->parent, $taxonomy );

			if ( $term->parent !== 0 && ! is_wp_error( $term ) )
				$parent[] = $term->parent;
		}
	}

	$parent = array_unique( $parent );


	return $parent;
}


/**
 * Get the parent of an array of terms
 *
 * @param      $terms
 * @param      $taxonomy
 * @param bool $get_all_parents
 * @param bool $include_terms
 *
 * @return array
 */
function lct_get_terms_parents( $terms, $taxonomy, $get_all_parents = false, $include_terms = false ) {
	$parents = [ ];


	foreach ( $terms as $term ) {
		( is_object( $term ) ) ? $is_object = 1 : $is_object = 0;

		if ( $is_object )
			$term = $term->term_id;

		$parent  = lct_get_term_parent( $term, $taxonomy, $get_all_parents );
		$parents = array_merge( $parents, $parent );

		if ( $include_terms )
			$parents = array_merge( $parents, [ $term ] );
	}

	$parents = array_unique( $parents );


	return $parents;
}


/**
 * Get an array of term_ids from an array of term objects
 *
 * @param $terms
 *
 * @return array
 */
function lct_get_terms_ids( $terms ) {
	$term_ids = [ ];


	if ( is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			( is_object( $term ) ) ? $is_object = 1 : $is_object = 0;

			if ( $is_object )
				$term_ids[] = $term->term_id;
		}
	}


	return $term_ids;
}


/**
 * Check if this the post is brand new or not
 *
 * @param $post_id
 *
 * @return bool
 */
function lct_is_new_save_post( $post_id ) {
	global $lct_is_new_save_post;

	$post = get_post( $post_id );


	if (
		$lct_is_new_save_post ||
		(
			is_int( $post_id ) &&
			is_object( $post ) &&
			$post->post_modified_gmt &&
			$post->post_modified_gmt == $post->post_date_gmt
		)
	) {
		$lct_is_new_save_post = true;

		return $lct_is_new_save_post;
	}


	return false;
}


/**
 * Returns a 2 key array based on your $needle and $offset
 *
 * @param $string
 * @param $needle
 * @param $offset
 *
 * @return array|bool
 */
function lct_explode_nth( $string, $needle, $offset ) {
	$newString = $string;
	$totalPos  = 0;
	$length    = strlen( $needle );

	for ( $i = 0; $i < $offset; $i ++ ) {
		$pos = strpos( $newString, $needle );

		// If you run out of string before you find all your needles
		if ( $pos === false )
			return false;


		$newString = substr( $newString, $pos + $length );
		$totalPos += $pos + $length;
	}


	return [ substr( $string, 0, $totalPos - $length ), substr( $string, $totalPos ) ];
}


/**
 * Direct function for a function wrapped in a class
 *
 * @param $a
 *
 * @return
 */
function lct_theme_chunk( $a ) {
	global $lct_features_shortcode;


	return $lct_features_shortcode->theme_chunk( $a );
}
