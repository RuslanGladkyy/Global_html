<?php /*~~~*/
include( '_action.php' );
include( '_filter.php' );
include( '_function.php' );
include( '_shortcode.php' );

include( 'legacy.php' );

if ( lct_is_dev() )
	include( 'onetime.php' );
