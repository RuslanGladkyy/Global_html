<?php /*~~~*/
/**
 * Create the full_field_name so that we can reference it in the DB.
 *
 * @param null   $prefix
 * @param null   $field_name
 * @param string $delimiter
 *
 * @return bool|string
 */
function lct_acf_get_full_field_name( $prefix = null, $field_name = null, $delimiter = null ) {
	global $g_lct;
	$zxzp = $g_lct;


	if ( empty( $prefix ) )
		return false;

	if ( is_null( $delimiter ) )
		$delimiter = $zxzp->zxzd;


	$full_field_name = $prefix . $delimiter . $field_name;

	return $full_field_name;
}


/**
 * Unsave the values in the DB, so the fields are empty again
 *
 * @param       $fields
 * @param       $prefix
 * @param array $custom_exclude
 * @param array $custom_include
 *
 * @return bool
 */
function lct_acf_unsave_db_values( $fields, $prefix, $custom_exclude = [ ], $custom_include = [ ] ) {
	//The first is for the better True/False, but sometimes we used a checkbox in the past, so the second checks that.
	if (
		( isset( $fields['show_params::save_field_values'] ) && ! is_array( $fields['show_params::save_field_values'] ) && empty( $fields['show_params::save_field_values'] ) ) ||
		( isset( $fields['show_params::save_field_values'] ) && is_array( $fields['show_params::save_field_values'] ) && empty( $fields['show_params::save_field_values'][0] ) )
	) {
		foreach ( $fields as $field_name => $field_value ) {
			$exclude_from_clear = lct_acf_exclude_from_clear( $custom_exclude, $custom_include );

			if ( in_array( $field_name, $exclude_from_clear ) )
				continue;

			$full_field_name = lct_acf_get_full_field_name( $prefix, $field_name );

			update_field( $full_field_name, '', lct_o() );
		}


		return true;
	}


	return false;
}


/**
 * Set the fields that you don't want to have cleared out of the DB when Save Field Values is not set.
 *
 * @param array $custom_exclude
 * @param array $custom_include
 *
 * @return array
 */
function lct_acf_exclude_from_clear( $custom_exclude = [ ], $custom_include = [ ] ) {
	if ( ! empty( $custom_exclude ) ) {
		foreach ( $custom_exclude as $excluded_field ) {
			$exclude[ $excluded_field ] = 1;
		}
	}

	if ( ! empty( $custom_include ) ) {
		foreach ( $custom_include as $included_field ) {
			$exclude[ $included_field ] = 0;
		}
	}

	if ( ! isset( $exclude['show_params'] ) )
		$exclude['show_params'] = 1;

	$excluded_fields = [ ];

	foreach ( $exclude as $excluded_field => $status ) {
		if ( $status == 1 || ( $exclude['show_params'] == 1 && strpos( $excluded_field, 'show_params' ) !== false ) )
			$excluded_fields[] = $excluded_field;
	}

	return $excluded_fields;
}


/**
 * Get fields and create our $fields array
 *
 * @param        $parent
 * @param        $prefix
 * @param null   $excluded_fields
 * @param bool   $just_field_name
 * @param null   $prefix_2
 * @param string $delimiter
 *
 * @return mixed
 */
function lct_acf_get_mapped_fields( $parent, $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null ) {
	global $g_lct;
	$zxzp = $g_lct;


	$fields = [ ];

	if ( is_null( $delimiter ) )
		$delimiter = $zxzp->zxzd;


	if ( strpos( $parent, 'group_' ) === 0 || is_null( $parent ) )
		$field_names = lct_acf_get_field_names_by_object( $prefix, $excluded_fields, $just_field_name, $prefix_2, $delimiter );
	else
		$field_names = lct_acf_get_field_names_by_parent( $parent, $prefix, $excluded_fields, $just_field_name, $prefix_2, $delimiter );

	if ( empty( $field_names ) )
		return false;

	foreach ( $field_names as $field_name ) {
		if ( ! is_null( $prefix_2 ) )
			$full_field_name = lct_acf_get_full_field_name( $prefix . $delimiter . $prefix_2, $field_name, '' );
		else
			$full_field_name = lct_acf_get_full_field_name( $prefix, $field_name );

		$field_value = get_field( $full_field_name, lct_o() );

		$fields[ $field_name ] = $field_value;
	}

	//Unsave the values in the DB, so the fields are empty again
	lct_acf_unsave_db_values( $fields, $prefix );

	return $fields;
}


/**
 * Name says it all
 *
 * @param            $parent
 * @param null       $prefix
 * @param null       $excluded_fields
 * @param bool|false $just_field_name
 * @param null       $prefix_2
 * @param string     $delimiter
 *
 * @return array
 */
function lct_acf_get_field_names_by_parent( $parent, $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null ) {
	global $g_lct;
	$zxzp = $g_lct;


	$fields = [ ];

	if ( is_null( $delimiter ) )
		$delimiter = $zxzp->zxzd;


	$args          = [
		'posts_per_page' => - 1,
		'post_type'      => 'acf-field',
		'post_status'    => 'any',
		'post_parent'    => $parent
	];
	$field_objects = get_posts( $args );

	if ( ! is_wp_error( $field_objects ) ) {
		foreach ( $field_objects as $field_object ) {
			$post_excerpt = str_replace( $prefix . $delimiter, '', $field_object->post_excerpt );

			if ( ! is_null( $prefix_2 ) )
				$post_excerpt = str_replace( $prefix_2, '', $post_excerpt );

			if ( is_array( $excluded_fields ) && in_array( $post_excerpt, $excluded_fields ) )
				continue;

			if ( $just_field_name ) {
				$fields[ $field_object->menu_order ] = $post_excerpt;
			} else {
				$fields[ $field_object->post_name ] = $post_excerpt;
			}
		}
	}

	sort( $fields );

	$fields = array_filter( $fields );

	return $fields;
}


/**
 * Name says it all
 *
 * @param null       $prefix
 * @param null       $excluded_fields
 * @param bool|false $just_field_name
 * @param null       $prefix_2
 * @param string     $delimiter
 *
 * @return array
 */
function lct_acf_get_field_names_by_object( $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null ) {
	global $g_lct;
	$zxzp = $g_lct;


	$fields = [ ];

	if ( is_null( $delimiter ) )
		$delimiter = $zxzp->zxzd;


	$field_objects = get_field_objects( lct_o() );

	foreach ( $field_objects as $key => $field_object ) {
		if ( ! is_null( $prefix ) && strpos( $key, $prefix . $delimiter ) === false )
			continue;

		if ( ! is_null( $prefix_2 ) && strpos( $key, $prefix . $delimiter . $prefix_2 ) === false )
			continue;

		$post_excerpt = str_replace( $prefix . $delimiter, '', $key );

		if ( ! is_null( $prefix_2 ) )
			$post_excerpt = str_replace( $prefix_2, '', $post_excerpt );

		if ( is_array( $excluded_fields ) && in_array( $post_excerpt, $excluded_fields ) )
			continue;

		if ( $just_field_name == true ) {
			$fields[ $field_object['menu_order'] ] = $post_excerpt;
		} else {
			$fields[ $field_object['name'] ] = $post_excerpt;
		}
	}

	sort( $fields );

	$fields = array_filter( $fields );

	return $fields;
}


/**
 * Print the settings for the function
 *
 * @param $fields
 * @param $prefix
 *
 * @return string
 */
function lct_acf_recap_field_settings( $fields, $prefix ) {
	$recap = "<h2 style='color: green;font-weight: bold; margin-bottom: 0;'>Settings Recap:</h2>";


	$recap .= '<ul style="margin-top: 0;">';

	foreach ( $fields as $field_name => $field_value ) {
		$excluded_fields = [
			'show_params::save_field_values',
			'run_this'
		];

		if ( empty( $field_value ) || in_array( $field_name, $excluded_fields ) ) {
			if ( $field_name == 'run_this' )
				$recap .= "<li><span style='float: left;width: 115px;font-weight: bold;'>" . $field_name . ":</span><span style='color: green;font-weight: bold'>Just Ran {$prefix}</span></li>";

			continue;
		}

		if ( is_array( $field_value ) ) {
			$field_value = $field_value[0];

			//TODO: cs - We probably need a more dynamic check here - 7/24/2015 12:24 PM
			if ( $field_value == 1 )
				$field_value = 'Yes';
		}

		$recap .= "<li><span style='float: left;width: 115px;font-weight: bold;'>" . $field_name . ":</span> " . $field_value . "</li>";

	}

	$recap .= '</ul>';


	return $recap;
}


/**
 * Create a clean table out of the data
 *
 * @param $rows
 *
 * @return string
 */
function lct_acf_create_table( $rows ) {
	$table = '';

	if ( ! empty( $rows ) ) {
		$table .= '<table class="wp-list-table widefat fixed striped">';

		foreach ( $rows as $row_number => $row ) {
			if ( $row_number === 0 )
				$table .= '<thead>';

			if ( $row_number === 1 ) {
				$table .= '</thead>';
				$table .= '<tbody id="the-list">';
			}

			$table .= '<tr>';

			foreach ( $row as $k => $column ) {
				if ( $row_number === 0 ) {
					$table .= '<th scope="col" id="' . $k . '" class="manage-column column-customer_name" style="">' . $column . '</th>';

					continue;
				}

				$table .= '<td class="' . $k . ' column-' . $k . '">' . $column . '</td>';
			}

			$table .= '</tr>';
		}

		$table .= '</tbody>';

		$table .= '</table>';
	}

	return $table;
}


if ( ! function_exists( 'get_label' ) ) {
	/**
	 * Get the label by the field name
	 *
	 * @param            $field_name
	 * @param bool|false $post_id
	 *
	 * @return mixed
	 */
	function get_label( $field_name, $post_id = false ) {
		$field = get_field_object( $field_name, $post_id, [ 'load_value' => false ] );

		return $field['label'];
	}
}


if ( ! function_exists( 'the_label' ) ) {
	/**
	 * echo the label by the field name
	 *
	 * @param            $field_name
	 * @param bool|false $post_id
	 */
	function the_label( $field_name, $post_id = false ) {
		$field = get_field_object( $field_name, $post_id, [ 'load_value' => false ] );

		echo $field['label'];
	}
}


/**
 * Easily call a single ACF field and also have the ability to wrap it in a custom class
 *
 * @param        $field
 * @param array  $options
 * @param array  $zxza_options
 * @param bool   $return
 *
 * @return bool
 */
function lct_acf_form( $field, $options = [ ], $zxza_options = [ ], $return = false ) {
	if ( empty( $field ) )
		return false;


	global $lct_acf_filter;

	$output        = '';
	$current_field = get_field_object( $field );


	//We need this sometimes
	//TODO: cs - We need to limit this by key, but modals are not cooperating - 4/1/2016 12:01 AM
	//add_filter( "acf/prepare_field/key={$current_field['key']}", [ $lct_acf_filter, 'prepare_field_remove_conditionals' ], 1, 1 );
	add_filter( "acf/prepare_field/key=field_56fd993f72a45", [ $lct_acf_filter, 'prepare_field_remove_conditionals' ], 1, 1 );
	add_filter( "acf/prepare_field/key=field_56967be648f8b", [ $lct_acf_filter, 'prepare_field_remove_conditionals' ], 1, 1 );
	//add_filter( "acf/prepare_field", [ $lct_acf_filter, 'prepare_field_remove_conditionals' ], 1, 1 );


	if ( strpos( $current_field['wrapper']['class'], '_instant' ) !== false || ! isset( $options['submit_value'] ) )
		$options['submit_value'] = '&nbsp;';

	if ( $current_field && $current_field['wrapper']['class'] ) {
		$access = explode( ' ', $current_field['wrapper']['class'] );

		$can_access = apply_filters( 'lct_current_user_can_access', true, $access );

		if ( ! $can_access ) {
			$can_view = apply_filters( 'lct_current_user_can_view', false, $access );

			if ( $can_view ) {
				if ( $return )
					ob_start();

				echo '<div class="acf-field">
					<div class="acf-label">
						<label>' . get_label( $current_field['key'] ) . '</label>
					</div>
					<div class="acf-input">
						' . apply_filters( 'lct_get_format_acf_value', $current_field['value'], $current_field ) . '
					</div>
				</div>';

				if ( $return )
					$output = ob_get_clean();
			}

			return $output;
		}
	}

	$options_default = [
		'updated_message' => '',
		'form_attributes' => [ 'id' => 'lct_acf_form_' . sanitize_title( $current_field['key'] ) ]
	];

	$options = array_merge( $options, $options_default, [ 'fields' => [ $field ] ] );

	if ( ! isset( $zxza_options['wrapper_pre'] ) )
		$zxza_options['wrapper_pre'] = '<div class="%s" id="%s">';

	if ( ! isset( $zxza_options['wrapper_post'] ) )
		$zxza_options['wrapper_post'] = '</div>';

	if ( $return )
		ob_start();

	echo sprintf( $zxza_options['wrapper_pre'], $zxza_options['wrapper_class'], $zxza_options['wrapper_id'] );

	acf_form( $options );

	echo $zxza_options['wrapper_post'];

	if ( $return )
		$output = ob_get_clean();

	return $output;
}


/**
 * Easily call a FULL ACF form and also have the ability to wrap it in a custom class
 *
 * @param array $options
 * @param array $zxza_options
 * @param bool  $return
 *
 * @return bool
 */
function lct_acf_form_full( $options = [ ], $zxza_options = [ ], $return = false ) {
	if ( isset( $zxza_options['access'] ) ) {
		$can_access = apply_filters( 'lct_current_user_can_access', true, $zxza_options['access'] );

		if ( ! $can_access )
			return false;
	}

	$options_default = [
		'updated_message' => '',
		'form_attributes' => [ 'id' => 'lct_acf_form_' . sanitize_title( wp_generate_password( 30, false ) ) ]
	];

	if ( $options['new_post'] ) {
		$options_default['post_title']   = false;
		$options_default['post_content'] = false;

		$options['return'] = get_post_type_archive_link( $options['new_post']['post_type'] );
	}

	$options = array_merge( $options, $options_default );

	if ( $options['post_id'] == 'new_post' )
		do_action( 'lct_acf_new_post' );

	if ( ! isset( $zxza_options['wrapper_pre'] ) )
		$zxza_options['wrapper_pre'] = '<div class="%s" id="%s">';

	if ( ! isset( $zxza_options['wrapper_post'] ) )
		$zxza_options['wrapper_post'] = '</div>';

	$output = '';

	if ( $return )
		ob_start();

	echo sprintf( $zxza_options['wrapper_pre'], $zxza_options['wrapper_class'], $zxza_options['wrapper_id'] );

	do_action( 'lct/acf/before_lct_acf_form_full', $zxza_options, $options );

	acf_form( $options );

	echo $zxza_options['wrapper_post'];

	if ( $return )
		$output = ob_get_clean();

	return $output;
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_following() {
	global $g_lct;


	return $g_lct->zxza_acf . 'following';
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_following_parent() {
	global $g_lct;


	return $g_lct->zxza_acf . 'following_parent';
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_status() {
	global $g_lct;


	return $g_lct->zxza_acf . 'status';
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_tax_status() {
	global $g_lct;


	return $g_lct->zxza_acf . 'tax_status';
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_tax_public() {
	global $g_lct;


	return $g_lct->zxza_acf . 'tax_public';
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_tax_published() {
	global $g_lct;


	return $g_lct->zxza_acf . 'tax_published';
}


/**
 * Best way to solve an update issue with ACF for now.
 *
 * @param $name
 * @param $taxonomy
 *
 * @return mixed
 */
function lct_acf_get_key_taxonomy( $name, $taxonomy ) {
	$groups = acf_get_field_groups( [ 'taxonomy' => $taxonomy ] );

	if ( ! empty( $groups ) ) {
		foreach ( $groups as $group ) {
			if ( isset( $group['local'] ) )
				$field_objects = acf_get_local_fields( $group['key'] );
			else
				$field_objects = acf_get_fields_by_id( $group['ID'] );

			if ( $field_objects ) {
				foreach ( $field_objects as $field_object ) {
					if ( isset( $field_object['name'] ) && $field_object['name'] == $name ) {
						return $field_object['key'];
					}
				}
			}
		}
	}


	return $name;
}


/**
 * Best way to solve an update issue with ACF for now.
 *
 * @param        $name
 * @param string $user_id
 *
 * @return mixed
 */
function lct_acf_get_key_user( $name, $user_id = '' ) {
	if ( $user_id ) {
		$groups = acf_get_field_groups( [ 'user_id' => $user_id ] );

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				if ( isset( $group['local'] ) )
					$field_objects = acf_get_local_fields( $group['key'] );
				else
					$field_objects = acf_get_fields_by_id( $group['ID'] );

				if ( $field_objects ) {
					foreach ( $field_objects as $field_object ) {
						if ( isset( $field_object['name'] ) && $field_object['name'] == $name ) {
							return $field_object['key'];
						}
					}
				}
			}
		}
	} else {
		$groups = acf_get_field_groups();

		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				foreach ( $group['location'] as $location_ors ) {
					foreach ( $location_ors as $location ) {
						if ( $location['param'] == 'user_role' ) {
							if ( isset( $group['local'] ) )
								$field_objects = acf_get_local_fields( $group['key'] );
							else
								$field_objects = acf_get_fields_by_id( $group['ID'] );

							if ( $field_objects ) {
								foreach ( $field_objects as $field_object ) {
									if ( isset( $field_object['name'] ) && $field_object['name'] == $name ) {
										return $field_object['key'];
									}
								}
							}
						}
					}
				}
			}
		}
	}


	return $name;
}
