<?php /*~~~*/
add_filter( 'itsec_filter_server_config_file_path', 'lct_itsec_filter_server_config_file_path', 10, 2 );
/**
 * If we are running WP in the /x/ directory let's put the iThemes .htaccess there to, That way it doesn't get in our way in our main .htaccess file. It like to take over and destroy things.
 *
 * @param $file_path
 * @param $file
 *
 * @return string
 */
function lct_itsec_filter_server_config_file_path( $file_path, $file ) {
	$home_path = lct_path_site_wp() . '/';


	if ( strpos( $home_path, '/x/' ) !== false ) {
		$file_path = $home_path . $file;
	}


	return $file_path;
}
