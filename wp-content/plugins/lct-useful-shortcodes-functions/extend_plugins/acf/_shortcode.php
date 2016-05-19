<?php /*~~~*/

class lct_acf_shortcode {
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

		$this->zxzsc = $this->zxzp->zxza_acf . 'sc::';


		add_shortcode( "{$this->zxzp->zxzu}copyright", [ $this, 'shortcode_copyright' ] );
		add_shortcode( "{$this->zxzp->zxzu}acf_form", [ $this, 'form_shortcode' ] );
		add_shortcode( "{$this->zxzp->zxzu}acf_form_full", [ $this, 'form_full_shortcode' ] );
		add_shortcode( "{$this->zxzp->zxzu}acf_repeater_items", [ $this, 'repeater_items_shortcode' ] );
		add_shortcode( "{$this->zxzp->zxzu}acf_load_gfont", [ $this, 'load_gfont' ] );
		add_shortcode( "{$this->zxzp->zxzu}acf_load_typekit", [ $this, 'load_typekit' ] );
	}


	/**
	 * Create some copyright text based on the easy to use ACF form
	 *
	 * @return bool|mixed
	 */
	function shortcode_copyright() {
		if ( ! get_field( "{$this->zxzsc}use_this_shortcode", lct_o() ) )
			return false;


		$link_title = get_field( "{$this->zxzsc}link_title", lct_o() );
		$title_link = get_field( "{$this->zxzsc}title_link", lct_o() );

		if ( get_field( "{$this->zxzsc}title_link_blank", lct_o() ) )
			$target = 'target="_blank"';
		else
			$target = '';


		if (
			$link_title &&
			$title_link &&
			(
				! get_field( "{$this->zxzsc}no_single_link", lct_o() ) ||
				(
					get_field( "{$this->zxzsc}no_single_link", lct_o() ) &&
					! is_single()
				)
			)
		) {
			if (
				(
					strpos( $title_link, 'http:' ) !== false ||
					strpos( $title_link, 'https:' ) !== false
				) &&
				strpos( $title_link, lct_url_site() ) === false
			) {
				$onclick = lct_get_gaTracker_onclick( 'Footer Title Link', $title_link );
			} else {
				$onclick = '';
			}

			$title = sprintf( '<a href="%s" %s %s>%s</a>', $title_link, $target, $onclick, get_field( "{$this->zxzsc}title", lct_o() ) );
		} else {
			$title = get_field( "{$this->zxzsc}title", lct_o() );
		}


		if ( get_field( "{$this->zxzsc}use_copyright_layout_multi", lct_o() ) )
			$copyright_layout = get_field( "{$this->zxzsc}copyright_layout_multi", lct_o() );
		else
			$copyright_layout = get_field( "{$this->zxzsc}copyright_layout", lct_o() );


		$find_n_replace = [
			'{copy_symbol}'  => '&copy;',
			'{year}'         => date( 'Y', current_time( 'timestamp', 1 ) ),
			'{title}'        => $title,
			'{builder_plug}' => get_field( "{$this->zxzsc}builder_plug", lct_o() ),
			'{XML_sitemap}'  => "<a href='" . get_field( "{$this->zxzsc}xml", lct_o() ) . "'>XML Sitemap</a>",
			'{privacy}'      => "<a href='" . get_the_permalink( get_field( "{$this->zxzsc}privacy_policy_page", lct_o() ) ) . "'>" . get_the_title( get_field( "{$this->zxzsc}privacy_policy_page", lct_o() ) ) . "</a>",
		];
		$fnr            = lct_create_find_and_replace_arrays( $find_n_replace );


		$copyright = do_shortcode( str_replace( $fnr['find'], $fnr['replace'], do_shortcode( $copyright_layout ) ) );


		return $copyright;
	}


	/**
	 * shortcode for acf_form
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function form_shortcode( $a ) {
		if ( empty( $a['field'] ) )
			return false;


		$options      = [ ];
		$zxza_options = [ ];


		if ( $a['o_field_groups'] )
			$options['field_groups'] = $a['o_field_groups'];

		if ( $a['o_submit_value'] )
			$options['submit_value'] = $a['o_submit_value'];

		$zxza_options['wrapper_class'] = $a['class'];

		$zxza_options['wrapper_id'] = $a['id'];


		return lct_acf_form( $a['field'], $options, $zxza_options, true );
	}


	/**
	 * shortcode for acf_form full
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function form_full_shortcode( $a ) {
		$options      = [ ];
		$zxza_options = [ ];
		$new_post     = [ ];


		foreach ( $a as $k => $v ) {
			if ( in_array( $k, [ 'o_new_post', 'access', 'class', 'id' ] ) )
				continue;

			if ( strpos( $k, 'o_new_' ) !== false ) {
				$k_only = str_replace( 'o_new_', '', $k );

				if ( $a[ $k ] )
					$new_post[ $k_only ] = $a[ $k ];
			} else if ( strpos( $k, 'o_' ) !== false ) {
				$k_only = str_replace( 'o_', '', $k );

				if ( $a[ $k ] )
					$options[ $k_only ] = $a[ $k ];
			} else {
				if ( $a[ $k ] )
					$zxza_options[ $k ] = $a[ $k ];
			}
		}


		if (
			$a['o_new_post'] &&
			empty( $a['o_post_id'] )
		) {
			$options['post_id'] = 'new_post';

			if ( ! isset( $new_post['post_type'] ) )
				$new_post['post_type'] = 'post';


			if ( ! isset( $new_post['post_status'] ) )
				$new_post['post_status'] = 'publish';


			$options['new_post'] = $new_post;
		}


		if ( $a['o_field_groups'] )
			$options['field_groups'] = explode( ',', $a['o_field_groups'] );


		$zxza_options['wrapper_class'] = $a['class'];
		$zxza_options['wrapper_id']    = $a['id'];


		return lct_acf_form_full( $options, $zxza_options, true );
	}


	/**
	 * shortcode for repeater field
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function repeater_items_shortcode( $a ) {
		if ( empty( $a['field'] ) )
			return false;

		$output    = [ ];
		$the_first = '';
		$the_rest  = [ ];


		if ( have_rows( $a['field'] ) ) {
			while( have_rows( $a['field'] ) ) {
				$row = the_row();

				foreach ( $row as $sub_field_key => $sub_field_value ) {
					$sub_field = get_field_object( $sub_field_key );
					$value     = apply_filters( 'lct_get_format_acf_value', get_sub_field( $sub_field_key ), $sub_field );

					if ( ! $the_first )
						$the_first = $value;
					else
						$the_rest[] = sprintf( '<li><strong>%s</strong>: %s</li>', $sub_field['label'], $value );
				}
			}

			$output[] = '<h3>' . $the_first . '</h3>';
			$output[] = '<ul>';
			$output[] = lct_return( $the_rest );
			$output[] = '</ul>';
		}


		return lct_return( $output );
	}


	/**
	 * ADD a single Google Font stylesheet
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function load_gfont( $a ) {
		if ( isset( $a['id'] ) )
			do_action( 'lct_acf_single_load_google_fonts', $a['id'] );


		return;
	}


	/**
	 * ADD a single Adobe Typekit script
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function load_adobe_typekit( $a ) {
		if ( isset( $a['id'] ) )
			do_action( 'lct_acf_single_load_adobe_typekit', $a['id'] );


		return;
	}
}
