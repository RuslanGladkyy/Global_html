<?php /*~~~*/

class lct_Avada_override {
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
			! defined( 'LCT_DO_NOT_OVERRIDE_PAGEBUILDER' ) &&
			(
				strpos( $this->zxzp->Avada_version, '1.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '2.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '3.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '4.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '5.' ) === 0 ||
				strpos( $this->zxzp->Avada_version, '6.' ) === 0
			)
		) {
			add_action( 'plugins_loaded', [ $this, 'Fusion_Core_PageBuilder_override' ], 7 );
		}
	}


	/**
	 * Load our own version of the Fusion_Core_PageBuilder class
	 */
	public function Fusion_Core_PageBuilder_override() {
		$post_type = '';


		if ( ! empty( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		} else {
			if ( isset( $_GET['post'] ) && ! empty( $_GET['post'] ) )
				$post_type = get_post_type( $_GET['post'] );
		}


		if (
			empty( $post_type ) ||
			! in_array( $post_type, $this->zxzp->post_types )
		) {
			return;
		}


		if ( ! $this->zxzp->Avada_version != '3.8.8' ) {
			remove_action( 'plugins_loaded', [ 'Fusion_Core_PageBuilder', 'get_instance' ] );
			remove_action( 'after_setup_theme', [ 'Fusion_Core_PageBuilder', 'get_instance' ] );
		}


		include_once( 'fusion-core/admin/class-pagebuilder.php' );
		add_action( 'plugins_loaded', [ "{$this->zxzp->zxzu}Fusion_Core_PageBuilder", 'get_instance' ], 100 );
	}
}
