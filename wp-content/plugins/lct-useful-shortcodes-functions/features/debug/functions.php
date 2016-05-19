<?php /*~~~*/
if ( ! function_exists( 'P_R' ) ) {
	/**
	 * Used instead of print_r() function. It gives you a better understanding of how array's are laid out.
	 *
	 * @param            $var
	 * @param string     $name
	 * @param bool|false $return
	 *
	 * @return bool|string
	 */
	function P_R( $var, $name = 'Name Not Set', $return = false ) {
		//Let's only continue if the user is an administrator
		if ( ! current_user_can( 'administrator' ) && $_SERVER['SERVER_ADDR'] != '127.0.0.1' )
			return false;

		$skip = [ 'HTTP_COOKIE' ];

		$c  = 'odd';
		$c2 = 'even';

		$h = '';
		$h .= '<table class="P_R" style="max-width: 1000px;width: 100%;margin: 0 auto;">';
		$h .= '<tr><th class="' . $c . '" colspan="2">' . $name . '</th></tr>';

		foreach ( $var as $k => $v ) {
			if ( in_array( $k, $skip ) && $k !== 0 )
				continue;

			if ( $c == 'even' )
				$c = 'odd';
			else
				$c = 'even';

			$h .= '<tr>';
			$h .= '<td class="' . $c . '">';
			$h .= $k;
			$h .= '</td>';
			$h .= '<td class="' . $c . '">';

			if ( is_array( $v ) ) {
				$h .= '<table style="width:100%;margin:0 auto;">';
				foreach ( $v as $k2 => $v2 ) {
					if ( $c2 == 'even' )
						$c2 = 'odd';
					else
						$c2 = 'even';

					$h .= '<tr>';
					$h .= '<td class="' . $c2 . '">';
					$h .= $k2;
					$h .= '</td>';
					$h .= '<td class="' . $c2 . '">';

					if ( is_array( $v2 ) ) {
						$h .= '<pre>';
						$h .= print_r( $v2, true );
						$h .= '</pre>';
					} else
						$h .= $v2;

					$h .= '</td>';
					$h .= '</tr>';
				}

				$h .= '</table>';
			} else
				$h .= $v;

			$h .= '</td>';
			$h .= '</tr>';
		}

		if ( ! $var )
			$h .= '<tr><td class="' . $c . '">none</td></tr>';

		$h .= '</table>';

		$h .= P_R_STYLE();

		if ( $return === true )
			return $h;

		echo $h;

		if ( $return === 'exit' )
			exit;

		return false;
	}
}


if ( ! function_exists( 'P_R_O' ) ) {
	/**
	 * For Objects - Used instead of print_r() function. It gives you a better understanding of how array's are laid out.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	function P_R_O( $var ) {
		//Let's only continue if the user is an administrator
		if ( ! current_user_can( 'administrator' ) && $_SERVER['SERVER_ADDR'] != '127.0.0.1' )
			return false;

		echo '<pre>';
		print_r( $var );
		echo '</pre>';

		return false;
	}
}


if ( ! function_exists( 'P_R_STYLE' ) ) {
	/**
	 * Creates the table styling for the P_R function
	 *
	 * @return string
	 */
	function P_R_STYLE() {
		$style = '<style>
		.P_R p{
			text-align: center;
			margin: 0;
			padding: 0;
		}

		.P_R input[type="file"]{
			border: 1px solid #BBB;
		}

		.P_R td{
			padding: 5px;
			margin: 2px 15px;
		}

		.P_R .even{
			background-color: #aaa;
		}

		.P_R .odd{
			background-color: #ccc;
		}
		</style>';

		return $style;
	}
}


/**
 * A quick solution for echo when debugging.
 *
 * @param        $value
 * @param string $label
 * @param string $position
 * @param string $spacer
 *
 * @return bool
 */
function echo_br( $value, $label = '', $position = 'before', $spacer = ' : ' ) {
	//Let's only continue if the user is an administrator
	if ( current_user_can( 'administrator' ) ) {
		if ( $position == 'before' || $position == 'both' )
			echo '<br />';

		echo $label . $spacer . $value;

		if ( $position == 'after' || $position == 'both' )
			echo '<br />';
	}


	return;
}


/**
 * Store an lct_debug row in the options table
 *
 * @param        $data
 * @param string $extra
 *
 * @return bool
 */
function lct_debug( $data, $extra = '' ) {
	//Let's only continue if the user is an administrator
	if ( ! current_user_can( 'administrator' ) && $_SERVER['SERVER_ADDR'] != '127.0.0.1' )
		return false;

	update_option( "lct_debug" . $extra, $data );

	return false;
}


/**
 * Send lct_debug info to the site's error_log
 *
 * @param $data
 */
function lct_debug_to_error_log( $data ) {
	$caller = array_shift( debug_backtrace() );

	if ( is_array( $data ) )
		error_log( 'lct_debug: ' . end( explode( '/', $caller['file'] ) ) . ':' . $caller['line'] . ' => ' . implode( ',', $data ) );
	else
		error_log( 'lct_debug: ' . end( explode( '/', $caller['file'] ) ) . ':' . $caller['line'] . ' => ' . $data );
}


/**
 * Send lct_debug info to the browser's console
 *
 * @param      $data
 * @param null $label
 *
 * @return bool
 */
function lct_send_to_console( $data, $label = null ) {
	//Let's only continue if the user is an administrator and there is data to process
	if (
		(
			! current_user_can( 'administrator' ) ||
			empty( $data )
		) &&
		$_SERVER['SERVER_ADDR'] != '127.0.0.1'
	)
		return false;

	$console = [ ];

	if ( ! empty( $label ) )
		$label = '( ' . $label . ' ) ';

	if ( is_object( $data ) )
		$data = (array) $data;

	if ( is_array( $data ) ) {
		foreach ( $data as $k => $v ) {
			//Weird woocommerce bug
			if ( strpos( $k, 'product_categories' ) !== false )
				continue;

			if ( is_array( $v ) ) {
				if ( ! empty( $v ) ) {
					$sub_array = '(array) ';

					foreach ( $v as $sub_k => $sub_v ) {
						$sub_array .= '[' . $sub_k . '] = ' . $sub_v;
					}
					$v = $sub_array;
				} else {
					$v = '(array) __EMPTY__';
				}
			}

			if ( $v === '' || ! strlen( $v ) )
				$v = '__EMPTY__';

			$console[] = lct_console_log_sprint( 'lct_debug: ARRAY' . $label . '[' . $k . ']' . ' = ' . $v );
		}
	} else {
		$console[] = lct_console_log_sprint( 'lct_debug: ' . $label . $data );
	}

	$script = implode( '', $console );

	do_action( 'lct_jq_doc_ready_add', $script, 'admin_footer' );

	return false;
}


/**
 * Process a variable to it can be added to the console
 *
 * @param $data
 *
 * @return string
 */
function lct_console_log_sprint( $data ) {
	if ( $data )
		return sprintf( "console.log('%s');", $data );
	else
		return false;
}


/**
 * Start a function timer
 * You can also use this directly
 * $lct_timer      = microtime( true );
 * $lct_timer      = number_format( microtime( true ) - $lct_timer, 8 );
 */
function lct_timer_start() {
	global $lct_timer;

	$lct_timer = microtime( true );
}


/**
 * Stop a function timer and display the data
 *
 * @param string $type :: console or print
 * @param string $track_type
 */
function lct_timer_end( $type = 'console', $track_type = 'function' ) {
	global $lct_timer;
	$debug = debug_backtrace();


	$lct_timer = number_format( microtime( true ) - $lct_timer, 8 );


	switch ( $type ) {
		case 'console':
			lct_send_to_console( $lct_timer . ' Seconds', $debug[1][ $track_type ] . '() Run Time' );
			break;

		case 'print':
			printf( "<p>%s() Run Time :: %0.9f Seconds</p>", $debug[1][ $track_type ], $lct_timer );
			exit;
			break;
	}
}
