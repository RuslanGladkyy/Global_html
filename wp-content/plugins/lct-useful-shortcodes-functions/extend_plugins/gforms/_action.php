<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}gforms_action", 'init' ], 1000000 );

class lct_gforms_action {
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


		add_action( 'gform_enqueue_scripts', [ $this, 'style_n_script' ], 11 );

		add_action( 'gform_after_submission', [ $this, 'remove_form_entry' ], 13, 2 );

		add_action( 'gform_notification', [ $this, 'cj_check' ], 9999, 3 );

		add_action( 'gform_confirmation', [ $this, 'query_string_add' ], 9999, 4 );
	}


	/**
	 * Add any existing custom gforms' LCT styles/scripts or child theme styles/scripts
	 *
	 * @param $gf_form
	 * @param $ajax
	 */
	function style_n_script( $gf_form, $ajax = null ) {
		//We can only process this action is acf is also installed and running
		if (
			! class_exists( 'acf' ) ||
			empty( $gf_form )
		)
			return;


		//Should we load our gforms css?
		if ( get_field( "{$this->zxzp->zxza_acf}use_gforms_css_tweaks", lct_o() ) ) {
			wp_enqueue_style(
				"{$this->zxzp->zxzu}gforms",
				$this->zxzp->plugin_dir_url . 'assets/css/extend_plugins/gforms.min.css',
				[ 'gforms_reset_css', 'gforms_formsmain_css' ]
			);
		}


		//Should we load our Avada gforms css?
		if (
			$this->zxzp->Avada &&
			get_field( "{$this->zxzp->zxza_acf}use_gforms_css_tweaks", lct_o() ) &&
			! get_field( "{$this->zxzp->zxza_acf}disable_avada_css", lct_o() )
		) {
			wp_enqueue_style(
				"{$this->zxzp->zxzu}Avada_gforms",
				$this->zxzp->plugin_dir_url . 'assets/css/extend_plugins/Avada-gforms.min.css',
				[ "{$this->zxzp->zxzu}gforms" ]
			);
		}


		//Let's check and see if the child theme has any gforms css it wants to load?
		if ( file_exists( get_stylesheet_directory() . '/custom/css/gforms.min.css' ) ) {
			wp_enqueue_style(
				"{$this->zxzp->zxzu}theme_gforms",
				get_stylesheet_directory_uri() . '/custom/css/gforms.min.css',
				[ 'gforms_reset_css', 'gforms_formsmain_css' ]
			);
		}


		//Let's check and see if the child theme has any gforms form specific css it wants to load?
		if ( file_exists( get_stylesheet_directory() . '/custom/css/gforms_' . $gf_form['id'] . '.min.css' ) ) {
			wp_enqueue_style(
				"{$this->zxzp->zxzu}theme_gforms_{$gf_form['id']}",
				get_stylesheet_directory_uri() . '/custom/css/gforms_' . $gf_form['id'] . '.min.css',
				[ 'gforms_reset_css', 'gforms_formsmain_css' ]
			);
		}


		//Let's check and see if the child theme has any gforms js it wants to load?
		if ( file_exists( get_stylesheet_directory() . '/custom/js/gforms.min.js' ) ) {
			wp_enqueue_script(
				"{$this->zxzp->zxzu}theme_gforms",
				get_stylesheet_directory_uri() . '/custom/js/gforms.min.js',
				[ 'jquery' ]
			);
		}


		//Let's check and see if the child theme has any gforms form specific js it wants to load?
		if ( file_exists( get_stylesheet_directory() . '/custom/js/gforms_' . $gf_form['id'] . '.min.js' ) ) {
			wp_enqueue_script(
				"{$this->zxzp->zxzu}theme_gforms_{$gf_form['id']}",
				get_stylesheet_directory_uri() . '/custom/js/gforms_' . $gf_form['id'] . '.min.js',
				[ 'jquery' ]
			);
		}


		//Add textarea autosize to gforms
		$jq = "\n" . 'var gforms_action_style_n_script_selector = \'.gform_wrapper textarea\';

		if( jQuery( gforms_action_style_n_script_selector ).length ) {
			var gforms_action_style_n_script_ta = document.querySelector( gforms_action_style_n_script_selector );
			gforms_action_style_n_script_ta.addEventListener( \'focus\', function() {
				autosize( gforms_action_style_n_script_ta );
			});
		}' . "\n";

		//Set all stars in a sub-label to gfield_required
		$jq .= "\n" . 'jQuery(\'.gform_wrapper .ginput_complex label\').each( function () {
			jQuery(this).html( jQuery(this).html().replace(/(\*)/g, \'<span class="gfield_required">$1</span>\'));
		});' . "\n";

		do_action( 'lct_jq_autosize' );
		do_action( 'lct_jq_doc_ready_add', $jq );
	}


	/**
	 * store or remove entry based on settings.
	 *
	 * @since 1.2.9
	 *
	 * @param array $gf_entry ALL entry data
	 * @param array $gf_form  ALL form data
	 */
	function remove_form_entry( $gf_entry, $gf_form ) {
		if ( get_field( "{$this->zxzp->zxza_acf}gforms_store", lct_o() ) ) {
			if ( lct_gf_form_should_alter( $gf_form['id'] ) )
				return;
		} else {
			if ( ! lct_gf_form_should_alter( $gf_form['id'] ) )
				return;
		}


		global $wpdb;

		$lead_id                = $gf_entry['id'];
		$lead_table             = RGFormsModel::get_lead_table_name();
		$lead_detail_table      = RGFormsModel::get_lead_details_table_name();
		$lead_detail_long_table = RGFormsModel::get_lead_details_long_table_name();
		$lead_meta_table        = RGFormsModel::get_lead_meta_table_name();
		$lead_notes_table       = RGFormsModel::get_lead_notes_table_name();


		//Delete from detail long
		$sql = $wpdb->prepare(
			"DELETE FROM $lead_detail_long_table
		WHERE lead_detail_id IN
		(SELECT id FROM $lead_detail_table WHERE lead_id=%d)",
			$lead_id
		);
		$wpdb->query( $sql );


		//Delete from lead details
		$sql = $wpdb->prepare(
			"DELETE FROM $lead_detail_table WHERE lead_id=%d",
			$lead_id
		);
		$wpdb->query( $sql );


		//Delete from lead meta
		$sql = $wpdb->prepare(
			"DELETE FROM $lead_meta_table WHERE lead_id=%d",
			$lead_id
		);
		$wpdb->query( $sql );


		//Delete from lead notes
		$sql = $wpdb->prepare(
			"DELETE FROM $lead_notes_table WHERE lead_id=%d",
			$lead_id
		);
		$wpdb->query( $sql );


		//Delete from lead
		$sql = $wpdb->prepare(
			"DELETE FROM $lead_table WHERE id=%d",
			$lead_id
		);
		$wpdb->query( $sql );
	}


	/**
	 * Our custom SPAM checker
	 * //TODO: cs - the foreach's could be made more efficient - 12/17/2015 3:32 PM
	 *
	 * @param $gf_notification
	 * @param $gf_form
	 * @param $gf_entry
	 *
	 * @return mixed
	 */
	function cj_check( $gf_notification, $gf_form, $gf_entry ) {
		if ( ! get_field( "{$this->zxzp->zxza_acf}enable_cj_spam_check", lct_o() ) )
			return $gf_notification;


		$failed_cj_checks = 0;

		$cj_checks   = [ ];
		$cj_checks[] = '';

		$cj_checks_name   = [ ];
		$cj_checks_name[] = 'c~~~';
		$cj_checks_name[] = 'c ~~~';
		$cj_checks_name[] = 'c~~~j';
		$cj_checks_name[] = 'c ~~~j';

		$cj_checks_phone   = [ ];
		$cj_checks_phone[] = '6192025400';

		$cj_checks_email   = [ ];
		$cj_checks_email[] = 'resultsfirst';

		$mapped_fields = lct_map_label_to_field_id( $gf_form['fields'], $gf_entry );


		foreach ( $mapped_fields as $k => $v ) {
			if ( strpos( $k, 'name' ) !== false ) {
				foreach ( $cj_checks_name as $name ) {
					if ( strpos( strtolower( $v ), $name ) !== false ) {
						$failed_cj_checks ++;
					}
				}
			}

			if ( strpos( $k, 'phone' ) !== false ) {
				$phone = preg_replace( '/\D/', '', $v );
				if ( in_array( $phone, $cj_checks_phone ) )
					$failed_cj_checks ++;
			}

			if ( strpos( $k, 'email' ) !== false ) {
				foreach ( $cj_checks_email as $email ) {
					if ( strpos( $v, $email ) !== false ) {
						$failed_cj_checks ++;
					}
				}
			}
			$tmp[] = $k . '...' . $v;
		}


		if ( $failed_cj_checks ) {
			$gf_notification['subject'] = '[CAUGHT BY CJ SPAM CHECK] :: ' . $gf_notification['subject'];

			$emails   = [ ];
			$emails[] = get_field( "{$this->zxzp->zxza_acf}enable_cj_spam_check_email", lct_o() );

			$gf_notification['toType'] = "email";
			$gf_notification['to']     = implode( ",", $emails );
			$gf_notification['bcc']    = '';
		}


		return $gf_notification;
	}


	/**
	 * We need some variables from the form in order to proper set our contact page trackers
	 *
	 * @param $confirmation
	 * @param $form
	 * @param $lead
	 * @param $ajax
	 *
	 * @return array
	 */
	function query_string_add( $confirmation, $form, $lead, $ajax ) {
		if (
			! empty( $form['confirmation'] ) &&
			$form['confirmation']['type'] == 'page' &&
			get_field( 'lct:::is_thanks_page', $form['confirmation']['pageId'] ) &&
			get_field( 'lct:::is_google_event_tracking', $form['confirmation']['pageId'] )
		) {
			$queryString   = [ ];
			$queryString[] = 'form_url=' . urlencode( str_replace( lct_url_site(), '', $lead['source_url'] ) );
			$queryString[] = 'thanks=' . $form['id'];


			if ( ! empty( $form['confirmation']['queryString'] ) )
				$queryString[] = GFCommon::replace_variables( trim( $form['confirmation']['queryString'] ), $form, $lead, true, true, false, 'text' );

			$confirmation = [ "redirect" => get_the_permalink( $form['confirmation']['pageId'] ) . '?' . implode( '&', $queryString ) ];
		}


		return $confirmation;
	}
}
