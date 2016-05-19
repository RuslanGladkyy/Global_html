=== LCT Useful Shortcodes & Functions ===
Contributors: ircary
Donate link: http://lookclassy.com/
Stable tag: 5.40.14
Requires at least: 4.0
Tested up to: 4.5.2
Tags: Functions, Shortcodes
License: GPLv3 or later
License URI: http://opensource.org/licenses/GPL-3.0

Shortcodes & Functions that will help make your life easier.

== Description ==
Shortcodes & Functions that will help make your life easier.

<h4>Site URL Shortcode == [url_site]</h4>
Use this to get your site URL with a shortcode. EX: if your site was http://www.example.com/, then [url_site] would return http://www.example.com/


<h4>Theme URL Shortcode == [url_theme]</h4>
Use this to get the URL of your theme with a shortcode. This comes in handy when you want to add an image that is stored in your theme folder.

EX: if your site was http://www.example.com/ and your theme folder was my-theme, then [url_theme] would return http://www.example.com/wp-content/themes/my-theme


<h4>Upload folder URL Shortcode == [up]</h4>
Use this to get the URL of your uploads folder with a shortcode. This comes in handy when you want to add an image to a widget that is stored in your uploads folder.

EX: if your site was http://www.example.com/, then [up] would return http://www.example.com/wp-content/uploads


<h4>Upload folder Path Shortcode == [up_root]</h4>
Use this to get the path of your uploads folder with a shortcode. This comes in handy when you want to run file_exists function for an item that is stored in your uploads folder.

EX: if your site was http://www.example.com/ and you public_html folder was located at /home/mysite/public_html/, then [up_root] would return /home/mysite/public_html/wp-content/uploads


<h4>is_blog() Function</h4>
You can call this to check if the page you are on is a blogroll or single post.


<h4>Tel Link Shortcode</h4>
Syntax:
[lct_tel_link phone='{REQUIRED, with formatting}' action='{defaults to "tel_link", but you can change it in the advanced options}' category='{defaults to "{pre} {phone} {post}", but you can change it in the advanced options}' class='{optional}' pre='{optional pre text}' post='{optional post text}' text='{optional link text override}']
converts to
<a class="{class}" href="tel:{phone}" onclick="_gaq.push(['_trackEvent', '{category}', '{action}'])">{pre} {phone} {post}</a>

Examples:
(Basic)
[lct_tel_link phone='(970) 555-1234']
converts to
<a href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'tel_link', '(970) 555-1234'])">(970) 555-1234</a>

(Advanced)
[lct_tel_link phone='(970) 555-1234' action='My Custom Action' category='Something_NOT_tel_link' class='button' pre='before number:' post='after the number.']
converts to
<a class="button" href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'Something_NOT_tel_link', 'My Custom Action'])">before number: (970) 555-1234 after the number.</a>

(Link Text Override)
[lct_tel_link phone='(970) 555-1234' text='Link Text Here']
converts to
<a href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'tel_link', '(970) 555-1234'])">Link Text Here</a>


== Installation ==
1. Upload the zip file contents to your Wordpress plugins directory.
2. Go to the Plugins page in your WordPress Administration area and click 'Activate' for LCT Useful Shortcodes & Functions.

== Screenshots ==
none

== Frequently Asked Questions ==
none


== Upgrade Notice ==
none


== Changelog ==
= 5.40 =
	- Modified: The way plugins_loaded is called in the plugin.
	- Moved: Avada overrides
	- Bug Fix: lct_t()
	- Cleanup: lct_doing()
	- Added: support for Avada v4.0
	- Replaced: deprecated function get_currentuserinfo()
	- Add More Avada CSS Support
	- Avada CSS Cleanup
	- Moved: instant_save.js
	- Added: ACF lct:::enable_avada_css_page_defaults
	- Added: ACF lct:::page_title_bar_auto
	- Added: ACF lct:::page_title_bar_padding_top
	- Added: ACF lct:::page_title_bar_padding_bottom
	- Cleanup: Features Action
	- Added: add_action( 'wp_enqueue_scripts', [ $this, 'always_load_google_fonts' ] );
	- Added: add_action( 'lct_acf_single_load_google_fonts', [ $this, 'single_load_google_fonts' ], 10, 1 );
	- Added: shortcode [{$this->zxzp->zxzu}load_gfont]
	- Added: ACF lct:::load_google_fonts
	- Reinstate lct_theme_chunk()
	- Update: Avada-page_defaults.scss
	- Enhanced: read_more()
	- Cleanup: instant_save.js
	- Cleanup: instant_save.php
	- Bug Fix: For Avada versions that do not have of_options(), causing FATAL error
	- Removed: add_filter( 'wpseo_opengraph_image', 'lct_opengraph_single_image_filter' ); as it was actually causing errors now with the newer version of Yoast SEO
	- Added: Adobe Typekit Support
	- Added: ACF load_typekit field
	- Added: add_action( 'wp_enqueue_scripts', [ $this, 'always_load_typekit' ] );
	- Added: add_action( 'lct_acf_single_load_typekit', [ $this, 'single_load_typekit' ], 10, 1 );
	- Added: Shortcode [{$this->zxzp->zxzu}acf_load_typekit]
	- Bug Fix: Fixed fatal errors caused by ACF not being activated
	- Bug Fix: lct_Avada_override{} on Avada v4.0.x
	- Added: add_filter( 'init', [ $this, 'allow_comments_for_loop_only' ] );
	- Changed Text Domain
	- Added: Avada_clear()
	- Bug Fix: add_filter( 'embed_handler_html', [ $this, 'embed' ], 10, 3 );
	- Added: add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
	- Added: class to [get_directions]

= 5.39 =
	- WP v4.5 Ready
	- Added: lct_features_class_mail{}
	- Updated: lct_send_function_check_email()
	- Removed call_outside/login.php
	- Modified: lct_acf_get_key_taxonomy(), it is now more efficient
	- Modified: lct_acf_get_key_user(), it is now more efficient
	- Code Cleanup: taxonomies.php
	- Added: add_action( "created_term", [ $this, 'created_term' ], 10, 3 );
	- Added: lct_repair_acf_usermeta()
	- Added: lct_repair_acf_postmeta()
	- Added: lct_repair_acf_termmeta()
	- Added: add_filter( "insert_user_meta", [ $this, 'insert_user_meta' ], 10, 3 );
	- Bug Fix: in lct_acf_instant_save{add_comment()}
	- Added: add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 );
	- Modified: update_term_meta(), Added support for repeater fields
	- Added: load_term_option_override()
	- Added: set_term_option_override()
	- Added: do_action( 'lct/acf/before_lct_acf_form_full', $zxza_options, $options );
	- Modified: [lct_acf_form_full]
	- Added: lct_explode_nth()
	- Modified: theme_chunk()
	- Modified: Video embed filter. Made it work much better
	- Bug Fix: get_format_acf_value(); Display Error
	- Modified lct_t(); to allow just a term object
	- Refactored: lct_t()

= 5.38 =
	- lc*a* Cleanup
	- add_shortcode() Cleanup
	- Added: [br]
	- Added: [lct_br]
	- Added: [faicon]
	- gaTracker Code Cleanup
	- Added: ACF lct:::get_directions
	- Added: lct_get_gaTracker_onclick()
	- Added: lct_i_append_dev_sb()
	- Added: lct_i_get_gaTracker_category()
	- Modified [get_directions]
	- Modified shortcode_copyright()
	- Added: Onetime [lct_bulk_post_content_search]
	- Minor Tweaks
	- Improve lct_check_for_nested_shortcodes() for esc_html
	- Added: class to faicon()
	- Allow lct_features_shortcode{} to be accessed outside of plugin
	- CSS Tweak ACF
	- Moved: lct_org() to plugin_reliant{}
	- Added: lct_get_checkboxes()
	- Added: lct_get_checkboxes_get_terms()
	- Added: lct_get_terms()
	- Added: lct_get_comment_meta_field_keys()
	- Error: renamed lct_wc_login_form() to wc_login_form()
	- Error: renamed lct_wc_login_form() to wc_login_form()
	- Moved: lct_get_post_type_slug() to deprecated
	- Modified: lct_get_field_data_array()
	- Added: lct_get_field_data_get_users()
	- Added: lct_get_sel_opts_get_users()
	- Added: lct_get_users()
	- Added: apply_filters( 'lct_field_data_array_obj_label_piece' );
	- Added: lct_acf_get_key_taxonomy()
	- Fixed: Bug in load_field_primary()
	- Fixed: Bug in current_user_can_access()
	- Added [{$this->zxzp->zxzu}get_the_id]
	- Added [{$this->zxzp->zxzu}get_the_modified_date_time]
	- Added [{$this->zxzp->zxzu}get_the_date]
	- Modified lct_acf_instant_save{}, so that lct_audit logs are not saved it a new post is being generated
	- Modified: load_term_meta(), filtering out comments and users now
	- Modified: update_term_meta(), filtering out comments and users now
	- Added: lct_acf_get_key_user()
	- Modified: echo_br()
	- Added: lct_is_new_save_post()
	- Moved lct_doing() to plugin_reliant.php
	- Modified: [Avada_clear]
	- Updated: Includes in extend_plugins/Avada/
	- Added: g_lct{ load_class() }

= 5.37 =
	- Added: add_action( 'edited_term_taxonomy', 'lct_edited_term_taxonomy', 10, 2 );
	- Bug fix: in update_version()
	- Added: add_action( 'avada_before_body_content', 'lct_avada_before_body_content' );
	- Added: add_filter( 'acf/location/rule_values/comment', [ $this, 'register_comment_types_w_acf' ] );
	- Bug Fix: in get_comment_types()
	- Added: ACF Group Audit Type
	- Added: ACF Group Review::: Site Info
	- Added: lct_get_fixes_cleanups_message___lct_review_site_info()
	- Added: lct_get_site_info()
	- Added: lct_return()
	- Added: add_action( 'init', [ $this, 'wp_init' ] );
	- Added: add_filter( 'acf/update_value', [ $this, 'update_term_meta' ], 10, 3 );
	- Added: add_filter( 'acf/load_value', [ $this, 'load_term_meta' ], 10, 3 );
	- Added: add_action( 'acf/save_post', [ $this, 'after_save_post' ], 100001 );
	- Converted to lct_admin_filter{}
	- Converted to lct_admin_action{}
	- Added: add_action( 'admin_init', [ $this, 'cleanup_profile_page' ] );
	- Added: Some ACF Special Functions to handle the lame $post_ids
	- Modified: lct_instant so that we can use it for users
	- Added: google_map_api to ACF General Settings
	- Added: lct_get_full_address()
	- Added: shortcode [lct_acf_repeater_items]
	- Plugin Cleanup
	- Resolved (2): //TO-DO: cs - Maybe we want to narrow this down a bit - 12/7/2015 3:15 PM
	- ACF filter cleanup
	- Moved: lct_get_lct_useful_settings() to deprecated
	- Modified: add_action( 'manage_acf-field-group_posts_custom_column', [ $this, 'field_groups_columns_values' ], 11 );
	- Modified: add_filter( 'acf/get_field_groups', [ $this, 'export_title_mod' ], 11 );
	- Modified: add_filter( 'acf/update_value', [ $this, 'non_ajax_update_value' ], 10, 3 );
	- Added: add_filter( 'post_row_actions', [ $this, 'add_post_id' ], 2, 101 );
	- Added: add_filter( 'media_row_actions', [ $this, 'add_post_id' ], 2, 101 );
	- Added: add_filter( 'page_row_actions', [ $this, 'add_page_id' ], 2, 101 );
	- Added: add_action( 'lct_after_register_post_type', [ $this, 'after_register_post_type' ], 10, 2 );
	- Added: lct_taxonomies{}
	- Added: lct_get_field_data_array()
	- Added: lct_get_sel_opts()
	- Added: lct_get_sel_opts_get_terms()
	- Added: lct_org()
	- Added: lct_status()
	- Added: lct_tax_status()
	- Added: lct_tax_public()
	- Added: lct_tax_published()
	- Added: lct_merge_w_select_blank()
	- Added: lct_following()
	- Added: lct_following_parent()
	- Modified: the slow lct_acf_shortcode{shortcode_copyright()}
	- Added: of_options() Avada Override
	- Fixed: lct_return() (PHP Warning:  implode(): Invalid arguments passed in)
	- Fixed: Bug in add_filter( 'acf/update_value', [ $this, 'update_term_meta' ], 10, 3 );
	- Fixed: Bug in add_filter( 'acf/load_value', [ $this, 'load_term_meta' ], 10, 3 );
	- Added: apply_filters( 'lct_class_conditional_items', $the_items );
	- Added: support for page notes
	- Fixed: bad conditional_logic in support for page notes
	- Added: ACF lct:::disable_image_sizes
	- Added: admin.php?page=lct_cleanup_uploads
	- Added: add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );
	- Added: add_action( 'admin_init', [ $this, 'remove_image_size' ], 11 );
	- Bug Fix: in lct_legacy_rename_option_fields()
	- Added: privacy_policy_page to shortcode_copyright()
	- Minor lct_audit bug fix

= 5.36 =
	- WP v4.4.2 Ready
	- Minor gforms CSS tweak
	- Minor Avada-gforms CSS tweak
	- Fixed Bug: output of shortcode lct_wc_login_form
	- Added lct_check_for_nested_shortcodes()
	- Added add_filter( 'widget_text', [ $this, 'widget_text_final' ], 99999 );
	- Added lct_doing()
	- Added add_shortcode( 'is_user_logged_in', 'lct_sc_is_user_logged_in' );
	- Fixed [embed] for vimeo
	- Added [lct_get_the_title]
	- Added lct_get_order_product_ids()
	- Added lct_get_order_product_id_terms()
	- Added lct_get_term_parent()
	- Added lct_get_terms_parents()
	- Added lct_get_terms_ids()
	- Added [lct_get_the_permalink]
	- Added: lct_check_is_thanks_page()
	- Added: add_action( 'gform_confirmation', [ $this, 'query_string_add' ], 9999, 4 );
	- Modified [lct_tel_link]; Added label support and lct_is_dev() check
	- Added: New ACF Field Group Page Settings [LCT Useful ACF]
	- Added: lct_is_sandbox()
	- Added: lct_is_dev_or_sb()

= 5.35 =
	- Made sure the plugin was ready for Avada v3.9
	- Finally fixed the add_action( 'plugins_loaded', 'lct_Fusion_Core_PageBuilder_override', 2 ); override
	- Clean min CSS
	- Minor CSS
	- Added: Action lct_ws_menu_editor
	- Minor gforms CSS Tweaks
	- lct_ws_menu_editor action fix
	- Minor Bug Fix in field_groups_columns_values()
	- Moved call_outside/login.php
	- Added lct_get_post_type_slug();
	- Better way to explode items in add_filter( 'wp_nav_menu_items', 'lct_wp_nav_menu_items', 10, 2 );
	- autosize tweaks
	- Added: autosize to acf textarea
	- Bug Fix: meta_value was saving in lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
	- Comment Bug
	- Added support for analytics.js when using the Yoast GA Plugin to lct_tel_link shortcode
	- Set all stars in a sub-label to gfield_required

= 5.34 =
	- Converted gforms action functions to class: lct_gforms_action{}
	- Converted gforms filter functions to class: lct_gforms_filter{}
	- Converted gforms shortcode functions to class: lct_gforms_shortcode{}
	- Simplified lct_path_theme()
	- Simplified lct_path_theme_parent()
	- Cleaned up features/function
	- Completed: //TO-DO: cs - get this in an action - 7/29/2015 2:12 PM
	- Converted features action functions to class: lct_features_action{}
	- Cleaned up static shortcodes
	- Replaced lct_path_theme() with get_stylesheet_directory()
	- Code Cleanup
	- Added: lct_gforms_filter::mobile_placeholder()
	- Changed: $form to $gf_form
	- Changed: $field to $gf_field
	- Converted features filter functions to class: lct_features_filter{}
	- Fixed Bug with lct_features_filter::embed()
	- UD lct_Fusion_Core_PageBuilder{}
	- Fixed g_lct::update_version()

= 5.33 =
	- Added: lct_wp_redirect()
	- Added: non-logged in support for lct_acf_instant_save{}

= 5.32 =
	- Improved: lct_jq_doc_ready() and allowed it to work in wp-admin or really any action
	- Cleaned up debug functions
	- Added: lct_timer_start() & lct_timer_end()
	- renamed lct_clean_sb_url() to lct_use_lct_dev_url()
	- Improved: lct_use_lct_dev_url()
	- Added: lct_is_dev()
	- Clean up main class
	- Cleanup lct_tel_link {}
	- Improved: lct_acf_filter::show_admin_bar()

= 5.31 =
	- WP v4.4 Ready

= 5.30 =
	- Cleaned up require and include lines
	- Changed include_once to include
	- Modified lct_acf_form_full() so that it can process new_posts
	- Added: lct_instant_startup()
	- Added: do_action( 'lct_acf_new_post' );
	- lct_jq_doc_ready is now allowed to run in wp_head and wp_footer
	- Made lct_instant buttons hide a little quicker

= 5.29 =
	- Modified: add_filter( 'acf/load_field', 'lct_acf_load_field_primary' );
		- We don't want to alter the class on the field editing page
	- Minor bug tweaks
	- Added: add_action( 'after_setup_theme', 'lct_after_setup_theme_ajax_disable_stuff' ); so we can disable crap on an ajax call
	- wp_enqueue cleanup
	- Added: add_action( 'wp_footer', 'lct_jq_doc_ready', 999 );
	- Added: add_action( 'wp_footer', 'lct_wp_footer_style', 998 );
	- Update include library autosize to v3.0.14
	- Modified autosize in gform_enqueue_scripts
	- fixed double ajax calls
	- Added woo updater to load_updater_instances

= 5.28 =
	- Added: lct_get_comment_type_lct_audit_settings()
	- Improved: add_comment_lct_audit()
	- Improved: lct_non_ajax_update_value()
	- Added: add_filter( 'lct_get_format_acf_value', 'lct_get_format_acf_value', 10, 2 );
	- Added: add_filter( 'lct_get_format_acf_date_picker', 'lct_get_format_acf_date_picker', 10, 2 );
	- Added: add_filter( 'get_comments_number', 'lct_comment_count', 11, 1 );
	- Added: add_filter( 'lct_get_comments_number', 'lct_comment_count', 11, 2 );
	- Added: add_filter( 'lct_get_comments_number', 'lct_comment_count', 11, 2 );
	- Added: Added: lct_get_role_cap_prefixes()
	- Fixed the_label() function check
	- Improved: lct_acf_form()
	- Added: access support to lct_acf_form()
	- Added: add_filter( 'lct_current_user_can_access', 'lct_current_user_can_access', 10, 2 );
	- Added: add_filter( 'lct_current_user_can_view', 'lct_current_user_can_view', 10, 2 );
	- Added: lct_get_role_cap_prefixes_only()
	- Added: add_filter( 'acf/load_field', 'lct_acf_load_field_primary' );
	- Improved can_view view
	- Added Shortcode [lct_current_user_can]
	- Added: add_action( 'shutdown', 'lct_after_redirection_apache_save' );

= 5.27 =
	- Added: hidden-imp to front.css

= 5.26 =
	- Fix the RedirectMatch bug in the redirection plugin

= 5.25 =
	- Cleaned up and moved around some misplaced functions
	- ADDED add_filter( 'wp_nav_menu_items', 'lct_wp_nav_menu_items', 10, 2 );
	- Cleaned up main file
	- ADDED lct_instant
	- ADDED lct_acf_form & lct_acf_form_full
	- Hot fix for lct_Fusion_Core_PageBuilder issue in Avada v3.8.8
	- ADDED shortcode lct_wc_login_form
	- Cleaned up create_lct_theme_chunk so it is better hidden on the front-end
	- ADDED add_action( 'set_object_terms', 'lct_acf_set_object_terms', 10, 6 );
	- ADDED acf.unload.active = false; to lct_instant
	- Reworked lct_instant()
	- ADDED add_action( 'lct_add_tax_to_user_admin_page', 'lct_add_tax_to_user_admin_page' );

= 5.24 =
	- lct_is_in_page()

= 5.22 - 5.23 =
	- Tweaked gform css

= 5.21 =
	- ADDED lct_the_content_final()
	- ADDED shortcode lct_amp
	- ADDED shortcode get_directions

= 5.20 =
	- WP Standards code reformat

= 5.19 =
	- Tweaked lct_avada_save_options()

= 5.18 =
	- Fixed bug with lct_acf_unsave_db_values()

= 5.17 =
	- Add an Avada sanitize bug fix

= 5.16 =
	- Code Reformat

= 5.15 =
	- Bug Fix: lct_plugin_reliant_plugins_loaded
	- Added lct_additional_primes action to do multiple user-agent primes with W3 total cache
	- Bug Fix: Embed filter

= 5.14 =
	- Responsive Video Embed

= 5.13 - 5.13.7 =
	- Tweak Avada CSS
	- Minor Bug fixes: PHP Warnings

= 5.12 - 5.12.2 =
	- ADDED custom_post_type support
	- ADDED override for Fusion_Core_PageBuilder
	- ADDED shortcode to access theme_chucks
	- Tweak theme_chucks
	- Fixed theme_chunk shortcode bug

= 5.11 =
	- ADDED add_shortcode( 'Avada_clear', 'Avada_clear' );
	- Fixed return bug in the [clear] shortcode

= 5.10 - 5.10.11 =
	- Fixed plugin activation hook
	- Added code to manage _editzz Files
	- Fixed mkdir bug
	- Minor CSS Tweaks

= 5.9 =
	- Fixed bugs that were causing sites without ACF installed to break
	- Moved geocode functions to it's own file
	- Added better failure checks to geocode functions

= 5.8 - 5.8.1 =
	- WP v4.3.1 Ready
	- Add OVER_QUERY_LIMIT check to lct_geocode()
	- lct_gforms_css() Tweak

= 5.7 =
	- NEW: add_filter( 'gform_enable_field_label_visibility_settings', 'lct_gform_enable_field_label_visibility_settings' );
	- Only run lct_gforms_css when it is_gravity_page()
	- re-organized gform items
	- Modified: add_action( 'gform_enqueue_scripts', 'lct_gforms_css', 11 );
	- Modified gforms CSS
	- Modified Avada CSS
	- NEW: add_filter( 'gform_multiselect_placeholder', 'set_multiselect_placeholder', 10, 2 );
	- NEW: add_filter( 'gform_field_content', 'lct_gf_columns', 10, 5 ); 2 & 3 column forms
	- NEW: add_shortcode( 'lct_gf_submit', 'lct_gf_sc_submit_button' );

= 5.6.1 =
	- Added do_shortcode() to the [lct_copyright] shortcode

= 5.6 =
	- Modified [lct_copyright] shortcode

= 5.5 =
	- Fixed wp_enqueue_scripts bug

= 5.4 =
	- Added shortcode [lct_read_more]

= 5.3 =
	- Updated to work with new ACF Pro
	- Added lct_send_function_check_email()
	- Tweak to lct_url_root_site()

= 5.2 =
	- Fixed non-LF Files
	- Added get_label()
	- Added the_label()

= 5.1 =
	- Tweaked gforms.css

= 5.0 =
	- WP v4.3 Ready
	- Added Google Maps Geocode support
	- Added lct_geocode()
	- Added lct_parse_address_components()
	- Added lct_get_street_address()
	- Added lct_get_city()
	- Added lct_get_zip()
	- CSS Tweaks
	- Added lct_get_state()
	- Changed ALL $g_lct = new g_lct; TO global $g_lct;
	- redo of gforms.css
	- redo of avada.css
	- Removed lct_use_placeholders_instead_of_labels();
	- Moved x\lc-content\plugins\lct-useful-shortcodes-functions\extend_plugins\gforms\_function.php
	- Moved gform_button_custom_class
	- Fixed lct_gform_submit_button() bug
	- Moved lct_store_gforms_array()
	- File restructure
	- Moved disable_avada_css
	- Added lct_all_fields_extra_options()
	- Changed include to include_once
	- Added add_filter( 'embed_handler_html', 'lct_embed_handler_html', 10, 3 );

= 4.3.7 =
	- Tweaked acf.css

= 4.3.6 =
	- Added lct_maintenance_Avada_fix()

= 4.3.5 =
	- Added: add_filter( 'itsec_filter_server_config_file_path', 'lct_itsec_filter_server_config_file_path', 10, 2 );
	- Fixed buggy lct_path_site_wp()

= 4.3.4 =
	- Added strpos_array()

= 4.3.3 =
	- Fixed a bug that will now redirect to the lct directory to check if a file exists and then it will return false. lct_js_uploads_dir() & lct_css_uploads_dir()

= 4.3.2 =
	- Added lct_avada_save_options() to do_action( 'avada_save_options' );
	- Fixed bug that was showing an empty admin bar to visitors

= 4.3.1 =
	- Added shortcode [theme_css]
	- Cleaned up some code bugs in /misc/shortcodes.php
	- Stopped saving lct directory in uploads when the plugin is activated
	- Deprecated lct_get_test()
	- Deprecated lct_php()
	- Deprecated lct_copyyear()
	- Moved /misc/shortcodes.php TO /features/shortcode/shortcode.php
	- Moved /features/lct_post_content_shortcode/index.php TO /features/shortcode/lct_post_content.php
	- Moved /features/shortcode_tel_link.php TO /features/shortcode/tel_link.php
	- Moved /features/misc_functions.php TO /features/function/_function.php
	- Changed all lusf to lct
	- Code Reformat plugin wide
	- Deprecated lct_css_uploads_dir()
	- Deprecated lct_js_uploads_dir()
	- Moved lct_theme_css() into file_processor.php
	- Finished lct_shortcode_file_processor()
	- Added shortcode [lct_css] that grab files from the lct-useful-shortcodes-functions plugin directory
	- Added shortcode [lct_js] that grab files from the lct-useful-shortcodes-functions plugin directory
	- gforms.css tweaks
	- Added add_filter( 'avada_blog_read_more_excerpt', 'lct_acf_avada_blog_read_more_excerpt' );
	- Added ACF Group Theme Settings: Avada
	- Added Fix/Cleanup 'DB Fix::: Add Post Meta to Multiple Posts'
	- Removed lct_acf_get_fields_mapped()
	- Removed lct_acf_get_mapped_fields_of_object()

= 4.3 =
	- WP v4.2.3 Ready
	- Added shortcode.php to ACF
	- Added $prefix_2 to lct_acf_get_fields_by_parent()
	- Added lct_acf_get_mapped_fields()
	- Added Shortcode [lct_copyright]
	- Added lct_acf_get_mapped_fields_of_object()
	- Added lct_acf_get_fields_by_object()
	- Added Shortcodes group to lct_acf_op_main_settings

= 4.2.2.27 =
	- Moved: lct_remove_admin_bar() to lct_show_admin_bar(), under /acf/filter.php
	- Modified lct_show_admin_bar() so that it will be a dynamic setting in LCT Useful ACF, rather than being hard coded.
	- Updated fields in lct_acf_op_main_settings_groups.php to support lct_show_admin_bar()

= 4.2.2.26 =
	- Added: add_filter( 'acf/load_field/type=radio', 'lct_acf_options_check_show_params' );
	- Updated acf.css
	- Modified Fixes and cleanups
	- Completed: //TO-DO: cs - Make this dynamic - 7/23/2015 12:08 AM By adding lct_acf_get_fields_by_parent()\
	- Added lct_acf_recap_field_settings()
	- Added lct_acf_create_table()
	- Added lct_acf_field_groups_columns()
	- Added lct_acf_field_groups_columns_values()
	- Added lct_acf_acf_export_title_mod()
	- Fixed tel_link version bug.
	- Added lct_create_find_and_replace_arrays()
	- Code refactoring
	- acf.css update
	- Added local groups
	- Updated import for:
			- options_page__lct_settings_main_acf_settings___general_settings__lct.json
			- options_page__lct_settings_main_acf_fixes_and_cleanups___db_fix_add_taxonomy_field_data_to_old_entries.json

= 4.2.2.25 =
	- Added extend_plugin dir, now we can properly include functions. But only is the plugin is loaded up first. YAY!
	- Added support for plugin acf
	- changed instances of lc*a* to lct
	- Added lct_acf_print_scripts()
	- Added wp-admin css
	- Added Function to create fixed and clean ups
	- Added a New ACF Fix/Cleanup (db_fix_add_taxonomy_field_data)
	- Added import for:
		- lct_settings_main_acf_fixes_and_cleanups -- DB Fix Add taxonomy field data to old entries.json
		- lct_settings_main_acf_settings -- General Settings.json

= 4.2.2.24 =
	- Added lct_get_dev_emails() function
	- Added lct_is_user_a_dev() function
	- Change C:/s to W:/wamp

= 4.2.2.23 =
	- Added disable_auto_set_user_timezone feature
	- Reformat code

= 4.2.2.22 =
	- Added target as an att to the shortcode lct_shortcode_link()

= 4.2.2.21 =
	- Added query as an att to the shortcode lct_shortcode_link()

= 4.2.2.20 =
	- Added Shortcode lct_post_content_shortcode()
	- Added lct_is_in_url()

= 4.2.2.19 =
	- Updated front.css

= 4.2.2.18 =
	- Reworked all the code for lct_shortcode_link()

= 4.2.2.17 =
	- added lct_tel_link shortcode

= 4.2.2.16 =
	- Added lct_close_all_pings_and_comments()

= 4.2.2.14 - 4.2.2.15 =
	- Fixed up functions to be better:
		- lct_select_options()
		- lct_select_options_default()
		- lct_get_select_blank()

= 4.2.2.13 =
	- Moved lct_select_options_meta_key() to deprecated
	- added lct_get_select_blank() in display/options.php
	- reformatted code in display/options.php

= 4.2.2.11 - 4.2.2.12 =
	- Tweaks to gforms CSS
	- Tweaks to css

= 4.2.2.10 =
	- Tweaks to lct_remove_site_root

= 4.2.2.9 =
	- Minor Tweaks

= 4.2.2.5 - 4.2.2.8 =
	- ADDED Cleanup Guid

= 4.2.2.4 =
	- Debug function tweaks

= 4.2.2.2 - 4.2.2.3 =
	- Additions to Avada.css

= 4.2.2.1 =
	- Additions to Avada.css
	- ADDED to gforms.css

= 4.2.2 =
	- WP 4.2.2 Ready
	- Additions to front.css

= 4.2.1.3 =
	- Minor Tweaks

= 4.2.1.2 =
	- Updated to iFrame Resizer v2.8.6
	- Code cleanup

= 4.2.1.1 =
	- Avada.css Tweaks

= 4.2.1 =
	- WP 4.2.1 Ready

= 4.1.26 =
	- Removed labob
	- WP v4.2 Ready

= 4.1.25 =
	- Removed CRLF

= 4.1.22 - 4.1.24 =
	- BAW test
	- ADDED lct_baw_force_plugin_updates

= 4.1.15 - 4.1.21 =
	- Added Avada Theme Support
	- gform css tweaks
	- Code Cleanup

= 4.1.14 =
	- CJ Spam Filter

= 4.1.13 =
	- added includes: iframe_resizer

= 4.1.12 =
	- ADDED lct_preload

= 4.1.11 =
	- WP 4.1.1 Ready
	- Fixed lct_get_user_agent_info
	- Fixed Browscap.php

= 4.1.9 - 4.1.10 =
	- changes to wpauto selection
	- lct_useful_settings default settings and checker

= 4.1.2 - 4.1.8 =
	- Minor tweaks

= 4.1.1 =
	- Fixed lct_sitemap_generator call to english-us.php

= 4.1 =
	- WP 4.1 Ready
	- jumped version to match WP

= 1.4.28 =
	- ADDED Shortcode: P_R_O

= 1.4.27 =
	- Minor tweaks

= 1.4.26 =
	- ADDED Shortcode: admin_onetime_script_run

= 1.4.17 thru 1.4.25 =
	- Minor tweaks

= 1.4.16 =
	- ADDED lct_send_to_console()

= 1.4.8 thru 1.4.15 =
	- Minor tweaks

= 1.4.7 =
	- WP 4.0 Ready

= 1.4.6 =
	- ADDED lct_opengraph_site_name

= 1.4.5 =
	- Minor tweaks

= 1.4.4 =
	- Fixed login shortcode

= 1.4.3 =
	- Fixed ")[" issues
	- Added ga.js

= 1.4.2 =
	- Fixed global class issue

= 1.4.1 =
	- Fixed global class issue

= 1.4 =
	- Changed the lct-useful-shortcodes-functions is_plugin_active() code

= 1.2.95 =
	- minor tweaks
	- Added lct_opengraph_single_image_filter

= 1.2.94 =
	- Tested for WP 3.9.2 Compatibility

= 1.2.93 =
	- minor tweaks
	- added sitemap-generator

= 1.2.92 =
	- minor tweaks

= 1.2.91 =
	- minor tweaks

= 1.2.9 =
	- ADDED lct_textimage_linking_shortcode
	- ADDED lct_admin_bar_on_bottom

= 1.2.8 =
	- Fixed Bugs in Gravity Form Placeholder Functionality
	- Added Login Form

= 1.2.7 =
	- Added Gravity Form Placeholder Functionality

= 1.2.6 =
	- Add Setting Menu

= 1.2.5 =
	- Added function echo_br()

= 1.2.4 =
	- Added Fix Multisite plugins_url issue

= 1.2.3 =
	- Fixed conflict with function 'wpautop_Disable'

= 1.2.2 =
	- Updated Globals

= 1.2.1 =
	- Updated Globals

= 1.2 =
	- Tested for WP 3.9.1 Compatibility
	- Cleaned up code.
	- Updated Globals

= 1.1.1 =
	- [get_test] bug fix.

= 1.1 =
	- Added debug/functions.php
	- Added new shortcode items

= 1.0 =
	- First Release
