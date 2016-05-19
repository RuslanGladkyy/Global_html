<?php /*~~~*/
if (
	! function_exists( 'of_options' ) &&
	function_exists( 'of_options_array' ) &&
	(
		strpos( $this->Avada_version, '1.' ) === 0 ||
		strpos( $this->Avada_version, '2.' ) === 0 ||
		strpos( $this->Avada_version, '3.' ) === 0
	)
) {
	/**
	 * OVERRIDE: from /framework/admin/functions/functions.options.php
	 * We don't need this for versions greater than v4.0, see the above conditional
	 */
	function of_options() {
		global $of_options;


		if ( empty( $of_options ) )
			/** @noinspection PhpUndefinedFunctionInspection */
			$of_options = of_options_array();
	}
}
