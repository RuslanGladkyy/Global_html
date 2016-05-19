<?php /*~~~*/
//Set this to 1 if you need to edit the fields
$dev = false;

if (
	(
		$dev == false &&
		lct_is_wpall() == false
	) ||
	$dev == 'force_local'
):

	acf_add_local_field_group( [
		'key'                   => 'group_56d5dc3c5d3c0',
		'title'                 => 'Page Settings [LCT Useful ACF]',      //TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE
		'fields'                => [
			[
				'key'               => 'field_56d5dc53d3ae0',
				'label'             => 'This is a thank you page',
				'name'              => 'lct:::is_thanks_page',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'message'           => 'This is a thank you page',
				'default_value'     => 0,
			],
			[
				'key'               => 'field_56d5dfdada774',
				'label'             => 'Add Google Analytics Event Tracking',
				'name'              => 'lct:::is_google_event_tracking',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'message'           => 'Add Google Analytics Event Tracking',
				'default_value'     => 0,
			],
			[
				'key'               => 'field_56d5e021da775',
				'label'             => 'Custom Action',
				'name'              => 'lct:::google_event_tracking_action',
				'type'              => 'text',
				'instructions'      => 'Only put something in here if you know what you are doing.',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
						[
							'field'    => 'field_56d5dfdada774',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d5e074da777',
				'label'             => 'Custom Label',
				'name'              => 'lct:::google_event_tracking_label',
				'type'              => 'text',
				'instructions'      => 'Only put something in here if you know what you are doing.',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
						[
							'field'    => 'field_56d5dfdada774',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d5dcd1d3ae2',
				'label'             => 'Has Google Conversion Code',
				'name'              => 'lct:::is_google_conversion_code',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'message'           => 'Has Google Conversion Code',
				'default_value'     => 0,
			],
			[
				'key'               => 'field_56d5dd1d379e5',
				'label'             => 'Google Conversion Code',
				'name'              => 'lct:::google_conversion_code',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 1,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
						[
							'field'    => 'field_56d5dcd1d3ae2',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d5dc8ed3ae1',
				'label'             => 'Has Bing Conversion Code',
				'name'              => 'lct:::is_bing_conversion_code',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'message'           => 'Has Bing Conversion Code',
				'default_value'     => 0,
			],
			[
				'key'               => 'field_56d5dd56379e7',
				'label'             => 'Bing Conversion Code',
				'name'              => 'lct:::bing_conversion_code',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 1,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56d5dc53d3ae0',
							'operator' => '==',
							'value'    => '1',
						],
						[
							'field'    => 'field_56d5dc8ed3ae1',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56e8aaf8d5f6b',
				'label'             => 'Store a Page Note?',
				'name'              => 'lct:::has_page_note',
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'message'           => 'Store a Page Note?',
				'default_value'     => 0,
			],
			[
				'key'               => 'field_56e8aa90d5f6a',
				'label'             => 'Page Note',
				'name'              => 'lct:::page_note',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56e8aaf8d5f6b',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => 'br',
				'readonly'          => 0,
				'disabled'          => 0,
			],
		],
		'location'              => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				],
			],
		],
		'menu_order'            => 9,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );


	acf_add_local_field_group( [
		'key'                   => 'group_56d90ec87101f',
		'title'                 => 'LCT Audit',
		'fields'                => [
			[
				'key'               => 'field_56d90eefc4ced',
				'label'             => 'Audit Type',
				'name'              => 'lct:::audit_type',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d90faec4cee',
				'label'             => 'field_key',
				'name'              => 'lct:::field_key',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d91207c4cef',
				'label'             => 'Value',
				'name'              => 'lct:::value',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_56d91223c4cf0',
				'label'             => 'Old Value',
				'name'              => 'lct:::value_old',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
		],
		'location'              => [
			[
				[
					'param'    => 'comment',
					'operator' => '==',
					'value'    => 'lct_audit',
				],
			],
		],
		'menu_order'            => 10,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );

endif;
