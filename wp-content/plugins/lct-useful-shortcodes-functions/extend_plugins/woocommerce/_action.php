<?php /*~~~*/
add_action( 'init', 'lct_wc_memberships_comments_open_hack' );
/**
 * We need to get rid of this when wc_memberships figures out how to use comments_open properly
 */
function lct_wc_memberships_comments_open_hack() {
	remove_all_filters( 'comments_open', 10 );
}
