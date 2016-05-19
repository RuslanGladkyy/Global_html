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
		'key'                   => 'group_56db14df1d7a9',
		'title'                 => 'Review::: Site Info',      //TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE
		'fields'                => [
			[
				'key'               => 'field_56db14df2e5de',
				'label'             => 'site_info',
				'name'              => 'lct_review_site_info:::lct_fix',
				'type'              => 'oembed',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'width'             => '',
				'height'            => '',
			],
			[
				'key'               => 'field_56db14df2e7e8',
				'label'             => 'Run This',
				'name'              => 'lct_review_site_info:::run_this',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Run This Now',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_56db14df2ea0d',
				'label'             => 'Save Field Values',
				'name'              => 'lct_review_site_info:::show_params::save_field_values',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_56db14df2ebee',
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
				'choices'           => [
					1 => 'Save Field Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_56db14df2ebee',
				'label'             => 'Show Params to Non Developers',
				'name'              => 'lct_review_site_info:::show_params',
				'type'              => 'radio',
				'instructions'      => 'If you can see this field and you are NOT a developer. Do NOT change it.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 100,
					'class' => 'clear',
					'id'    => '',
				],
				'choices'           => [
					0 => 'No',
					1 => 'Yes',
				],
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => 0,
				'layout'            => 'vertical',
			],
		],
		'location'              => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_fixes_cleanups',
				],
			],
		],
		'menu_order'            => 5,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );


	acf_add_local_field_group( [
		'key'                   => 'group_55b0076c9b2bd',
		'title'                 => 'DB Fix::: Add taxonomy field data to old entries',
		//TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE
		'fields'                => [
			[
				'key'               => 'field_55b009663b857',
				'label'             => 'lct_fix',
				'name'              => 'db_fix_atfd_7637:::lct_fix',
				'type'              => 'oembed',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'width'             => '',
				'height'            => '',
			],
			[
				'key'               => 'field_55b064d3f17c8',
				'label'             => 'Run This',
				'name'              => 'db_fix_atfd_7637:::run_this',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Run This Now',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b00dbfe155a',
				'label'             => 'Taxonomy',
				'name'              => 'db_fix_atfd_7637:::taxonomy',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
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
				'key'               => 'field_55b0783accbd9',
				'label'             => 'Overwrite Existing Values',
				'name'              => 'db_fix_atfd_7637:::overwrite_value',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Overwrite Existing Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b0646530db8',
				'label'             => 'Field Key',
				'name'              => 'db_fix_atfd_7637:::f_key',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
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
				'key'               => 'field_55b0649330db9',
				'label'             => 'Field Name',
				'name'              => 'db_fix_atfd_7637:::f_name',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
					'class' => 'alignright',
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
				'key'               => 'field_55b0717b4d5c3',
				'label'             => 'Option Value',
				'name'              => 'db_fix_atfd_7637:::option_value',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
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
				'key'               => 'field_55b2725121152',
				'label'             => 'Is Array',
				'name'              => 'db_fix_atfd_7637:::is_array',
				'type'              => 'checkbox',
				'instructions'      => 'Separate each array value in Option Value with commas. Array KEY is not currently supported.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
					'class' => 'hide_label_show_desc alignright',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Is Array',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b268246d39f',
				'label'             => 'Save Field Values',
				'name'              => 'db_fix_atfd_7637:::show_params::save_field_values',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_55b268ba7ae4c',
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
				'choices'           => [
					1 => 'Save Field Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b268ba7ae4c',
				'label'             => 'Show Params to Non Developers',
				'name'              => 'db_fix_atfd_7637:::show_params',
				'type'              => 'radio',
				'instructions'      => 'If you can see this field and you are NOT a developer. Do NOT change it.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 100,
					'class' => 'clear',
					'id'    => '',
				],
				'choices'           => [
					0 => 'No',
					1 => 'Yes',
				],
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => 0,
				'layout'            => 'vertical',
			],
		],
		'location'              => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_fixes_cleanups',
				],
			],
		],
		'menu_order'            => 6,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );


	acf_add_local_field_group( [
		'key'                   => 'group_55b95d013ee9d',
		'title'                 => 'DB Fix::: Add Post Meta to Multiple Posts',      //TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE
		'fields'                => [
			[
				'key'               => 'field_55b95d01505d0',
				'label'             => 'lct_fix',
				'name'              => 'db_fix_apmmp_5545:::lct_fix',
				'type'              => 'oembed',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'width'             => '',
				'height'            => '',
			],
			[
				'key'               => 'field_55b95d01509af',
				'label'             => 'Run This',
				'name'              => 'db_fix_apmmp_5545:::run_this',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Run This Now',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b95d0150d96',
				'label'             => 'Post IDs to Add Meta Data To',
				'name'              => 'db_fix_apmmp_5545:::posts',
				'type'              => 'text',
				'instructions'      => 'Separate each post id in with commas.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
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
				'key'               => 'field_55b95d015117f',
				'label'             => 'Force Overwrite Existing Values',
				'name'              => 'db_fix_apmmp_5545:::overwrite_value',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Force Overwrite Existing Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b95d0151576',
				'label'             => 'Meta Key',
				'name'              => 'db_fix_apmmp_5545:::meta_key',
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
				'key'               => 'field_55b95d0151d37',
				'label'             => 'Meta Value',
				'name'              => 'db_fix_apmmp_5545:::meta_value',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
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
				'key'               => 'field_55b95d0152126',
				'label'             => 'Is Array',
				'name'              => 'db_fix_apmmp_5545:::is_array',
				'type'              => 'checkbox',
				'instructions'      => 'Separate each array value in Meta Value with commas. Array KEY is not currently supported.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 50,
					'class' => 'hide_label_show_desc alignright',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Is Array',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b95d0152508',
				'label'             => 'Save Field Values',
				'name'              => 'db_fix_apmmp_5545:::show_params::save_field_values',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_55b95d01528ed',
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
				'choices'           => [
					1 => 'Save Field Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55b95d01528ed',
				'label'             => 'Show Params to Non Developers',
				'name'              => 'db_fix_apmmp_5545:::show_params',
				'type'              => 'radio',
				'instructions'      => 'If you can see this field and you are NOT a developer. Do NOT change it.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 100,
					'class' => 'clear',
					'id'    => '',
				],
				'choices'           => [
					0 => 'No',
					1 => 'Yes',
				],
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => 0,
				'layout'            => 'vertical',
			],
		],
		'location'              => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_fixes_cleanups',
				],
			],
		],
		'menu_order'            => 7,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );


	acf_add_local_field_group( [
		'key'                   => 'group_55fdae0f56605',
		'title'                 => 'File Fix::: Run _editzz File Overwrite',      //TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE----TITLE
		'fields'                => [
			[
				'key'               => 'field_55fdae0f6723e',
				'label'             => 'lct_fix',
				'name'              => 'file_fix_editzz_or:::lct_fix',
				'type'              => 'oembed',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'width'             => '',
				'height'            => '',
			],
			[
				'key'               => 'field_55fdae0f675f4',
				'label'             => 'Run This',
				'name'              => 'file_fix_editzz_or:::run_this',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => 'hide_label',
					'id'    => '',
				],
				'choices'           => [
					1 => 'Run This Now',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55fdae0f691a5',
				'label'             => 'Save Field Values',
				'name'              => 'file_fix_editzz_or:::show_params::save_field_values',
				'type'              => 'checkbox',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_55fdae0f6958d',
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
				'choices'           => [
					1 => 'Save Field Values',
				],
				'default_value'     => [
				],
				'layout'            => 'vertical',
				'toggle'            => 0,
			],
			[
				'key'               => 'field_55fdae0f6958d',
				'label'             => 'Show Params to Non Developers',
				'name'              => 'file_fix_editzz_or:::show_params',
				'type'              => 'radio',
				'instructions'      => 'If you can see this field and you are NOT a developer. Do NOT change it.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => 100,
					'class' => 'clear',
					'id'    => '',
				],
				'choices'           => [
					0 => 'No',
					1 => 'Yes',
				],
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => 0,
				'layout'            => 'vertical',
			],
		],
		'location'              => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_fixes_cleanups',
				],
			],
		],
		'menu_order'            => 8,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	] );

endif;
