<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}features_filter", 'init' ] );

class lct_features_filter {
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


		add_filter( 'embed_handler_html', [ $this, 'embed' ], 10, 3 );

		add_filter( 'embed_defaults', [ $this, 'embed_defaults' ], 10, 2 );

		add_filter( 'the_content', [ $this, 'the_content_final' ], 99999 );

		add_filter( 'widget_text', [ $this, 'widget_text_final' ], 99999 );

		add_filter( 'get_comments_number', [ $this, 'comment_count' ], 11, 1 );
		add_filter( 'lct_get_comments_number', [ $this, 'comment_count' ], 11, 2 );

		add_filter( 'lct_current_user_can_access', [ $this, 'current_user_can_access' ], 10, 2 );

		add_filter( 'lct_current_user_can_view', [ $this, 'current_user_can_view' ], 10, 2 );

		add_filter( 'init', [ $this, 'allow_comments_for_loop_only' ] );
	}


	/**
	 * Add query string elements into an embed URL
	 *
	 * @param $return
	 * @param $url
	 * @param $attr
	 *
	 * @return mixed
	 */
	public function embed( $return, $url, $attr ) {
		$query_string = [ ];
		$style        = [ ];
		$script       = [ ];
		$class        = [ ];
		$class[]      = 'videoWrapper';
		$id           = str_replace( '-', '_', sanitize_title( $url ) );
		$ratio        = '.5625';


		if ( ! empty( $attr['style'] ) )
			$style[] = rtrim( $attr['style'], ';' );

		if ( ! empty( $attr['class'] ) )
			$class[] = trim( $attr['class'] );

		if ( ! empty( $attr['width'] ) && ! empty( $attr['height'] ) )
			$ratio = ( (int) $attr['height'] / (int) $attr['width'] );

		if ( ! empty( $attr['ratio'] ) )
			$ratio = $attr['ratio'];


		//Get the src URL squared away
		preg_match( '/src="(.*?)"/', $return, $src );
		$src = $src[1];

		if ( $src ) {
			if ( strpos( $src, '?' ) !== false ) {
				$tmp            = explode( '?', $src );
				$query_string[] = $tmp[1];
			}


			if ( isset( $attr['rel'] ) )
				$query_string[] = 'rel=' . $attr['rel'];


			foreach ( $attr as $key => $a ) {
				if ( strpos( $key, 'query_' ) !== false ) {
					$key            = str_replace( 'query_', '', $key );
					$query_string[] = $key . '=' . $a;
				}
			}


			if ( ! empty( $query_string ) )
				$query_string = '?' . lct_return( $query_string, '&' );
			else
				$query_string = '';


			$return = str_replace( $src, $url . $query_string, $return );
		}


		if ( ! empty( $attr['width'] ) ) {
			$padding_bottom = $attr['width'] * $ratio;
			$script[]       = '<script>
				jQuery( document ).ready( function() {
					resize_' . $id . '();

					jQuery( window ).resize(function() {
						resize_' . $id . '();
					});
				});


				function resize_' . $id . '(){
					var resize_id = jQuery( \'#' . $id . '\' );

					if( resize_id.width() < ' . $attr['width'] . ' )
						resize_id.css({ "padding-bottom": "" });
					else
						resize_id.css({ "padding-bottom": "' . $padding_bottom . 'px" });
				}
			</script>';
		}


		if ( ! empty( $attr['width'] ) )
			$style[] = sprintf( 'max-width:' . $attr['width'] . 'px' );

		if ( ! empty( $attr['height'] ) )
			$style[] = sprintf( 'max-height:' . $attr['height'] . 'px' );


		$return = preg_replace(
			'/(\r\n|\n|\r)+/',
			'',
			sprintf(
				'<div id="%s" class="%s" style="%s">%s%s</div>',
				$id,
				lct_return( $class, ' ' ),
				lct_return( $style, ';' ),
				$return,
				lct_return( $script )
			)
		);


		return $return;
	}


	/**
	 * Set the defaults for embedded video to full size
	 *
	 * @param $atts
	 * @param $url
	 *
	 * @return mixed
	 */
	public function embed_defaults( $atts, $url ) {
		if (
			isset( $atts['width'] ) &&
			$atts['width'] == 669
		) {
			$atts['width'] = '';
		}

		if (
			isset( $atts['height'] ) &&
			$atts['height'] == 1000
		) {
			$atts['height'] = '';
		}


		return $atts;
	}


	/**
	 * Do any final tweaks to the_content at the very end of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function the_content_final( $content ) {
		//Check for nested shortcodes
		$content = lct_check_for_nested_shortcodes( $content );

		//Add any JS tracking scripts
		$content = lct_check_is_thanks_page( $content );


		return $content;
	}


	/**
	 * Do any final tweaks to widget_text at the very end of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function widget_text_final( $content ) {
		$content = lct_check_for_nested_shortcodes( $content );


		return $content;
	}


	/**
	 * We need to recount the comments since we have different comment_types sometimes
	 *
	 * @param        $count
	 * @param string $type
	 *
	 * @return int
	 */
	public function comment_count( $count, $type = 'comment' ) {
		$comment_count = count(
			get_approved_comments(
				get_the_ID(),
				[
					'type' => $type
				]
			)
		);


		return $comment_count;
	}


	/**
	 * Only allow comments to be queried through the comments_template
	 */
	public function allow_comments_for_loop_only() {
		$allow = apply_filters( 'lct_allow_comments_for_loop_only', false );


		if ( $allow )
			add_filter( 'pre_get_comments', [ $this, 'pre_get_comments' ] );
	}


	/**
	 * Only allow comments to be queried through the comments_template
	 *
	 * @param $comments
	 */
	public function pre_get_comments( $comments ) {
		$comments->query_vars['type'] = 'comment';


		return;
	}


	/**
	 * Takes an array of roles and caps and let's us know if the current user can do at least one of them.
	 * //TODO: cs - This can be improved by using global $wp_roles - 4/3/2016 2:31 AM
	 *
	 * @param      $has_access
	 * @param null $roles_n_caps
	 *
	 * @return bool
	 */
	public function current_user_can_access( $has_access, $roles_n_caps = null ) {
		if ( empty( $roles_n_caps ) )
			return $has_access;


		if ( ! is_array( $roles_n_caps ) )
			$roles_n_caps = explode( ',', $roles_n_caps );

		$can_access = 1;

		//first check and see if we are filtering by role_cap
		foreach ( $roles_n_caps as $class_prefix_check_key => $class_prefix_check ) {
			if ( strpos_array( $class_prefix_check, lct_get_role_cap_prefixes() ) !== false )
				$can_access = 0;
			else
				unset( $roles_n_caps[ $class_prefix_check_key ] );
		}

		if ( ! $can_access && ! empty( $roles_n_caps ) && ! empty( $roles_n_caps ) ) {
			foreach ( $roles_n_caps as $role ) {
				$role = str_replace( lct_get_role_cap_prefixes(), '', $role );

				if ( current_user_can( $role ) )
					$can_access ++;
			}
		}

		if ( $can_access )
			return true;


		return false;
	}


	/**
	 * Takes an array of roles and caps and let's us know if the current user can do at least one of them, in view only mode.
	 *
	 * @param      $has_view
	 * @param null $roles_n_caps
	 *
	 * @return bool
	 */
	public function current_user_can_view( $has_view, $roles_n_caps = null ) {
		if ( empty( $roles_n_caps ) )
			return $has_view;


		if ( ! is_array( $roles_n_caps ) )
			$roles_n_caps = explode( ',', $roles_n_caps );

		$can_view = 0;


		foreach ( $roles_n_caps as $role ) {
			if ( strpos( $role, 'viewonly_' ) === false )
				continue;

			$role = str_replace( array_merge( lct_get_role_cap_prefixes(), [ 'viewonly_' ] ), '', $role );

			if ( current_user_can( $role ) )
				$can_view ++;
		}

		if ( $can_view )
			return true;


		return false;
	}
}
