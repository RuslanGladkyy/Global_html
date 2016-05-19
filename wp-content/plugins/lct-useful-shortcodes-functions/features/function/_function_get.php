<?php /*~~~*/
/**
 * We use this all over the place, so let's save some time
 *
 * @param        $category
 * @param string $action
 * @param string $label
 * @param bool   $onclick_wrap
 *
 * @return string
 */
function lct_get_gaTracker_onclick( $category, $action = '', $label = '', $onclick_wrap = true ) {
	global $g_lct;

	$onclick = '';


	if ( $g_lct->Yoast_GA ) {
		if ( $action )
			$action = ", '{$action}'";

		if ( $label )
			$label = ", '{$label}'";

		if ( $g_lct->Yoast_GA_universal ) {
			$onclick = sprintf(
				'__gaTracker( \'send\', \'event\', \'%s\'%s%s );',
				lct_i_get_gaTracker_category( $category ),
				$action,
				$label
			);
		} else {
			$onclick = sprintf(
				'_gaq.push( [ \'_trackEvent\', \'%s\'%s%s ] );',
				lct_i_get_gaTracker_category( $category ),
				$action,
				$label
			);
		}

		if ( $onclick_wrap )
			$onclick = 'onclick="' . $onclick . '"';
	}


	return $onclick;
}


/**
 * filter out the default results of get_terms()
 *
 * @param       $taxonomy
 * @param       $plugin
 * @param array $custom_args
 *
 * @return array|int|WP_Error
 */
function lct_get_terms( $taxonomy, $plugin, $custom_args = [ ] ) {
	global ${$plugin};
	$plugin = ${$plugin};

	$meta_query = [ ];


	if ( ! empty( $plugin ) && ! isset( $custom_args['lct_global'] ) ) {
		$meta_query['relation'] = 'AND';
		$meta_query[]           = [
			'key'     => lct_org(),
			'value'   => $plugin->user_orgs,
			'compare' => 'IN'
		];
	}


	$args = [
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'pad_counts'   => false,
		'meta_query'   => $meta_query
	];
	$args = wp_parse_args( $custom_args, $args );


	return get_terms( $taxonomy, $args );
}


/**
 * filter out the default results of get_users()
 *
 * @param $plugin
 * @param $custom_args
 *
 * @return array|int|WP_Error
 */
function lct_get_users( $plugin, $custom_args ) {
	global ${$plugin};
	$plugin = ${$plugin};

	$meta_query = [ ];


	if ( ! empty( $plugin ) && ! isset( $custom_args['lct_global'] ) ) {
		$meta_query['relation'] = 'OR';

		foreach ( $plugin->user_orgs as $user_org ) {
			$meta_query[] = [
				'key'     => lct_org(),
				'value'   => serialize( strval( $user_org ) ),
				'compare' => 'LIKE'
			];
		}
	}


	$args = [
		'meta_query' => $meta_query
	];
	$args = wp_parse_args( $custom_args, $args );


	return get_users( $args );
}


/**
 * We need the keys to run a loop in ACF or something like that.
 *
 * @param $comment_ID
 *
 * @return array
 */
function lct_get_comment_meta_field_keys( $comment_ID ) {
	$keys             = [ ];
	$get_comment_meta = get_comment_meta( $comment_ID );


	if ( $get_comment_meta ) {
		foreach ( $get_comment_meta as $key => $meta ) {
			if ( strpos( $key, '_' ) === 0 )
				$keys[] = $meta[0];
		}
	}


	return $keys;
}
