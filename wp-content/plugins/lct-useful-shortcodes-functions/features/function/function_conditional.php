<?php /*~~~*/
if ( ! function_exists( 'createPath' ) ) {
	/**
	 * Create a long path if it does not exist, returns true if exists or finished creating
	 *
	 * @param        $path
	 * @param null   $startPath
	 * @param string $string
	 *
	 * @return bool
	 */
	function createPath( $path, $startPath = null, $string = "<?php header('Location: /');\n" ) {
		createPathFolders( $path );


		if ( ! $startPath )
			return true;


		$startPath = rtrim( $startPath, "/" );
		$objects   = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $startPath ), RecursiveIteratorIterator::SELF_FIRST );


		foreach ( $objects as $name => $object ) {
			if ( is_dir( $object->getPathname() ) && strpos( $object->getPathname(), '.svn' ) === false && strpos( $object->getPathname(), '..' ) === false )
				$dirs[] = $object->getPathname();
		}


		return false;
	}
}


if ( ! function_exists( 'createPathFolders' ) ) {
	/**
	 * Create the folders - only call in createPath()
	 *
	 * @param $path
	 *
	 * @return bool
	 */
	function createPathFolders( $path ) {
		$path = rtrim( $path, "/" );

		if ( ! $path )
			return false;


		if ( is_dir( $path ) )
			return true;


		$lastPath = substr( $path, 0, strrpos( $path, '/', - 2 ) + 1 );
		$r        = createPath( $lastPath );


		return ( $r && is_writable( $lastPath ) ) ? mkdir( $path, 0755, true ) : false;
	}
}


if ( ! function_exists( 'strpos_array' ) ) {
	/**
	 * Check an array with strpos
	 *
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool
	 */
	function strpos_array( $haystack, $needle ) {
		if ( is_array( $needle ) ) {


			foreach ( $needle as $need ) {
				if ( strpos( $haystack, $need ) !== false )
					return true;
			}


		} else {


			if ( strpos( $haystack, $needle ) !== false )
				return true;


		}


		return false;
	}
}
