<?php /*~~~*/

class lct_post_types {
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
		global $g_lct;
		$this->zxzp = $g_lct;

		$this->tmp_use_post_types            = $this->get_post_types();
		$this->tmp_use_post_types_w_statuses = [ ];


		add_filter( 'wp_get_nav_menu_items', [ $this, 'archive_menu_filter' ], 10, 3 );

		add_filter( 'acf/location/rule_values/comment', [ $this, 'register_comment_types_w_acf' ] );


		add_action( 'init', [ $this, 'register_post_types' ], 1 );

		add_action( 'admin_head-nav-menus.php', [ $this, 'inject_archives_menu_meta_box' ] );

		add_action( 'lct_after_register_post_type', [ $this, 'after_register_post_type' ], 10, 2 );
	}


	/**
	 * Return an array of all this plugin's post_types
	 *
	 * @return array
	 */
	public function get_post_types() {
		return [
			"{$this->zxzp->zxzu}theme_chunk",
		];
	}


	/**
	 * Return an array of all this plugin's post_types, Also includes 3rd party post_types that we are controlling with this plugin
	 *
	 * @return array
	 */
	public function get_post_types_all_monitored() {
		return array_merge(
			self::get_post_types(),
			[ ]
		);
	}


	/**
	 * Return an array of all this plugin's post_types that are parents
	 *
	 * @return array
	 */
	public function get_post_types_parents() {
		return [ ];
	}


	/**
	 * Return an array of all this plugin's post_types that are parents, Also includes 3rd party post_types that we are controlling with this plugin
	 *
	 * @return array
	 */
	public function get_post_types_all_parents() {
		return array_merge(
			self::get_post_types_parents(),
			[ ]
		);
	}


	/**
	 * Return an array of all this plugin's comment_types
	 *
	 * @return array
	 */
	public function get_comment_types() {
		return [
			"{$this->zxzp->zxzu}audit",
		];
	}


	/**
	 * Return an array of all this plugin's comment_types, Also includes 3rd party comment_types that we are controlling with this plugin
	 *
	 * @return array
	 */
	public function get_comment_types_all_monitored() {
		return array_merge(
			self::get_comment_types(),
			[ ]
		);
	}


	/**
	 * Register all our awesome post_types
	 */
	public function register_post_types() {
		$this->create_theme_chunk();
	}


	/**
	 * Register all our awesome comment_types with ACF
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function register_comment_types_w_acf( $choices ) {
		$comment_types = self::get_comment_types_all_monitored();


		if ( ! empty( $comment_types ) && ! empty( $choices ) ) {
			foreach ( $comment_types as $comment_type ) {
				$choices[ $comment_type ] = $comment_type;
			}
		}


		return $choices;
	}


	/**
	 * Register
	 */
	public function create_theme_chunk() {
		$slug      = 'theme_chunk';
		$post_type = "{$this->zxzp->zxzu}{$slug}";
		$lowercase = 'theme chunk';


		if ( post_type_exists( $post_type ) )
			return false;


		$labels = [ ];
		$labels = $this->default_labels( $labels, $lowercase );


		$args = [
			"public"              => false,
			"exclude_from_search" => true,
			"publicly_queryable"  => false,
			"show_in_nav_menus"   => false,
			"show_in_admin_bar"   => false,
			"has_archive"         => false,
			"supports"            => [ "editor", "title", "author", "thumbnail" ]
		];
		$args = $this->default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );

		do_action( 'lct_after_register_post_type', $post_type, $this );


		return true;
	}


	/**
	 * Get all the label data that we need to properly register a post_type
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
			'name'               => _x( "{$capitals}", "post type general name" ),
			'singular_name'      => _x( "{$capital}", "post type singular name" ),
			'menu_name'          => _x( "{$capitals}", "admin menu" ),
			'name_admin_bar'     => _x( "{$capital}", "add new on admin bar" ),
			'all_items'          => __( "All {$capitals}" ),
			'add_new'            => _x( "Add New {$capital}", "{$lowercase}" ),
			'add_new_item'       => __( "Add New {$capital}" ),
			'edit_item'          => __( "Edit {$capital}" ),
			'new_item'           => __( "New {$capital}" ),
			'view_item'          => __( "View {$capital}" ),
			'search_items'       => __( "Search {$capitals}" ),
			'not_found'          => __( "No {$lowercases} found." ),
			'not_found_in_trash' => __( "No {$lowercases} found in Trash." ),
			'parent_item_colon'  => __( "Parent {$capitals}:" )
		];
		$labels = wp_parse_args( $custom_labels, $labels );


		return $labels;
	}


	/**
	 * Get all the data that we need to properly register a post_type
	 *
	 * @param array $custom_args
	 * @param null  $slug
	 * @param null  $labels
	 *
	 * @return array
	 */
	public function default_args( $custom_args = [ ], $slug = null, $labels = null ) {
		$args = [
			'labels'               => $labels,
			'description'          => "",
			'public'               => true,
			'exclude_from_search'  => false,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'show_in_nav_menus'    => true,
			'show_in_menu'         => true,
			'show_in_admin_bar'    => true,
			'menu_position'        => null,
			'menu_icon'            => null,
			'capability_type'      => "post",
			//'capabilities' => null,
			'map_meta_cap'         => null,
			'hierarchical'         => false,
			'supports'             => [ "title", "author", "thumbnail", "comments" ],
			'register_meta_box_cb' => null,
			//'taxonomies' => null,
			'has_archive'          => true,
			'rewrite'              => [ "slug" => $slug, "with_front" => true, "feeds" => false ],
			'query_var'            => true,
			'can_export'           => true
		];
		//Custom Args
		/**
		 * lct_following_is_parent bool|true :: true if is parent
		 * lct_following_parent string :: the post_type of the parent
		 */
		$args = wp_parse_args( $custom_args, $args );


		return $args;
	}


	/**
	 * Get all the label data that we need to properly register a post_type status
	 *
	 * @param array  $custom_labels
	 * @param null   $lowercase
	 * @param string $s
	 *
	 * @return array
	 */
	public function status_default_labels( $custom_labels = [ ], $lowercase = null, $s = 's' ) {
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
			"capital"    => $capital,
			"lowercase"  => $lowercase,
			"capitals"   => $capitals,
			"lowercases" => $lowercases,
		];
		$labels = wp_parse_args( $custom_labels, $labels );


		return $labels;
	}


	/**
	 * Get all the data that we need to properly register a post_type status
	 *
	 * @param array $custom_args
	 * @param null  $slug
	 * @param null  $labels
	 *
	 * @return array
	 */
	public function status_default_args( $custom_args = [ ], $slug = null, $labels = null ) {
		$args = [
			'label'                     => _x( $labels['capital'], 'Status General Name' ),
			'public'                    => true,
			'internal'                  => true,
			'exclude_from_search'       => false,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'label_count'               => _n_noop( "{$labels['capital']} (%s)", "{$labels['capitals']} (%s)" ),
		];
		$args = wp_parse_args( $custom_args, $args );


		return $args;
	}


	/**
	 * Do cool things after we register a custom post_type
	 *
	 * @param $post_type
	 * @param $class
	 */
	public function after_register_post_type( $post_type, $class ) {
		//load post_type specific functions, if exists
		if ( file_exists( "{$class->zxzp->plugin_dir_path}admin/plugins_loaded/post_types_{$post_type}.php" ) ) {
			include_once( "{$class->zxzp->plugin_dir_path}admin/plugins_loaded/post_types_{$post_type}.php" );
		}
	}


	/**
	 * Add the menu metabox
	 */
	public function inject_archives_menu_meta_box() {
		add_meta_box( "add-{$this->zxzp->zxza}-archives", 'Archive Pages', [ $this, 'wp_nav_menu_archives_meta_box' ], 'nav-menus', 'side' );
	}


	/**
	 * Render custom post_type archives metabox
	 */
	public function wp_nav_menu_archives_meta_box() {
		/* get custom post types with archive support */
		$post_types = get_post_types( [ 'has_archive' => true ], 'object' );


		/* hydrate the necessary object properties for the walker */
		foreach ( $post_types as &$post_type ) {
			$post_type->classes   = [ ];
			$post_type->type      = $post_type->name;
			$post_type->object_id = $post_type->name;
			$post_type->title     = $post_type->labels->name . ' Archive';
			$post_type->object    = "{$this->zxzp->zxza}-archive";
		}

		$walker = new Walker_Nav_Menu_Checklist( [ ] );
		?>

		<div id="<?php echo $this->zxzp->zxza; ?>-archive" class="posttypediv">
			<div id="tabs-panel-<?php echo $this->zxzp->zxza; ?>-archive" class="tabs-panel tabs-panel-active">
				<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
					<?php
					echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $post_types ), 0, (object) [ 'walker' => $walker ] );
					?>
				</ul>
			</div>
			<!-- /.tabs-panel -->
		</div>
		<p class="button-controls">
			<span class="add-to-menu">
				<img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt=""/>
				<input type="submit"
				       class="button-secondary submit-add-to-menu" value="<?php esc_attr_e( 'Add to Menu' ); ?>"
				       name="add-ctp-archive-menu-item"
				       id="submit-<?php echo $this->zxzp->zxza; ?>-archive"/>
			</span>
		</p>
	<?php }


	/**
	 * Take care of the URLs
	 *
	 * @param $items
	 * @param $menu
	 * @param $args
	 *
	 * @return mixed
	 */
	public function archive_menu_filter( $items, $menu, $args ) {
		/* alter the URL for archive objects */
		foreach ( $items as &$item ) {
			if ( $item->object != "{$this->zxzp->zxza}-archive" )
				continue;
			$item->url = get_post_type_archive_link( $item->type );

			/* set current */
			if ( get_query_var( 'post_type' ) == $item->type ) {
				$item->classes [] = 'current-menu-item';
				$item->current    = true;
			}
		}


		return $items;
	}
}
