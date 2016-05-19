<?php /*~~~*/
/**
 * format an array so that we can easily create selects, checkboxes, etc.
 *
 * @param        $array
 * @param string $label
 * @param string $value
 * @param string $label_key
 * @param string $value_key
 *
 * @return array
 */
function lct_get_field_data_array( $array, $label = 'label', $value = 'value', $label_key = 'label', $value_key = 'value' ) {
	$sel_opts = [ ];


	foreach ( $array as $opt ) {
		if ( is_object( $opt ) ) {
			if ( is_array( $label ) ) {
				$big_label = [ ];

				foreach ( $label as $label_piece ) {
					$big_label[] = apply_filters( 'lct_field_data_array_obj_label_piece', $value, $label_piece, $opt );
				}

				$L = lct_return( $big_label, ' ' );
			} else {
				isset( $opt->$label ) ? $L = $opt->$label : $L = '';
			}

			isset( $opt->$value ) ? $V = $opt->$value : $V = '';
		} else {
			isset( $opt[ $label ] ) ? $L = $opt[ $label ] : $L = '';
			isset( $opt[ $value ] ) ? $V = $opt[ $value ] : $V = '';
		}


		$sel_opts[] = [
			$label_key => $L,
			$value_key => $V,
		];
	}


	return $sel_opts;
}


/**
 * Get field_data for a bunch of terms
 *
 * @param        $taxonomy
 * @param string $plugin
 * @param array  $custom_args
 * @param string $label_key
 * @param string $value_key
 *
 * @return array
 */
function lct_get_field_data_get_terms( $taxonomy, $plugin = 'g_lct', $custom_args = [ ], $label_key = 'label', $value_key = 'value' ) {
	$field_data = [ ];
	$terms      = lct_get_terms( $taxonomy, $plugin, $custom_args );


	if ( ! is_wp_error( $terms ) && ! empty( $terms ) )
		$field_data = lct_get_field_data_array( $terms, 'name', 'term_id', $label_key, $value_key );


	return $field_data;
}


/**
 * Get field_data for a bunch of users
 *
 * @param string $plugin
 * @param array  $custom_args
 * @param string $label
 * @param string $value
 * @param string $label_key
 * @param string $value_key
 *
 * @return array
 */
function lct_get_field_data_get_users( $plugin = 'g_lct', $custom_args = [ ], $label = 'display_name', $value = 'ID', $label_key = 'label', $value_key = 'value' ) {
	$field_data = [ ];
	$users      = lct_get_users( $plugin, $custom_args );


	if ( ! is_wp_error( $users ) && ! empty( $users ) )
		$field_data = lct_get_field_data_array( $users, $label, $value, $label_key, $value_key );


	return $field_data;
}
