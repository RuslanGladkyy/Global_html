<?php /*~~~*/
add_action( 'plugins_loaded', [ "{$this->zxzu}admin_onetime", 'init' ] );

class lct_admin_onetime {
	/**
	 * Get the class running
	 */
	public static function init() {
		$class = __CLASS__;
		//global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 */
	public function __construct() {
		//global $g_zxza;
		//$this->zxzp = $g_zxza;


		add_shortcode( 'lct_bulk_post_content_delimit', [ $this, 'bulk_post_content_delimit' ] );
		add_shortcode( 'lct_bulk_post_content_search', [ $this, 'bulk_post_content_search' ] );
	}


	/**
	 * [lct_bulk_post_content_search]
	 */
	function bulk_post_content_search() {
		$return   = [ ];
		$return[] = 'works<br />';

		$post_type = [ 'page' ];

		$hub_finds = [
			'<span(.*?)>(.*?)<\/span>',
		];


		$args  = [
			'posts_per_page' => - 1,
			//'post__in'       => [ 783 ],
			'post_type'      => $post_type,
		];
		$posts = get_posts( $args );
		//P_R_O( $posts );


		foreach ( $posts as $post ) {
			$post_content = $post->post_content;
			foreach ( $hub_finds as $hub_find ) {
				$find = "/{$hub_find}/";

				preg_match( $find, $post_content, $matches );

				if ( isset( $matches[0] ) ) {
					echo $post->ID;
					P_R_O( $matches );
				}

				if ( isset( $matches[0] ) && isset( $matches[2] ) ) {
					$post_content = str_replace( $matches[0], $matches[2], $post_content );

					if ( $post_content ) {
						$args           = [
							'ID'           => $post->ID,
							'post_content' => $post_content
						];
						$update_success = wp_update_post( $args );

						if ( ! is_wp_error( $update_success ) )
							echo 'update_success ' . $post->ID . '<br />';
					}
				}
			}
		}


		exit;

		//return lct_return( $return );
	}


	/**
	 * [lct_bulk_post_content_delimit]
	 */
	function bulk_post_content_delimit() {
		$return = [ ];

		echo 'works<br />';

		$post_type = [ 'page', 'post' ];
		$delimit   = '~~~~~~~~~';

		$hub_finds = [
			'<!--HubSpot Call-to-Action Code-->',
			'<!-- HubSpot Call-to-Action Code -->',
			'<!--HubSpot Call-to-Action Code -->',
			'<!-- HubSpot Call-to-Action Code-->',
			'<!--end HubSpot Call-to-Action Code-->',
			'<!-- end HubSpot Call-to-Action Code -->',
			'<!--end HubSpot Call-to-Action Code -->',
			'<!-- end HubSpot Call-to-Action Code-->',
		];

		$hub_inside_finds = [
			'class="hs-cta-img"',
			'hs-cta-img',
		];

		$args  = [
			'posts_per_page' => - 1,
			'post_type'      => $post_type,
		];
		$posts = get_posts( $args );
		//P_R_O( $posts );

		foreach ( $posts as $post ) {
			$update       = 0;
			$post_content = $post->post_content;
			//echo $post->post_content;

			foreach ( $hub_finds as $hub_find ) {
				if ( strpos( $post_content, $hub_find ) !== false ) {
					$post_content = str_replace( $hub_find, $delimit, $post_content );

					$update ++;
				}
			}

			$content_pieces = explode( $delimit, $post_content );

			foreach ( $content_pieces as $key => $content_piece ) {
				foreach ( $hub_inside_finds as $hub_inside_find ) {
					if ( strpos( $content_piece, $hub_inside_find ) !== false ) {
						unset( $content_pieces[ $key ] );

						$update ++;
					}
				}
			}

			$post_content = implode( $delimit, $content_pieces );

			if ( $update ) {
				$args           = [
					'ID'           => $post->ID,
					'post_content' => $post_content
				];
				$update_success = wp_update_post( $args );

				if ( ! is_wp_error( $update_success ) )
					echo 'update_success ' . $post->ID . '<br />';
			} else {
				$content_pieces = explode( $delimit, $post_content );
				$post_content   = implode( '', $content_pieces );

				$args           = [
					'ID'           => $post->ID,
					'post_content' => $post_content
				];
				$update_success = wp_update_post( $args );

				if ( ! is_wp_error( $update_success ) )
					echo 'removed delimit ' . $post->ID . '<br />';
			}
		}


		return implode( '', $return );
	}
}
