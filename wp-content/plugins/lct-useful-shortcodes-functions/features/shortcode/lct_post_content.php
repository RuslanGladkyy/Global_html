<?php /*~~~*/
//TODO: cs - Add these to the shortcode creator - 6/20/2015 11:53 AM

/**
 * esc_html
 * Don't load this if lct-post-content-shortcode plugin is installed
 */
if ( ! function_exists( 'lct_post_content_shortcode' ) ) {
	define( 'LCT_POST_CONTENT', 'lct_post_content' );


	function lct_post_content_shortcode( $a ) {
		foreach ( $a as $k => $v ) {
			$a[ $k ] = do_shortcode( str_replace( [ "{", "}" ], [ "[", "]" ], $v ) );
		}

		extract(
			shortcode_atts(
				[
					'id'       => null,
					'esc_html' => null,
				],
				$a
			)
		);

		if ( empty( $id ) )
			return false;

		if ( ! empty( $esc_html ) )
			$esc_html = esc_attr( $esc_html );
		else
			$esc_html = 'true';

		$post_content = get_post( $id );
		$content      = $post_content->post_content;

		if ( $esc_html == 'true' )
			$content = apply_filters( 'the_content', $content );

		return $content;
	}


	add_shortcode( LCT_POST_CONTENT, 'lct_post_content_shortcode' );


	add_action( 'init', 'lctpcs_request_handler' );
	function lctpcs_request_handler() {
		if ( ! empty( $_GET['lctpcs_action'] ) ) {
			switch ( $_GET['lctpcs_action'] ) {
				case 'lctpcs_id_lookup':
					lctpcs_id_lookup();
					break;

				case 'lctpcs_admin_js':
					lctpcs_admin_js();
					break;

				case 'lctpcs_admin_css':
					lctpcs_admin_css();
					die();
					break;
			}
		}
	}


	function lctpcs_id_lookup() {
		global $wpdb;

		$title = stripslashes( $_GET['post_title'] );
		$wild  = '%' . esc_sql( $title ) . '%';

		if ( strpos( $title, 'http:' ) !== false || strpos( $title, 'https:' ) !== false || strpos( $title, '//' ) !== false ) {
			$output = "<ul><li class='{$title}'>{$title}</li></ul>";

			echo $output;
			die();
		}

		$posts = $wpdb->get_results( "
			SELECT *
			FROM $wpdb->posts
			WHERE (
				post_title LIKE '$wild'
				OR post_name LIKE '$wild'
				OR ID = '$title'
			)
			AND post_status = 'publish'
			AND post_type NOT IN ( 'nav_menu_item', 'revision', 'attachment' )
			ORDER BY post_title
			LIMIT 25
		" );

		if ( count( $posts ) ) {
			$output = '<ul>';

			foreach ( $posts as $post ) {
				$title = esc_html( $post->post_title );

				if ( $post->post_type == 'page' ) {
					$extra_info = esc_html( '/' . the_slug( $post->ID ) );
					$extra_info = " <strong>({$extra_info})</strong>";
				} else {
					$post_type  = esc_html( $post->post_type );
					$extra_info = " <strong>({$post_type})</strong>";
				}

				$output .= "<li class='{$post->ID}'>{$title} | ID={$post->ID}{$extra_info}</li>";
			}

			$output .= '</ul>';
		} else {
			$output = '<ul><li>Sorry, no matches.</li></ul>';
		}

		echo $output;
		die();
	}


	function lctpcs_admin_js() {
		header( 'Content-type: text/javascript' ); ?>
		jQuery(function($) {
		$('#lctpcs_editor_button').click(function() {
		var id = " id=\"" + $('#lctpcs_id').val() + "\"";

		window.send_to_editor( "[lct_post_content" + id + "]" );

		return false;
		});

		$('#lctpcs_id').keyup(function(e) {
		form = $('#lctpcs_meta_box');
		term = $(this).val();
		// catch everything except up/down arrow
		switch (e.which) {
		case 27: // esc
		form.find('.live_search_results ul').remove();
		break;
		case 13: // enter
		case 38: // up
		case 40: // down
		break;
		default:
		if (term == '') {
		form.find('.live_search_results ul').remove();
		}
		if (term.length > 2) {
		$.get(
		'<?php echo admin_url( 'index.php' ); ?>',
		{
		lctpcs_action: 'lctpcs_id_lookup',
		post_title: term
		},
		function(response) {
		$('#lctpcs_meta_box div.live_search_results').html(response).find('li').click(function() {
		$('#lctpcs_id').val( $(this).attr( 'class' ) );
		$('#lctpcs_extras').show();
		$('#lctpcs_extras [id*="lctpcs_"]').val('');
		form.find('.live_search_results ul').remove();
		return false;
		});
		},
		'html'
		);
		}
		}
		});
		});
		<?php
		die();
	}


	add_action( 'admin_enqueue_scripts', 'lctpcs_admin_js_call' );


	function lctpcs_admin_js_call() {
		wp_enqueue_script( 'lctpcs_admin_js', trailingslashit( get_bloginfo( 'url' ) ) . '?lctpcs_action=lctpcs_admin_js', [ 'jquery' ] );
	}


	function lctpcs_admin_css() {
		header( 'Content-type: text/css' ); ?>
		#lctpcs_meta_box fieldset a.lctpcs_help {
		background: #f5f5f5;
		border-radius: 6px;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		color: #666;
		display: block;
		font-size: 11px;
		float: right;
		padding: 4px 6px;
		text-decoration: none;
		}

		#lctpcs_meta_box fieldset label {
		display: none;
		}

		#lctpcs_meta_box fieldset input {
		width: 235px;
		}

		#lctpcs_meta_box .live_search_results {
		position: relative;
		z-index: 500;
		}

		#lctpcs_meta_box .live_search_results ul {
		background: #fff;
		list-style: none;
		margin: 0 0 0 1px;
		padding: 0 2px 3px;
		position: absolute;
		width: 230px;
		}

		#lctpcs_meta_box .live_search_results ul li {
		border: 1px solid #eee;
		border-top: 0;
		cursor: pointer;
		line-height: 100%;
		margin: 0;
		overflow: hidden;
		padding: 5px;
		}

		#lctpcs_meta_box .live_search_results ul li.active,
		#lctpcs_meta_box .live_search_results ul li:hover {
		background: #e0edf5;
		font-weight: bold;
		}

		#lctpcs_meta_box .live_search_results input {
		width: 200px;
		}

		#lctpcs_meta_box div.lctpcs_readme {
		display: none;
		}

		#lctpcs_meta_box div.lctpcs_readme li {
		margin: 0 10px 10px;
		}

		#lctpcs_extras input{
		margin-bottom: 6px;
		}
		<?php
		die();
	}


	add_action( 'admin_print_styles', 'lctpcs_admin_head' );
	function lctpcs_admin_head() {
		echo '<link rel="stylesheet" type="text/css" href="' . trailingslashit( get_bloginfo( 'url' ) ) . '?lctpcs_action=lctpcs_admin_css" />';
	}


	function lctpcs_meta_box() { ?>
		<fieldset>
			<p style="margin: 0;text-align: center;">
				<strong>To start:</strong><br/>Search for a page that you would like to pull content from.</p>

			<input type="text" name="lctpcs_id" id="lctpcs_id" placeholder="Search by Page/Post Title..." autocomplete="off"/>

			<div class="live_search_results"></div>

			<div id="lctpcs_extras" style="display: none;">
				<p style="margin: 0;text-align: center;">
					<strong>Now:</strong><br/>Add any of the optional features.<br/>And click Send To Content Area.
				</p>

				<p style="margin: 0;text-align: center;"><strong>Advanced Attributes:</strong><br/>esc_html</p>

				<button class="button button-primary button-large" name="lctpcs_editor_button" id="lctpcs_editor_button" style="margin: 0 auto; display: block;">Send To Content Area</button>
			</div>
		</fieldset>
	<?php }


	add_action( 'admin_init', 'lctpcs_add_meta_box' );
	function lctpcs_add_meta_box() {
		add_meta_box( 'lctpcs_meta_box', 'Other Post Content Grabber', 'lctpcs_meta_box', 'post', 'side' );
		add_meta_box( 'lctpcs_meta_box', 'Other Post Content Grabber', 'lctpcs_meta_box', 'page', 'side' );

		$args = [
			'public'   => true,
			'_builtin' => false
		];

		$output     = 'names';
		$post_types = get_post_types( $args, $output );

		if ( count( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				add_meta_box( 'lctpcs_meta_box', 'Other Post Content Grabber', 'lctpcs_meta_box', $post_type, 'side' );
			}
		}
	}
}
