<?php /*~~~*/
if ( ! function_exists( 'is_blog' ) ) {
	/**
	 * Check if a page is a blogroll or single post.
	 *
	 * @return bool
	 */
	function is_blog() {
		global $post;

		$post_type = get_post_type( $post );


		return (
			(
				is_home() ||
				is_archive() ||
				is_single()
			) &&
			( $post_type == 'post' )
		) ? true : false;
	}
}


if ( ! function_exists( 'the_slug' ) ) {
	/**
	 * Get the slug of a post
	 *
	 * @param null      $post_id
	 * @param bool|true $slash
	 *
	 * @return bool|string
	 */
	function the_slug( $post_id = null, $slash = true ) {
		if ( ! $post_id )
			return false;

		$post_data = get_post( $post_id, ARRAY_A );

		if ( ! $slash )
			return $post_data['post_name'];

		return $post_data['post_name'] . '/';
	}
}


add_filter( 'widget_text', 'lct_execute_php', 100 );
/**
 * execute php in the text widget
 *
 * @param $html
 *
 * @return string
 */
function lct_execute_php( $html ) {
	if ( strpos( $html, "<" . "?php" ) !== false ) {
		ob_start();
		eval( "?" . ">" . $html );
		$html = ob_get_contents();
		ob_end_clean();
	}

	return $html;
}


add_filter( 'post_thumbnail_html', 'lct_remove_thumbnail_dimensions', 10, 1 );
add_filter( 'image_send_to_editor', 'lct_remove_thumbnail_dimensions', 10, 1 );
/**
 * Alter the html input when adding media
 *
 * @param $html
 *
 * @return mixed
 */
function lct_remove_thumbnail_dimensions( $html ) {
	//remove width & height tags from img
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );

	return $html;
}


add_filter( 'image_send_to_editor', 'lct_remove_site_root', 10, 1 );
/**
 * Alter the html input when adding media
 *
 * @param $html
 *
 * @return mixed
 */
function lct_remove_site_root( $html ) {
	//remove the root of the url
	$root_site                = lct_url_root_site();
	$root_site_https          = 'https' . $root_site;
	$root_site_http           = 'http' . $root_site;
	$root_site_without_scheme = str_replace( [ 'http:', 'https:' ], '', $root_site );

	$find = [
		$root_site_https,
		$root_site_http,
		$root_site,
		$root_site_without_scheme,
	];

	$html = str_replace( $find, '', $html );

	return $html;
}


if ( ! function_exists( 'lct_domain_mapping_plugins_uri' ) && function_exists( 'domain_mapping_plugins_uri' ) ) {
	remove_filter( 'plugins_url', 'domain_mapping_plugins_uri', 1 );
	add_filter( 'plugins_url', 'lct_domain_mapping_plugins_uri', 1 );
	/**
	 * Fix Multisite plugins_url issue
	 *
	 * @param      $full_url
	 * @param null $path
	 * @param null $plugin
	 *
	 * @return string
	 */
	function lct_domain_mapping_plugins_uri( $full_url, $path = null, $plugin = null ) {
		$pos = stripos( $full_url, PLUGINDIR );
		if ( $pos === false )
			return $full_url;
		else
			return get_option( 'siteurl' ) . substr( $full_url, $pos - 1 );
	}
}


add_filter( 'widget_title', 'lct_html_widget_title', 999 );
/**
 * Allow html tags in widget titles
 *
 * @param $title
 *
 * @return string
 */
function lct_html_widget_title( $title ) {
	$title = str_replace( '[', '<', $title );
	$title = str_replace( '[/', '</', $title );
	$title = str_replace( ']', '>', $title );
	$title = str_replace( '/]', '/>', $title );

	return html_entity_decode( $title );
}


add_action( 'init', 'set_user_timezone' );
/**
 * //TODO: cs - This needs some lovin' - 3/29/2016 9:30 PM
 * Set the timezone to the logged in users default Timezone
 *
 * @param null $user_ID
 *
 * @return bool|mixed|void
 */
function set_user_timezone( $user_ID = null ) {
	//We can only process this action is acf is also installed and running
	if ( ! class_exists( 'acf' ) )
		return false;


	if ( get_field( 'lct:::disable_auto_set_user_timezone', lct_o() ) )
		return false;


	if ( ! $user_ID )
		$user_ID = get_current_user_id();


	if ( ! $user_ID ) {
		date_default_timezone_set( get_option( 'timezone_string' ) );


		return get_option( 'timezone_string' );
	}


	$user_timezone = get_field( 'npl_user_timezone', lct_u( $user_ID ) );


	if ( $user_timezone ) {
		date_default_timezone_set( $user_timezone );


		return $user_timezone;
	}


	date_default_timezone_set( get_option( 'timezone_string' ) );


	return get_option( 'timezone_string' );
}


/**
 * Get a single value from a WP Term
 *
 * @param        $term_id
 * @param string $tax - The terms taxonomy
 * @param null   $key
 * @param string $output
 * @param string $filter
 *
 * @return array|int|null|object|WP_Error
 * *
 * @value    string - The value you want to retrieve
 *           Possible values to call
 * @slug
 * @term_group
 * @term_order
 * @term_taxonomy_id
 * @taxonomy
 * @description
 * @parent
 * @count
 * @filter
 */
function lct_get_term_value( $term_id, $tax = "lct_option", $key = null, $output = "OBJECT", $filter = "raw" ) {
	$term = get_term( $term_id, $tax, $output, $filter );

	if ( ! $term )
		return 0;

	if ( $key )
		return $term->$key;

	return $term;
}


/**
 * get the meta of a term
 *
 * @param        $term_id
 * @param string $tax
 * @param null   $key
 * @param string $output
 * @param string $filter
 *
 * @return bool|mixed|void
 */
function lct_get_term_meta( $term_id, $tax = "lct_option", $key = null, $output = "OBJECT", $filter = "raw" ) {
	if ( ! $term_id || ! $tax )
		return false;

	$tax_term_id = get_option( $tax . "_" . $term_id );

	if ( $key )
		return $tax_term_id[ $key ];
	else
		return $tax_term_id;
}


/**
 * get the parent of a term
 *
 * @param        $term_id
 * @param        $tax
 * @param null   $key
 * @param string $output
 * @param string $filter
 *
 * @return array|int|mixed|null|object|WP_Error
 */
function lct_get_parent_term_value( $term_id, $tax, $key = null, $output = "OBJECT", $filter = "raw" ) {
	$term = get_term( $term_id, $tax, $output, $filter );

	$parent_term = get_term( $term->parent, $tax, $output, $filter );

	if ( ! $parent_term )
		return 0;

	if ( $key )
		return $parent_term->$key;

	return $parent_term;
}


/**
 * get the meta of a term's parent
 *
 * @param null   $term_id
 * @param null   $tax
 * @param null   $key
 * @param string $output
 * @param string $filter
 *
 * @return bool|mixed|void
 */
function lct_get_parent_term_meta( $term_id = null, $tax = null, $key = null, $output = "OBJECT", $filter = "raw" ) {
	if ( ! $term_id || ! $tax )
		return false;

	$term = get_term( $term_id, $tax, $output, $filter );

	$parent_term_id = $term->parent;

	$tax_parent_term_id = get_option( $tax . "_" . $parent_term_id );

	if ( $key )
		return $tax_parent_term_id[ $key ];
	else
		return $tax_parent_term_id;
}


/**
 * fix a number
 *
 * @param $number
 *
 * @return float
 */
function lct_clean_number_for_math( $number ) {
	$new_number = floatval( preg_replace( "/[^-0-9\.]/", "", $number ) );

	return (float) $new_number;
}


/**
 * I don't know
 *
 * @param $content
 * @param $maxchars
 *
 * @return string
 */
function lct_excerpt_of_string( $content, $maxchars ) {
	$content = substr( $content, 0, $maxchars );
	$pos     = strrpos( $content, " " );

	if ( $pos > 0 )
		$content = substr( $content, 0, $pos );

	return $content;
}
