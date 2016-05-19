<?php //Version: 1.4
//Don't load this if wp-textimage-linking-shortcode plugin is installed

//TODO: cs - Add these to the shortcode creator - 6/20/2015 11:53 AM
/*
 * imagetext
 * textimage
 * esc_html
 * url_only
 */
if ( ! function_exists( 'shortcode_textimage' ) ) {
	define( 'LCT_LINK', 'link' );


	function lct_shortcode_link( $a ) {
		foreach ( $a as $k => $v ) {
			if ( strpos( $v, '{{' ) === false && strpos( $v, '}}' ) === false )
				$a[ $k ] = do_shortcode( str_replace( [ "{", "}" ], [ "[", "]" ], $v ) );
		}

		extract(
			shortcode_atts(
				[
					'id'        => null,
					'text'      => null,
					'class'     => null,
					'alt'       => null,
					'title'     => null,
					'rel'       => null,
					'style'     => null,
					'src'       => null,
					'query'     => null,
					'anchor'    => null,
					'onclick'   => null,
					'target'    => null,
					'imagetext' => null,
					'textimage' => null,
					'esc_html'  => null,
					'url_only'  => null,
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

		if ( is_numeric( $id ) ) {
			$url = get_permalink( $id );

			if ( empty( $text ) )
				$text = get_the_title( $id );
		} else {
			$url = $id;
		}

		if ( ! empty( $text ) && $esc_html == 'true' )
			$text = esc_html( $text );

		if ( ! empty( $class ) ) {
			$class = esc_attr( $class );
			$class = " class=\"{$class}\"";
		}

		if ( empty( $alt ) ) {
			$alt = esc_html( $text );
			$alt = " alt=\"{$alt}\"";
		} else {
			$alt = esc_attr( $alt );
			$alt = " alt=\"{$alt}\"";
		}

		if ( ! empty( $title ) ) {
			$title = esc_attr( $title );
			$title = " title=\"{$title}\"";
		}

		if ( ! empty( $rel ) ) {
			$rel = esc_attr( $rel );
			$rel = " rel=\"{$rel}\"";
		}

		if ( ! empty( $style ) ) {
			$style = esc_attr( $style );
			$style = " style=\"{$style}\"";
		}

		if ( ! empty( $src ) && $esc_html == 'true' ) {
			$src = esc_html( $src );
			$src = "src=\"{$src}\"";
		} else if ( ! empty( $src ) ) {
			$src = "src=\"{$src}\"";
		}

		if ( ! empty( $query ) ) {
			$query = "?{$query}";

			$url .= $query;
		}

		if ( ! empty( $anchor ) ) {
			$anchor = "#{$anchor}";

			$url .= $anchor;
		}

		if ( ! empty( $onclick ) ) {
			$onclick = esc_attr( $onclick );
			$onclick = " onclick=\"{$onclick}\"";
		}

		if ( ! empty( $target ) ) {
			$target = esc_attr( $target );
			$target = " target=\"{$target}\"";
		}

		if ( $url_only )
			return $url;

		$href = "href=\"{$url}\"";

		if ( ! empty( $src ) ) {

			if ( $imagetext || $textimage ) {

				if ( $imagetext ) {
					$link = "<a {$href}{$class}{$title}{$rel}{$style}{$onclick}{$target}><img {$src}{$alt} />{$text}</a>";
				} else if ( $textimage ) {
					$link = "<a {$href}{$class}{$title}{$rel}{$style}{$onclick}{$target}>{$text}<img {$src}{$alt} /></a>";
				}

			} else {
				$link = "<a {$href}{$class}{$title}{$rel}{$style}{$onclick}{$target}><img {$src}{$alt} /></a>";
			}

		} else {
			$link = "<a {$href}{$class}{$title}{$rel}{$style}{$onclick}{$target}>{$text}</a>";
		}

		return $link;
	}


	add_shortcode( LCT_LINK, 'lct_shortcode_link' );


	add_action( 'init', 'wptisc_request_handler' );
	function wptisc_request_handler() {
		if ( ! empty( $_GET['tisc_action'] ) ) {
			switch ( $_GET['tisc_action'] ) {
				case 'wptisc_id_lookup':
					wptisc_id_lookup();
					break;

				case 'wptisc_admin_js':
					wptisc_admin_js();
					break;

				case 'wptisc_admin_css':
					wptisc_admin_css();
					die();
					break;
			}
		}
	}


	function wptisc_id_lookup() {
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


	function wptisc_admin_js() {
		header( 'Content-type: text/javascript' ); ?>
		jQuery(function($) {
		$('#wptisc_meta_box a.wptisc_help').click(function() {
		$('#wptisc_meta_box div.wptisc_readme').slideToggle(function() {
		$('#wptisc_id').css('background', '#fff');
		});
		return false;
		});

		$('#wptisc_editor_button').click(function() {
		var id = " id=\"" + $('#wptisc_id').val() + "\"";
		var text = "";
		var link_class = "";
		var alt = "";
		var title = "";
		var rel = "";
		var style = "";
		var src = "";
		var anchor = "";
		var onclick = "";
		var target = "";

		if( $('#wptisc_text').val() )
		text = " text=\"" + $('#wptisc_text').val() + "\"";

		if( $('#wptisc_class').val() )
		link_class = " class=\"" + $('#wptisc_class').val() + "\"";

		if( $('#wptisc_alt').val() )
		alt = " alt=\"" + $('#wptisc_alt').val() + "\"";

		if( $('#wptisc_title').val() )
		title = " title=\"" + $('#wptisc_title').val() + "\"";

		if( $('#wptisc_rel').val() )
		rel = " rel=\"" + $('#wptisc_rel').val() + "\"";

		if( $('#wptisc_style').val() )
		style = " style=\"" + $('#wptisc_style').val() + "\"";

		if( $('#wptisc_src').val() )
		src = " src=\"" + $('#wptisc_src').val() + "\"";

		if( $('#wptisc_anchor').val() )
		anchor = " anchor=\"" + $('#wptisc_anchor').val() + "\"";

		if( $('#wptisc_onclick').val() )
		onclick = " onclick=\"" + $('#wptisc_onclick').val() + "\"";

		if( $('#wptisc_target').val() )
		target = " target=\"" + $('#wptisc_target').val() + "\"";

		window.send_to_editor( "[link" + id + text + link_class + alt + title + rel + style + src + anchor + onclick + target + "]" );

		return false;
		});

		$('#wptisc_id').keyup(function(e) {
		form = $('#wptisc_meta_box');
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
		tisc_action: 'wptisc_id_lookup',
		post_title: term
		},
		function(response) {
		$('#wptisc_meta_box div.live_search_results').html(response).find('li').click(function() {
		$('#wptisc_id').val( $(this).attr( 'class' ) );
		$('#wptisc_extras').show();
		$('#wptisc_extras [id*="wptisc_"]').val('');
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


	add_action( 'admin_enqueue_scripts', 'wptisc_admin_js_call' );


	function wptisc_admin_js_call() {
		wp_enqueue_script( 'wptisc_admin_js', trailingslashit( get_bloginfo( 'url' ) ) . '?tisc_action=wptisc_admin_js', [ 'jquery' ] );
	}


	function wptisc_admin_css() {
		header( 'Content-type: text/css' ); ?>
		#wptisc_meta_box fieldset a.wptisc_help {
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

		#wptisc_meta_box fieldset label {
		display: none;
		}

		#wptisc_meta_box fieldset input {
		width: 235px;
		}

		#wptisc_meta_box .live_search_results {
		position: relative;
		z-index: 500;
		}

		#wptisc_meta_box .live_search_results ul {
		background: #fff;
		list-style: none;
		margin: 0 0 0 1px;
		padding: 0 2px 3px;
		position: absolute;
		width: 230px;
		}

		#wptisc_meta_box .live_search_results ul li {
		border: 1px solid #eee;
		border-top: 0;
		cursor: pointer;
		line-height: 100%;
		margin: 0;
		overflow: hidden;
		padding: 5px;
		}

		#wptisc_meta_box .live_search_results ul li.active,
		#wptisc_meta_box .live_search_results ul li:hover {
		background: #e0edf5;
		font-weight: bold;
		}

		#wptisc_meta_box .live_search_results input {
		width: 200px;
		}

		#wptisc_meta_box div.wptisc_readme {
		display: none;
		}

		#wptisc_meta_box div.wptisc_readme li {
		margin: 0 10px 10px;
		}

		#wptisc_extras input{
		margin-bottom: 6px;
		}
		<?php
		die();
	}


	add_action( 'admin_print_styles', 'wptisc_admin_head' );
	function wptisc_admin_head() {
		echo '<link rel="stylesheet" type="text/css" href="' . trailingslashit( get_bloginfo( 'url' ) ) . '?tisc_action=wptisc_admin_css" />';
	}


	function wptisc_meta_box() { ?>
		<fieldset>
			<p style="margin: 0;text-align: center;"><strong>To start:</strong><br/>Search for a page to create a link.<br/>Or just type in the url if it is external.
			</p>
			<a href="#" class="wptisc_help">?</a>

			<input type="text" name="wptisc_id" id="wptisc_id" placeholder="Search by Page/Post Title..." autocomplete="off"/>

			<div class="live_search_results"></div>

			<div id="wptisc_extras" style="display: none;">
				<p style="margin: 0;text-align: center;">
					<strong>Now:</strong><br/>Add any of the optional features.<br/>And click Send To Content Area.
				</p>

				<input type="text" name="wptisc_text" id="wptisc_text" placeholder="Link Text (optional)"/>
				<input type="text" name="wptisc_class" id="wptisc_class" placeholder="Link Class (optional)"/>
				<input type="text" name="wptisc_alt" id="wptisc_alt" placeholder="Alt Tag (optional)"/>
				<input type="text" name="wptisc_title" id="wptisc_title" placeholder="Title Tag (optional)"/>
				<input type="text" name="wptisc_rel" id="wptisc_rel" placeholder="Rel Tag (optional)"/>
				<input type="text" name="wptisc_style" id="wptisc_style" placeholder="Link Style (optional)"/>
				<input type="text" name="wptisc_src" id="wptisc_src" placeholder="Linked Image src (optional)"/>
				<input type="text" name="wptisc_anchor" id="wptisc_anchor" placeholder="Link Anchor (optional)"/>
				<input type="text" name="wptisc_onclick" id="wptisc_onclick" placeholder="Link onclick (optional)"/>
				<input type="text" name="wptisc_target" id="wptisc_target" placeholder="Link target (optional)"/>

				<p style="margin: 0;text-align: center;">
					<strong>Advanced Attributes:</strong><br/>query, imagetext, textimage, esc_html</p>


				<button class="button button-primary button-large" name="wptisc_editor_button" id="wptisc_editor_button" style="margin: 0 auto; display: block;">Send To Content Area</button>
			</div>

			<?php wptisc_get_readme(); ?>
		</fieldset>
	<?php }


	function wptisc_get_readme() { ?>
		<div class="wptisc_readme">
			<h4>Shortcode Syntax / Customization</h4>

			<p>There are several different ways that you can enter the shortcode:</p>
			<ul>
				<li><code>[link id='123']</code> =
					<code>&lt;a href="{url of post/page #123}">{title of post/page #123}&lt;/a></code>
				</li>
				<li><code>[link id='123' text='<b>my link text</b>']</code> =
					<code>&lt;a href="{url of post/page #123
						}"><b>my link text</b>&lt;/a></code>
				</li>
			</ul>

			<p>You can also add a <code>class</code> or <code>rel</code> attribute to the shortcode, and it will be included in the resulting <code>&lt;a></code> tag:</p>
			<ul>
				<li>
					<code>[link id='123' text='my link text' class='my-class' rel='external']</code> =
					<code>&lt;a href="{url of post/page #123}" class="my-class"
						rel="external">my link text&lt;/a></code>
				</li>
			</ul>

			<h4>Usage</h4>

			<p>Type into the <a href="#" id="wptisc_search_box">search box</a> and posts whose title matches your search will be returned so that you can grab an internal link shortcode for them for
				use in the content of a post / page.</p>

			<p>The shortcode to link to a page looks something like this:</p>

			<p><code>[link id='123']</code></p>

			<p>Add this to the content of a post or page and when the post or page is displayed, this would be replaced with a link to the post or page with the id of 123.</p>

			<p>These internal links are site reorganization-proof, the links will change automatically to reflect the new location or name of a post or page when it is moved.</p>
		</div>
	<?php }


	add_action( 'admin_init', 'wptisc_add_meta_box' );
	function wptisc_add_meta_box() {
		add_meta_box( 'wptisc_meta_box', 'Link Shortcode Creator', 'wptisc_meta_box', 'post', 'side' );
		add_meta_box( 'wptisc_meta_box', 'Link Shortcode Creator', 'wptisc_meta_box', 'page', 'side' );

		$args = [
			'public'   => true,
			'_builtin' => false
		];

		$output     = 'names';
		$post_types = get_post_types( $args, $output );

		if ( count( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				add_meta_box( 'wptisc_meta_box', 'Link Shortcode Creator', 'wptisc_meta_box', $post_type, 'side' );
			}
		}
	}
}
