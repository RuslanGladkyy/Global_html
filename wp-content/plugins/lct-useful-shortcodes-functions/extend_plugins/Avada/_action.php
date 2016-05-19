<?php /*~~~*/

class lct_Avada_action {
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


		if (
			strpos( $this->zxzp->Avada_version, '1.' ) === 0 ||
			strpos( $this->zxzp->Avada_version, '2.' ) === 0 ||
			strpos( $this->zxzp->Avada_version, '3.' ) === 0
		) {
			add_action( 'plugins_loaded', [ $this, 'early_run_of_options' ], 1000001 );

			add_action( 'avada_save_options', [ $this, 'avada_save_options' ] );
		} else {
			add_action( 'appearance_page_avada_options', [ $this, 'set_is_appearance_page_avada_options' ] );
			add_action( 'shutdown', [ $this, 'avada_theme_options' ] );
		}


		add_action( 'wp_enqueue_scripts', [ $this, 'avada_css' ], 1000 );

		add_action( 'avada_before_body_content', [ $this, 'avada_before_body_content' ] );

		add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );

		add_action( 'admin_init', [ $this, 'remove_image_size' ], 11 );
	}


	/**
	 * remove the full url for the logo when saving
	 */
	public function avada_save_options() {
		$Avada_options = get_option( 'Avada_options' );


		foreach ( $Avada_options as $k => $Avada_option ) {
			if ( ! is_array( $Avada_option ) && ! empty( $Avada_option ) && ( strpos( $Avada_option, '//' ) !== false || strpos( $Avada_option, '/uploads/' ) !== false ) ) {
				$image_path = lct_remove_site_root( $Avada_option );

				$image_path_parts = explode( '.', $image_path );

				$image_path_wo_ext = implode( '.', $image_path_parts );

				if ( file_exists( lct_path_site() . $image_path_wo_ext . '.svg' ) )
					$image_path = $image_path_wo_ext . '.svg';

				$Avada_options[ $k ] = $image_path;
			}
		}


		update_option( 'Avada_options', $Avada_options );
	}


	/**
	 * remove the full url for the logo when saving
	 */
	public function set_is_appearance_page_avada_options() {
		global $lct_is_appearance_page_avada_options;

		$lct_is_appearance_page_avada_options = true;
	}


	/**
	 * remove the full url for the logo when saving
	 */
	public function avada_theme_options() {
		global $lct_is_appearance_page_avada_options;

		if ( empty( $lct_is_appearance_page_avada_options ) )
			return;


		$avada_theme_options = get_option( 'avada_theme_options' );
		$update              = false;
		$fields_to_update    = [
			'logo',
			'logo_retina',
			'sticky_header_logo',
			'sticky_header_logo_retina',
			'mobile_logo',
			'mobile_logo_retina',
			'favicon',
			'iphone_icon',
			'iphone_icon_retina',
			'ipad_icon',
			'ipad_icon_retina',
			'header_bg_image',
			'page_title_bg',
			'page_title_bg_retina',
			'footerw_bg_image',
			'content_bg_image',
			'bg_image',
			'countdown_background_image',
		];


		//Use this to find settings that you need to strip the URL
		//P_R( $avada_theme_options ); exit;


		foreach ( $fields_to_update as $field ) {
			switch ( $field ) {
				case 'none_yet':
					break;


				default:
					if ( ! empty( $avada_theme_options[ $field ]['url'] ) ) {
						$avada_theme_options[ $field ]['url'] = lct_remove_site_root( $avada_theme_options[ $field ]['url'] );

						$update = true;
					}

					if ( ! empty( $avada_theme_options[ $field ]['thumbnail'] ) ) {
						$avada_theme_options[ $field ]['thumbnail'] = lct_remove_site_root( $avada_theme_options[ $field ]['thumbnail'] );

						$update = true;
					}
			}
		}


		if ( $update )
			update_option( 'avada_theme_options', $avada_theme_options );
	}


	/**
	 * ADD Avada stylesheet to front-end
	 */
	public function avada_css() {
		if ( class_exists( 'acf' ) ) { //We can only process this action is acf is also installed and running
			if ( ! get_field( "{$this->zxzp->zxza_acf}disable_avada_css", lct_o() ) ) {
				if (
					strpos( $this->zxzp->Avada_version, '1.' ) === 0 ||
					strpos( $this->zxzp->Avada_version, '2.' ) === 0 ||
					strpos( $this->zxzp->Avada_version, '3.' ) === 0
				) {
					wp_enqueue_style( "{$this->zxzp->zxzu}Avada", "{$this->zxzp->plugin_dir_url}assets/css/extend_plugins/Avada-legacy.min.css", null );
				} else {
					wp_enqueue_style( "{$this->zxzp->zxzu}Avada", "{$this->zxzp->plugin_dir_url}assets/css/extend_plugins/Avada.min.css", null );
				}
			}


			if ( ! get_field( "{$this->zxzp->zxza_acf}enable_avada_css_page_defaults", lct_o() ) )
				wp_enqueue_style( "{$this->zxzp->zxzu}Avada_page_defaults", "{$this->zxzp->plugin_dir_url}assets/css/extend_plugins/Avada-page_defaults.min.css", null );


			if ( get_field( "{$this->zxzp->zxza_acf}page_title_bar_auto", lct_o() ) ) {
				$top    = str_replace( 'px', '', get_field( "{$this->zxzp->zxza_acf}page_title_bar_padding_top", lct_o() ) );
				$bottom = str_replace( 'px', '', get_field( "{$this->zxzp->zxza_acf}page_title_bar_padding_bottom", lct_o() ) );

				if ( strpos( $top, '%' ) === false )
					$top .= 'px';

				if ( strpos( $bottom, '%' ) === false )
					$bottom .= 'px';

				$style = sprintf(
					".fusion-page-title-bar{
						height: auto !important;
						padding-top: %s;
						padding-bottom: %s;
					}",
					$top,
					$bottom
				);

				do_action( 'lct_wp_footer_style_add', $style );
			}
		}
	}


	/**
	 * ADD sandbox bars so that people don't put in content meant for live site.
	 */
	public function avada_before_body_content() {
		$do_it = false;


		if ( get_field( "{$this->zxzp->zxza_acf}show_sandbox_warning", lct_o() ) ) {
			if ( get_field( "{$this->zxzp->zxza_acf}show_sandbox_warning_dev", lct_o() ) && lct_is_dev() ) {
				$do_it = true;
			}

			if ( get_field( "{$this->zxzp->zxza_acf}show_sandbox_warning_sandbox", lct_o() ) && lct_is_sandbox() ) {
				$do_it = true;
			}
		}


		if ( $do_it ) {
			$message = "
						<a href='" . get_field( "{$this->zxzp->zxza_acf}show_sandbox_warning_url", lct_o() ) . "' target='_blank'>
						DEV<br />
						SITE<br />
						DO NOT<br />
						PUT IN<br />
						LIVE<br />
						CHANGES<br />
						GO HERE
						</a>
						";
			$message = $message . $message . $message . $message . $message;

			if ( get_field( "{$this->zxzp->zxza_acf}show_sandbox_warning_side", lct_o() ) ) {
				echo "<div class='{$this->zxzp->zxzu}sandbox {$this->zxzp->zxzu}sandbox_left'>{$message}</div>";
				echo "<div class='{$this->zxzp->zxzu}sandbox {$this->zxzp->zxzu}sandbox_right'>{$message}</div>";
			} else {
				$message = str_replace( '<br />', ' ', $message );

				echo "<div class='{$this->zxzp->zxzu}sandbox {$this->zxzp->zxzu}sandbox_bottom'>{$message}</div>";
			}

			echo "<style>
					.{$this->zxzp->zxzu}sandbox{
						color: #FFFFFF;
					}


					.{$this->zxzp->zxzu}sandbox a{
						color: #FFFFFF;
					}


					.{$this->zxzp->zxzu}sandbox_left{
						z-index:    999999;
						position:   fixed;
						top:        0;
						left:       0;
						background: red;
						height:     100%;
						width:      75px;
						text-align: center;
					}


					.{$this->zxzp->zxzu}sandbox_right{
						z-index:    999999;
						position:   fixed;
						top:        0;
						right:      0;
						background: red;
						height:     100%;
						width:      75px;
						text-align: center;
					}


					.{$this->zxzp->zxzu}sandbox_bottom{
						z-index:    999999;
						position:   fixed;
						bottom:     0;
						background: red;
						height:     55px;
						width:      100%;
						text-align: center;
					}
					</style>";
		}
	}


	/**
	 * There is a bug in Avada and the 10 priority is just not soon enough.
	 */
	public function early_run_of_options() {
		remove_action( 'init', 'of_options' );

		add_action( 'init', 'of_options', 1 );
	}


	/**
	 * Remove image sizes
	 */
	public function remove_image_size() {
		if ( class_exists( 'acf' ) ) { //We can only process this action is acf is also installed and running
			$sizes = get_field( "{$this->zxzp->zxza_acf}disable_image_sizes", lct_o() );


			if ( ! empty( $sizes ) ) {
				foreach ( $sizes as $size ) {
					remove_image_size( $size );
				}
			}
		}
	}
}
