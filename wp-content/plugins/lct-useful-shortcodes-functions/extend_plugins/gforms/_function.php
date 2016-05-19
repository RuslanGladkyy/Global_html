<?php /*~~~*/
/**
 * Return ALL Gravity form fields adminLabel's $gf_field['id']
 *
 * @since 1.2.7
 *
 * @param array $gf_fields ALL form field's data
 * @param array $lead      Optional. If set the adminLabel's entered value is returned
 *
 * @return array {
 * @type int '{adminLabel}' = $gf_field['id'] OR $lead[] value
 * }
 */
function lct_map_adminLabel_to_field_id( $gf_fields, $lead = null ) {
	$map = [ ];


	foreach ( $gf_fields as $gf_field ) {
		$gf_field['adminLabel'] ? $k = $gf_field['adminLabel'] : $k = $gf_field['id'];

		if ( ! $gf_field['adminLabel'] )
			$gf_field['inputName'] ? $k = $gf_field['inputName'] : $k = $gf_field['id'];

		if ( $lead )
			$v = $lead[ $gf_field['id'] ];
		else
			$v = $gf_field['id'];

		$map[ $k ] = $v;
	}


	return $map;
}


function lct_map_label_to_field_id( $gf_fields, $lead = null ) {
	$map = [ ];


	foreach ( $gf_fields as $gf_field ) {
		$gf_field['label'] ? $k = sanitize_title( $gf_field['label'] ) : $k = $gf_field['id'];

		if ( $lead ) {
			if ( $gf_field['inputs'] ) {
				$tmp_v = [ ];
				foreach ( $gf_field['inputs'] as $tmp ) {
					$tmp_v[] = $lead[ $tmp['id'] ];
				}

				$v = implode( '~~~', $tmp_v );
			} else
				$v = $lead[ $gf_field['id'] ];
		} else
			$v = $gf_field['id'];

		$map[ $k ] = $v;
	}


	return $map;
}


function lct_gf_form_should_alter( $form_id ) {
	$gf = get_field( 'lct:::gforms', lct_o() );

	if ( empty( $gf ) )
		$gf = [ 0 ];


	$gf_form_should_alter = in_array( $form_id, $gf );


	return $gf_form_should_alter;
}
