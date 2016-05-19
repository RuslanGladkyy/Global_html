<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}features_shortcode_file_processor", 'init' ] );

class lct_features_shortcode_file_processor {
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


		add_shortcode( "{$this->zxzp->zxzu}css", [ $this, 'processor' ] );
		add_shortcode( 'theme_css', [ $this, 'processor' ] );
		add_shortcode( "{$this->zxzp->zxzu}js", [ $this, 'processor' ] );
		add_shortcode( 'theme_js', [ $this, 'processor' ] );
	}


	/**
	 * [{$this->zxzp->zxzu}css file="{file_name}" write="{whether you want to write the css to the page or just add a link to it}"]
	 * Grab some custom css when this shortcode is called
	 * *
	 * You can also use shortcode:
	 * theme_css
	 *
	 * @param      $a
	 * @param null $content
	 * @param      $shortcode
	 *
	 * @return bool|string
	 */
	public function processor(
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content = null,
		$shortcode
	) {
		if ( empty( $a['file'] ) )
			return false;

		if ( empty( $a['write'] ) )
			$a['write'] = false;


		$get_stylesheet_directory     = get_stylesheet_directory();
		$get_stylesheet_directory_uri = get_stylesheet_directory_uri();


		switch ( $shortcode ) {
			case 'theme_css':
				$type = 'css';
				$base = '/custom/' . $type . '/';
				$path = $get_stylesheet_directory . $base;
				$url  = $get_stylesheet_directory_uri . $base;
				break;


			case "{$this->zxzp->zxzu}css":
				$type = 'css';
				$base = "{$this->zxzp->zxza}/{$type}/";
				$path = $this->zxzp->plugin_dir_path . $base;
				$url  = $this->zxzp->plugin_dir_url . $base;
				break;


			case 'theme_js':
				$type = 'js';
				$base = '/custom/' . $type . '/';
				$path = $get_stylesheet_directory . $base;
				$url  = $get_stylesheet_directory_uri . $base;
				break;


			case "{$this->zxzp->zxzu}js":
				$type = 'js';
				$base = "{$this->zxzp->zxza}/{$type}/";
				$path = $this->zxzp->plugin_dir_path . $base;
				$url  = $this->zxzp->plugin_dir_url . $base;
				break;


			default:
				$type = '';
				$base = '';
				$path = '';
				$url  = '';
		}


		$args   = [
			'file'  => $a['file'],
			'type'  => $type,
			'base'  => $base,
			'path'  => $path,
			'url'   => $url,
			'write' => $a['write']
		];
		$return = $this->shortcode_file_processor( $args );


		return $return;
	}


	/**
	 * Let's get this all processed
	 *
	 * @param $a
	 *
	 * @return bool|string
	 */
	public function shortcode_file_processor( $a ) {
		$return = '';

		$f = [
			'full' => $a['file'] . '.' . $a['type'],
			'min'  => $a['file'] . '.min.' . $a['type'],
		];

		$loc = [
			'min_path'  => $a['path'] . $f['min'],
			'min_url'   => $a['url'] . $f['min'],
			'full_path' => $a['path'] . $f['full'],
			'full_url'  => $a['url'] . $f['full']
		];

		$file_path = $loc['min_path'];
		$file_url  = $loc['min_url'];


		if ( ! file_exists( $loc['min_path'] ) ) {
			if ( ! file_exists( $loc['full_path'] ) )
				return false;

			$file_path = $loc['full_path'];
			$file_url  = $loc['full_url'];
		}


		switch ( $a['type'] ) {
			case 'css' :
				$tag = 'style';
				break;


			case 'js' :
				$tag = 'script';
				break;


			default:
				$tag = '';
		}


		if ( $a['write'] == true ) {
			$return .= "<{$tag}>";
			$return .= file_get_contents( $file_path );
			$return .= "</{$tag}>";
		} else {
			switch ( $a['type'] ) {
				case 'css' :
					$return .= '<link rel="stylesheet" type="text/css" href="' . $file_url . '">';
					break;


				case 'js' :
					$return .= '<script type="text/javascript" src="' . $file_url . '"></script>';
					break;
			}
		}


		return $return;
	}
}
