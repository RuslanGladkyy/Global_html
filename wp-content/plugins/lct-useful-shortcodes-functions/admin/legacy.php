<?php /*~~~*/
add_action( 'lct_database_check', 'lct_legacy_rename_option_fields' );
/**
 * lct_useful_settings conversion to ACF
 */
function lct_legacy_rename_option_fields() {
	if ( ! class_exists( 'acf' ) ) { //We can only process this action is acf is also installed and running
		return;
	}


	global $g_lct;

	$array = [
		[
			'old' => 'Enable_Front_css',
			'new' => "{$g_lct->zxza_acf}enable_front_css",
			'key' => 'field_55ce8ec44d416',
		],
		[
			'old' => 'disable_auto_set_user_timezone',
			'new' => "{$g_lct->zxza_acf}disable_auto_set_user_timezone",
			'key' => 'field_55cea16b5cc0f',
		],
		[
			'old' => 'use_gforms_css_tweaks',
			'new' => "{$g_lct->zxza_acf}use_gforms_css_tweaks",
			'key' => 'field_55cea24748247',
		],
		[
			'old' => 'gform_button_custom_class',
			'new' => "{$g_lct->zxza_acf}gform_button_custom_class",
			'key' => 'field_55ce86bd562d8',
		],
		[
			'old' => 'disable_avada_css',
			'new' => "{$g_lct->zxza_acf}disable_avada_css",
			'key' => 'field_55ce9ee4e2e26',
		],
		[
			'old' => 'print_user_agent_in_footer',
			'new' => "{$g_lct->zxza_acf}print_user_agent_in_footer",
			'key' => 'field_56e0f5ee3e161',
		],
		[
			'old' => 'hide_og_site_name',
			'new' => "{$g_lct->zxza_acf}hide_og_site_name",
			'key' => 'field_56e1137a096f9',
		],
		[
			'old' => 'enable_cj_spam_check',
			'new' => "{$g_lct->zxza_acf}enable_cj_spam_check",
			'key' => 'field_56e1220e69af1',
		],
		[
			'old' => 'enable_cj_spam_check_email',
			'new' => "{$g_lct->zxza_acf}enable_cj_spam_check_email",
			'key' => 'field_56e1221169af2',
		],
		[
			'old' => 'store_hide_selected_gforms',
			'new' => "{$g_lct->zxza_acf}gforms_store",
			'key' => 'field_56e1250c3ef86',
		],
		[
			'old' => 'store_gforms',
			'new' => "{$g_lct->zxza_acf}gforms",
			'key' => 'field_56e125133ef87',
		],
		[
			'old' => 'choose_a_raw_tag_option',
			'new' => "{$g_lct->zxza_acf}choose_a_raw_tag_option",
			'key' => 'field_56e12ed3c33d1',
		],
		[
			'old' => 'Default_Taxonomy',
			'new' => "{$g_lct->zxza_acf}default_taxonomy",
			'key' => 'field_56e12ea4c33d0',
		],
	];


	foreach ( $array as $a ) {
		$useful_value      = lct_legacy_unset_useful_settings( $a['old'] );
		$current_acf_value = get_option( lct_o() . '_' . $a['new'] );


		if (
			$useful_value !== false ||
			$current_acf_value === false
		) {
			if ( $useful_value === false ) {
				$field = get_field_object( $a['key'], lct_o() );

				if (
					empty( $field ) ||
					! isset( $field['default_value'] )
				) {
					if ( $field['type'] == 'true_false' )
						$useful_value = 0;
					else
						$useful_value = '';
				} else if ( isset( $field['default_value'] ) ) {
					$useful_value = $field['default_value'];
				}
			}

			update_field( $a['key'], $useful_value, lct_o() );
			update_field( $a['new'], $useful_value, lct_o() );
		} else {
			$old_acf_value = get_option( lct_o() . '_lct_' . $a['old'] );

			if ( $old_acf_value !== false ) {
				update_field( $a['key'], $old_acf_value, lct_o() );
				update_field( $a['new'], $old_acf_value, lct_o() );

				acf_delete_field( $a['old'] );
				delete_option( lct_o() . '_' . $a['old'] );
				delete_option( '_' . lct_o() . '_' . $a['old'] );
			}
		}
	}


	//Leftovers
	delete_option( "{$g_lct->zxzu}useful_settings_default_set_Enable_Front_css" );
	delete_option( "{$g_lct->zxzu}useful_settings_default_set_use_gforms_css_tweaks" );
}


/**
 * conversion to ACF
 *
 * @param $a
 *
 * @return bool
 */
function lct_legacy_unset_useful_settings( $a ) {
	global $g_lct;

	$settings = get_option( "{$g_lct->zxzu}useful_settings" );


	if ( empty( $settings ) )
		delete_option( "{$g_lct->zxzu}useful_settings" );

	if ( isset( $settings[ $a ] ) ) {
		$value = $settings[ $a ];

		unset( $settings[ $a ] );

		update_option( "{$g_lct->zxzu}useful_settings", $settings );


		return $value;
	}


	return false;
}
