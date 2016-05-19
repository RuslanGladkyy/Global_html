<?php /*~~~*/

class lct_acf_op_main_fixes_cleanups {
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

		$this->zxzu_acf = "{$this->zxzp->zxzu}acf_";


		add_action( 'admin_menu', [ $this, 'old_useful_menu' ] );

		if (
			isset( $_GET['page'] ) &&
			$_GET['page'] == "{$this->zxzp->zxzu}acf_op_main_fixes_cleanups"
		) {
			add_filter( 'acf/load_field', [ $this, 'fixes_cleanups' ] );
		}
	}


	/**
	 * Register the old_useful_menu menus
	 */
	public function old_useful_menu() {
		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Cleanup Guid Fields', 'Cleanup Guid Fields',
			'manage_options',
			"{$this->zxzp->zxzu}cleanup_guid", "{$this->zxzp->zxzu}cleanup_guid"
		);

		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Close all pings and comments', 'Close all pings and comments',
			'manage_options',
			"{$this->zxzp->zxzu}close_all_pings_and_comments", "{$this->zxzp->zxzu}close_all_pings_and_comments"
		);

		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Cleanup Uploads Folder', 'Cleanup Uploads Folder',
			'manage_options',
			"{$this->zxzp->zxzu}cleanup_uploads", "{$this->zxzp->zxzu}cleanup_uploads"
		);

		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Repair ACF User Meta Data', 'Repair ACF User Meta Data',
			'manage_options',
			"{$this->zxzp->zxzu}repair_acf_usermeta", "{$this->zxzp->zxzu}repair_acf_usermeta"
		);

		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Repair ACF Post Meta Data', 'Repair ACF Post Meta Data',
			'manage_options',
			"{$this->zxzp->zxzu}repair_acf_postmeta", "{$this->zxzp->zxzu}repair_acf_postmeta"
		);

		add_submenu_page(
			"{$this->zxzu_acf}op_main",
			'Repair ACF Term Meta Data', 'Repair ACF Term Meta Data',
			'manage_options',
			"{$this->zxzp->zxzu}repair_acf_termmeta", "{$this->zxzp->zxzu}repair_acf_termmeta"
		);
	}


	/**
	 * populate the fixes_and_cleanups stuff
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function fixes_cleanups( $field ) {
		if (
			$field['type'] == 'oembed' &&
			strpos( $field['name'], ":::{$this->zxzp->zxzu}fix" ) !== false
		) {
			$fixes_and_cleanup = str_replace( ":::{$this->zxzp->zxzu}fix", '', $field['name'] );

			unset( $field['width'] );
			unset( $field['height'] );

			$field['type']     = 'message';
			$field['message']  = lct_get_fixes_cleanups_message( $fixes_and_cleanup, $field['parent'] );
			$field['esc_html'] = 0;
		}


		return $field;
	}
}


/**
 * Routes you to the proper fixes_and_cleanups_message
 *
 * @param null $prefix
 * @param null $parent
 *
 * @return mixed
 */
function lct_get_fixes_cleanups_message( $prefix = null, $parent = null ) {
	$message = call_user_func( "lct_get_fixes_cleanups_message___{$prefix}", $prefix, $parent );


	return $message;
}


/**
 * DB Fix::: Add taxonomy field data to old entries
 * Adds ACF taxonomy meta to newly created fields for existing groups
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 */
function lct_get_fixes_cleanups_message___db_fix_atfd_7637( $prefix, $parent ) {
	$message = '';

	$excluded_fields = [
		'show_params',
		'lct_fix'
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! isset( $fields['run_this'][0] ) ) {
		$message = "<h1 style='color: green;font-weight: bold'>Select some options below to run this Fix/Cleanup.</h1>";


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	$tax_args = [
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'fields'       => 'ids'
	];
	$term_ids = get_terms( $fields['taxonomy'], $tax_args );


	if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {
		$option_value = '';

		if ( $fields['is_array'][0] ) {
			$option_value = explode( ",", $fields['option_value'] );
		}

		$message .= '<h2>Updated Terms</h2>';

		$message .= '<ul>';

		foreach ( $term_ids as $k => $term_id ) {
			$option_name = implode( '_', [ $fields['taxonomy'], $term_id, $fields['f_name'] ] );

			if ( ! $fields['overwrite_value'][0] ) {
				$current_option = get_option( '_' . $option_name );

				if ( ! empty( $current_option ) && $current_option == $fields['f_key'] )
					continue;
			}

			$message .= "<li><span style='font-weight: bold;'>Term ID " . $term_id . ":</span> " . $fields['option_value'] . "</li>";

			update_option( $option_name, $option_value );
			update_option( '_' . $option_name, $fields['f_key'] );
		}

		$message .= '</ul>';
	} else {
		$message = "<h1 style='color: red;font-weight: bold'>Invalid Taxonomy</h1>";


		return $message;
	}


	//Done with the fix


	$message .= lct_acf_recap_field_settings( $fields, $prefix );


	return $message;
}


/**
 * DB Fix::: Add Post Meta to Multiple Posts
 * Adds/Updates your desired post meta key and value to your noted array of posts
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 */
function lct_get_fixes_cleanups_message___db_fix_apmmp_5545( $prefix, $parent ) {
	$message = '';

	$excluded_fields = [
		'show_params',
		'lct_fix'
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! isset( $fields['run_this'][0] ) ) {
		$message = "<h1 style='color: green;font-weight: bold'>Select some options below to run this Fix/Cleanup.</h1>";


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	if ( ! empty( $fields['posts'] ) ) {
		$posts = explode( ',', $fields['posts'] );

		if ( $fields['is_array'][0] )
			$meta_value = explode( ",", $fields['meta_value'] );
		else
			$meta_value = $fields['meta_value'];

		$message .= '<h2>Updated Post IDs</h2>';

		$message .= '<ul>';


		foreach ( $posts as $post_id ) {
			if ( ! is_numeric( $post_id ) )
				continue;

			if ( ! $fields['overwrite_value'][0] ) {
				$current_value = get_field( $fields['meta_key'], $post_id );

				if ( ! empty( $current_value ) )
					continue;
			}

			$message .= "<li><span style='font-weight: bold;'>Post ID " . $post_id . ":</span> " . get_the_title( $post_id ) . "</li>";

			//TODO: cs - This needs a little more testing - 4/3/2016 9:42 PM
			update_field( $fields['meta_key'], $meta_value, $post_id );
		}

		$message .= '</ul>';
	} else {
		$message = "<h1 style='color: red;font-weight: bold'>Invalid Post ID Array</h1>";


		return $message;
	}


	//Done with the fix


	$message .= lct_acf_recap_field_settings( $fields, $prefix );


	return $message;
}


/**
 * File Fix::: Run _editzz File Overwrite
 * Overwrites managed _editzz files.
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 */
function lct_get_fixes_cleanups_message___file_fix_editzz_or( $prefix, $parent ) {
	$message = '';

	$excluded_fields = [
		'show_params',
		'lct_fix'
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! isset( $fields['run_this'][0] ) ) {
		$message = '<h1 style="color: green;font-weight: bold;">Check Run This Now and Save Options to overwrite _editzz files</h1>';

		$message .= '<table style="border: 5px solid #ff0000;">
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_cleanup_guid' ) . '" class="button button-primary" target="_blank">Cleanup Guid Fields</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_close_all_pings_and_comments' ) . '" class="button button-primary" target="_blank">Close all pings and comments</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_cleanup_uploads' ) . '" class="button button-primary" target="_blank">Cleanup Uploads Folder</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_repair_acf_usermeta' ) . '" class="button button-primary" target="_blank">Repair ACF User Meta Data</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_repair_acf_postmeta' ) . '" class="button button-primary" target="_blank">Repair ACF Post Meta Data</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=lct_repair_acf_termmeta' ) . '" class="button button-primary" target="_blank">Repair ACF Term Meta Data</a>
					</p>
				</td>
			</tr>
		</table>';


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	$files_updated = apply_filters( 'lct_editzz_update_files', false, true );

	if ( empty( $files_updated ) ) {
		$message = "<h1 style='color: red;font-weight: bold'>_editzz overwrite failed</h1>";


		return $message;
	}


	//Done with the fix


	$message .= "<h1 style='color: green;font-weight: bold'>_editzz overwrite was successful</h1>";


	return $message;
}


/**
 * Review::: Shows a list of site info that we care about
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 */
function lct_get_fixes_cleanups_message___lct_review_site_info( $prefix, $parent ) {
	$message = [ ];

	$excluded_fields = [
		'show_params',
		'lct_fix'
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! isset( $fields['run_this'][0] ) ) {
		$message[] = "<h1 style='color: green;font-weight: bold'>Check Run This Now and Save Options to see the Site Info</h1>";


		return lct_return( $message );
	}


	//Ok, We are finally able to run the fix if we made it this far.


	$site_info = lct_get_site_info();

	if ( empty( $site_info ) ) {
		$message[] = "<h1 style='color: red;font-weight: bold'>Gathering Site Info failed</h1>";

		return lct_return( $message );
	} else {
		$message[] = $site_info;
	}


	//Done with the fix


	$message[] = "<h1 style='color: green;font-weight: bold'>Gathering Site Info was successful</h1>";


	return lct_return( $message );
}


/**
 * Clean up the guid in the database
 */
function lct_cleanup_guid() {
	global $wpdb;

	$siteurl        = get_option( 'siteurl' );
	$siteurl_tmp    = explode( '/', $siteurl );
	$siteurl_scheme = $siteurl_tmp[0];
	$siteurl_root   = $siteurl_tmp[2];

	$post_types = get_post_types();
	unset( $post_types['revision'] );
	ksort( $post_types );

	$post_info = [ ];

	foreach ( get_post_types() as $post_type ) {
		$args  = [
			'posts_per_page' => - 1,
			'post_type'      => $post_type,
			'fields'         => 'ids',
		];
		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post_id ) {
				$guid          = get_the_guid( $post_id );
				$post_info_tmp = '<strong>' . $post_id . ': (' . $post_type . ') ' . '</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;' . $guid;

				$guid_tmp    = explode( '/', $guid );
				$guid_tmp[0] = $siteurl_scheme;
				$guid_tmp[2] = $siteurl_root;
				$guid_new    = implode( '/', $guid_tmp );

				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET guid = %s WHERE ID = %d", $guid_new, $post_id ) );

				$post_info[] = $post_info_tmp . '<br />&nbsp;&nbsp;&nbsp;&nbsp;' . $guid_new . '<br />';
			}
		}
	} ?>


	<h3>Fixes and Cleanups: Cleanup Guid</h3>

	<h4>New Scheme: <?php echo $siteurl_scheme; ?></h4>

	<h4>New URL: <?php echo $siteurl_root; ?></h4>

	<h4>Post Types</h4>
	<p><?php echo implode( '<br />', $post_types ); ?></p>

	<h4>Posts Updated</h4>
	<p><?php echo implode( '<br />', $post_info ); ?></p>
	<h1>Done</h1>
<?php }


/**
 * Close all the pings and comments on posts, pages, etc.
 */
function lct_close_all_pings_and_comments() {
	$post_types = get_post_types();
	unset( $post_types['revision'] );

	$post_info = [ ];

	foreach ( get_post_types() as $post_type ) {
		$args  = [
			'posts_per_page' => - 1,
			'post_type'      => $post_type,
		];
		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$post_id = $post->ID;

				if ( $post->comment_status != 'closed' || $post->ping_status != 'closed' ) {
					$args           = [
						'ID'             => $post_id,
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
					];
					$update_success = wp_update_post( $args );

					if ( $update_success ) {
						$post_info[] = '<strong>' . $post_id . ' (' . $post_type . ') ' . ':</strong> Pings and comments are now closed for ' . get_the_title( $post_id ) . '<br />';
					}
				}
			}
		}
	} ?>


	<h3>Close all pings and comments</h3>

	<p><?php echo implode( '<br />', $post_info ); ?></p>

	<h1>Done</h1>
<?php }


/**
 * Cleanup unneeded files
 */
function lct_cleanup_uploads() {
	$post_info           = [ ];
	$disable_image_sizes = get_field( 'lct:::disable_image_sizes', lct_o() );


	if ( empty( $disable_image_sizes ) ) {
		echo '<h1>Nothing to do.</h1>';


		return;
	}


	$args  = [
		'posts_per_page' => - 1,
		'post_type'      => 'attachment',
	];
	$posts = get_posts( $args );


	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$attachment = wp_get_attachment_metadata( $post->ID );

			if ( strpos( $attachment['file'], '/' ) !== false ) {
				$attachment_folder_tmp = explode( '/', $attachment['file'] );
				array_pop( $attachment_folder_tmp );
				$attachment_folder = implode( '/', $attachment_folder_tmp ) . '/';
			} else {
				$attachment_folder = '';
			}


			if ( ! empty( $attachment['sizes'] ) ) {
				foreach ( $attachment['sizes'] as $size_key => $size ) {
					if (
						in_array( $size_key, $disable_image_sizes ) &&
						file_exists( lct_up_path() . '/' . $attachment_folder . $size['file'] )
					) {
						$unlink = unlink( lct_up_path() . '/' . $attachment_folder . $size['file'] );
						unset( $attachment['sizes'][ $size_key ] );

						if ( $unlink )
							$post_info[] = '<strong>' . $post->ID . ' (attachment) ' . ':</strong> Image Removed: uploads/' . $attachment_folder . $size['file'] . '<br />';
					}
				}

				wp_update_attachment_metadata( $post->ID, $attachment );
			}
		}
	} ?>


	<h3>Cleaned up unneeded files</h3>

	<p><?php echo implode( '<br />', $post_info ); ?></p>

	<h1>Done</h1>
<?php }


/**
 * Repair ACF User Meta Data
 */
function lct_repair_acf_usermeta() {
	$users_successful         = [ ];
	$users_successful_already = [ ];
	$users_failed             = [ ];


	$args  = [
		'fields' => 'ids'
	];
	$users = get_users( $args );
	//P_R_O( $users );
	//$users = [ 5091 ];


	foreach ( $users as $user_id ) {
		$groups = acf_get_field_groups( [ 'user_id' => $user_id ] );

		foreach ( $groups as $group ) {
			if ( isset( $group['local'] ) )
				$field_objects = acf_get_local_fields( $group['key'] );
			else
				$field_objects = acf_get_fields_by_id( $group['ID'] );

			if ( $field_objects ) {
				foreach ( $field_objects as $field_object ) {
					$current_key = get_user_meta( $user_id, '_' . $field_object['name'], true );

					if ( ! $current_key ) {
						$current_value = get_field( $field_object['key'], lct_u( $user_id ) );
						$updated       = update_field( $field_object['key'], $current_value, lct_u( $user_id ) );

						if ( $updated ) {
							$users_successful[] = $user_id;
						}
					} else {
						if ( $current_key == $field_object['key'] ) {
							$users_successful_already[] = $user_id;
						} else {
							$users_field_failed[] = $field_object['key'] . ' BUT SET TO ' . $current_key;
							$users_failed[]       = $user_id;
						}
					}
				}
			}
		}
	}


	echo '<h3>Repair ACF User Meta Data</h3>';


	echo '<h1 style="color: green;">Users Successful</h1>';
	if ( ! empty( $users_successful ) ) {
		echo '<p>Updated:<br />' . lct_return( array_unique( $users_successful ), ',' ) . '</p>';
		echo '<p>Already:<br />' . lct_return( array_unique( $users_successful_already ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}

	echo '<h1 style="color: red;">Users Failed</h1>';
	if ( ! empty( $users_field_failed ) && ! empty( $users_failed ) ) {
		echo '<p>Fields:<br />' . lct_return( array_unique( $users_field_failed ), '<br />' ) . '</p>';
		echo '<p>Users:<br />' . lct_return( array_unique( $users_failed ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}


	echo '<h1>Done</h1>';
}


/**
 * Repair ACF Post Meta Data
 */
function lct_repair_acf_postmeta() {
	$all_post_types           = get_post_types();
	$post_types               = [ ];
	$post_type_exclude        = [ 'revision', 'nav_menu_item', 'acf-field-group', 'acf-field', 'shop_webhook', 'wc_membership_plan', 'wc_user_membership' ];
	$posts_successful         = [ ];
	$posts_successful_already = [ ];


	foreach ( $all_post_types as $post_type ) {
		if ( ! in_array( $post_type, $post_type_exclude ) )
			$post_types[] = $post_type;
	}


	foreach ( $post_types as $post_type ) {
		$args  = [
			'posts_per_page' => - 1,
			'post_type'      => $post_type,
			'fields'         => 'ids',
		];
		$posts = get_posts( $args );
		//P_R_O( $posts );
		//$posts = [ 3840 ];


		if ( empty( $posts ) )
			continue;


		foreach ( $posts as $post_id ) {
			$groups = acf_get_field_groups( [ 'post_id' => $post_id ] );

			foreach ( $groups as $group ) {
				if ( isset( $group['local'] ) )
					$field_objects = acf_get_local_fields( $group['key'] );
				else
					$field_objects = acf_get_fields_by_id( $group['ID'] );

				if ( $field_objects ) {
					foreach ( $field_objects as $field_object ) {
						$current_key = get_post_meta( $post_id, '_' . $field_object['name'], true );

						if ( ! $current_key ) {
							$current_value = get_field( $field_object['key'], $post_id );
							$updated       = update_field( $field_object['key'], $current_value, $post_id );

							if ( $updated ) {
								$posts_successful[] = $post_id;
							}
						} else {
							if ( $current_key == $field_object['key'] ) {
								$posts_successful_already[] = $post_id;
							} else {
								$posts_field_failed[] = $field_object['key'] . ' BUT SET TO ' . $current_key;
								$posts_failed[]       = $post_id;
							}
						}
					}
				}
			}
		}
	}


	echo '<h3>Repair ACF Post Meta Data</h3>';


	echo '<h1 style="color: green;">Posts Successful</h1>';
	if ( ! empty( $posts_successful ) ) {
		echo '<p>Updated:<br />' . lct_return( array_unique( $posts_successful ), ',' ) . '</p>';
		echo '<p>Already:<br />' . lct_return( array_unique( $posts_successful_already ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}

	echo '<h1 style="color: red;">Posts Failed</h1>';
	if ( ! empty( $posts_field_failed ) && ! empty( $posts_failed ) ) {
		echo '<p>Fields:<br />' . lct_return( array_unique( $posts_field_failed ), '<br />' ) . '</p>';
		echo '<p>Posts:<br />' . lct_return( array_unique( $posts_failed ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}


	echo '<h1>Done</h1>';
}


/**
 * Repair ACF User Meta Data
 */
function lct_repair_acf_termmeta() {
	$terms_successful         = [ ];
	$terms_successful_already = [ ];
	$taxonomies               = get_taxonomies();


	$args  = [ ];
	$terms = get_terms( $taxonomies, $args );
	//P_R_O( $terms );
	//$terms = [ get_term( 8333, 'npl_customer_address' ) ];

	foreach ( $terms as $term ) {
		$term_id = $term->term_id;

		$groups = acf_get_field_groups( [ 'taxonomy' => $term->taxonomy ] );

		foreach ( $groups as $group ) {
			if ( isset( $group['local'] ) )
				$field_objects = acf_get_local_fields( $group['key'] );
			else
				$field_objects = acf_get_fields_by_id( $group['ID'] );

			if ( $field_objects ) {
				foreach ( $field_objects as $field_object ) {
					$current_key = get_term_meta( $term_id, '_' . $field_object['name'], true );

					if ( ! $current_key ) {
						$current_value = get_field( $field_object['key'], lct_t( $term ) );
						$updated       = update_field( $field_object['key'], $current_value, lct_t( $term ) );

						if ( $updated ) {
							$terms_successful[] = $term_id;

							delete_option( "{$term->taxonomy}_{$term_id}_{$field_object['name']}" );
							delete_option( "_{$term->taxonomy}_{$term_id}_{$field_object['name']}" );
						}
					} else {
						if ( $current_key == $field_object['key'] ) {
							$terms_successful_already[] = $term_id;

							delete_option( "{$term->taxonomy}_{$term_id}_{$field_object['name']}" );
							delete_option( "_{$term->taxonomy}_{$term_id}_{$field_object['name']}" );
						} else {
							$terms_field_failed[] = $field_object['key'] . ' BUT SET TO ' . $current_key;
							$terms_failed[]       = $term_id;
						}
					}
				}
			}
		}
	}


	echo '<h3>Repair ACF Term Meta Data</h3>';


	echo '<h1 style="color: green;">Terms Successful</h1>';
	if ( ! empty( $terms_successful ) ) {
		echo '<p>Updated:<br />' . lct_return( array_unique( $terms_successful ), ',' ) . '</p>';
		echo '<p>Already:<br />' . lct_return( array_unique( $terms_successful_already ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}

	echo '<h1 style="color: red;">Terms Failed</h1>';
	if ( ! empty( $terms_field_failed ) && ! empty( $terms_failed ) ) {
		echo '<p>Fields:<br />' . lct_return( array_unique( $terms_field_failed ), '<br />' ) . '</p>';
		echo '<p>Terms:<br />' . lct_return( array_unique( $terms_failed ), ',' ) . '</p>';
	} else {
		echo '<p>None</p>';
	}


	echo '<h1>Done</h1>';
}
