<?php /*~~~*/
$dir = dirname( __FILE__ ) . '/';

$this->load_class( 'plugins_loaded', "{$this->zxzu}features_action", '', "{$dir}action/_action.php", 'every' );

include_once( 'class/mail.php' );
//Don't need to load this, We will just load it when we need it. So it does need to be available at all times
// $this->load_class( 'plugins_loaded', "{$this->zxzu}features_class_mail_header", 10, "{$dir}class/mail_header.php", 'every' );

include( 'filter/__init.php' );

include( 'function/__init.php' );

include( 'shortcode/__init.php' );

include( 'debug/functions.php' );
include( 'debug/shortcodes.php' );

include( 'display/field_data.php' );
include( 'display/OLD_fields.php' );
include( 'display/OLD_options.php' );
include( 'display/sel_opts.php' );
include( 'display/checkboxes.php' );

include( 'misc/functions.php' );
