<?php /*~~~*/

class lct_taxonomies {
	/**
	 * Get the class running
	 */
	public static function init() {
		$class = __CLASS__;
		global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 */
	public function __construct() {
		global $g_lct, $lct_post_types;
		$this->zxzp = $g_lct;

		$this->lct_post_types = $lct_post_types;


		add_action( 'init', [ $this, 'register_taxonomies' ], 1 );

		add_action( 'lct_after_register_taxonomy', [ $this, 'after_register_taxonomy' ], 10, 3 );

		add_action( "admin_footer-post.php", [ $this, 'extend_submitdiv_post_status' ] );
		add_action( "admin_footer-post-new.php", [ $this, 'extend_submitdiv_post_status' ] );
	}


	/**
	 * Return an array of all this plugin's taxonomies
	 *
	 * @return array
	 */
	public function get_taxonomies() {
		return [ ];
	}


	/**
	 * Register all our awesome {taxonomies}s with WordPress
	 */
	public function register_taxonomies() {
	}


	/**
	 * Get all the label data that we need to properly register a taxonomy
	 *
	 * @param array  $custom_labels
	 * @param null   $lowercase
	 * @param string $s
	 *
	 * @return array
	 */
	public function default_labels( $custom_labels = [ ], $lowercase = null, $s = 's' ) {
		$capital   = ucwords( $lowercase );
		$lowercase = strtolower( $lowercase );

		if ( $s == 'ies' ) {
			$capitals   = rtrim( $capital, 'y' ) . $s;
			$lowercases = rtrim( $lowercase, 'y' ) . $s;
		} else {
			$capitals   = $capital . $s;
			$lowercases = $lowercase . $s;
		}


		$labels = [
			'name'                       => _x( "{$capitals}", 'taxonomy general name' ),
			'singular_name'              => _x( "{$capital}", 'taxonomy singular name' ),
			'menu_name'                  => _x( "{$capitals}", 'admin menu' ),
			'all_items'                  => __( "All {$capitals}" ),
			'edit_item'                  => __( "Edit {$capital}" ),
			'view_item'                  => __( "View {$capital}" ),
			'update_item'                => __( "Update {$capital}" ),
			'add_new_item'               => __( "Add New {$capital}" ),
			'new_item_name'              => __( "New {$capital} Name" ),
			'parent_item'                => __( "Parent {$capital}" ),
			'parent_item_colon'          => __( "Parent {$capitals}:" ),
			'search_items'               => __( "Search {$capitals}" ),
			'popular_items'              => __( "Popular {$capitals}" ),
			'separate_items_with_commas' => __( "Separate {$lowercases} with commas" ),
			'add_or_remove_items'        => __( "Add or remove {$lowercases}" ),
			'choose_from_most_used'      => __( "Choose from the most used {$lowercases}" ),
			'not_found'                  => __( "No {$lowercases} found." )
		];
		$labels = wp_parse_args( $custom_labels, $labels );


		return $labels;
	}


	/**
	 * Get all the data that we need to properly register a taxonomy
	 *
	 * @param array $custom_args
	 * @param null  $slug
	 * @param null  $labels
	 *
	 * @return array
	 */
	public function default_args( $custom_args = [ ], $slug = null, $labels = null ) {
		$args = [
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_tagcloud'       => true,
			'show_in_quick_edit'  => true,
			'meta_box_cb'         => null,
			'show_admin_column'   => false,
			'description'         => '',
			'hierarchical'        => true,
			//'update_count_callback' => null,
			//'query_var' => true,
			'rewrite'             => [ 'slug' => $slug, 'with_front' => true ],
			//'capabilities' => null,
			'exclude_from_search' => false
			//'sort' => null,
		];
		$args = wp_parse_args( $custom_args, $args );


		return $args;
	}


	/**
	 * Do cool things after we register a custom taxonomy
	 *
	 * @param $taxonomy
	 * @param $post_type
	 * @param $class
	 */
	public function after_register_taxonomy( $taxonomy, $post_type, $class ) {
		//Add Custom statuses to the post_type from a taxonomy designed for statuses
		if ( strpos( $taxonomy, '_status' ) !== false || strpos( $taxonomy, 'status_' ) !== false ) {
			$this->register_post_status( $taxonomy, $post_type, $class );
		}
	}


	/**
	 * Register Status
	 *
	 * @param $taxonomy
	 * @param $post_type
	 * @param $class
	 *
	 * @return bool
	 */
	public function register_post_status( $taxonomy, $post_type, $class ) {
		if ( $this->zxzp->doing_ajax ) //This slows down ajax and is not needed
			return false;


		$tax_args = [
			'hide_empty'   => 0,
			'hierarchical' => 1,
		];
		$terms    = get_terms( $taxonomy, $tax_args );


		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $status ) {
				$lowercase = strtolower( $status->name );
				$slug      = str_replace( [ ' ', '-' ], '_', $lowercase );


				$labels = [ ];
				$labels = $this->lct_post_types->status_default_labels( $labels, $lowercase );

				$existing_post_status = get_post_status_object( $slug );

				if ( ! empty( $existing_post_status ) && is_array( $existing_post_status->post_types ) ) {
					$post_type = array_unique( array_merge( $post_type, $existing_post_status->post_types ) );
				}

				$args = [ 'post_types' => $post_type ];

				if (
					get_field( 'lct:::tax_public', lct_t( $status ) ) != 1 ||
					get_field( 'lct:::tax_status', lct_t( $status ) ) === 0 ||
					get_field( 'lct:::tax_status', lct_t( $status ) ) === '0' ||
					get_field( 'lct:::tax_status', lct_t( $status ) ) === '' ||
					get_field( 'lct:::tax_status', lct_t( $status ) ) === false
				) {
					$args['public'] = false;
				}

				$args = $this->lct_post_types->status_default_args( $args, $slug, $labels );


				register_post_status( $slug, $args );

				$this->lct_post_types->tmp_use_post_types_w_statuses = array_unique( array_merge( $this->lct_post_types->tmp_use_post_types_w_statuses, $post_type ) );
			}
		}


		return true;
	}


	/**
	 * Adds post status to the "submitdiv" Meta Box and post type WP List Table screens
	 */
	public function extend_submitdiv_post_status() {
		global $post_type;


		if ( empty( $post_type ) )
			return;


		// Abort if we're on the wrong post type, but only if we got a restriction
		if ( ! empty( $this->lct_post_types->tmp_use_post_types_w_statuses ) ) {


			if ( ! in_array( $post_type, $this->lct_post_types->tmp_use_post_types_w_statuses ) )
				return;
		}


		// Our post status and post type objects
		global $wp_post_statuses, $post;
		// Get all non-builtin post status and add them as <option>
		$options = $display = '';


		foreach ( $wp_post_statuses as $status ) {
			if ( ! $status->_builtin ) {
				if ( isset( $status->post_types ) && in_array( $post_type, $status->post_types ) ) {
					// Match against the current posts status
					$selected = selected( $post->post_status, $status->name, false );
					// If we one of our custom post status is selected, remember it
					$selected AND $display = $status->label;
					// Build the options
					$options .= sprintf( '<option %s value="%s">%s</option>', $selected, $status->name, $status->label );
				}
			}
		}
		?>

		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var appended = false;
				<?php
				// Add the selected post status label to the "Status: [Name] (Edit)"
				if ( ! empty( $display ) ) :
				?>
				$( '#post-status-display' ).html( '<?php echo $display; ?>' );
				<?php
				endif;

				// Add the options to the <select> element
				?>
				$( '.edit-post-status' ).on( 'click', function() {
					if( !appended ) {
						var select = $( '#post-status-select' ).find( 'select' );
						$( select ).append( "<?php echo $options; ?>" );
						appended = true;
					}
				} );
			} );
		</script>
		<?php
	}
}
