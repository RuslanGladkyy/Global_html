<?php /*~~~*/
/**
 * Get the site's upload directory path
 * PATH
 *
 * @return mixed
 */
function lct_path_up() {
	$wp_upload_dir = wp_upload_dir();

	return $wp_upload_dir['basedir'];
}


/**
 * Same as lct_path_up()
 * PATH
 *
 * @return mixed
 */
function lct_up_path() {
	return lct_path_up();
}


/**
 * Get the site's path
 * PATH
 *
 * @return mixed
 */
function lct_path_site() {
	return $_SERVER['DOCUMENT_ROOT'];
}


/**
 * Get the site's path
 * PATH
 *
 * @return string
 */
function lct_path_site_wp() {
	return str_replace( '\\', '/', rtrim( rtrim( ABSPATH, '/' ), '\\' ) );
}


/**
 * Get the child theme's path
 * PATH
 *
 * @return string
 */
function lct_path_theme() {
	return str_replace( '\\', '/', get_stylesheet_directory() );
}


/**
 * Get the plugin directory path
 * PATH
 *
 * @return array|string
 */
function lct_path_plugin() {
	global $g_lct;


	$path = explode( '/', rtrim( str_replace( '\\', '/', $g_lct->plugin_dir_path ), '/' ) );
	array_pop( $path );
	$path = implode( '/', $path );


	return $path;
}


/**
 * Get the parent theme's path
 * PATH
 *
 * @return string
 */
function lct_path_theme_parent() {
	return str_replace( '\\', '/', get_template_directory() );
}


/**
 * Get the site's upload directory URL
 * URL
 *
 * @return mixed
 */
function lct_up() {
	$wp_upload_dir = wp_upload_dir();


	return $wp_upload_dir['baseurl'];
}


/**
 * Replaced by lct_up()
 * URL
 *
 * @return mixed
 */
function lct_url_up() {
	return lct_up();
}


/**
 * Get the site's URL
 * URL
 *
 * @return string|void
 */
function lct_url_site() {
	return get_bloginfo( "url" );
}


/**
 * Get the site's root URL
 * URL
 *
 * @return string
 */
function lct_url_root_site() {
	$http = 'http';


	if ( $_SERVER['HTTPS'] == 'on' )
		$http = 'https';


	return $http . '://' . $_SERVER['HTTP_HOST'];
}


/**
 * Get the site's Wordpress URL
 * URL
 *
 * @return string
 */
function lct_url_site_wp() {
	return get_site_url();
}


/**
 * Get the child theme's URL
 * URL
 *
 * @return string
 */
function lct_url_theme() {
	return get_stylesheet_directory_uri();
}


/**
 * Get the plugin directory URL
 * URL
 *
 * @return array|string
 */
function lct_url_plugin() {
	return rtrim( str_replace( '\\', '/', plugin_dir_url( '' ) ), '/' );
}


/**
 * Get the parent theme's URL
 * URL
 *
 * @return string
 */
function lct_url_theme_parent() {
	return get_template_directory_uri();
}
