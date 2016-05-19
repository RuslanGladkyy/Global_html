<?php /*~~~*/
/**
 * A better version of lct_select_options()... I hope.
 *
 * @param        $array
 * @param string $selected_value
 * @param null   $hide select 'hide' is you don't want to show a blank option
 * @param string $label
 * @param string $value
 *
 * @return array
 */
function lct_get_sel_opts( $array, $selected_value = '', $hide = null, $label = 'label', $value = 'value' ) {
	$options = [ ];


	if ( empty( $hide ) )
		$array = array_merge( [ lct_get_select_blank() ], $array );

	foreach ( lct_get_field_data_array( $array, $label, $value ) as $fe_s ) {
		$selected_value == $fe_s['value'] ? $selected = 'selected="selected"' : $selected = '';

		$options[] = sprintf( '<option value="%s" %s>%s</option>', $fe_s['value'], $selected, $fe_s['label'] );
	}


	return lct_return( $options );
}


/**
 * Get select options for a bunch of terms
 *
 * @param        $taxonomy
 * @param        $selected
 * @param null   $hide select 'hide' is you don't want to show a blank option
 * @param string $plugin
 * @param array  $custom_args
 *
 * @return array
 */
function lct_get_sel_opts_get_terms( $taxonomy, $selected, $hide = null, $plugin = 'g_lct', $custom_args = [ ] ) {
	$field_data = lct_get_field_data_get_terms( $taxonomy, $plugin, $custom_args );

	$sel_opts = lct_get_sel_opts( $field_data, $selected, $hide );


	return $sel_opts;
}


/**
 * Get select options for a bunch of users
 *
 * @param        $selected
 * @param null   $hide select 'hide' is you don't want to show a blank option
 * @param string $plugin
 * @param array  $custom_args
 * @param string $label
 *
 * @return array
 */
function lct_get_sel_opts_get_users( $selected, $hide = null, $plugin = 'g_lct', $custom_args = [ ], $label = 'display_name' ) {
	$field_data = lct_get_field_data_get_users( $plugin, $custom_args, $label );

	$sel_opts = lct_get_sel_opts( $field_data, $selected, $hide );


	return $sel_opts;
}
