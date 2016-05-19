<?php /*~~~*/

class lct_acf_filter {
	/**
	 * Get the class running
	 */
	public static function init() {
		$class = __CLASS__;
		global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 */
	public function __construct() {
		global $g_lct;
		$this->zxzp = $g_lct;


		add_action( 'init', [ $this, 'autosize' ] );
		add_action( 'init', [ $this, 'wp_init' ] );
		add_action( 'init', [ $this, 'use_page_note' ] );


		//update_value
		$filter = 'acf/update_value';
		if ( function_exists( 'update_term_meta' ) )
			add_filter( $filter, [ $this, 'update_term_meta' ], 10, 3 );


		//load_value
		$filter = 'acf/load_value';
		if ( function_exists( 'update_term_meta' ) )
			add_filter( $filter, [ $this, 'load_term_meta' ], 10, 3 );


		//load_field
		$filter = 'acf/load_field';
		add_filter( "{$filter}/name={$this->zxzp->zxza_acf}hide_admin_bar__by_role", [ $this, 'wp_roles' ] );
		add_filter( "{$filter}/name={$this->zxzp->zxza_acf}gforms", [ $this, 'gforms' ] );
		add_filter( "{$filter}/name={$this->zxzp->zxza_acf}default_taxonomy", [ $this, 'all_taxonomies' ] );
		add_filter( "{$filter}/name=dyn::taxonomy", [ $this, 'all_taxonomies' ] );

		if ( ! is_admin() ) //We don't want to alter the class on the field editing page or anywhere on the back-end
			add_filter( $filter, [ $this, 'load_field_primary' ], 10, 1 );

		if ( isset( $_GET['page'] ) )
			add_filter( "{$filter}/type=radio", [ $this, 'op_show_params_check' ], 10, 1 );


		//Other
		add_action( 'acf/save_post', [ $this, 'after_save_post' ], 100001 );

		if (
			isset( $_GET['page'] ) &&
			(
				$_GET['page'] == 'acf-settings-export' ||
				$_GET['page'] == 'acf-settings-tools'
			)
		) {
			add_filter( 'acf/get_field_groups', [ $this, 'export_title_mod' ], 11 );
		}

		add_filter( 'lct_get_format_acf_value', [ $this, 'get_format_acf_value' ], 10, 3 );
		add_filter( 'lct_get_format_acf_date_picker', [ $this, 'get_format_acf_date_picker' ], 10, 2 );

		add_filter( 'manage_edit-acf-field-group_columns', [ $this, 'field_groups_columns' ], 11 );
		add_action( 'manage_acf-field-group_posts_custom_column', [ $this, 'field_groups_columns_values' ], 11 );

		add_filter( 'show_admin_bar', [ $this, 'show_admin_bar' ], 11 );

		add_filter( 'avada_blog_read_more_excerpt', [ $this, 'avada_blog_read_more_excerpt' ] );
	}


	/**
	 * hide params for people who don't know what they are.
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function op_show_params_check( $field ) {
		if ( strpos( $field['name'], "{$this->zxzp->zxzd}show_params" ) === false )
			return $field;

		$user_is_dev = lct_is_user_a_dev();


		if (
			empty( $user_is_dev ) &&
			strpos( $field['name'], "{$this->zxzp->zxzd}show_params" ) !== false &&
			$field['value'] != 1
		) {
			$field['conditional_logic'] = [
				[
					'field'    => $field['key'],
					'operator' => '==',
					'value'    => 1
				]
			];
		}


		return $field;
	}


	/**
	 * Add some custom columns to help us know where the heck the Field Groups go to.
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function field_groups_columns( $columns ) {
		$columns['rule'] = 'Group Rules & Key';


		return $columns;
	}


	/**
	 * Process the values for our custom columns
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function field_groups_columns_values( $column, $post_id = null ) {
		if ( $column == 'rule' ) {
			$group = _acf_get_field_group_by_id( $post_id );
			$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';


			if ( $group ) {
				$location_or_count = 1;
				foreach ( $group['location'] as $location_ors ) {
					$location_count = 1;

					foreach ( $location_ors as $location ) {
						echo $location['param'] . $space . $location['operator'] . $space . $location['value'];

						if ( count( $location_ors ) > $location_count )
							echo ' <strong style="font-weight: bold;">AND</strong>';

						echo ' <br />';

						$location_count ++;
					}

					if ( count( $group['location'] ) > $location_or_count )
						echo '<strong style="font-weight: bold;">OR</strong><br />';

					$location_or_count ++;
				}

				echo 'Key: <strong style="font-weight: bold;">' . $group['key'] . '</strong>';
			}
		}
	}


	/**
	 * We need to know what we are exporting. The title is just not enough info.
	 *
	 * @param $field_groups
	 *
	 * @return mixed
	 */
	public function export_title_mod( $field_groups ) {
		foreach ( $field_groups as $key => $field_group ) {
			$space          = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$title_addition = $field_group['location'][0][0]['param'] . '__' . $field_group['location'][0][0]['value'] . '___' . $field_groups[ $key ]['title'];

			$fnr = lct_create_find_and_replace_arrays(
				[
					' ' => '_',
					'-' => '_',
					'=' => '',
				]
			);

			$file_name = str_replace( $fnr['find'], $fnr['replace'], sanitize_title( $title_addition ) ) . '.json';


			$field_groups[ $key ]['title'] .= $space . '<strong>Filename(' . $space . $file_name . $space . ')</strong>';
		}


		return $field_groups;
	}


	/**
	 * Remove the front-end admin bar from selected users in the Useful Settings
	 *
	 * @return bool
	 */
	public function show_admin_bar() {
		//always show in wp-admin
		if ( is_admin() )
			return true;

		//always hide if noone is logged in
		if ( ! is_user_logged_in() )
			return false;

		//hide it if the profile says so
		if ( get_user_meta( get_current_user_id(), 'show_admin_bar_front', true ) == 'false' )
			return false;


		//get the roles that are to be hidden
		$roles_to_hide = get_field( "{$this->zxzp->zxza_acf}hide_admin_bar__by_role", lct_o() );


		//compare those roles to what this user's roles are
		if ( ! empty( $roles_to_hide ) ) {
			$current_user = wp_get_current_user();


			foreach ( $current_user->roles as $role ) {
				//if there is a match, hide the admin bar
				if ( in_array( $role, $roles_to_hide ) )
					return false;
			}
		}


		//if we made it through all that show the dang the admin bar
		return true;
	}


	/**
	 * Replace the default read more text with an ACF value
	 *
	 * @param $read_more_text
	 *
	 * @return bool|mixed|null|void
	 */
	public function avada_blog_read_more_excerpt( $read_more_text ) {
		if ( get_field( "{$this->zxzp->zxza_acf}avada::is_post_excerpt_read_more", lct_o(), true ) )
			$read_more_text = get_field( "{$this->zxzp->zxza_acf}avada::post_excerpt_read_more", lct_o(), true );


		return $read_more_text;
	}


	/**
	 * Use a raw ACF value and get it back in its formatted value
	 *
	 * @param      $value
	 * @param      $field
	 *
	 * @return bool|mixed|null|void
	 */
	public function get_format_acf_value( $value, $field ) {
		if ( $value == LCT_VALUE_EMPTY )
			return $value;


		if ( ! empty( $value ) ) {
			switch ( $field['type'] ) {
				case 'user':
					$users_display_names = [ ];

					if ( is_array( $value ) ) {
						foreach ( $value as $user ) {
							if ( is_numeric( $user ) )
								$user = (int) $user;

							if ( is_int( $user ) ) {
								$userdata              = get_userdata( $user );
								$users_display_names[] = $userdata->display_name;

								if ( ! is_numeric( $value[1] ) )
									break;
							} else {
								$users_display_names[] = $value['display_name'];
							}
						}
					} else {
						$userdata              = get_userdata( $value );
						$users_display_names[] = $userdata->display_name;
					}

					if ( count( $users_display_names ) > 1 ) {
						$value = '<ul><li>';
						$value .= implode( "</li><li>", $users_display_names );
						$value .= '</li></ul>';
					} else {
						$value = $users_display_names[0];
					}
					break;


				case 'date_picker':
					$value = apply_filters( 'lct_get_format_acf_date_picker', $value, $field );
					break;


				case 'taxonomy':
					if ( ! is_array( $value ) )
						$value = [ $value ];

					$term_names = [ ];
					foreach ( $value as $term ) {
						$term = get_term( $term, $field['taxonomy'] );

						if ( ! is_wp_error( $term ) )
							$term_names[] = $term->name;
					}

					if ( count( $term_names ) > 1 ) {
						$value = '<ul><li>';
						$value .= implode( "</li><li>", $term_names );
						$value .= '</li></ul>';
					} else {
						$value = $term_names[0];
					}
					break;


				case 'post_object':
					if ( ! is_array( $value ) )
						$value = [ $value ];

					$post_names = [ ];
					foreach ( $value as $post_obj_id ) {
						if ( $post_obj_id > 0 )
							$post_names[] = get_the_title( $post_obj_id );
						else
							$post_names[] = LCT_VALUE_EMPTY;
					}

					if ( count( $post_names ) > 1 ) {
						$value = '<ul><li>';
						$value .= implode( "</li><li>", $post_names );
						$value .= '</li></ul>';
					} else {
						$value = $post_names[0];
					}
					break;

				default:
					if ( is_array( $value ) )
						$value = lct_return( $value, ', ' );
			}
		}


		return $value;
	}


	/**
	 * ACF has a bug/lack of feature where it will not allow the return a date_picker in the display_format so we have to do their job for them.
	 *
	 * @param $value
	 * @param $field
	 *
	 * @return string
	 */
	public function get_format_acf_date_picker( $value, $field ) {
		if ( $field['type'] = 'date_picker' ) {
			$date = DateTime::createFromFormat( $field['return_format'], $value );

			if ( ! empty( $date ) )
				$value = $date->format( $field['display_format'] );
		}


		return $value;
	}


	/**
	 * Remove Conditionals
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function prepare_field_remove_conditionals( $field ) {
		$field['conditional_logic'] = 0;


		return $field;
	}


	/**
	 * Run the field classes thru current_user_can() so we can hide stuff from unauthorized people
	 * //TODO: cs - This can be improved by using global $wp_roles - 4/3/2016 2:31 AM
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function load_field_primary( $field ) {
		if (
			$field &&
			$field['wrapper']['class']
		) {
			$can_view         = 1;
			$classes_prefixes = $classes = explode( ' ', $field['wrapper']['class'] );


			//first check and see if we are filtering by role_cap
			foreach ( $classes_prefixes as $class_prefix_check_key => $class_prefix_check ) {
				if ( strpos_array( $class_prefix_check, lct_get_role_cap_prefixes() ) !== false )
					$can_view = 0;
				else
					unset( $classes_prefixes[ $class_prefix_check_key ] );
			}


			//If so, lets check it all out
			if ( ! $can_view && ! empty( $classes_prefixes ) ) {
				foreach ( $classes_prefixes as $class_key => $class ) {
					$role = str_replace( lct_get_role_cap_prefixes(), '', $class );

					$classes[ $class_key ] = $role;

					if ( current_user_can( $role ) )
						$can_view ++;
				}
			}

			if ( ! $can_view )
				$classes[] = 'hidden-imp';

			$field['wrapper']['class'] = implode( ' ', $classes );
		}


		return $field;
	}


	/**
	 * Activate jq_autosize
	 */
	public function autosize() {
		if ( ! is_admin() ) {
			//Add textarea autosize to acf
			$jq = "\n" . 'var acf_filter_autosize_selector = \'.acf-field textarea\';

			if( jQuery( acf_filter_autosize_selector ).length ) {
				var acf_filter_autosize_ta = document.querySelector( acf_filter_autosize_selector );
				acf_filter_autosize_ta.addEventListener( \'focus\', function() {
					autosize( acf_filter_autosize_ta );
				});
			}' . "\n";


			do_action( 'lct_jq_autosize' );
			do_action( 'lct_jq_doc_ready_add', $jq );
			do_action( 'lct_wp_footer_style_add', '.acf-field textarea{min-height: 50px;}' );
		}
	}


	/**
	 * Prepare for any crazy stuff we are about to do
	 */
	public function wp_init() {
		global $lct_acf_delete_values;

		$lct_acf_delete_values = [ ];
	}


	/**
	 * Cleanup after everything is done
	 */
	public function after_save_post() {
		global $lct_acf_delete_values;


		if ( ! empty( $lct_acf_delete_values ) ) {
			foreach ( $lct_acf_delete_values as $delete ) {
				acf_delete_value( $delete['post_id'], $delete['field'] );
			}
		}
	}


	/**
	 * Update term meta based on ACF term meta fields
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return mixed
	 */
	public function update_term_meta( $value, $post_id, $field ) {
		$field_group = acf_get_field_group( $field['parent'] );


		if (
			$field_group['location'][0][0]['param'] == 'taxonomy' &&
			! in_array( $post_id, [ 'option', 'options' ] ) && //NO options
			strpos( $post_id, 'user_' ) === false && //NO users
			strpos( $post_id, 'comment_' ) === false && //NO comments
			strpos( $post_id, '_' ) !== false //This should only leave taxonomies if an _ is present
		) {
			$term_id = intval( filter_var( $post_id, FILTER_SANITIZE_NUMBER_INT ) );

			if ( $term_id > 0 ) {
				if (
					$field['type'] == 'repeater' &&
					is_array( $value )
				) {
					$counter           = 0;
					$new_row_count     = count( $value );
					$current_row_count = count( get_field( $field['key'], $post_id ) );

					if ( $current_row_count != $new_row_count ) {
						update_term_meta( $term_id, $field['name'], $new_row_count );
						update_term_meta( $term_id, '_' . $field['name'], $field['key'] );

						if ( $current_row_count > $new_row_count ) {
							global $wpdb;

							//Delete extra rows
							$sql = $wpdb->prepare(
								"DELETE FROM $wpdb->termmeta WHERE ( `meta_key` LIKE \"%s\" OR `meta_key` LIKE \"%s\" ) AND `term_id` = %s",
								$field['name'] . '_%_%',
								'_' . $field['name'] . '_%_%',
								$term_id
							);
							$wpdb->query( $sql );
						}
					}


					foreach ( $value as $repeater_item ) {
						foreach ( $repeater_item as $repeater_item_key => $repeater_item_item ) {
							$repeater_field = get_field_object( $repeater_item_key, $post_id, false, false );

							$selector = $field['name'] . '_' . $counter . '_' . $repeater_field['name'];

							update_term_meta( $term_id, $selector, $repeater_item_item, [ 'change_it_always' ] );
							update_term_meta( $term_id, '_' . $selector, $repeater_item_key, [ 'change_it_always' ] );
						}

						$counter ++;
					}
				} else {
					update_term_meta( $term_id, $field['name'], $value );
					update_term_meta( $term_id, '_' . $field['name'], $field['key'] );
				}


				global $lct_acf_delete_values;
				$lct_acf_delete_values[ $field['key'] ] = [ 'post_id' => $post_id, 'field' => $field ];
			}
		}


		return $value;
	}


	/**
	 * Load term meta in for ACF meta fields
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return mixed
	 */
	public function load_term_meta( $value, $post_id, $field ) {
		$field_group = acf_get_field_group( $field['parent'] );


		if (
			(
				! empty( $field_group ) && //Has to have a $field_group set
				isset( $field_group['location'][0][0]['param'] ) && //Has to have a location set
				$field_group['location'][0][0]['param'] == 'taxonomy' //Has to be a taxonomy
			) ||
			(
				! in_array( $post_id, [ 'option', 'options' ] ) && //NO options
				strpos( $post_id, 'user_' ) === false && //NO users
				strpos( $post_id, 'comment_' ) === false && //NO comments
				! $field_group && //NO $field_group set
				strpos( $post_id, '_' ) !== false //This should only leave taxonomies if an _ is present
			)
		) {
			$term_id = intval( filter_var( $post_id, FILTER_SANITIZE_NUMBER_INT ) );

			if (
				$term_id > 0 &&
				$value == null
			) {
				$value = get_term_meta( $term_id, $field['name'], true );
			} else if ( $term_id > 0 ) {
				$update_term_meta = update_term_meta( $term_id, $field['name'], $value );
				update_term_meta( $term_id, '_' . $field['name'], $field['key'] );

				if ( ! is_wp_error( $update_term_meta ) )
					acf_delete_value( $post_id, $field );
			}
		}


		return $value;
	}


	/**
	 * Load term meta in for ACF meta fields
	 *
	 * @param $option
	 *
	 * @return mixed
	 */
	public function load_term_option_override( $option ) {
		global $lct_set_term_options;


		if ( ! empty( $lct_set_term_options ) )
			$option = get_term_meta( $lct_set_term_options['term_id'], $lct_set_term_options['field_name'], true );


		return $option;
	}


	/**
	 * Load term meta in for ACF meta fields
	 *
	 * @param $term_id
	 * @param $taxonomy
	 * @param $field_name
	 *
	 * @return mixed
	 */
	public function set_term_option_override( $term_id, $taxonomy, $field_name ) {
		global $lct_set_term_options;


		$lct_set_term_options = [
			'term_id'    => $term_id,
			'field_name' => '_' . $field_name
		];


		add_filter( 'pre_option__' . lct_t( $term_id, $taxonomy ) . '_' . $field_name, [ $this, 'load_term_option_override' ] );
	}


	/**
	 * populate an ACF field with some custom options
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function wp_roles( $field ) {
		global $wp_roles;

		$field['choices'] = [ ];


		foreach ( $wp_roles->roles as $role_key => $role ) {
			$field['choices'][ $role_key ] = $role['name'];
		}


		return $field;
	}


	/**
	 * populate an ACF field with some custom options
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function gforms( $field ) {
		if ( ! class_exists( 'GFForms' ) )
			return [ ];


		$field['choices'] = [ ];
		$gf_forms         = RGFormsModel::get_forms( null, 'title' );


		if ( ! empty( $gf_forms ) ) {
			foreach ( $gf_forms as $gf_form ) {
				$field['choices'][ $gf_form->id ] = $gf_form->title;
			}
		}


		return $field;
	}


	/**
	 * populate an ACF field with some custom options
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function all_taxonomies( $field ) {
		$field['choices'] = [ ];
		$taxonomies       = get_taxonomies();


		if ( ! empty( $taxonomies ) ) {
			ksort( $taxonomies );

			foreach ( $taxonomies as $taxonomy ) {
				$field['choices'][ $taxonomy ] = $taxonomy;
			}
		}


		return $field;
	}


	public function use_page_note() {
		if ( get_field( "{$this->zxzp->zxza_acf}use_page_note", lct_o() ) ) {
			add_filter( 'manage_edit-page_columns', [ $this, 'page_field_groups_columns' ], 1 );
			add_action( 'manage_pages_custom_column', [ $this, 'page_field_groups_columns_values' ], 1 );
		}
	}

	/**
	 * Add some custom columns to display page notes
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function page_field_groups_columns( $columns ) {
		$new_columns = [ ];


		foreach ( $columns as $column_key => $column ) {
			if ( $column_key == 'title' ) {
				$new_columns[ $column_key ] = $column;

				$new_columns["{$this->zxzp->zxza_acf}page_note"] = 'Page Notes';
			} else {
				$new_columns[ $column_key ] = $column;
			}
		}


		return $new_columns;
	}


	/**
	 * Add some custom columns to display page notes
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function page_field_groups_columns_values( $column, $post_id = null ) {
		if ( $column == "{$this->zxzp->zxza_acf}page_note" ) {
			if ( get_field( "{$this->zxzp->zxza_acf}has_page_note", $post_id ) ) {
				echo sprintf( "<span style='color: red;font-weight: bold;'>%s</span>", get_field( "{$this->zxzp->zxza_acf}page_note", $post_id ) );
			}
		}
	}
}
