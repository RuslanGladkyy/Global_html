<?php /*~~~*/
add_action( 'shutdown', 'lct_after_redirection_apache_save' );
/**
 * Fix the RedirectMatch bug in the redirection plugin
 *
 * @return bool
 */
function lct_after_redirection_apache_save() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'redirection.php' ) {
		$redirection_options = get_option( 'redirection_options' );

		if ( empty( $redirection_options['modules'][2]['location'] ) )
			return false;

		$htaccess_file_path = $redirection_options['modules'][2]['location'];

		if ( ! file_exists( $htaccess_file_path ) )
			return false;


		$htaccess_file = file_get_contents( $htaccess_file_path );

		preg_match_all( "#\# Created by Redirection(.*?)\# End of Redirection#s", $htaccess_file, $redirection_content );
		$redirection_content = $redirection_content[0][0];

		preg_match_all( "#<IfModule mod_rewrite.c>(.*?)</IfModule>#s", $redirection_content, $RewriteRule_lines );
		$RewriteRule_lines = explode( '~~', preg_replace( '/(\r\n|\n)+/', '~~', $RewriteRule_lines[1][0] ) );
		$RewriteRule_lines = array_values( array_filter( $RewriteRule_lines ) );


		foreach ( $RewriteRule_lines as $key => $RewriteRule_line ) {
			if ( strpos( $RewriteRule_line, 'RewriteRule' ) !== false ) {
				preg_match_all( "#\[R\=(.*?),L\]#s", $RewriteRule_line, $redirect_level );

				$RewriteRule_line_working = str_replace( [ 'RewriteRule ', $redirect_level[0][0] ], '', $RewriteRule_line );

				$RewriteRule_line = 'RedirectMatch ' . $redirect_level[1][0] . ' ' . trim( $RewriteRule_line_working );
			}

			$RewriteRule_lines[ $key ] = $RewriteRule_line;
		}


		$version = get_plugin_data( lct_path_plugin() . '/redirection.php' );

		$text[] = '# Created by Redirection';
		$text[] = '# ' . date( 'r' );
		$text[] = '# Redirection ' . trim( $version['Version'] ) . ' - http://urbangiraffe.com/plugins/redirection/';
		$text[] = '# modified by lct_after_redirection_apache_save';
		$text[] = '';

		// mod_rewrite section
		$text[] = '<IfModule mod_rewrite.c>';

		// Add redirects
		$text[] = implode( "\r\n", $RewriteRule_lines );

		// End of mod_rewrite
		$text[] = '</IfModule>';
		$text[] = '';

		// End of redirection section
		$text[] = '# End of Redirection';

		$text = implode( "\r\n", $text );

		file_put_contents( $htaccess_file_path, str_replace( $redirection_content, $text, $htaccess_file ) );
	}

	remove_action( 'shutdown', 'lct_after_redirection_apache_save' );


	return false;
}
