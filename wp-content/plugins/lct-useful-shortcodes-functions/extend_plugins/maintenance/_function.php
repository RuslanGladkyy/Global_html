<?php /*~~~*/
add_action( 'init', 'lct_maintenance_Avada_fix' );
/**
 * Fixes the broken maintenance page for Avada sites
 */
function lct_maintenance_Avada_fix() {
	$maintenance_options = get_option( 'maintenance_options' );


	if ( $maintenance_options['state'] ) {
		if ( ! function_exists( 'is_bbpress' ) ) {
			function is_bbpress() {
				return false;
			}
		}

		if ( ! function_exists( 'is_buddypress' ) ) {
			function is_buddypress() {
				return false;
			}
		}
	}
}
