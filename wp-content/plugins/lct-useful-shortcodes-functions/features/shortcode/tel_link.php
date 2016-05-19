<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}tel_link", 'init' ], 1000000 );

class lct_tel_link {
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


		add_shortcode( "{$this->zxzp->zxzu}tel_link", [ $this, 'add_shortcode' ] );


		if ( is_admin() && $this->page_supports_add_button() ) {
			add_action( 'init', [ $this, 'admin_register_scripts' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

			add_action( 'print_media_templates', [ $this, 'print_media_templates' ] );

			add_action( 'media_buttons', [ $this, 'add_button' ], 20 );

			add_action( 'admin_footer', [ $this, 'add_mce_popup' ] );
		}
	}


	/**
	 * Load external files that we need
	 */
	public function admin_register_scripts() {
		/** @noinspection PhpIncludeInspection */
		include_once( $this->zxzp->plugin_dir_path . 'available/tooltips.php' );


		wp_register_script(
			"{$this->zxzp->zxzu}tel_link", "{$this->zxzp->plugin_dir_url}assets/wp-admin/js/tel_link.min.js",
			[
				'jquery',
				'wp-backbone'
			],
			false,
			true
		);

		wp_register_style(
			"{$this->zxzp->zxzu}tel_link",
			"{$this->zxzp->plugin_dir_url}assets/wp-admin/css/tel_link.min.css",
			null
		);
	}


	/**
	 * Should we load everything?
	 *
	 * @return bool
	 */
	public function page_supports_add_button() {
		global $pagenow;


		if (
			isset( $pagenow ) &&
			$pagenow &&
			in_array( $pagenow, [ 'post.php', 'page.php', 'page-new.php', 'post-new.php' ] )
		)
			return true;


		return false;
	}


	/**
	 * @param $a
	 * [
	 * phone
	 * action
	 * category
	 * class
	 * pre
	 * post
	 * text
	 * ]
	 *
	 * @return bool|string
	 */
	public function add_shortcode( $a ) {
		if ( empty( $a['phone'] ) )
			return false;


		if ( empty( $a['category'] ) )
			$a['category'] = 'tel_link';


		if ( empty( $a['action'] ) )
			$a['action'] = $a['phone'];


		if ( ! empty( $a['class'] ) )
			$a['class'] = " class=\"{$a['class']}\"";
		else
			$a['class'] = '';


		if ( ! empty( $a['pre'] ) )
			$a['pre'] = "{$a['pre']} ";
		else
			$a['pre'] = '';


		if ( ! empty( $a['post'] ) )
			$a['post'] = " {$a['post']}";
		else
			$a['post'] = '';


		if ( empty( $a['text'] ) )
			$a['text'] = "{$a['pre']}{$a['phone']}{$a['post']}";


		$stripped_phone = preg_replace( '/[^0-9]/', '', $a['phone'] );


		return sprintf( '<a %s %s%s>%s</a>', "href=\"tel:{$stripped_phone}\"", lct_get_gaTracker_onclick( $a['category'], $a['action'], $a['label'] ), $a['class'], $a['text'] );
	}


	/**
	 * Action target that adds the 'Insert a Tel Link' button to the post/page edit screen
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( "{$this->zxzp->zxzu}tel_link" );

		wp_enqueue_style( "{$this->zxzp->zxzu}tel_link" );

		wp_localize_script( "{$this->zxzp->zxzu}tel_link", "{$this->zxzp->zxza}ShortcodeUIData", [
			'shortcodes'      => $this->get_shortcodes(),
			'previewNonce'    => wp_create_nonce( "{$this->zxzp->zxza}tellink-shortcode-ui-preview" ),
			'previewDisabled' => true,
			'strings'         => [
				'pleaseEnterAPhone'   => 'Please enter a phone number.',
				'errorLoadingPreview' => 'Failed to load the preview for this phone number.',
			]
		] );
	}


	/**
	 * Action target that displays the popup to insert a Tel Link to a post/page
	 *
	 * @return array
	 */
	public function get_shortcodes() {
		$atts = [
			[
				'label'       => 'Phone Number',
				'attr'        => 'phone',
				'type'        => 'text',
				'section'     => 'required',
				'description' => 'Be sure to INCLUDE the desired formatting.',
				'tooltip'     => 'Specify the phone number you are creating the link for. Ex: (970) 555-1234 or 970.555.1234'
			],
			[
				'label'       => 'Link Class',
				'attr'        => 'class',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add custom css class to your link'
			],
			[
				'label'       => 'Text before the phone number',
				'attr'        => 'pre',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add some link text before the phone number.'
			],
			[
				'label'       => 'Text after the phone number',
				'attr'        => 'post',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add some link text after the phone number.'
			],
			[
				'label'       => 'Link Text Override',
				'attr'        => 'text',
				'type'        => 'text',
				'description' => 'Use this to override the default Link text that this shortcode creates.',
				'tooltip'     => 'Use this to override the default Link text that this shortcode creates.'
			],
			[
				'label'       => 'GATC Action',
				'attr'        => 'action',
				'type'        => 'text',
				'description' => 'ONLY change this if you do NOT want the action in Google Analytics to be \'{pre} {phone} {post}\'. See tooltip for more info.',
				'tooltip'     => 'See this for more info: <a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEventTracking">Google Analytics Tracking Code: Event Tracking</a>'
			],
			[
				'label'       => 'GATC Category',
				'attr'        => 'category',
				'type'        => 'text',
				'description' => 'ONLY change this if you do NOT want the category in Google Analytics to be \'tel_link\'. See tooltip for more info.',
				'tooltip'     => 'See this for more info: <a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEventTracking">Google Analytics Tracking Code: Event Tracking</a>'
			],
		];

		$shortcode = [
			'shortcode_tag' => "{$this->zxzp->zxzu}tel_link",
			'action_tag'    => '',
			'label'         => 'Tel Link',
			'attrs'         => $atts,
		];


		return [ $shortcode ];
	}


	/**
	 * display button matching new UI
	 */
	public function add_button() {
		echo "<a href=\"#\" class=\"button {$this->zxzp->zxzu}tel_link_media_link\" id=\"add_{$this->zxzp->zxzu}tel_link\" title=\"Add Tel Link\">
			<div>Add Tel Link</div>
		</a>";
	}


	public function add_mce_popup() {
		$popup = "<div id=\"select_{$this->zxzp->zxzu}tel_link\" style=\"display:none;\">
			<div id=\"{$this->zxzp->zxzu}tel_link-shortcode-ui-wrap\" class=\"wrap\">
				<div id=\"{$this->zxzp->zxzu}tel_link-shortcode-ui-container\"></div>
			</div>
		</div>";

		echo $popup;
	}


	public function print_media_templates() {
		$template = "{$this->zxzp->plugin_dir_path}features/tpl/tel_link.tpl.php";

		if ( file_exists( $template ) ) {
			ob_start();

			/** @noinspection PhpIncludeInspection */
			include_once( $template );

			echo ob_get_clean();
		}
	}
}
