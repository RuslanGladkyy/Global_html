<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}gforms_filter", 'init' ], 100000 );

class lct_gforms_filter {
	/**
	 * Get the class running
	 */
	public static function init() {
		$class = __CLASS__;
		//global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 */
	public function __construct() {
		global $g_lct;
		$this->zxzp = $g_lct;


		add_filter( 'gform_merge_tag_filter', [ $this, 'all_fields_extra_options' ], 11, 5 );

		add_filter( 'gform_enable_field_label_visibility_settings', [ $this, 'gform_enable_field_label_visibility_settings' ] );

		add_filter( 'gform_submit_button', [ $this, 'gform_submit_button' ], 10, 2 );

		add_filter( 'gform_multiselect_placeholder', [ $this, 'gform_multiselect_placeholder' ], 10, 2 );

		add_filter( 'gform_field_content', [ $this, 'form_with_columns' ], 10, 5 );

		add_filter( 'gform_pre_render', [ $this, 'submit_button_anywhere' ] );

		add_filter( 'gform_pre_render', [ $this, 'mobile_placeholder' ], 10, 5 );
	}


	public function mobile_placeholder( $gf_form ) {
		$ph_classes = [
			'mobile_placeholder',
			'mobile_ph'
		];


		foreach ( $gf_form['fields'] as $gf_entry_id => $gf_field ) {
			if ( $gf_field['cssClass'] )
				$cssClasses = explode( ' ', $gf_field['cssClass'] );
			else
				$cssClasses = [ ];

			$ph_classes_intersect = array_intersect( $ph_classes, $cssClasses );


			if (
				$gf_field['type'] == 'multiselect' &&
				$cssClasses &&
				! empty( $ph_classes_intersect )
			) {
				if ( $gf_field['label'] )
					$ph_label = $gf_field['label'];
				else
					$ph_label = 'Click to select...';

				$ph_choice = [
					[
						'text'       => $ph_label,
						'value'      => $ph_label,
						'isSelected' => true,
						'price'      => ''
					]
				];

				$gf_form['fields'][ $gf_entry_id ]['choices'] = array_merge_recursive( $ph_choice, $gf_field['choices'] );


				$gform_post_render = "<script type=\"text/javascript\">
				jQuery(document).bind( 'gform_post_render', function(){
					var width = jQuery(window).width();

					if( width >= 800 ){
						jQuery( '#input_{$gf_form['id']}_{$gf_field['id']}' ).val( '' ).trigger( 'chosen:updated' );
						jQuery( '#input_{$gf_form['id']}_{$gf_field['id']} option[value=\"{$ph_label}\"]' ).remove();
					}
				});
				</script>";

				echo $gform_post_render;
			}


		}


		return $gf_form;
	}


	/**
	 * To exclude field from notification add 'exclude[ID]' option to {all_fields} tag
	 * 'include[ID]' option includes HTML field / Section Break field description / Signature image in notification
	 * see http://www.gravityhelp.com/documentation/page/Merge_Tags for a list of standard options
	 * Credit: https://gist.github.com/richardW8k/6947682
	 * example: {all_fields:exclude[2,3]}
	 * example: {all_fields:include[6]}
	 * example: {all_fields:include[6],exclude[2,3]}
	 *
	 * @param $value
	 * @param $merge_tag
	 * @param $options
	 * @param $gf_field
	 * @param $raw_value
	 *
	 * @return bool|string
	 */
	public function all_fields_extra_options( $value, $merge_tag, $options, $gf_field, $raw_value ) {
		if ( $merge_tag != 'all_fields' )
			return $value;


		// usage: {all_fields:include[ID],exclude[ID,ID]}
		$include       = preg_match( "/include\[(.*?)\]/", $options, $include_match );
		$include_array = explode( ',', rgar( $include_match, 1 ) );

		$exclude       = preg_match( "/exclude\[(.*?)\]/", $options, $exclude_match );
		$exclude_array = explode( ',', rgar( $exclude_match, 1 ) );

		$log = "all_fields_extra_options(): {$gf_field['label']}({$gf_field['id']} - {$gf_field['type']}) - ";


		if (
			$include &&
			in_array( $gf_field['id'], $include_array )
		) {
			switch ( $gf_field['type'] ) {
				case 'html' :
					$value = $gf_field['content'];
					break;


				case 'section' :
					$value .= sprintf(
						'<tr bgcolor="#FFFFFF">
						<td width="20">&nbsp;</td>
						<td>
							<span style="font-family: sans-serif; font-size:12px;">%s</span>
						</td>
					</tr>',
						$gf_field['description']
					);
					break;


				case 'signature' :
					$url   = GFSignature::get_signature_url( $raw_value );
					$value = '<img alt="signature" src="' . $url . '"/>';
					break;
			}

			GFCommon::log_debug( $log . 'included.' );
		}

		if (
			$exclude &&
			in_array( $gf_field['id'], $exclude_array )
		) {
			GFCommon::log_debug( $log . 'excluded.' );


			return false;
		}


		return $value;
	}


	/**
	 * Enables the Gravity Forms label visibility/hidden dropdown
	 *
	 * @param $status
	 *
	 * @return bool
	 */
	public function gform_enable_field_label_visibility_settings( $status ) {
		$status = true;


		return $status;
	}


	/**
	 * Filter the Gravity Forms button class to add our own custom one if set
	 *
	 * @param $button
	 * @param $gf_form
	 *
	 * @return mixed
	 */
	public function gform_submit_button( $button, $gf_form ) {
		if (
			class_exists( 'acf' ) && //We can only process this filter is acf is also installed and running
			$custom_class = get_field( "{$this->zxzp->zxza_acf}gform_button_custom_class", lct_o() )
		) {
			$fnr = [
				"class='button "        => "class='{$custom_class} button ",
				"class='gform_button "  => "class='{$custom_class} gform_button ",
				"class=\"button "       => "class=\"{$custom_class} button ",
				"class=\"gform_button " => "class=\"{$custom_class} gform_button ",
			];
			$fnr = lct_create_find_and_replace_arrays( $fnr );

			$button = str_replace( $fnr['find'], $fnr['replace'], $button );
		}


		return $button;
	}


	/**
	 * Use the label as a placeholder for the multi-select chozen
	 *
	 * @param $placeholder
	 * @param $gf_form_id
	 *
	 * @return mixed
	 */
	public function gform_multiselect_placeholder( $placeholder, $gf_form_id ) {
		$gf_form = RGFormsModel::get_form_meta( $gf_form_id );


		foreach ( $gf_form['fields'] as $gf_field ) {
			if (
				$gf_field['type'] == 'multiselect' &&
				(
					$gf_field['labelPlacement'] == 'hidden_label' ||
					strpos( $gf_field['cssClass'], 'hide_label_if_desktop' ) !== false
				)
			)
				return $gf_field['label'];
		}


		return $placeholder;
	}


	/**
	 * Add columns to the form
	 * //TODO: cs - Need to make the <li> thing better. the function currently creates empty divs that we have to hide with CSS - 12/2/2015 12:40 PM
	 *
	 * @param $content
	 * @param $gf_field
	 * @param $value
	 * @param $lead_id
	 * @param $gf_form_id
	 *
	 * @return string
	 */
	public function form_with_columns( $content, $gf_field, $value, $lead_id, $gf_form_id ) {
		//Only modify HTML on the front end
		if ( is_admin() )
			return $content;


		$gf_form          = RGFormsModel::get_form_meta( $gf_form_id );
		$gf_form_class    = array_key_exists( 'cssClass', $gf_form ) ? $gf_form['cssClass'] : '';
		$gf_form_classes  = preg_split( '/[\n\r\t ]+/', $gf_form_class, - 1, PREG_SPLIT_NO_EMPTY );
		$gf_fields_class  = array_key_exists( 'cssClass', $gf_field ) ? $gf_field['cssClass'] : '';
		$gf_field_classes = preg_split( '/[\n\r\t ]+/', $gf_fields_class, - 1, PREG_SPLIT_NO_EMPTY );


		if ( $gf_field['type'] == 'section' ) {
			//check for the presence of multi-column form classes
			$gf_form_class_matches = array_intersect( $gf_form_classes, [ "lct_gf_2_col", 'lct_gf_3_col' ] );

			//check for the presence of section break column classes
			$gf_field_class_matches = array_intersect( $gf_field_classes, [ 'lct_gf_col' ] );

			//if field is a column break in a multi-column form, perform the list split
			if (
				! empty( $gf_form_class_matches ) &&
				! empty( $gf_field_class_matches )
			) {
				//retrieve the form's field list classes for consistency
				$ul_classes = GFCommon::get_ul_classes( $gf_form ) . ' ' . $gf_field['cssClass'];

				//close current field's li and ul and begin a new list with the same form field list classes
				return '</li></ul><ul class="' . $ul_classes . '"><li class="gfield gsection empty">';
			}
		}


		return $content;
	}


	/**
	 * Add a forms submit button anywhere you feel like it should go
	 *
	 * @param $gf_form
	 *
	 * @return mixed
	 */
	public function submit_button_anywhere( $gf_form ) {
		$shortcode = 'lct_gf_submit';


		foreach ( $gf_form['fields'] as $gf_entry_id => $gf_field ) {


			if (
				$gf_field['type'] == 'html' &&
				strpos( $gf_field['content'], $shortcode ) !== false
			) {
				$atts = [ ];
				$find = '/\[' . $shortcode . '(.*?)\]/';

				preg_match( $find, $gf_field['content'], $matches );

				if ( isset( $matches[1] ) && $matches[1] ) {
					$tmp_atts = explode( ' ', trim( $matches[1] ) );

					foreach ( $tmp_atts as $tmp_att ) {
						$tmp = explode( '=', $tmp_att );

						$atts[ strtolower( $tmp[0] ) ] = rtrim( rtrim( ltrim( ltrim( $tmp[1], '"' ), "'" ), '"' ), "'" );
					}
				}

				if ( empty( $atts['id'] ) )
					$atts['id'] = $gf_form['id'];

				if ( $gf_form['button']['type'] == 'text' )
					$atts['text'] = $gf_form['button']['text'];

				$atts_replace = $atts;
				$atts         = [ ];

				foreach ( $atts_replace as $att_k => $att ) {
					$atts[] = $att_k . "=" . "'" . $att . "'";
				}

				$atts = implode( ' ', $atts );

				$replacement = "[{$shortcode} {$atts}]";

				$content = preg_replace( $find, $replacement, $gf_field['content'] );

				$gf_form['fields'][ $gf_entry_id ]['content'] = $content;
			}


		}


		return $gf_form;
	}
}
