<?php
/*
Plugin Name: BAW Force Plugin Updates
Description: You may need to reinstall any of your plugins, just check the plugins, select "Update", and that's all!
Author: Julio Potier
Author URI: http://boiteaweb.fr
Plugin URI: http://boiteaweb.fr/force-plugin-updates-re-installez-vos-plugins-rapidement-8016.html
Version: 1.0
*/


add_action( 'load-update.php', '__lct_reinstall_plugin', 0 );
function __lct_reinstall_plugin() {
	if (
		isset( $_GET['action'], $_GET['plugins'], $_GET['_wpnonce'] ) &&
		'update-selected' == $_GET['action'] &&
		wp_verify_nonce( $_GET['_wpnonce'], 'bulk-update-plugins' )
	) {
		$plugins = explode( ',', $_GET['plugins'] );
		lct_hack_site_transient_update_plugins( $plugins );
	}

}

function lct_hack_site_transient_update_plugins( $plugin_paths ) {
	$plugin_transient = get_site_transient( 'update_plugins' );

	foreach ( $plugin_paths as $plugin_path ) {
		list( $plugin_folder, $plugin_file ) = explode( '/', $plugin_path );

		if ( $plugin_file ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				include_once( ABSPATH . '/wp-admin/includes/plugin-install.php' );
			}

			$plugin_api = plugins_api(
				'plugin_information',
				[
					'slug'   => $plugin_folder,
					'fields' => [
						'sections'      => false,
						'compatibility' => false,
						'tags'          => false
					]
				]
			);

			$temp_array = [
				'slug'        => $plugin_folder,
				'new_version' => $plugin_api->version,
				'package'     => $plugin_api->download_link
			];

			$temp_object                                = ( object ) $temp_array;
			$plugin_transient->response[ $plugin_path ] = $temp_object;
		}
	}

	set_site_transient( 'update_plugins', $plugin_transient );
}
