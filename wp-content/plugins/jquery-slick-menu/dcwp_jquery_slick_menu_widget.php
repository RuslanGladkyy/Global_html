<?php 

class dc_jqslickmenu_widget extends WP_Widget {
    /** constructor */
    function dc_jqslickmenu_widget() {
	
		$name =			'jQuery Slick Menu';
		$desc = 		'Create Sticky Sliding Menus From Any Wordpress Custom Menu';
		$id_base = 		'dc_jqslickmenu_widget';
		$css_class = 	'';
		$alt_option = 	'widget_dcjq_slick_menu_navigation'; 

		$widget_ops = array(
			'classname' => $css_class,
			'description' => __( $desc, 'dcjq-slick-menu' ),
		);
		parent::WP_Widget( 'nav_menu', __('Custom Menu'), $widget_ops );
		
		$this->WP_Widget($id_base, __($name, 'dcjqslickmenu'), $widget_ops);
		$this->alt_option_name = $alt_option;
		
		add_action( 'wp_head', array(&$this, 'styles'), 10, 1 );	
		add_action( 'wp_footer', array(&$this, 'footer'), 10, 1 );	

		$this->defaults = array(
			'title' => '',
			'location' => 'left',
			'offset' => '100',
			'speed' => 'slow',
			'tabText' => 'Click',
			'skin' => 'white'
		);
    }
	
	function widget($args, $instance) {
		extract( $args );
		// Get menu
		
		if(! isset($instance['speed']) ){ $instance['speed'] = 'slow'; }	
		if(! isset($instance['location']) ){ $instance['location'] = 'left'; }
		if(! isset($instance['offset']) ){ $instance['offset'] = '100'; }
		if(! isset($instance['tabText']) ){ $instance['tabText'] = 'Click'; }
		if(! isset($instance['autoClose']) ){ $instance['autoClose'] = ''; }		
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
		$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] );

		if ( !$nav_menu )
			return;
			
		?>
		<div class="dcjq-slick-menu" id="<?php echo $this->id.'-item'; ?>">
		
		<?php 
			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'container' => false ) );
		?>
		
		</div>
		<?php
	}

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		$instance['location'] = $new_instance['location'];
		$instance['skin'] = $new_instance['skin'];
		$instance['speed'] = $new_instance['speed'];
		$instance['autoClose'] = $new_instance['autoClose'];
		$instance['offset'] = (int) strip_tags( stripslashes($new_instance['offset']) );
		$instance['tabText'] = strip_tags( stripslashes($new_instance['tabText']) );
		
		return $instance;
	}

    /** @see WP_Widget::form */
    function form($instance) {
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$location = isset( $instance['location'] ) ? $instance['location'] : '';
		$skin = isset( $instance['skin'] ) ? $instance['skin'] : '';
		$autoClose = isset( $instance['autoClose'] ) ? $instance['autoClose'] : '';
		$speed = isset( $instance['speed'] ) ? $instance['speed'] : '';
		$offset = isset( $instance['offset'] ) ? $instance['offset'] : '';
		$tabText = isset( $instance['tabText'] ) ? $instance['tabText'] : '';
		
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );

		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
	<p>
		<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
		<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
		<?php
			foreach ( $menus as $menu ) {
				$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
				echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
			}
		?>
		</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tabText'); ?>"><?php _e('Tab Text:') ?></label>
		<input type="text" id="<?php echo $this->get_field_id('tabText'); ?>" name="<?php echo $this->get_field_name('tabText'); ?>" value="<?php echo $tabText; ?>" />
	</p>
	<p>
	  <label for="<?php echo $this->get_field_id('location'); ?>"><?php _e( 'Location' , 'dcjq-slick-menu' ); ?></label>
		<select name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_id('location'); ?>" >
			<option value='top-left' <?php selected( $location, 'top-left'); ?> >Top Left</option>
			<option value='top-right' <?php selected( $location, 'top-right'); ?> >Top Right</option>
			<option value='bottom-left' <?php selected( $location, 'bottom-left'); ?> >Bottom Left</option>
			<option value='bottom-right' <?php selected( $location, 'bottom-right'); ?> >Bottom Right</option>
			<option value='right' <?php selected( $location, 'right'); ?> >Right</option>
			<option value='left' <?php selected( $location, 'left'); ?> >Left</option>
		</select>
		</p>
	<p><label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Offset (px):', 'dcjq-slick-menu'); ?>
		<input type="text" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" value="<?php echo $offset; ?>" />
		</label>
	</p>
	<p><label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Animation Speed:', 'dcjq-slick-menu'); ?>
		<select name="<?php echo $this->get_field_name('speed'); ?>" id="<?php echo $this->get_field_id('speed'); ?>" >
			<option value='fast' <?php selected( $speed, 'fast'); ?> >Fast</option>
			<option value='normal' <?php selected( $speed, 'normal'); ?> >Normal</option>
			<option value='slow' <?php selected( $speed, 'slow'); ?> >Slow</option>
		</select>
		</label>
	</p>
	<p>
	  <input type="checkbox" value="true" class="checkbox" id="<?php echo $this->get_field_id('autoClose'); ?>" name="<?php echo $this->get_field_name('autoClose'); ?>"<?php checked( $autoClose, 'true'); ?> />
		<label for="<?php echo $this->get_field_id('autoClose'); ?>"><?php _e( 'Auto-Close Menu' , 'dcjq-slick-menu' ); ?></label>
	</p>
	<p><label for="<?php echo $this->get_field_id('skin'); ?>"><?php _e('Skin:', 'dcjq-slick-menu'); ?>  <?php 
		
		// http://www.codewalkers.com/c/a/File-Manipulation-Code/List-files-in-a-directory-no-subdirectories/

		echo "<select name='".$this->get_field_name('skin')."' id='".$this->get_field_id('skin')."'>";
		echo "<option value='no-theme' ".selected( $skin, 'no-theme', false).">No theme</option>";
			
		//The path to the style directory
		$dirpath = plugin_dir_path(__FILE__) . 'skins/';	
			
		$dh = opendir($dirpath);
		while (false !== ($file = readdir($dh))) {
			//Don't list subdirectories
			if (!is_dir("$dirpath/$file")) {
				//Remove file extension
				$newSkin = htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $file)));
				echo "<option value='$newSkin' ".selected($skin, $newSkin, false).">" . $newSkin . '</option>';
			}
		}
		closedir($dh); 
		echo "</select>"; ?> </label><br />
	</p>
	<div class="widget-control-actions alignright">
		<p><small><a href="http://www.designchemical.com/blog/index.php/wordpress-plugins/wordpress-plugin-jquery-slick-menu-widget/"><?php esc_attr_e('Visit plugin site', 'dcjq-slick-menu'); ?></a></small></p>
	</div>
	
	<?php 
	}
	
	/** Adds ID based slick menu skin to the header. */
	function styles(){
		
		if(!is_admin()){

			$all_widgets = $this->get_settings();
		
			foreach ($all_widgets as $key => $wpdcjqslickmenu){
				$widget_id = $this->id_base . '-' . $key;		
				if(is_active_widget(false, $widget_id, $this->id_base)){
		
					$skin = $wpdcjqslickmenu['skin'];
					$skin = htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $skin)));
					if($skin != 'No-theme'){
						echo "\n\t<link rel=\"stylesheet\" href=\"".dc_jqslickmenu::get_plugin_directory()."/skin.php?widget_id=".$key."&amp;skin=".strtolower($skin)."\" type=\"text/css\" media=\"screen\"  />";
					}
				}
			}
		}
	}

	/** Adds ID based activation script to the footer */
	function footer(){
		
		if(!is_admin()){
		
		$all_widgets = $this->get_settings();
		
		foreach ($all_widgets as $key => $wpdcjqslickmenu){
		
			$widget_id = $this->id_base . '-' . $key;
			
			$slick_id = 'dc-slick-' . $key;

			if(is_active_widget(false, $widget_id, $this->id_base)){
			
				$align = '';
				$location = $wpdcjqslickmenu['location'];
			//	if($location == ''){$location = 'left';};
				if($location == 'top-left'){
					$loc = 'top';
					$align = 'left';
				}
				if($location == 'top-right'){
					$loc = 'top';
					$align = 'right';
				}
				if($location == 'bottom-left'){
					$loc = 'bottom';
					$align = 'left';
				}
				if($location == 'bottom-right'){
					$loc = 'bottom';
					$align = 'right';
				}
				if($location == 'left'){
					$loc = 'left';
					$align = 'top';
				}
				if($location == 'right'){
					$loc = 'right';
					$align = 'top';
				}

				$offset = $wpdcjqslickmenu['offset']."px";
				if($offset == ''){$offset = '100px';};
				
				$autoClose = $wpdcjqslickmenu['autoClose'];
				if($autoClose == ''){$autoClose = 'false';};

				$tabText = $wpdcjqslickmenu['tabText'];
				if($tabText == ''){$tabText = 'Click';};

			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					jQuery('#<?php echo $widget_id.'-item'; ?>').dcSlick({
						location: '<?php echo $loc; ?>',
						align: '<?php echo $align; ?>',
						speed: '<?php echo $wpdcjqslickmenu['speed']; ?>',
						offset: '<?php echo $offset; ?>',
						autoClose: <?php echo $autoClose; ?>,
						tabText: '<?php echo $tabText; ?>',
						idWrapper: '<?php echo $slick_id ?>',
						classOpen: 'dcsl-open',
						classClose: 'dcsl-close',
						classToggle: 'dcsl-link'
					});
				});
			</script>
		
			<?php
			
			}		
		}
		}
	}
} // class dc_jqslickmenu_widget