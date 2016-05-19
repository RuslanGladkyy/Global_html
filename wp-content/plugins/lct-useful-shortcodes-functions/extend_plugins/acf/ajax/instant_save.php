<?php /*~~~*/

class lct_acf_instant_save {
	public $commentmeta;


	/**
	 * Get the class running
	 * //TODO: cs - Find a way to check if this is needed...right now it loads on every page. - 12/7/2015 3:44 PM
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

		$this->commentmeta             = new stdClass();
		$this->commentmeta->audit_type = $this->zxzp->zxza_acf . 'audit_type';
		$this->commentmeta->field_key  = $this->zxzp->zxza_acf . 'field_key';
		$this->commentmeta->value      = $this->zxzp->zxza_acf . 'value';
		$this->commentmeta->value_old  = $this->zxzp->zxza_acf . 'value_old';


		add_action( 'wp', [ $this, 'prepare_scripts' ] );
		add_action( "wp_ajax_{$this->zxzp->zxzu}acf_instant_save", [ $this, 'ajax_handler' ] );
		add_action( "wp_ajax_nopriv_{$this->zxzp->zxzu}acf_instant_save", [ $this, 'ajax_handler' ] );

		//We need to run non-ajax acf field updates through our audit log
		//don't do this if it is through Ajax
		if ( ! $this->zxzp->doing_ajax )
			add_filter( 'acf/update_value', [ $this, 'non_ajax_update_value' ], 10, 3 );
	}


	/**
	 * Get the script registered in WP
	 */
	public function prepare_scripts() {
		wp_enqueue_script( "{$this->zxzp->zxzu}acf_instant_save", $this->zxzp->plugin_dir_url . 'assets/js/instant_save.min.js', [ 'jquery' ] );
		wp_localize_script(
			"{$this->zxzp->zxzu}acf_instant_save",
			"{$this->zxzp->zxzu}acf_instant_save",
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( "{$this->zxzp->zxzu}acf_instant_save_nonce" ),
			]
		);
	}


	/**
	 * Do some stuff ajax style
	 */
	public function ajax_handler() {
		if ( ! wp_verify_nonce( $_POST['nonce'], "{$this->zxzp->zxzu}acf_instant_save_nonce" ) ) {
			echo json_encode( [ 'status' => 'Nonce Failed' ] );
			exit;
		}


		//We do not want to continue if there is not a post_id set
		if ( empty( $_POST['post_id'] ) ) {
			echo json_encode( [ 'status' => 'post_id Not Set' ] );
			exit;
		}


		$zxza_vars = $_POST;


		switch ( $zxza_vars['task'] ) {
			case 'update':
				$updated                                    = 'no';
				$zxza_vars[ $this->commentmeta->field_key ] = str_replace( [ 'acf-', '-input' ], '', $zxza_vars[ $this->commentmeta->field_key ] );
				$field                                      = get_field_object( $zxza_vars[ $this->commentmeta->field_key ], $zxza_vars['post_id'] );

				$field_types_that_dont_pass_value_old = [ 'select', 'multi_select' ];
				$types_that_dont_pass_value_old       = [ 'post_object', 'user' ];

				if (
					( ! isset( $zxza_vars[ $this->commentmeta->value_old ] ) && in_array( $field['field_type'], $field_types_that_dont_pass_value_old ) ) ||
					( ! isset( $zxza_vars[ $this->commentmeta->value_old ] ) && in_array( $field['type'], $types_that_dont_pass_value_old ) )
				) {
					$zxza_vars[ $this->commentmeta->value_old ] = get_field( $field['key'], $zxza_vars['post_id'], false );
				}

				if ( $zxza_vars[ $this->commentmeta->value ] == $zxza_vars[ $this->commentmeta->value_old ] )
					$updated = 'dont_update';

				if ( empty( $field ) )
					$updated = 'dont_update';

				if ( $field['field_type'] == 'multi_select' || in_array( $field['type'], $types_that_dont_pass_value_old ) )
					$zxza_vars[ $this->commentmeta->value ] = explode( '||', $zxza_vars[ $this->commentmeta->value ] );

				$output_to_js          = [ ];
				$output_to_js['label'] = $field['label'];

				if ( $updated != 'dont_update' ) {
					switch ( $field['type'] ) {
						case 'date_picker':
							$date = DateTime::createFromFormat( $field['display_format'], $zxza_vars[ $this->commentmeta->value ] );

							$zxza_vars[ $this->commentmeta->value ] = $date->format( $field['return_format'] );
							break;


						default:
					}

					$new_value_successful = update_field( $zxza_vars[ $this->commentmeta->field_key ], $zxza_vars[ $this->commentmeta->value ], $zxza_vars['post_id'] );

					if ( $new_value_successful )
						$updated = 'yes';
				}


				switch ( $updated ) {
					case 'yes':
						$zxza_vars['status']                         = 'Updated';
						$zxza_vars[ $this->commentmeta->audit_type ] = 'acf_update_field';

						$output_to_js['status'] = $zxza_vars['status'];

						$this->add_comment( $zxza_vars );
						break;


					case 'dont_update':
						$output_to_js['status'] = 'Nothing Changed';
						break;


					default:
						$output_to_js['status'] = 'Something Went Wrong';
				}
				break;


			default:
				$output_to_js['status'] = 'Nothing Happened';
		}


		echo json_encode( $output_to_js );
		exit;
	}


	/**
	 * Add an audit comment
	 *
	 * @param $zxza_vars
	 *
	 * @return bool|false|int
	 */
	public function add_comment( $zxza_vars ) {
		if (
			! isset( $zxza_vars['post_id'] ) ||
			$zxza_vars['post_id'] == 0 ||
			$zxza_vars['post_id'] == '' ||
			lct_is_new_save_post( $zxza_vars['post_id'] ) ||
			! $zxza_vars["{$this->zxzp->zxza_acf}field_key"] ||
			(
				isset( $zxza_vars['post_id'] ) &&
				(
					$zxza_vars['post_id'] == lct_o() ||
					empty( $zxza_vars['post_id'] ) ||
					$zxza_vars['post_id'] == 'option'
				)
			)
		) {
			return false;
		}


		$current_user    = wp_get_current_user();
		$comment_content = '';
		$lct_audit_group = acf_get_local_fields( 'group_56d90ec87101f' );


		if ( $audit_exclude = get_field( "{$this->zxzp->zxza_acf}{$this->zxzp->zxzu}audit_exclude", lct_o() ) ) {
			$audit_exclude = explode( "\r\n", $audit_exclude );

			$current_field = get_field_object( $zxza_vars[ $this->commentmeta->field_key ], $zxza_vars['post_id'], false, false );

			if ( in_array( $current_field['name'], $audit_exclude ) )
				return false;
		}

		if ( ! $zxza_vars[ $this->commentmeta->value ] )
			$zxza_vars[ $this->commentmeta->value ] = LCT_VALUE_EMPTY;

		if ( ! $zxza_vars[ $this->commentmeta->value_old ] )
			$zxza_vars[ $this->commentmeta->value_old ] = LCT_VALUE_EMPTY;

		if ( $zxza_vars[ $this->commentmeta->audit_type ] == 'acf_update_field' ) {
			$audit_settings  = lct_get_comment_type_lct_audit_settings();
			$comment_content = $audit_settings['audit_types']['acf_update_field']['text'];
		}

		$new_comment = [
			'comment_post_ID'      => $zxza_vars['post_id'],
			'user_id'              => $current_user->ID,
			'comment_author'       => $current_user->display_name,
			'comment_author_email' => $current_user->user_email,
			'comment_content'      => $comment_content,
			'comment_approved'     => 1,
			'comment_type'         => "{$this->zxzp->zxzu}audit",
		];
		$comment_id  = wp_insert_comment( $new_comment );

		if ( ! empty( $lct_audit_group ) && ! empty( $this->commentmeta ) ) {
			foreach ( $this->commentmeta as $commentmeta_item ) {
				foreach ( $lct_audit_group as $field_array_key => $field ) {
					if ( $field['name'] == $commentmeta_item ) {
						update_field( $field['key'], $zxza_vars[ $commentmeta_item ], lct_c( $comment_id ) );
						unset( $lct_audit_group[ $field_array_key ] );

						break;
					}
				}
			}
		}


		return $comment_id;
	}


	/**
	 * Format an array
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public function array_process( $value ) {
		if ( is_array( $value ) ) {
			$tmp = '';

			foreach ( $value as $k => $v ) {
				$tmp .= "{$k} => {$v}<br />";
			}

			$value = rtrim( $tmp, "<br />" );
		}


		return $value;
	}


	/**
	 * Update a value directly.
	 * We need to run non-ajax acf field updates through our audit log
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return mixed
	 */
	public function non_ajax_update_value( $value, $post_id, $field ) {
		//don't do this if it is a comment field update or a repeater parent
		if (
			strpos( $post_id, 'comment' ) !== false ||
			$field['type'] == 'repeater'
		) {
			return $value;
		}


		$should_add_comment                          = false;
		$zxza_vars                                   = [ ];
		$zxza_vars['post_id']                        = $post_id;
		$zxza_vars[ $this->commentmeta->field_key ]  = $field['key'];
		$zxza_vars[ $this->commentmeta->value ]      = $value;
		$zxza_vars[ $this->commentmeta->audit_type ] = 'acf_update_field';


		if ( empty( $zxza_vars[ $this->commentmeta->value_old ] ) ) {
			if ( ! empty( $field['parent'] ) ) {
				$field_parent = _acf_get_field_by_id( $field['parent'] );

				if ( ! empty( $field_parent ) && $field_parent['type'] == 'repeater' )
					$zxza_vars[ $this->commentmeta->value_old ] = get_field( $field['name'], $zxza_vars['post_id'], false );
				else
					$zxza_vars[ $this->commentmeta->value_old ] = get_field( $field['key'], $zxza_vars['post_id'], false );
			}
		}


		if ( is_array( $zxza_vars[ $this->commentmeta->value_old ] ) ) {
			if ( ! is_array( $zxza_vars[ $this->commentmeta->value ] ) )
				$zxza_vars[ $this->commentmeta->value ] = [ $zxza_vars[ $this->commentmeta->value ] ];

			$should_add_comment = array_diff( $zxza_vars[ $this->commentmeta->value_old ], $zxza_vars[ $this->commentmeta->value ] );
		} else {
			if ( $zxza_vars[ $this->commentmeta->value_old ] != $zxza_vars[ $this->commentmeta->value ] )
				$should_add_comment = true;
		}

		if ( ! empty( $should_add_comment ) )
			$this->add_comment( $zxza_vars );


		return $value;
	}
}
