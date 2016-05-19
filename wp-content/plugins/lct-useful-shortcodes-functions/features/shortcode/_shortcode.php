<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}features_shortcode", 'init' ] );

class lct_features_shortcode {
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


		add_action( 'init', [ $this, 'features_shortcode_init' ] );


		add_shortcode( 'clear', [ $this, 'clear' ] );
		add_shortcode( "{$this->zxzp->zxzu}auto_logout", [ $this, 'auto_logout' ] );
		add_shortcode( "{$this->zxzp->zxzu}jquery_mask", [ $this, 'jquery_mask' ] );
		add_shortcode( "{$this->zxzp->zxzu}preload", [ $this, 'preload' ] );
		add_shortcode( "{$this->zxzp->zxzu}read_more", [ $this, 'read_more' ] );
		add_shortcode( "{$this->zxzp->zxzu}amp", [ $this, 'amp' ] );
		add_shortcode( 'get_directions', [ $this, 'get_directions' ] );
		add_shortcode( "{$this->zxzp->zxzu}current_user_can", [ $this, 'current_user_can' ] );
		add_shortcode( 'is_user_logged_in', [ $this, 'is_user_logged_in' ] );
		add_shortcode( "{$this->zxzp->zxzu}get_the_title", [ $this, 'get_the_title' ] );
		add_shortcode( "{$this->zxzp->zxzu}get_the_permalink", [ $this, 'get_the_permalink' ] );
		add_shortcode( "{$this->zxzp->zxzu}get_the_id", [ $this, 'get_the_ID' ] );
		add_shortcode( "{$this->zxzp->zxzu}get_the_date", [ $this, 'get_the_date' ] );
		add_shortcode( "{$this->zxzp->zxzu}get_the_modified_date_time", [ $this, 'get_the_modified_date_time' ] );
		add_shortcode( "theme_chunk", [ $this, 'theme_chunk' ] );
		add_shortcode( "faicon", [ $this, 'faicon' ] );
		add_shortcode( "pimg_link", [ $this, 'pimg_link' ] );

		add_shortcode( "lct_br", [ $this, 'br' ] );
		if ( ! shortcode_exists( 'br' ) )
			add_shortcode( "br", [ $this, 'br' ] );
	}


	/**
	 * Used to route the wpautop_disable AND wpautop_disable_new functions
	 * Choose which function to use wpautop_disable() OR wpautop_disable_new()
	 */
	public function features_shortcode_init() {
		if ( class_exists( 'acf' ) ) { //We can only process this action is acf is also installed and running
			switch ( get_field( "{$this->zxzp->zxza_acf}choose_a_raw_tag_option", lct_o() ) ) {
				case 'wpautop':
					break;


				case 'off':
					remove_filter( 'the_content', 'wpautop' );
					remove_filter( 'the_content', 'wptexturize' );
					break;


				case 'old':
					remove_filter( 'the_content', 'wpautop' );
					remove_filter( 'the_content', 'wptexturize' );
					add_filter( 'the_content', [ $this, 'wpautop_disable' ], 99 );
					break;


				case 'new':
					remove_filter( 'the_content', 'wpautop' );
					remove_filter( 'the_content', 'wptexturize' );
					add_filter( 'the_content', [ $this, 'wpautop_disable_new' ], 1 );
					break;


				default:
			}
		}
	}


	/**
	 * [clear style=""]
	 * add a clear div
	 *
	 * @param $a
	 *
	 * @return bool|string
	 */
	public function clear( $a ) {
		if ( isset( $a['style'] ) && $a['style'] )
			$r = '<div class="clear" style="' . $a['style'] . '"></div>';
		else
			$r = '<div class="clear"></div>';


		return $r;
	}


	/**
	 * [raw]Content to disable wpautop[/raw]
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function wpautop_disable( $content ) {
		$new_content      = '';
		$pattern_full     = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces           = preg_split( $pattern_full, $content, - 1, PREG_SPLIT_DELIM_CAPTURE );


		foreach ( $pieces as $piece ) {
			if ( preg_match( $pattern_contents, $piece, $matches ) ) {
				$new_content .= $matches[1];
			} else {
				$new_content .= wptexturize( wpautop( $piece ) );
			}
		}

		$new_content = str_replace( [ "[raw]", "[/raw]" ], "", $new_content );


		return $new_content;
	}


	/**
	 * [raw]Content to disable wpautop[/raw]
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function wpautop_disable_new( $content ) {
		if ( strpos( $content, '[raw]' ) === false )
			return wptexturize( wpautop( $content ) );

		$new_content      = '';
		$pattern_full     = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces           = preg_split( $pattern_full, $content, - 1, PREG_SPLIT_DELIM_CAPTURE );


		foreach ( $pieces as $piece ) {
			if ( preg_match( $pattern_contents, $piece, $matches ) )
				$new_content .= $matches[1];
			else
				$new_content .= wptexturize( wpautop( $piece ) );
		}

		$new_content = str_replace( [ "[raw]", "[/raw]" ], "", $new_content );


		return $new_content;
	}


	/**
	 * [{$this->zxzp->zxzu}auto_logout]
	 */
	public function auto_logout() {
		if ( is_user_logged_in() ) {
			$time = time();

			echo '<a id="logout' . $time . '" href="' . wp_logout_url() . '">Logout</a>';

			$script = '<script>
				document.getElementById("logout' . $time . '").click();
			</script>';

			echo $script;
			die();
		}
	}


	/**
	 * [{$this->zxzp->zxzu}jquery_mask]
	 * Add digit mask
	 */
	public function jquery_mask() {
		wp_enqueue_script( "{$this->zxzp->zxzu}jquery_mask", $this->zxzp->plugin_dir_url . 'includes/jquery_mask/jquery_mask.js', [ 'jquery' ] );
	}


	/**
	 * [{$this->zxzp->zxzu}preload]
	 * preload an image or set of images
	 *
	 * @param $a
	 *
	 * @return string
	 */
	public function preload( $a ) {
		extract(
			shortcode_atts(
				[
					'css'    => '',
					'js'     => '',
					'images' => '',
				],
				$a
			)
		);

		$time = current_time( 'timestamp', 1 );

		$html = '';
		$html .= '<div id="' . $this->zxzp->zxzu . 'preload" style="position: fixed;top: 0;left: 0;height: 1px;width: 100px;z-index:9999;opacity: 0.1;"></div>';
		$html .= '<script>';
		$html .= 'jQuery(window).load( function() {';
		$html .= 'setTimeout(function() {';
		if ( ! empty( $css ) ) {
			$tmp = explode( ',', $css );
			foreach ( $tmp as $t ) {
				$html .= 'xhr = new XMLHttpRequest();';
				$html .= 'xhr.open(\'GET\', ' . $t . ');';
				$html .= 'xhr.send(\'\');';
			}
		}

		if ( ! empty( $js ) ) {
			$tmp = explode( ',', $js );
			foreach ( $tmp as $t ) {
				$html .= 'xhr = new XMLHttpRequest();';
				$html .= 'xhr.open(\'GET\', ' . $t . ');';
				$html .= 'xhr.send(\'\');';
			}
		}

		if ( ! empty( $images ) ) {
			$tmp = explode( ',', $images );
			$i   = 1;
			foreach ( $tmp as $t ) {
				$html .= 'jQuery("#' . $this->zxzp->zxzu . 'preload").append(\'<img id="image_' . $time . '_' . $i . '" src="' . $t . '" style="height: 1px;width: 1px;"></div>\');';
				$i ++;
			}
		}
		$html .= '}, 1000 );';

		$html .= 'setTimeout(function() {';
		$html .= 'jQuery("#' . $this->zxzp->zxzu . 'preload").hide();';
		$html .= '}, 1200 );';
		$html .= '});';
		$html .= '</script>';


		return $html;
	}


	/**
	 * [{$this->zxzp->zxzu}read_more]
	 * create a read more hidden div
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return string
	 */
	public function read_more( $a, $content ) {
		wp_register_script( "{$this->zxzp->zxzu}read_more", $this->zxzp->plugin_dir_url . 'assets/js/' . $this->zxzp->zxzu . 'read_more.min.js', [ 'jquery' ] );
		wp_print_scripts( "{$this->zxzp->zxzu}read_more" );

		$html[] = '';
		$id     = '0';

		if ( ! empty( $a['id'] ) )
			$id = $a['id'];

		if ( ! empty( $content ) )
			$content = do_shortcode( $content );

		if ( empty( $a['text'] ) )
			$a['text'] = 'Read More...';

		if ( $a['type'] != 'content' )
			$html[] = "<a class='read_more_button read_more_button_{$id} {$a['class']}' href='#' data-read_class='{$id}'>{$a['text']}</a>";

		if ( $a['type'] != 'button' )
			$html[] = "<div class='read_more_button_copy read_more_button_{$id}_copy' style='display: none;'>{$content}</div>";


		return implode( '', $html );
	}


	/**
	 * [{$this->zxzp->zxzu}amp]
	 * Place an & where HTML encoding is sensitive
	 *
	 * @return string
	 */
	public function amp() {
		return '&';
	}


	/**
	 * [get_directions]
	 * Generate a get directions link
	 *
	 * @param $a
	 *
	 * @return string
	 */
	public function get_directions( $a ) {
		$url   = '/contact/directions/';
		$text  = 'Get Directions';
		$class = '';


		if ( class_exists( 'acf' ) ) { //We can only process this function is acf is also installed and running
			if ( $get_directions = get_field( "{$this->zxzp->zxzu}get_directions", lct_o() ) )
				$url = get_permalink( $get_directions );

			if ( $get_directions = get_field( "{$this->zxzp->zxza_acf}get_directions", lct_o() ) )
				$url = $get_directions;
		}


		if ( ! empty( $a['text'] ) )
			$text = $a['text'];

		if ( ! empty( $a['class'] ) )
			$class = "class=\"{$a['class']}\"";

		$onclick = lct_get_gaTracker_onclick( 'get_directions', $url );

		$return = sprintf( '<a href="%s" target="_blank" %s %s>%s</a>', $url, $onclick, $class, $text );


		return $return;
	}


	/**
	 * [{$this->zxzp->zxzu}current_user_can]
	 * Sometimes you just need to know if the current_user_can() anywhere.
	 * //TODO: cs - This can be improved by using global $wp_roles - 4/3/2016 2:31 AM
	 *
	 * @param $a      array(
	 *                cap => WP capabilities
	 *                )
	 *
	 * @return array|bool|string
	 */
	public function current_user_can( $a ) {
		if ( ! isset( $a['cap'] ) )
			return false;

		$caps = explode( ',', $a['cap'] );

		$can_view = 0;

		foreach ( $caps as $cap_key => $cap ) {
			$role = str_replace( lct_get_role_cap_prefixes(), '', $cap );

			$caps[ $cap_key ] = $role;

			if ( current_user_can( $role ) )
				$can_view ++;
		}

		if ( ! $can_view )
			$caps[] = 'hidden-imp';

		$caps = implode( ' ', $caps );


		return $caps;
	}


	/**
	 * [is_user_logged_in][/is_user_logged_in]
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return array|bool|string
	 */
	public function is_user_logged_in( $a, $content = null ) {
		if (
			! is_user_logged_in() ||
			(
				isset( $a['logged_out'] ) && is_user_logged_in()
			)
		) {
			$content = '';
		}


		return $content;
	}


	/**
	 * [{$this->zxzp->zxzu}get_the_title]
	 *
	 * @param $a
	 *
	 * @return array|bool|string
	 */
	public function get_the_title( $a ) {
		global $post;

		$title = '';


		if ( isset( $a['id'] ) )
			$post_id = $a['id'];
		else if ( ! empty( $post ) )
			$post_id = $post->ID;
		else
			$post_id = '';


		if ( $post_id )
			$title = get_the_title( $post_id );


		return $title;
	}


	/**
	 * [{$this->zxzp->zxzu}get_the_permalink]
	 *
	 * @param $a
	 *
	 * @return array|bool|string
	 */
	public function get_the_permalink( $a ) {
		global $post;

		$permalink = '';


		if ( isset( $a['id'] ) )
			$post_id = $a['id'];
		else if ( ! empty( $post ) )
			$post_id = $post->ID;
		else
			$post_id = '';


		if ( $post_id )
			$permalink = get_the_permalink( $post_id );


		return $permalink;
	}


	/**
	 * [theme_chunk id=""]
	 * Get the post_content from your theme_chunk
	 *
	 * @param $a
	 *
	 * @return string
	 */
	public function theme_chunk( $a ) {
		if ( ! empty( $a['id'] ) ) {
			$theme_chunk         = get_post( $a['id'] );
			$theme_chunk_content = $theme_chunk->post_content;
		} else {
			$theme_chunk_content = '';
		}

		if ( ! isset( $a['dont_check'] ) )
			$theme_chunk_content = lct_check_for_nested_shortcodes( $theme_chunk_content );

		if ( ! isset( $a['dont_sc'] ) )
			$theme_chunk_content = do_shortcode( $theme_chunk_content );


		return $theme_chunk_content;
	}


	/**
	 * [br]
	 * OR
	 * [{$this->zxzp->zxzu}br]
	 * Place a <br /> where HTML encoding is sensitive
	 *
	 * @return string
	 */
	public function br() {
		return '<br />';
	}


	/**
	 * [br]
	 * OR
	 * [{$this->zxzp->zxzu}br]
	 * Place a <br /> where HTML encoding is sensitive
	 *
	 * @param $a
	 *
	 * @return string
	 */
	public function faicon( $a ) {
		$icon  = '';
		$style = '';
		$class = '';


		if ( $a['style'] )
			$style = "style='{$a['style']}'";

		if ( $a['class'] )
			$class = $a['class'];

		if ( $a['id'] )
			$icon = sprintf( '<i class="fa fa-%s %s" %s></i>', $a['id'], $class, $style );

		if ( $a['link'] ) {
			if ( $a['target'] )
				$target = "target='{$a['target']}'";
			else
				$target = '';

			if ( $a['gatracker_cat'] )
				$onclick = lct_get_gaTracker_onclick( $a['gatracker_cat'], $a['link'] );
			else
				$onclick = '';

			$icon = sprintf( '<a href="%s" %s %s>%s</a>', $a['link'], $target, $onclick, $icon );
		}

		return $icon;
	}


	/**
	 * [pimg_link]
	 * Generate a PIMG link
	 *
	 * @param $a
	 *
	 * @return string
	 */
	public function pimg_link( $a ) {
		$url  = 'https://www.proimpressionsgroup.com';
		$text = 'Pro Impressions Marketing Group';


		if ( ! empty( $a['text'] ) )
			$text = $a['text'];

		$onclick = lct_get_gaTracker_onclick( 'PIMG Link', $url );

		$return = sprintf( '<a href="%s" rel=nofollow" target="_blank" %s>%s</a>', $url, $onclick, $text );


		return $return;
	}


	/**
	 * [{$this->zxzp->zxzu}get_the_id]
	 *
	 * @return array|bool|string
	 */
	public function get_the_ID() {
		global $post;

		$ID = '';


		if ( ! empty( $post ) )
			$ID = $post->ID;


		return $ID;
	}


	/**
	 * [{$this->zxzp->zxzu}get_the_date]
	 *
	 * @param $a
	 *
	 * @return array|bool|string
	 */
	public function get_the_date( $a ) {
		global $post;

		$value = '';


		if ( isset( $a['id'] ) )
			$post_id = $a['id'];
		else if ( ! empty( $post ) )
			$post_id = $post->ID;
		else
			$post_id = '';


		if ( $post_id )
			$value = get_the_date( '', $post_id );


		return $value;
	}


	/**
	 * [{$this->zxzp->zxzu}get_the_modified_date_time]
	 *
	 * @param $a
	 *
	 * @return array|bool|string
	 */
	public function get_the_modified_date_time( $a ) {
		global $post;

		$value = '';


		if ( isset( $a['id'] ) )
			$post_id = $a['id'];
		else if ( ! empty( $post ) )
			$post_id = $post->ID;
		else
			$post_id = '';


		if ( $post_id )
			$value = get_post_modified_time( get_option( 'date_format' ) . ' \@ ' . get_option( 'time_format' ), false, $post_id );


		return $value;
	}
}
