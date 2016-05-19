<?php /*~~~*/
/**
 * Redirect a page just before it is too late.
 * Don't use this unless you really have to
 *
 * @param bool|true  $force_exit
 * @param bool|false $headers_sent_already
 */
function lct_custom_redirect_wrapper( $force_exit = true, $headers_sent_already = false ) {
	$current_user = wp_get_current_user();


	if ( $headers_sent_already ) {
		$script = '<script type="text/javascript">
			window.location = "/redirect/"
	    </script>';

		echo $script;
		die();
	}

	if ( $current_user->ID || $force_exit ) {
		$redirect_url = $redirect_to = home_url( "/" );

		if ( function_exists( 'redirect_wrapper' ) )
			$redirect_url = redirect_wrapper( $redirect_to, '', $current_user );

		wp_redirect( $redirect_url );
		die();
	}
}


/**
 * Redirect a page just before it is too late.
 * This is the better version
 *
 * @param      $location
 * @param int  $status
 * @param bool $headers_sent
 */
function lct_wp_redirect( $location, $status = 302, $headers_sent = false ) {
	if ( headers_sent() || $headers_sent ) {
		$script = "<script type=\"text/javascript\">window.location = '{$location}'</script>";

		echo $script;
		die();
	} else {
		wp_redirect( $location, $status );
		die();
	}
}


/**
 * An array of emails that can be used to exclude or include in view, conditionals, etc.
 * //TODO: cs - Make this into an LCT Useful Setting - 11/11/2015 5:38 PM
 *
 * @return array
 */
function lct_get_dev_emails() {
	$emails = [
		'info@ircary.com',
		'cary@capital-designs.com',
		'cary@l-wconsulting.com',
		'dev@eetah.com'
	];


	return $emails;
}


/**
 * Check if the logged in user is a dev based on their email address on file.
 *
 * @param null $emails
 *
 * @return bool
 */
function lct_is_user_a_dev( $emails = null ) {
	if ( ! is_user_logged_in() )
		return false;

	if ( empty( $emails ) )
		$emails = lct_get_dev_emails();

	$current_user = wp_get_current_user();


	foreach ( $emails as $email ) {
		$user = get_user_by( 'email', $email );

		if ( $current_user->ID == $user->ID )
			return true;
	}


	return false;
}


/**
 * Take a single array and parse it into a find array and replace array
 *
 * @param $fr
 *
 * @return array
 */
function lct_create_find_and_replace_arrays( $fr ) {
	$find    = [ ];
	$replace = [ ];


	if ( is_array( $fr ) ) {
		foreach ( $fr as $f => $r ) {
			$find[]    = $f;
			$replace[] = $r;
		}
	}


	return [
		'find'    => $find,
		'replace' => $replace
	];
}


/**
 * Send an email if a critical function fails
 *
 * @param $args
 *
 * @return bool
 */
function lct_send_function_check_email( $args ) {
	global $sent_function_check_email;

	//Only send the email one per pageload
	if ( ! empty( $sent_function_check_email ) )
		return false;


	$mail = new lct_features_class_mail();
	$mail->set_from( 'noreply@lctformcheck.com', 'LCT Auto Function Check' );
	$mail->set_to( get_option( 'admin_email' ) );
	$mail->set_subject( sprintf( '%s is not working properly.', $args['function'] ) );
	$mail->set_message( sprintf( '%s at %s%s', $mail->get_subject(), get_bloginfo( 'url' ), $_SERVER['REQUEST_URI'] ) );
	$mail->send();


	$sent_function_check_email ++;


	return true;
}


/**
 * Check if this item should be unset based on user_logged_in status
 *
 * @param $item
 *
 * @return bool
 */
function lct_check_user_logged_in_of_class( $item ) {
	//Check if this item is for any guest user
	if ( strpos( $item, 'lct_ch_not_is_user_logged_in' ) !== false ) {
		if ( is_user_logged_in() )
			return true;
	}


	//Check if this item is for any logged in user
	if ( strpos( $item, 'lct_ch_is_user_logged_in' ) !== false ) {
		if ( ! is_user_logged_in() )
			return true;
	}


	return false;
}


/**
 * Check if this item is for a particular role
 * See: lct_get_role_cap_prefixes() to properly set $class_types
 *
 * @param       $item
 * @param array $class_types
 *
 * @return bool
 */
function lct_check_role_of_class( $item, $class_types = [ 'lct_role_', 'lct_cap_', 'lct_ch_' ] ) {
	//parse the classes
	preg_match_all( '/class="(.*?)"/', $item, $classes_match );

	if ( is_array( $classes_match[1] ) )
		$classes = explode( ' ', $classes_match[1][0] );
	else if ( $classes_match[1] )
		$classes = explode( ' ', $classes_match[1] );
	else
		$classes = explode( ' ', $item );

	foreach ( $classes as $class_k => $class ) {
		if (
			strpos( $class, 'menu-item' ) !== false ||
			strpos( $class, 'fusion' ) !== false
		) {
			unset( $classes[ $class_k ] );
		}
	}

	foreach ( $class_types as $class_type ) {
		if ( strpos( $item, $class_type ) !== false ) {
			$should_unset = 0;

			foreach ( $classes as $class_k => $class ) {
				$role = str_replace( $class_type, '', $class );

				if ( current_user_can( $role ) )
					$should_unset ++;
			}

			if ( empty( $should_unset ) )
				return true;
		}
	}


	return false;
}


/**
 * Return an array of lct_audit comment_type settings
 *
 * @param array $args
 *
 * @return array
 */
function lct_get_comment_type_lct_audit_settings( $args = [ ] ) {
	$audit_types = [ ];

	$audit_types['acf_update_field'] = [
		'name' => 'ACF Field Updated',
		'text' => 'An ACF field was updated.',
	];

	$defaults = [
		'singular'    => 'Audit Log Entry',
		'plural'      => 'Audit Log Entries',
		'audit_types' => $audit_types,
	];


	return apply_filters( 'lct_get_comment_type_lct_audit_settings', wp_parse_args( $args, $defaults ) );
}


/**
 * We want to do any of our shortcodes that are nested (so they don't break the theme or other plugins)
 *
 * @param $content
 *
 * @return mixed
 */
function lct_check_for_nested_shortcodes( $content ) {
	if ( strpos( $content, '{{' ) !== false && strpos( $content, '}}' ) !== false ) {
		$esc_html_shortcodes = [
			'get_directions'
		];
		$delimiters          = [
			[
				'before' => '{{{{',
				'after'  => '}}}}',
				'equal'  => '~~~~',
			],
			[
				'before' => '{{{',
				'after'  => '}}}',
				'equal'  => '~~~',
			],
			[
				'before' => '{{',
				'after'  => '}}',
				'equal'  => '~~',
			]
		];


		foreach ( $delimiters as $delimiter ) {
			if ( strpos( $content, $delimiter['before'] ) !== false && strpos( $content, $delimiter['after'] ) !== false ) {
				preg_match_all( "/" . $delimiter['before'] . "(.*?)" . $delimiter['after'] . "/", $content, $matches );

				if ( $matches[1] ) {
					$find    = [ ];
					$replace = [ ];

					foreach ( $matches[1] as $k => $tmp ) {
						$shortcode_output = do_shortcode( '[' . str_replace( [ $delimiter['equal'], '&#039;' ], [ '=', '\'' ], $tmp ) . ']' );

						if ( strpos( $shortcode_output, '[' ) !== false && strpos( $shortcode_output, ']' ) !== false )
							$shortcode_output = '';

						if ( in_array( $tmp, $esc_html_shortcodes ) )
							$shortcode_output = esc_html( $shortcode_output );

						$find[]    = $matches[0][ $k ];
						$replace[] = $shortcode_output;
					}

					$content = str_replace( $find, $replace, $content );
				}
			}
		}
	}


	return $content;
}


/**
 * Add any JS tracking scripts
 *
 * @param $content
 *
 * @return mixed
 */
function lct_check_is_thanks_page( $content ) {
	$post_id = get_the_ID();


	if (
		! empty( $content ) &&
		get_field( 'lct:::is_thanks_page', $post_id )
	) {
		if ( get_field( 'lct:::is_google_event_tracking', $post_id ) ) {
			$action = esc_js( lct_check_for_nested_shortcodes( get_field( 'lct:::google_event_tracking_action', $post_id ) ) );
			$label  = esc_js( lct_check_for_nested_shortcodes( get_field( 'lct:::google_event_tracking_label', $post_id ) ) );

			if (
				empty( $action ) &&
				isset( $_GET['thanks'] )
			) {
				$action = "gforms ID: {$_GET['thanks']}";
			}

			if (
				empty( $label ) &&
				isset( $_GET['form_url'] )
			) {
				$label = $_GET['form_url'];
			}


			$content .= sprintf( '<script>%s</script>', lct_get_gaTracker_onclick( 'form_submit', $action, $label, false ) );
		}


		if ( get_field( 'lct:::is_google_conversion_code', $post_id ) ) {
			$conversion_code = html_entity_decode( get_field( 'lct:::google_conversion_code', $post_id ) );

			$content .= $conversion_code;
		}


		if ( get_field( 'lct:::is_bing_conversion_code', $post_id ) ) {
			$conversion_code = html_entity_decode( get_field( 'lct:::bing_conversion_code', $post_id ) );

			$content .= $conversion_code;
		}
	}


	return $content;
}


/**
 * Preps a variable for return
 *
 * @param        $array
 * @param string $glue
 *
 * @return string
 */
function lct_return( $array, $glue = '' ) {
	$return = '';


	if (
		is_array( $array ) &&
		! empty( $array )
	) {
		$return = implode( $glue, $array );
	}


	return $return;
}


/**
 * Preps a list of site info that we care about
 *
 * @return string
 */
function lct_get_site_info() {
	$site_info = [ ];


	$site_info[] = lct_get_site_info_post_types();

	$site_info[] = lct_get_site_info_taxonomies();

	$site_info[] = lct_get_site_info_roles();

	$site_info[] = lct_get_site_info_caps();


	return lct_return( $site_info );
}


/**
 * Preps a list of all post_types
 *
 * @return string
 */
function lct_get_site_info_post_types() {
	$message    = [ ];
	$post_types = get_post_types();


	if ( ! empty( $post_types ) ) {
		unset( $post_types['revision'] );
		ksort( $post_types );


		$message[] = "<h2 style='color: green;font-weight: bold'>Post Types (post_types)</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		foreach ( $post_types as $post_type ) {
			$message[] = sprintf( '<li>%s</li>', $post_type );
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Preps a list of all taxonomies
 *
 * @return string
 */
function lct_get_site_info_taxonomies() {
	$message    = [ ];
	$taxonomies = get_taxonomies();


	if ( ! empty( $taxonomies ) ) {
		ksort( $taxonomies );


		$message[] = "<h2 style='color: green;font-weight: bold'>Taxonomies</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		foreach ( $taxonomies as $taxonomy ) {
			$message[] = sprintf( '<li>%s</li>', $taxonomy );
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Preps a list of all user roles
 *
 * @return string
 */
function lct_get_site_info_roles() {
	$message = [ ];
	$roles   = get_editable_roles();


	if ( ! empty( $roles ) ) {
		ksort( $roles );
		unset( $roles['administrator'] );
		unset( $roles['author'] );
		unset( $roles['contributor'] );
		unset( $roles['editor'] );
		unset( $roles['subscriber'] );


		$message[] = "<h2 style='color: green;font-weight: bold'>Custom User Roles</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		if ( ! empty( $roles ) ) {
			foreach ( $roles as $role_key => $role ) {
				$message[] = sprintf( '<li>%s</li>', $role_key );
			}
		} else {
			$message[] = '<li>None</li>';
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Preps a list of all user capabilities
 * //TODO: cs - Come up with a way to filter this more. Maybe look at: user-role-editor - 3/5/2016 12:02 PM
 *
 * @return string
 */
function lct_get_site_info_caps() {
	$message = [ ];
	$roles   = get_editable_roles();


	if ( ! empty( $roles['administrator']['capabilities'] ) ) {
		ksort( $roles['administrator']['capabilities'] );


		$message[] = "<h2 style='color: green;font-weight: bold'>User Capabilities</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		foreach ( $roles['administrator']['capabilities'] as $cap_key => $cap ) {
			$message[] = sprintf( '<li>%s</li>', $cap_key );
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}
