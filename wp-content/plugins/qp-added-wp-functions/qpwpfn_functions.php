<?php
//Wordpress Function.php Added Functionality

	//redirect logout
	if(get_option(qpwpfn1) == 1) { 
		function qpwpfn_go_home(){
			wp_redirect(home_url());
			exit();
		}
		add_action('wp_logout', 'qpwpfn_go_home');
	}

	//remove admin bar from frontend for all users
	if(get_option(qpwpfn2) == 1){ 
		show_admin_bar( false );	
	}

	//Remove unnecessary meta-data from your WordPress site
	if(get_option(qpwpfn3) == 1){ 
		remove_action( 'wp_head', 'wp_generator' ) ; 
		remove_action( 'wp_head', 'wlwmanifest_link' ) ; 
		remove_action( 'wp_head', 'rsd_link' ) ;
	}

	//Disable HTML in WordPress comments
	if(get_option(qpwpfn4) == 1){ 
		add_filter( 'pre_comment_content', 'wp_specialchars' );
	}

	//Change the Post Auto-Save Interval - 120secs
	if(get_option(qpwpfn5) == 1){ 
		define( 'AUTOSAVE_INTERVAL', 120 );	
	}

	//Hide Errors on the WordPress Login screen
	if(get_option(qpwpfn6) == 1){ 
		function qpwpfn_no_errors_please(){
			$qpwpfn_lge = get_option(qpwpfn_lge);	//get user defined error
			if(!empty($qpwpfn_lge)) {} else {
				$qpwpfn_lge = 'There seems to be a problem, Please try again.';
			}
		  return $qpwpfn_lge;
		}
		add_filter( 'login_errors', 'qpwpfn_no_errors_please' );
	}


// Change URL Slug from Author to Sellers
	if(get_option(qpwpfn7) == 1){ 
		function qpwpfn_new_author_base() {
		global $wp_rewrite;

		$qpwpfn_as = get_option(qpwpfn_as);	//get user defined author slug
		if(!empty($qpwpfn_as)) { } else {
			$qpwpfn_as = 'author';
		}
		$wp_rewrite->author_base = $qpwpfn_as;
		}
		add_action('init', 'qpwpfn_new_author_base');
	}


//Add "Next-page"-button in WYSIYG-editor
	if(get_option(qpwpfn8) == 1){ 
		function qpwpfn_wysiwyg_editor($mce_buttons) {
			$pos = array_search('wp_more',$mce_buttons,true);
			if ($pos !== false) {
				$tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
				$tmp_buttons[] = 'wp_page';
				$mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
			}
			return $mce_buttons;
		}
		add_filter('mce_buttons','qpwpfn_wysiwyg_editor');
	}


//Customize admin message "Howdy" to "Welcome"
	if(get_option(qpwpfn9) == 1){ 
		function qpwpfn_change_howdy($translated, $text, $domain) {
			if (!is_admin() || 'default' != $domain)
			return $translated;
			if (false !== strpos($translated, 'Howdy'))
			$qpwpfn_ch = get_option(qpwpfn_ch);	//get user defined Welcome message
			if (empty($qpwpfn_ch)) {
				$qpwpfn_ch = 'Welcome';
			} else {}
			return str_replace('Howdy', $qpwpfn_ch, $translated);
			return $translated;
		}
		add_filter('gettext', 'qpwpfn_change_howdy', 10, 3);
	}

//Change Your WordPress Feed Links without .htaccess
	if (get_option(qpwpfn10) == 1) {
		function custom_feed_link($output, $feed) {

			$feed_url = get_option(qpwpfn_furl1);

			$feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
			$feed_array[$feed] = $feed_url;
			$output = $feed_array[$feed];

			return $output;
		}

		function other_feed_links($link) {

			$link = get_option(qpwpfn_furl2);
			if (empty($link)) {
				$link = $feed_url;
			}
			return $link;

		}
			//Add our functions to the specific filters
			add_filter('feed_link','custom_feed_link', 1, 2);
			add_filter('category_feed_link', 'other_feed_links');
			add_filter('author_feed_link', 'other_feed_links');
			add_filter('tag_feed_link','other_feed_links');
			add_filter('search_feed_link','other_feed_links');

	}


//Set the number of post revisions available unless the constant was set in wp-config.php

	if(get_option(qpwpfn11) == 1){ 
		if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);
	}


// delay feed update
	if(get_option(qpwpfn12) == 1){ 
		function qpwpfn_publish_later_on_feed($where) {
			global $wpdb;
			if (is_feed()) {
				// timestamp in WP-format
				$now = gmdate('Y-m-d H:i:s');
				// value for wait; + device
				$wait = '15'; // integer
				// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
				$device = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
				// add SQL-sytax to default $where
				$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
			}
			return $where;
		}
		add_filter('posts_where', 'qpwpfn_publish_later_on_feed');
	}

//loading jQuery from the External CDN

	if(get_option(qpwpfn13) == 1){ 
		function qpwpfn_jquery_register() {

			if ( !is_admin() ) {

				wp_deregister_script( 'jquery' );

				$qpwpfn_jq = get_option(qpwpfn_jq);	//get user defined External CDN
				if(empty($qpwpfn_jq)){
					$qpwpfn_jq = 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';	//get user defined Google CDN
				} else {}
				wp_register_script( 'jquery', ( $qpwpfn_jq ), false, null, true );

				wp_enqueue_script( 'jquery' );

			}

		}

		add_action( 'init', 'qpwpfn_jquery_register' );
	}

// CUSTOM ADMIN MENU LINK FOR ALL SETTINGS
	if(get_option(qpwpfn14) == 1){ 
	function qpwpfn_all_settings_link() {
		add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	}
		add_action('admin_menu', 'qpwpfn_all_settings_link');
	}

//Add Custom Dashboard Widgets in WordPress
	if(get_option(qpwpfn15) == 1){ 

		function qpwpfn_custom_dashboard_content() {
			$qpwpfn_cdcon = get_option(qpwpfn_cdcon);	//get user defined custom dashboard help;
			echo $qpwpfn_cdcon;
		}

		function qpwpfn_custom_dashboard_widget() {
			global $wp_meta_boxes;

			$qpwpfn_cdtit = get_option(qpwpfn_cdtit);	//get user defined custom dashboard title

			if (empty($qpwpfn_cdtit)) {
				$qpwpfn_cdtit = 'Theme Support';
			}

			wp_add_dashboard_widget('custom_help_widget', $qpwpfn_cdtit , 'qpwpfn_custom_dashboard_content');
		}

		add_action('wp_dashboard_setup', 'qpwpfn_custom_dashboard_widget');
	}	

//Change the Footer in WordPress Admin Panel
	if(get_option(qpwpfn16) == 1){ 
		function qpwpfn_remove_footer_admin() {
			$qpwpfn_foo = get_option(qpwpfn_foo);

			if(empty($qpwpfn_foo)){
				$qpwpfn_foo = 'Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>';
			}
			echo $qpwpfn_foo;
		}

		add_filter('admin_footer_text', 'qpwpfn_remove_footer_admin');
	}

//Add a Custom Dashboard Logo
	if(get_option(qpwpfn17) == 1){ 

		function qpwpfn_custom_login_logo() {
			$qpwpfn_cl = get_option(qpwpfn_cl);
			echo "
			<style type='text/css'>
			#header-logo { background-image: url('".$qpwpfn_cl."') !important; }
			</style>
			";
		}
		add_action('admin_head', 'qpwpfn_custom_login_logo');
	}

//Remove WordPress Version Number
	if(get_option(qpwpfn18) == 1){ 
		function qpwpfn_remove_version() {
			return '';
		}
		add_filter('the_generator', 'qpwpfn_remove_version');
	}

//Add a Favicon to your Blog
	if(get_option(qpwpfn19) == 1){ 
		function qpwpfn_blog_favicon() {
			$qpwpfn_bf = get_option(qpwpfn_bf);	//get user defined favicon url
			echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.$qpwpfn_bf.'" />';
		}
		add_action('wp_head', 'qpwpfn_blog_favicon');
}

//Change the Default Gravatar in WordPress
	if(get_option( qpwpfn21 ) == 1){
		function qp_custom_gravatar ($avatar) {
			$qpwpfn_gr = get_option(qpwpfn_grim);
			
			if($qpwpfn_gr){
			$custom_avatar = get_option(qpwpfn_grim);
			$avatar[$custom_avatar] = get_option(qpwpfn_grbn);
			if(empty($avatar[$qpwpfn_gr])) {$avatar[$qpwpfn_gr] == 'Custom Avatar';}
			}

			return $avatar;
		}
add_filter( 'avatar_defaults', 'qp_custom_gravatar' ); 
}

//Disable Search in WordPress
	if(get_option(qpwpfn22) == 1){ 
		function qpwpfn_disable_search( $query, $error = true ) {

			if ( is_search() ) {
			$query->is_search = false;
			$query->query_vars[s] = false;
			$query->query[s] = false;

			// to error
			if ( $error == true )
			$query->is_404 = true;
			}
			}

			add_action( 'parse_query', 'qpwpfn_disable_search' );
			add_filter( 'get_search_form', create_function( '$a', "return null;" ) );

		}


?>