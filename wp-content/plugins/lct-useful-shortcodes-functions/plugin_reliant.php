<?php /*~~~*/

class lct_plugin_reliant {
	/**
	 * Get the class running
	 *
	 * @param $zxzp
	 */
	public static function init( $zxzp ) {
		$class = __CLASS__;
		global ${$class};
		${$class} = new $class( $zxzp );
	}


	/**
	 * Setup action and filter hooks
	 *
	 * @param $zxzp
	 */
	public function __construct( $zxzp ) {
		$this->zxzp = $zxzp;


		//Set default vars
		$this->zxzp->doing_ajax = lct_doing();
		$this->set_is_avada_theme_active();


		add_action( 'plugins_loaded', [ $this, 'set_is_Yoast_GA_theme_active' ], 4 );

		add_action( 'lct_ws_menu_editor', [ $this, 'update_ws_menu_editor' ] );

		add_filter( 'lct_editzz_update_files', [ $this, 'edit_zz_update_files' ], 10, 2 );
	}


	/**
	 * Update the ws_menu_editor version (Plugin: admin-menu-editor)
	 */
	public function update_ws_menu_editor() {
		$name   = 'ws_menu_editor';
		$editor = get_option( $name );


		if ( $editor ) {
			$repo_path = "{$this->zxzp->plugin_dir_path}admin/git/_lct_wp/";


			if ( file_exists( $repo_path ) ) {
				$edit_zz_version = $this->get_editzz_version( $repo_path );
			} else {
				$site_path       = str_replace( '/x/', '/', lct_path_site_wp() . '/' );
				$edit_zz_version = $this->get_editzz_version( $site_path );
			}

			if ( $edit_zz_version ) {
				$new_title = "Dashboard {$edit_zz_version}";

				if ( $editor['custom_menu']['tree']['index.php']['menu_title'] != $new_title ) {
					$editor['custom_menu']['tree']['index.php']['menu_title'] = $new_title;

					update_option( $name, $editor );
				}
			}
		}

		return true;
	}


	/**
	 * Pull the newest editzz files and then update the site.
	 * //TODO: cs - Need to add a return that will tell us whether this function was successful or not - 9/19/2015 2:00 PM
	 *
	 * @param bool $current_status
	 * @param bool $force
	 *
	 * @return bool
	 */
	public function edit_zz_update_files( $current_status = false, $force = false ) {
		if (
			! lct_is_dev() &&
			! $force
		) {
			return false;
		}


		if (
			is_callable( 'shell_exec' ) &&
			stripos( ini_get( 'disable_functions' ), 'shell_exec' ) === false
		) {
			$output          = [ ];
			$site_path       = str_replace( '/x/', '/', lct_path_site_wp() . '/' );
			$git_remote_repo = 'ftp://public_git%40eetah.com:The_PW_1123#@eetah.com/';
			$git_path        = "{$this->zxzp->plugin_dir_path}admin/git/";

			$repo      = '_lct_wp';
			$repo_path = $git_path . $repo . '/';
			if ( ! file_exists( $repo_path ) ) {
				$commands   = [ ];
				$commands[] = "cd {$git_path}";
				$commands[] = "git clone {$git_remote_repo}{$repo}.git";
				$output[]   = "git clone {$repo}.git\n";
				$output[]   = shell_exec( implode( ' && ', $commands ) );
			}

			if ( file_exists( $repo_path ) ) {
				$commands   = [ ];
				$commands[] = "cd {$repo_path}";
				$commands[] = 'git pull';
				$output[]   = shell_exec( implode( ' && ', $commands ) );

				$updated_files = $this->editzz_file_update( $site_path, $repo_path );

				if ( $updated_files || $force ) {
					$files = [
						'apps/',
						'x/',
						'zzFiles/',
						'.gitignore',
						'.htaccess',
						'robots.txt',
					];
					$this->copy_files( $site_path, $repo_path, $files );
				}
			}

			$repo           = '_lct_root';
			$repo_path      = $git_path . $repo . '/';
			$root_site_path = lct_path_site() . '/zzFiles/___root/';
			if ( ! file_exists( $repo_path ) ) {
				$commands   = [ ];
				$commands[] = "cd {$git_path}";
				$commands[] = "git clone {$git_remote_repo}{$repo}.git";
				$output[]   = "git clone {$repo}.git\n";
				$output[]   = shell_exec( implode( ' && ', $commands ) );
			}

			if ( file_exists( $repo_path ) && file_exists( $root_site_path ) ) {
				$commands   = [ ];
				$commands[] = "cd {$repo_path}";
				$commands[] = 'git pull';
				$output[]   = shell_exec( implode( ' && ', $commands ) );

				$updated_files = $this->editzz_file_update( $root_site_path, $repo_path );

				if ( $updated_files || $force ) {
					$files = [
						'.bash_profile',
						'.bashrc',
						'.gitconfig',
						'.gitignore',
					];
					$this->copy_files( $root_site_path, $repo_path, $files );
				}
			}


			return true;
		}


		return false;
	}


	/**
	 * Store whether Avada theme is active or not
	 */
	public function set_is_avada_theme_active() {
		$theme    = wp_get_theme();
		$themes[] = $theme->__get( 'name' );
		$themes[] = $theme->__get( 'parent_theme' );


		if ( in_array( 'Avada', $themes ) ) {
			$this->zxzp->Avada = true;

			$theme = wp_get_theme( 'Avada' );
			if ( $theme->__get( 'Version' ) )
				$this->zxzp->Avada_version = $theme->__get( 'Version' );
		}
	}


	/**
	 * Store whether Yoast GA plugin is active or not
	 */
	public function set_is_Yoast_GA_theme_active() {
		if ( class_exists( 'Yoast_GA_Frontend' ) ) {
			$this->zxzp->Yoast_GA               = true;
			$this->zxzp->Yoast_GA_option_yst_ga = get_option( 'yst_ga' );

			if (
				$this->zxzp->Yoast_GA &&
				! empty( $this->zxzp->Yoast_GA_option_yst_ga ) &&
				$this->zxzp->Yoast_GA_option_yst_ga['ga_general']['enable_universal']
			) {
				$this->zxzp->Yoast_GA_universal = true;
			}
		}
	}


	/**
	 * Copies files or folders
	 *
	 * @param $site_path
	 * @param $repo_path
	 * @param $files
	 *
	 * @return bool
	 */
	public function copy_files( $site_path, $repo_path, $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $repo_path . $file ) ) {
				if ( is_dir( $repo_path . $file ) ) {
					$this->recurse_copy( $repo_path . $file, $site_path . $file );
				} else {
					copy( $repo_path . $file, $site_path . $file );
				}
			}
		}


		return true;
	}


	/**
	 * Copies files or folders
	 *
	 * @param $src
	 * @param $dst
	 */
	public function recurse_copy( $src, $dst ) {
		$dir = opendir( $src );
		@mkdir( $dst );


		while( false !== ( $file = readdir( $dir ) ) ) {
			if ( ( $file != '.' ) && ( $file != '..' ) ) {
				if ( is_dir( $src . '/' . $file ) ) {
					$this->recurse_copy( $src . '/' . $file, $dst . '/' . $file );
				} else {
					copy( $src . '/' . $file, $dst . '/' . $file );
				}
			}
		}


		closedir( $dir );
	}


	/**
	 * Copies content of master editzz to site editzz
	 *
	 * @param $site_path
	 * @param $repo_path
	 *
	 * @return bool|mixed
	 */
	public function editzz_file_update( $site_path, $repo_path ) {
		$_editzz_scanned_site = array_diff( scandir( $site_path ), [ '..', '.' ] );
		$_editzz_files        = preg_grep( "/_editzz-(.*?).txt/", $_editzz_scanned_site );

		if ( ! empty( $_editzz_files ) )
			$_editzz_files = array_values( $_editzz_files );


		$_editzz_scanned_repo = array_diff( scandir( $repo_path ), [ '..', '.' ] );
		$_editzz_files_repo   = preg_grep( "/_editzz-(.*?).txt/", $_editzz_scanned_repo );

		if ( ! empty( $_editzz_files_repo ) )
			$_editzz_files_repo = array_values( $_editzz_files_repo );


		if ( file_exists( $site_path . $_editzz_files[0] ) && $_editzz_files[0] != $_editzz_files_repo[0] ) {
			file_put_contents( $site_path . $_editzz_files[0], file_get_contents( $repo_path . $_editzz_files_repo[0] ) );

			$version = str_replace( [ '_editzz-', '.txt' ], '', $_editzz_files_repo[0] );

			if ( $version )
				return $version;

			return true;
		} else {
			return false;
		}
	}


	/**
	 * Get the current version of editzz
	 *
	 * @param $repo_path
	 *
	 * @return mixed|string
	 */
	public function get_editzz_version( $repo_path ) {
		$version = '';

		$_editzz_scanned_repo = array_diff( scandir( $repo_path ), [ '..', '.' ] );
		$_editzz_files_repo   = preg_grep( "/_editzz-(.*?).txt/", $_editzz_scanned_repo );


		if ( ! empty( $_editzz_files_repo ) ) {
			$_editzz_files_repo = array_values( $_editzz_files_repo );

			$version = str_replace( [ '_editzz-', '.txt' ], '', $_editzz_files_repo[0] );
		}


		return $version;
	}
}


/**
 * Check if we are running this site as a dev site
 *
 * @return bool
 */
function lct_is_dev() {
	if ( defined( 'LCT_DEV' ) && LCT_DEV == 1 )
		return true;


	return false;
}


/**
 * Check if we are running this site as a sandbox site
 *
 * @return bool
 */
function lct_is_sandbox() {
	$server = $_SERVER['HTTP_HOST'];


	if (
		strpos( $server, 'dev.' ) !== false ||
		strpos( $server, 'new.' ) !== false ||
		strpos( $server, 'sandbox.' ) !== false ||
		strpos( $server, 'sb.' ) !== false
	) {
		return true;
	}


	return false;
}


/**
 * Check if we are running this site as a dev site
 *
 * @return bool
 */
function lct_is_dev_or_sb() {
	if (
		lct_is_dev() ||
		lct_is_sandbox()
	)
		return true;


	return false;
}


/**
 * Check if we are running this site as a dev site
 *
 * @return bool
 */
function lct_is_wpall() {
	if ( $_SERVER['HTTP_HOST'] == 'www.wpall.eetah.com.eetah.com' )
		return true;


	return false;
}


/**
 * Just tired of having to remember how to check DOING_AJAX
 *
 * @param string $doing
 *
 * @return bool
 */
function lct_doing( $doing = 'AJAX' ) {
	if ( $doing == 'AJAX' ) {
		if ( defined( 'DOING_' . $doing ) && DOING_AJAX )
			return true;
	}


	return false;
}


if (
	lct_is_dev() ||
	lct_is_sandbox()
) {
	add_filter( 'option_siteurl', 'lct_use_lct_dev_url' );
	add_filter( 'option_home', 'lct_use_lct_dev_url' );
	/**
	 * When we are in dev we want WP to use our HTTP_HOST not the one in the DB
	 *
	 * @param $url
	 *
	 * @return string
	 */
	function lct_use_lct_dev_url( $url ) {
		global $lct_option_siteurl, $lct_option_home;
		$current_filter = current_filter();


		if ( $current_filter == 'option_siteurl' ) {
			if ( $lct_option_siteurl )
				$url = $lct_option_siteurl;
			else
				$url = $lct_option_siteurl = lct_use_lct_dev_url_get_host( $url );
		}

		if ( $current_filter == 'option_home' ) {
			if ( $lct_option_home )
				$url = $lct_option_home;
			else
				$url = $lct_option_home = lct_use_lct_dev_url_get_host( $url );
		}


		return $url;
	}


	function lct_use_lct_dev_url_get_host( $url ) {
		$tmp    = explode( '/', $url );
		$tmp[2] = $_SERVER['HTTP_HOST'];
		$url    = implode( '/', $tmp );


		return $url;
	}
}
