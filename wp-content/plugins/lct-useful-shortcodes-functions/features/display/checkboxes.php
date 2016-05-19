<?php /*~~~*/
/**
 * A better version of lct_select_options()... I hope.
 *
 * @param        $array
 * @param array  $selected_array
 * @param null   $hide select 'hide' is you don't want to show a blank option
 * @param array  $checkbox_details
 * @param string $label
 * @param string $value
 *
 * @return array
 */
function lct_get_checkboxes( $array, $selected_array = [ ], $hide = null, $checkbox_details = [ 'name' => 'no_name' ], $label = 'label', $value = 'value' ) {
	$checkboxes = [ ];
	$i          = 0;
	$class      = '';


	if ( isset( $checkbox_details['class'] ) )
		$class = "class='{$checkbox_details['class']}'";


	if ( empty( $hide ) ) {
		if ( isset( $checkbox_details['hide_label'] ) )
			$array = array_merge( [ lct_get_select_blank( null, $checkbox_details['hide_label'], $checkbox_details['hide_value'] ) ], $array );
		else
			$array = array_merge( [ lct_get_select_blank() ], $array );
	}


	foreach ( lct_get_field_data_array( $array, $label, $value ) as $fe_s ) {
		( ! empty( $selected_array ) && in_array( $fe_s['value'], $selected_array ) ) ? $selected = 'checked="checked"' : $selected = '';

		$name_num = $checkbox_details['name'] . '_' . $i;

		$checkboxes[] = sprintf(
			'<input type="checkbox" name="%s[]" id="%s" value="%s" %s %s><label for="%s">%s</label>',
			$checkbox_details['name'],
			$name_num,
			$fe_s['value'],
			$class,
			$selected,
			$name_num,
			$fe_s['label']
		);

		$i ++;
	}


	return lct_return( $checkboxes, '<br />' );
}


/**
 * Get select options for a bunch of terms
 *
 * @param        $taxonomy
 * @param        $selected
 * @param null   $hide select 'hide' is you don't want to show a blank option
 * @param string $plugin
 * @param array  $checkbox_details
 * @param array  $custom_args
 *
 * @return array
 */
function lct_get_checkboxes_get_terms( $taxonomy, $selected, $hide = null, $plugin = 'g_lct', $checkbox_details = [ 'name' => 'no_name' ], $custom_args = [ ] ) {
	$field_data = lct_get_field_data_get_terms( $taxonomy, $plugin, $custom_args );

	$sel_opts = lct_get_checkboxes( $field_data, $selected, $hide, $checkbox_details );


	return $sel_opts;
}
