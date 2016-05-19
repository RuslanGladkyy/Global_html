<?php /*~~~*/
/**
 * Get a list of ALL gravity forms
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_gravity_forms( $hide, $type, $v ) {
	$select_options = [ ];


	if ( ! class_exists( 'RGFormsModel' ) )
		return $select_options;


	$gf_forms = RGFormsModel::get_forms( null, 'title' );


	if ( empty( $gf_forms ) )
		return $select_options;


	if ( ! $hide )
		$select_options[] = lct_get_select_blank();


	foreach ( $gf_forms as $gf_form ) {
		$select_options[] = [ 'label' => $gf_form->title, 'value' => $gf_form->id ];
	}


	return $select_options;
}


/**
 * Get a list of ALL fields for a single gravity form
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_gravity_forms_form_fields( $hide, $type, $v ) {
	$select_options = [ ];


	if ( ! class_exists( 'RGFormsModel' ) )
		return $select_options;


	$gf_form = RGFormsModel::get_form_meta( $v['gform_id'] );


	if ( ! $hide )
		$select_options[] = lct_get_select_blank();


	foreach ( $gf_form['fields'] as $gf_fields ) {
		$exclude_type = [
			'section',
			'html',
		];

		if ( in_array( $gf_fields['type'], $exclude_type ) )
			continue;


		switch ( $gf_fields['type'] ) {
			case 'address':
				foreach ( $gf_fields['inputs'] as $tmp ) {
					$select_options[] = [ 'label' => $tmp['label'], 'value' => $tmp['id'] ];
				}
				break;


			case 'checkbox':
				foreach ( $gf_fields['inputs'] as $tmp ) {
					$select_options[] = [ 'label' => $gf_fields['label'] . ': ' . $tmp['label'], 'value' => $tmp['id'] ];
				}
				break;


			default:
				$select_options[] = [ 'label' => $gf_fields['label'], 'value' => $gf_fields['id'] ];
				break;
		}
	}


	return $select_options;
}


/**
 * ACF Default field name
 *
 * @return string
 */
function lct_org() {
	global $g_lct;


	return $g_lct->zxza_acf . 'org';
}


/**
 * ACF Special Function
 * Sets $post_id to options
 *
 * @return string
 */
function lct_o() {
	$return = 'options';


	return $return;
}


/**
 * ACF Special Function
 * Add comment_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 */
function lct_c( $id ) {
	if ( $id )
		$id = 'comment_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of comment_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 */
function lct_cc( $id ) {
	if ( $id )
		$id = preg_replace( '/comment_/', '', $id, 1 );


	return $id;
}


/**
 * ACF Special Function
 * Sets the $post_id to {$term->taxonomy}_{$term->term_id}
 *
 * @param $id
 * @param $taxonomy
 *
 * @return string
 */
function lct_t( $id, $taxonomy = '' ) {
	if (
		(
			$id &&
			$taxonomy
		) ||
		(
			is_object( $id ) &&
			! is_wp_error( $id )
		)
	) {
		if ( is_object( $id ) ) {
			$taxonomy = $id->taxonomy;
			$id       = $id->term_id;
		}

		$id = $taxonomy . '_' . $id;
	}


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of {$term->taxonomy}_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 */
function lct_tt( $id ) {
	if ( $id )
		$id = intval( filter_var( $id, FILTER_SANITIZE_NUMBER_INT ) );


	return $id;
}


/**
 * ACF Special Function
 * Add user_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 */
function lct_u( $id ) {
	if ( $id )
		$id = 'user_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of user_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 */
function lct_uu( $id ) {
	if ( $id )
		$id = preg_replace( '/user_/', '', $id, 1 );


	return $id;
}


/**
 * ACF Special Function
 * Add widget_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 */
function lct_w( $id ) {
	if ( $id )
		$id = 'widget_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of widget_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 */
function lct_ww( $id ) {
	if ( $id )
		$id = preg_replace( '/widget_/', '', $id, 1 );


	return $id;
}
