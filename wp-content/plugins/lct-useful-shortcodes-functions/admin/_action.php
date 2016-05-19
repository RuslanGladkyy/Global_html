<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}admin_action", 'init' ] );

class lct_admin_action {
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


		add_action( 'init', [ $this, 'register_handler' ] );

		add_action( 'admin_init', [ $this, 'cleanup_profile_page' ] );

		add_action( 'wp_footer', [ $this, 'wp_footer_get_user_agent_info' ], 99999 );

		add_action( 'lct_get_user_agent_info', [ $this, 'get_user_agent_info' ], 10, 2 );

		add_action( 'set_object_terms', [ $this, 'acf_set_object_terms' ], 10, 6 );

		add_action( 'lct_add_tax_to_user_admin_page', [ $this, 'add_tax_to_user_admin_page' ] );

		add_action( 'after_setup_theme', [ $this, 'ajax_disable_stuff' ] );

		add_action( 'edited_term_taxonomy', [ $this, 'edited_term_taxonomy' ], 10, 2 );

		add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
	}


	/**
	 * We need this to run Vimeo thru the embed_handler_html filter
	 */
	public function register_handler() {
		global $wp_embed;


		$wp_embed->register_handler( 'lct_vimeo_embed_url', '#https?://(www.)?player\.vimeo\.com/(?:video|embed)/([^/]+)#i', [ $this, 'lct_wp_embed_handler_vimeo' ] );
	}


	/**
	 * Google Decided to stop serving their API files. so now we have to load them up.
	 */
	public function fix_google_api_scripts() {
		$wp_scripts = wp_scripts();


		if ( ! empty( $wp_scripts->registered['google-maps-api'] ) ) {
			$api = get_field( 'lct:::google_map_api', lct_o() );

			wp_deregister_script( 'google-maps-api' );
			wp_register_script( 'google-maps-api', '//maps.googleapis.com/maps/api/js?sensor=false&amp;language=' . substr( get_locale(), 0, 2 ) . '&amp;key=' . $api );
		}


		if ( ! empty( $wp_scripts->registered['google-maps-infobox'] ) ) {
			wp_deregister_script( 'google-maps-infobox' );
			wp_register_script( 'google-maps-infobox', $this->zxzp->plugin_dir_url . '/includes/google/maps-utility-library-v3/infobox_packed.js' );
		}
	}


	/**
	 * Callback for lct_vimeo_embed_url
	 *
	 * @param $matches
	 * @param $attr
	 * @param $url
	 * @param $rawattr
	 *
	 * @return mixed|void
	 */
	public function lct_wp_embed_handler_vimeo( $matches, $attr, $url, $rawattr ) {
		$embed = sprintf( '<iframe src="%s" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>', $url );


		return apply_filters( 'lct_wp_embed_handler_vimeo', $embed, $attr, $url, $rawattr );
	}


	/**
	 * We can hide some things that we never use.
	 */
	public function cleanup_profile_page() {
		if ( class_exists( 'acf' ) ) { //We can only process this action is acf is also installed and running
			global $pagenow;


			if ( get_field( "{$this->zxzp->zxza_acf}hide_contact_methods_on_profile_page", lct_o() ) ) {
				global $lct_admin_filter;


				add_filter( 'user_contactmethods', [ $lct_admin_filter, 'remove_contactmethods' ], 999999 );
			}


			if ( get_field( "{$this->zxzp->zxza_acf}hide_woocommerce_items_on_profile_page", lct_o() ) )
				add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );


			if ( get_field( "{$this->zxzp->zxza_acf}hide_color_picker_on_profile_page", lct_o() ) && $pagenow != 'profile.php' ) {
				remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
				remove_action( 'admin_', 'admin_color_scheme_picker' );
			}
		}
	}


	/**
	 * Add some stuff to wp_footer action
	 */
	public function wp_footer_get_user_agent_info() {
		if ( get_field( "{$this->zxzp->zxza_acf}print_user_agent_in_footer", lct_o() ) )
			do_action( 'lct_get_user_agent_info', true, true );
	}


	/**
	 * Display all the browscap data for a particular page
	 *
	 * @param null $print
	 * @param null $hide
	 *
	 * @return bool
	 */
	public function get_user_agent_info( $print = null, $hide = null ) {
		$cache_loc = '';

		$possible_locs = [
			'/home/_apps/browscap/',
			'W:/wamp/apps/browscap/',
			'/home/visartsa/_apps/browscap/'
		];

		foreach ( $possible_locs as $loc ) {
			if ( file_exists( "{$loc}Browscap.php" ) ) {
				include_once( "{$loc}Browscap.php" );
				$cache_loc = "{$loc}cache";

				break;
			}
		}


		if ( ! $cache_loc )
			return false;


		/** @noinspection PhpUndefinedClassInspection */
		$bc = new Browscap( $cache_loc );
		/** @noinspection PhpUndefinedMethodInspection */
		$getBrowser = $bc->getBrowser();


		if ( $print ) {
			if ( $hide ) {
				if ( WP_CACHE ) {
					$before = '<pre id="browscap" style="display: none !important;">';
					$after  = '</pre>';
				} else {
					$before = '<!-- ## id="browscap" ';
					$after  = '-->';
				}
			} else {
				$before = '<pre>';
				$after  = '</pre>';
			}

			echo $before;
			print_r( $getBrowser );
			echo $after;

			return false;
		}

		return $getBrowser;
	}


	/**
	 * This is used to fix a bug in saving terms to users. If you don't do this it just sets the object id to 0 (very bad)
	 *
	 * @param $object_id
	 * @param $terms
	 * @param $tt_ids
	 * @param $taxonomy
	 * @param $append
	 * @param $old_tt_ids
	 *
	 * @return bool|false|int
	 */
	public function acf_set_object_terms(
		$object_id,
		$terms,
		$tt_ids,
		$taxonomy,
		/** @noinspection PhpUnusedParameterInspection */
		$append,
		/** @noinspection PhpUnusedParameterInspection */
		$old_tt_ids
	) {
		$object_id = (int) $object_id;

		if ( $object_id === 0 && isset( $_POST['user_id'] ) ) {
			global $wpdb;

			$user_id   = (int) $_POST['user_id'];
			$tt_ids_IN = implode( ',', $tt_ids );

			$wpdb->query(
				$wpdb->prepare( "
				DELETE FROM {$wpdb->term_relationships}
				WHERE `term_taxonomy_id` IN ( {$tt_ids_IN} )
				AND `object_id` = %d
				",
					[
						$object_id
					]
				)
			);

			wp_set_object_terms( $user_id, $terms, $taxonomy, false );

			return true;
		}

		return false;
	}


	/**
	 * Adds the taxonomy page in the admin.
	 * Creates the admin page for the 'profession' taxonomy under the 'Users' menu.  It works the same as any
	 * other taxonomy page in the admin.  However, this is kind of hacky and is meant as a quick solution.  When
	 * clicking on the menu item in the admin, WordPress' menu system thinks you're viewing something under 'Posts'
	 * instead of 'Users'.  We really need WP core support for this.
	 *
	 * @param $taxonomy
	 */
	public function add_tax_to_user_admin_page( $taxonomy ) {
		$taxonomy = get_taxonomy( $taxonomy );

		add_users_page(
			esc_attr( $taxonomy->labels->menu_name ),
			esc_attr( $taxonomy->labels->menu_name ),
			$taxonomy->cap->manage_terms,
			'edit-tags.php?taxonomy=' . $taxonomy->name
		);
	}


	/**
	 * Somethings just don't need to run when ajax
	 */
	public function ajax_disable_stuff() {
		if ( ! $this->zxzp->doing_ajax )
			return;


		global $avada, $woothemes_updater;


		if ( $_POST['action'] != 'save-widget' )
			remove_action( 'init', 'wp_widgets_init', 1 );


		if ( $avada ) {
			if (
				strpos( $this->zxzp->Avada_version, '1.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '2.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '3.' ) === 0
			) {
				/** @noinspection PhpUndefinedFieldInspection */
				remove_action( 'wp_head', [ $avada->dynamic_css, 'add_inline_css' ], 999 );

				remove_action( 'init', 'of_options' );
			}
		}


		if ( $woothemes_updater ) {
			remove_action( 'admin_init', [ $woothemes_updater->admin, 'load_updater_instances' ] );
		}
	}


	/**
	 * Check all the taxonomy counts when we save a term
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	public function edited_term_taxonomy(
		/** @noinspection PhpUnusedParameterInspection */
		$term,
		$taxonomy
	) {
		global $wpdb;


		$sql = "UPDATE " . $wpdb->term_taxonomy . " tt
		SET count = (
			SELECT count(p.ID)
				FROM  " . $wpdb->term_relationships . " tr
				LEFT JOIN " . $wpdb->posts . " p ON ( p.ID = tr.object_id AND p.post_status = 'publish' )
				WHERE tr.term_taxonomy_id = tt.term_taxonomy_id
		)
		WHERE tt.taxonomy = '" . $taxonomy . "'";
		$wpdb->query( $sql );
	}
}
