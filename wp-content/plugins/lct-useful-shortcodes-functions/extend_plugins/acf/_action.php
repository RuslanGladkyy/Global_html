<?php /*~~~*/

class lct_acf_action {
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


		add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 );

		add_filter( 'insert_user_meta', [ $this, 'insert_user_meta' ], 10, 3 );

		add_action( 'created_term', [ $this, 'created_term' ], 10, 3 );

		add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ] );

		add_action( 'wp_print_scripts', [ $this, 'wp_print_scripts' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'always_load_google_fonts' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'always_load_typekit' ] );

		add_action( 'lct_acf_single_load_google_fonts', [ $this, 'single_load_google_fonts' ], 10, 1 );

		add_action( 'lct_acf_single_load_typekit', [ $this, 'single_load_typekit' ], 10, 1 );
	}


	/**
	 * Prints required acf scripts
	 */
	public function wp_print_scripts() {
		wp_enqueue_style( "{$this->zxzp->zxzu}acf", $this->zxzp->plugin_dir_url . 'assets/css/extend_plugins/acf.min.css', null );

		//Hide instant_save update buttons
		do_action( 'lct_jq_doc_ready_add', "jQuery('.{$this->zxzp->zxzu}instant .acf-form-submit').hide();", 'wp_head' );
	}


	/**
	 * Prints required acf scripts
	 */
	public function admin_print_scripts() {
		wp_enqueue_style( "{$this->zxzp->zxzu}acf_admin", $this->zxzp->plugin_dir_url . 'assets/wp-admin/css/extend_plugins/acf.min.css', null );
	}


	/**
	 * ADD Google Font stylesheets
	 */
	public function always_load_google_fonts() {
		if ( have_rows( "{$this->zxzp->zxza_acf}load_google_fonts", lct_o() ) ) {
			$row = 1;

			while( have_rows( "{$this->zxzp->zxza_acf}load_google_fonts", lct_o() ) ) {
				the_row();

				$name        = get_sub_field( 'name' );
				$additional  = get_sub_field( 'additional' );
				$always_load = get_sub_field( 'always_load' );

				wp_register_style( 'lct_gfont-' . $row, '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $name ) . $additional, [ ], null );

				if ( $always_load )
					wp_enqueue_style( 'lct_gfont-' . $row );

				$row ++;
			}
		}
	}


	/**
	 * ADD a single Google Font stylesheet
	 *
	 * @param $font_id
	 */
	public function single_load_google_fonts( $font_id ) {
		wp_print_styles( 'lct_gfont-' . $font_id );
	}


	/**
	 * ADD Adobe Typekit script
	 */
	public function always_load_typekit() {
		if ( have_rows( "{$this->zxzp->zxza_acf}load_typekit", lct_o() ) ) {
			$row = 1;

			while( have_rows( "{$this->zxzp->zxza_acf}load_typekit", lct_o() ) ) {
				the_row();

				$name        = get_sub_field( 'name' );
				$always_load = get_sub_field( 'always_load' );

				wp_register_script( 'lct_typekit-' . $row, 'https://use.typekit.net/' . $name . '.js', [ ], null );

				if ( $always_load )
					wp_enqueue_script( 'lct_typekit-' . $row );

				$row ++;
			}

			wp_add_inline_script( 'lct_typekit-' . $row, 'try{Typekit.load({ async: true });}catch(e){}' );
		}
	}


	/**
	 * ADD a single Adobe Typekit script
	 *
	 * @param $font_id
	 */
	public function single_load_typekit( $font_id ) {
		wp_print_scripts( 'lct_typekit-' . $font_id );
		wp_add_inline_script( 'lct_typekit-' . $font_id, 'try{Typekit.load({ async: true });}catch(e){}' );
	}


	/**
	 * We need to initialize the ACF fields when we add a new taxonomy inside code
	 *
	 * @param $term_id
	 * @param $tt_id
	 * @param $taxonomy
	 */
	public function created_term( $term_id, $tt_id, $taxonomy ) {
		global $lct_acf_filter;


		//The normal one gets fired too soon
		add_action( 'shutdown', [ $lct_acf_filter, 'after_save_post' ], 1 );


		$groups = acf_get_field_groups( [ 'taxonomy' => $taxonomy ] );


		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				if ( isset( $group['local'] ) )
					$group_fields = acf_get_local_fields( $group['key'] );
				else
					$group_fields = acf_get_fields_by_id( $group['ID'] );

				if ( ! empty( $group_fields ) ) {
					foreach ( $group_fields as $field ) {
						$current_key = get_term_meta( $term_id, '_' . $field['name'], true );

						if ( ! $current_key )
							update_field( $field['key'], '', lct_t( $term_id, $taxonomy ) );
					}
				}
			}
		}
	}


	/**
	 * We need to initialize the ACF fields when we add a new user inside code
	 *
	 * @param $meta
	 * @param $user
	 * @param $update
	 *
	 * @return mixed
	 */
	public function insert_user_meta( $meta, $user, $update ) {
		if ( $update == false ) {
			$groups = acf_get_field_groups();

			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group ) {
					foreach ( $group['location'] as $location_ors ) {
						foreach ( $location_ors as $location ) {
							if ( $location['param'] == 'user_role' ) {
								if ( isset( $group['local'] ) )
									$group_fields = acf_get_local_fields( $group['key'] );
								else
									$group_fields = acf_get_fields_by_id( $group['ID'] );

								if ( ! empty( $group_fields ) ) {
									foreach ( $group_fields as $field ) {
										$current_key = get_user_meta( $user->ID, '_' . $field['name'], true );

										if ( ! $current_key )
											update_field( $field['key'], '', lct_u( $user->ID ) );
									}
								}
							}
						}
					}
				}
			}


			remove_filter( 'insert_user_meta', [ $this, 'insert_user_meta' ], 10 );
		}


		return $meta;
	}


	/**
	 * We need to initialize the ACF fields when we add a new post inside code
	 *
	 * @param $data
	 * @param $postarr
	 *
	 * @return mixed
	 */
	public function insert_post_data( $data, $postarr ) {
		if (
			(
				isset( $_POST['action'] ) &&
				$_POST['action'] == 'heartbeat'
			) ||
			empty( $postarr['ID'] )
		) {
			return $data;
		}


		if ( lct_is_new_save_post( $postarr['ID'] ) ) {
			$groups = acf_get_field_groups( [ 'post_id' => $postarr['ID'] ] );

			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group ) {
					if ( isset( $group['local'] ) )
						$group_fields = acf_get_local_fields( $group['key'] );
					else
						$group_fields = acf_get_fields_by_id( $group['ID'] );

					if ( ! empty( $group_fields ) ) {
						foreach ( $group_fields as $field ) {
							$current_key = get_post_meta( $postarr['ID'], '_' . $field['name'], true );

							if ( ! $current_key )
								update_field( $field['key'], '', $postarr['ID'] );
						}
					}
				}
			}


			remove_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10 );
		}


		return $data;
	}
}
