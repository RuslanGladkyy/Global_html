<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}admin_filter", 'init' ] );

class lct_admin_filter {
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
		//global $g_zxza;
		//$this->zxzp = $g_zxza;


		add_filter( 'script_loader_src', [ $this, 'remove_script_version' ], 15, 1 );

		add_filter( 'style_loader_src', [ $this, 'remove_script_version' ], 15, 1 );

		add_filter( 'wp_nav_menu_items', [ $this, 'wp_nav_menu_items' ], 10, 2 );

		add_filter( 'post_row_actions', [ $this, 'add_post_id' ], 2, 101 );
		add_filter( 'media_row_actions', [ $this, 'add_post_id' ], 2, 101 );
		add_filter( 'page_row_actions', [ $this, 'add_page_id' ], 2, 101 );

		//We will call this dynamically outside of the class
		//add_filter( 'user_contactmethods', [ $this, 'remove_contactmethods' ], 10, 2 );
	}


	/**
	 * Strip out the query string that shows the WP version, on all enqueued scripts and styles
	 *
	 * @param $src
	 *
	 * @return mixed
	 */
	public function remove_script_version( $src ) {
		$parts = explode( '?', $src );

		if ( strpos( $src, 'fonts.googleapis.com/css?' ) !== false || strpos( $src, '/?' ) !== false )
			return $src;


		return $parts[0];
	}


	/**
	 * Use the class field to make menu items conditional
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function wp_nav_menu_items( $items, $args ) {
		$the_items = preg_replace( "#/li>([^<ul]*?)<li#", "/li>~~~~~<li", trim( $items ) );
		$the_items = preg_replace( "#<ul(.*?)>#", "~~~~~<ul$1>~~~~~", $the_items );
		$the_items = preg_replace( "#</ul>#", "~~~~~</ul>~~~~~", $the_items );
		$the_items = explode( '~~~~~', $the_items );


		if ( ! empty( $the_items ) ) {
			$the_items = apply_filters( 'lct_class_conditional_items', $the_items );


			foreach ( $the_items as $k => $item ) {
				//Check if this item should be unset based on user_logged_in status
				if ( lct_check_user_logged_in_of_class( $item ) ) {
					unset( $the_items[ $k ] );

					continue;
				}

				//Check if this item is for a particular role
				foreach ( lct_get_role_cap_prefixes_only() as $role ) {
					if ( strpos( $item, $role ) !== false ) {
						if ( lct_check_role_of_class( $item ) )
							unset( $the_items[ $k ] );
					}
				}
			}

			$items = implode( '', $the_items );
		}


		return $items;
	}


	/**
	 * These things are annoying
	 * We will call this dynamically outside of the class
	 *
	 * @param $contactmethods
	 *
	 * @return mixed
	 */
	public function remove_contactmethods( $contactmethods ) {
		unset( $contactmethods['aim'] );
		unset( $contactmethods['yim'] );
		unset( $contactmethods['jabber'] );
		unset( $contactmethods['author_facebook'] );
		unset( $contactmethods['author_twitter'] );
		unset( $contactmethods['author_linkedin'] );
		unset( $contactmethods['author_dribble'] );
		unset( $contactmethods['author_gplus'] );
		unset( $contactmethods['author_custom'] );


		return $contactmethods;
	}


	/**
	 * Set row actions for all post_types.
	 *
	 * @param  array   $actions
	 * @param  WP_Post $post
	 *
	 * @return array
	 */
	public function add_post_id( $actions, $post ) {
		return array_merge( [ 'id' => 'ID: ' . $post->ID ], $actions );
	}


	/**
	 * Set row actions for all pages.
	 *
	 * @param  array   $actions
	 * @param  WP_Post $post
	 *
	 * @return array
	 */
	public function add_page_id( $actions, $post ) {
		return array_merge( [ 'id' => 'ID: <a href="#' . $post->ID . '">' . $post->ID . '</a>' ], $actions );
	}
}
