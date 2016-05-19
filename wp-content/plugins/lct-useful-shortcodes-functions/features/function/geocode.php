<?php /*~~~*/
/**
 * function to geocode address, it will return false if unable to geocode address
 *
 * @param            $address
 * @param bool|false $whole_resp
 * @param null       $result_type
 *
 * @return array|bool
 */
function lct_geocode( $address, $whole_resp = false, $result_type = null ) {
	//We can only process this function is acf is also installed and running
	if (
		! class_exists( 'acf' ) ||
		empty( $address )
	)
		return false;


	$address = urlencode( $address );
	$api     = get_field( 'lct:::google_map_api', lct_o() );
	$url     = "https://maps.google.com/maps/api/geocode/json?sensor=false&key={$api}&address={$address}";
	$resp    = json_decode( file_get_contents( $url ), true );

	// response status will be 'OK', if able to geocode given address
	if ( $resp['status'] == 'OK' ) {
		if ( $whole_resp ) {
			if ( $result_type )
				return $resp['results'][0][ $result_type ];


			return $resp['results'][0];
		}

		// get the important data
		$lati              = $resp['results'][0]['geometry']['location']['lat'];
		$longi             = $resp['results'][0]['geometry']['location']['lng'];
		$formatted_address = $resp['results'][0]['formatted_address'];

		// verify if data is complete
		if ( $lati && $longi && $formatted_address ) {
			// put the data in the array
			$data_arr = [ ];

			array_push(
				$data_arr,
				$lati,
				$longi,
				$formatted_address
			);


			return $data_arr;
		} else {
			return false;
		}
	} else if ( $resp['status'] == 'OVER_QUERY_LIMIT' ) {
		lct_send_function_check_email( [ 'function' => 'lct_geocode OVER_QUERY_LIMIT' ] );
	}


	return false;
}


/**
 * parse address component form an address
 *
 * @param      $address
 * @param bool $global
 *
 * @return array
 */
function lct_parse_address_components( $address, $global = false ) {
	if ( empty( $address ) )
		return false;

	if ( $global || $address === true ) {
		global $lct_address_components;


		if ( $lct_address_components )
			return $lct_address_components;
	}

	$whole_resp         = true;
	$result_type        = 'address_components';
	$address_components = [ ];

	$lct_geocode = lct_geocode( $address, $whole_resp, $result_type );

	if ( empty( $lct_geocode ) )
		return false;


	foreach ( $lct_geocode as $tmp ) {
		$address_components[ $tmp['types'][0] ]['long_name']  = $tmp['long_name'];
		$address_components[ $tmp['types'][0] ]['short_name'] = $tmp['short_name'];
	}

	if ( $global )
		$lct_address_components = $address_components;


	return $address_components;
}


/**
 * get just the street address from a whole address
 *
 * @param        $address
 * @param string $type
 *
 * @return string
 */
function lct_get_street_address( $address, $type = 'long_name' ) {
	if ( empty( $address ) )
		return false;

	$comp = lct_parse_address_components( $address );

	if ( empty( $comp ) )
		return false;


	$subpremise = '';


	if ( $comp['subpremise'][ $type ] ) {
		$subpremise_prefix = '';
		$space             = ' ';
		$nospace           = [ '#' ];
		$tmp               = explode( $comp['subpremise'][ $type ] . ',', $address );

		if ( ! $tmp[1] ) {
			$tmp = explode( $comp['subpremise'][ $type ], $address );
		}

		if ( $tmp[1] ) {
			$find_and_replace = [
				PHP_EOL                              => ' ',
				'.'                                  => '',
				$comp['street_number']['long_name']  => '',
				$comp['route']['long_name']          => '',
				$comp['street_number']['short_name'] => '',
				$comp['route']['short_name']         => '',
			];
			$fnr              = lct_create_find_and_replace_arrays( $find_and_replace );

			$tmp[0] = preg_replace( "#\r|\n#", " ", $tmp[0] );
			$tmp[0] = str_replace( $fnr['find'], $fnr['replace'], $tmp[0] );

			$subpremise_prefix = trim( $tmp[0] );

			if ( strpos( $subpremise_prefix, ' ' ) !== false ) {
				$subpremise_prefixt = explode( ' ', $subpremise_prefix );
				$subpremise_prefix  = trim( end( $subpremise_prefixt ) );
			}
		}

		if ( in_array( $subpremise_prefix, $nospace ) )
			$space = '';

		$subpremise = ' ' . $subpremise_prefix . $space . $comp['subpremise'][ $type ];
	}

	$street_address = $comp['street_number'][ $type ] . ' ' . $comp['route'][ $type ] . $subpremise;


	return $street_address;
}


/**
 * get just the city from a whole address
 *
 * @param        $address
 * @param string $type
 *
 * @return string
 */
function lct_get_city( $address, $type = 'long_name' ) {
	if ( empty( $address ) )
		return false;

	$comp = lct_parse_address_components( $address );

	if ( empty( $comp ) )
		return false;

	$city = $comp['locality'][ $type ];


	return $city;
}


/**
 * get just the city from a whole address
 *
 * @param        $address
 * @param string $type
 *
 * @return string
 */
function lct_get_zip( $address, $type = 'long_name' ) {
	if ( empty( $address ) )
		return false;

	$comp = lct_parse_address_components( $address );

	if ( empty( $comp ) )
		return false;

	$city = $comp['postal_code'][ $type ];


	return $city;
}


/**
 * get just the city from a whole address
 *
 * @param        $address
 * @param string $type
 *
 * @return string
 */
function lct_get_state( $address, $type = 'long_name' ) {
	if ( empty( $address ) )
		return false;

	$comp = lct_parse_address_components( $address );

	if ( empty( $comp ) )
		return false;

	$state = $comp['administrative_area_level_1'][ $type ];


	return $state;
}


/**
 * get just the full address from a whole address
 *
 * @param        $address
 * @param string $type
 * @param string $break
 *
 * @return string
 */
function lct_get_full_address( $address, $type = 'long_name', $break = ', ' ) {
	if ( empty( $address ) )
		return false;

	$comp = lct_parse_address_components( $address, true );

	if ( empty( $comp ) )
		return false;

	$address = lct_get_street_address( true, $type ) . $break . lct_get_city( true, $type ) . ', ' . lct_get_state( true, $type ) . ' ' . lct_get_zip( true, $type );


	return $address;
}
