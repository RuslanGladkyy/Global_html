<?php /*~~~*/
/**
 * Generate Options for a select, checkbox, etc. form field
 *
 * @type string only is custom, otherwise it will pull from options_tax
 * @default default is 1, use 0 for custom
 * @hide    select 'hide' is you don't want to show a blank option
 * @v       array {
 * @type string 'options_tax'
 * @type int 'gform_id'
 * @type int 'npl_organization'
 * @type int 'lct_org()'
 * @type bool 'skip_npl_organization'
 *          }
 * @return mixed|void
 */
function lct_select_options( $type, $default = 1, $hide = null, $v = [ ] ) {
	$v = lct_initialize_v( $v );

	//Clean up $type
	$f    = [ 'term_meta[', 'lct_useful_settings[', ']' ];
	$r    = [ '', '', '' ];
	$type = str_replace( $f, $r, $type );

	if ( class_exists( 'acf' ) && empty( $v['options_tax'] ) )
		$v['options_tax'] = get_field( 'lct:::default_taxonomy', lct_o() );

	if ( ! empty( $default ) )
		return call_user_func( 'lct_select_options_default', $hide, $type, $v );

	return call_user_func( 'lct_select_options_' . $type, $hide, $type, $v );
}


/**
 * Uses Taxonomy
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array|string
 */
function lct_select_options_default( $hide, $type, $v ) {
	if ( ! class_exists( 'acf' ) )
		return [ ];

	$tax                = $v['options_tax'];
	$select_options     = [ ];
	$excluded_tax_terms = [ ];
	$meta_query         = [ ];
	$parent_term        = '';

	if ( ! empty( $v['skip_npl_organization'] ) ) {
		if ( ! empty( $type ) )
			$parent_term = get_term_by( 'slug', $type, $tax );
	} else {
		if ( ! empty( $v['npl_organization'] ) )
			$org = get_term_by( 'id', $v['npl_organization'], 'npl_organization' );
		else if ( ! empty( $v[ lct_org() ] ) )
			$org = $v[ lct_org() ];
		else
			$org = get_term_by( 'id', get_user_meta( get_current_user_id(), 'npl_organization', true ), 'npl_organization' );

		if ( ! empty( $v[ lct_org() ] ) ) {
			global $g_npl;

			$meta_query             = [ ];
			$meta_query['relation'] = 'AND';


			$meta_query[] = [
				'key'     => lct_org(),
				'value'   => $g_npl->user_orgs,
				'compare' => 'IN'
			];
		} else {
			if ( ! empty( $org ) && ! empty( $type ) )
				$parent_term = get_term_by( 'slug', $org->slug . '__' . $type, $tax );
		}
	}

	$child_of = ! empty( $parent_term ) ? $parent_term->term_id : 0;

	$tax_args  = [
		'child_of'     => $child_of,
		'orderby'      => 'term_order',
		'order'        => 'ASC',
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'pad_counts'   => false,
		'meta_query'   => $meta_query
	];
	$tax_terms = get_terms( $tax, $tax_args );

	if ( empty( $tax_terms ) )
		return $select_options[] = lct_get_select_blank( null, 'No taxonomies Match This Query. Please Add them first.' );

	if ( empty( $hide ) )
		$select_options[] = lct_get_select_blank();

	foreach ( $tax_terms as $tax_term ) {
		$term_meta = get_option( $tax . '_' . $tax_term->term_id );

		if ( ! empty( $term_meta['lct_hide_in_dropdown'] ) && empty( $v['override_lct_hide_in_dropdown'] ) )
			continue;

		//We can only process this function is acf is also installed and running
		if ( class_exists( 'acf' ) )
			$org_of_tax_term = get_field( 'npl:::attach_to_npl_organization', $tax . '_' . $tax_term->term_id );

		if ( ! empty( $org_of_tax_term ) && ! empty( $org ) && $org_of_tax_term != $org->term_id ) {
			$excluded_tax_terms[] = $tax_term->term_id;
			continue;
		}

		$value     = [ 'value' => $tax_term->term_id ];
		$tmp_array = [
			'label' => $tax_term->name,
			'color' => $term_meta['color'],
			'icon'  => $term_meta['icon'],
			'level' => $term_meta['level'],
		];

		$tmp = array_merge( $value, $tmp_array );

		$select_options[] = $tmp;
	}

	if ( ! empty( $excluded_tax_terms ) ) {
		$tax_args  = [
			'exclude'      => $excluded_tax_terms,
			'child_of'     => $child_of,
			'orderby'      => 'term_order',
			'order'        => 'ASC',
			'hide_empty'   => 0,
			'hierarchical' => 1,
			'pad_counts'   => false
		];
		$tax_terms = get_terms( $tax, $tax_args );

		if ( empty( $tax_terms ) ) {
			$select_options   = [ ];
			$select_options[] = lct_get_select_blank( null, 'No taxonomies Match This Query. Please Add them first.' );
		}
	}

	return $select_options;
}


/**
 * Returns the first option for a select input field
 *
 * @param null   $return_partial
 * @param string $label
 * @param string $value
 * @param string $label_key
 * @param string $value_key
 *
 * @return array|string
 */
function lct_get_select_blank( $return_partial = null, $label = '---', $value = '', $label_key = 'label', $value_key = 'value' ) {
	if ( $return_partial == 'label' )
		return $label;


	if ( $return_partial == 'value' )
		return $value;


	return [ $label_key => $label, $value_key => $value ];
}


/**
 * Returns the first option for a select input field
 *
 * @param        $array
 * @param string $label
 * @param string $value
 * @param string $label_key
 * @param string $value_key
 *
 * @return array
 */
function lct_merge_w_select_blank( $array, $label_key = 'label', $value_key = 'value', $label = '---', $value = '' ) {
	return array_merge( [ lct_get_select_blank( null, $label, $value, $label_key, $value_key ) ], $array );
}


/**
 * Uses Taxonomy
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_all_tax( $hide, $type, $v ) {
	$tax        = $v['options_tax'];
	$tax_parent = $v['options_tax_parent'];

	$args = [
		'orderby'      => 'term_order',
		'order'        => 'ASC',
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'taxonomy'     => $tax,
		'parent'       => $tax_parent,
		'pad_counts'   => false
	];
	$cats = get_categories( $args );

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	foreach ( $cats as $cat ) {
		$term_meta        = get_option( $tax . "_$cat->term_id" );
		$value            = [ 'value' => $cat->term_id ];
		$array            = [
			'label' => $cat->name,
			'color' => $term_meta['color'],
			'icon'  => $term_meta['icon'],
			'level' => $term_meta['level'],
		];
		$tmp              = array_merge( $value, $array );
		$select_options[] = $tmp;
	}

	return $select_options;
}


/**
 * Get a list of ALL Wordpress pages
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array|bool
 */
function lct_select_options_get_pages( $hide, $type, $v ) {
	$args  = [ ];
	$pages = get_pages( $args );

	if ( empty( $pages ) )
		return false;

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	foreach ( $pages as $page ) {
		$select_options[] = [ 'label' => $page->post_title . ' (ID: ' . $page->ID . ')', 'value' => $page->ID ];
	}

	return $select_options;
}


/**
 * Get a list of ALL Wordpress taxonomies
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array|bool
 */
function lct_select_options_get_taxonomies( $hide, $type, $v ) {
	$args       = [ ];
	$taxonomies = get_taxonomies( $args );

	if ( empty( $taxonomies ) )
		return false;

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	foreach ( $taxonomies as $taxonomy ) {
		$select_options[] = [ 'label' => $taxonomy, 'value' => $taxonomy ];
	}

	return $select_options;
}


/**
 * Get a list of ALL Wordpress taxonomies
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_get_raw_prefs( $hide, $type, $v ) {
	$prefs   = [ ];
	$prefs[] = [ 'v' => 'wpautop', 'l' => 'Use the default Wordpress wpautop' ];
	$prefs[] = [ 'v' => 'off', 'l' => 'Off: turn wpautop off sitewide.' ];
	$prefs[] = [ 'v' => 'old', 'l' => 'Old: [raw] tag only works once.' ];
	$prefs[] = [ 'v' => 'new', 'l' => '[raw] tags work multi time and only on content contained in tags.' ];

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	foreach ( $prefs as $pref ) {
		$select_options[] = [ 'label' => $pref['l'], 'value' => $pref['v'] ];
	}

	return $select_options;
}


//Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants
//Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants
//Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants
//Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants --- Constants
/**
 * generate a select for timezones
 * //TODO: cs - Find a better source for timezones - 6/12/2015 5:27 PM
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_user_timezone( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	$select_options[] = [ 'label' => 'Pacific', 'value' => 'America/Los_Angeles' ];
	$select_options[] = [ 'label' => 'Mountain', 'value' => 'America/Denver' ];
	$select_options[] = [ 'label' => 'Central', 'value' => 'America/Chicago' ];
	$select_options[] = [ 'label' => 'Eastern', 'value' => 'America/New_York' ];
	$select_options[] = [ 'label' => 'Mountain no DST', 'value' => 'America/Phoenix' ];
	$select_options[] = [ 'label' => 'Hawaii', 'value' => 'America/Adak' ];
	$select_options[] = [ 'label' => 'Hawaii no DST', 'value' => 'Pacific/Honolulu' ];
	$select_options[] = [ 'label' => 'Alaska', 'value' => 'America/Anchorage' ];

	return $select_options;
}


/**
 * Get a list of months
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_month( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	$select_options[] = [ 'label' => 'January', 'value' => '01' ];
	$select_options[] = [ 'label' => 'February', 'value' => '02' ];
	$select_options[] = [ 'label' => 'March', 'value' => '03' ];
	$select_options[] = [ 'label' => 'April', 'value' => '04' ];
	$select_options[] = [ 'label' => 'May', 'value' => '05' ];
	$select_options[] = [ 'label' => 'June', 'value' => '06' ];
	$select_options[] = [ 'label' => 'July', 'value' => '07' ];
	$select_options[] = [ 'label' => 'August', 'value' => '08' ];
	$select_options[] = [ 'label' => 'September', 'value' => '09' ];
	$select_options[] = [ 'label' => 'October', 'value' => '10' ];
	$select_options[] = [ 'label' => 'November', 'value' => '11' ];
	$select_options[] = [ 'label' => 'December', 'value' => '12' ];

	return $select_options;
}


/**
 * Get a list of days
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_day( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	for ( $i = 1; $i <= 31; $i ++ ) {
		if ( $i < 10 )
			$value = '0' . $i;
		else
			$value = $i;
		$label = $i;

		$select_options[] = [ 'label' => $label, 'value' => $value ];
	}

	return $select_options;
}


/**
 * Get a list of years
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_year( $hide, $type, $v ) {
	$time = current_time( 'timestamp', 1 );
	$v['date_start'] ? $start = $v['date_start'] : $start = date( "Y", $time ) - 3;
	$v['date_end'] ? $end = $v['date_end'] : $end = date( "Y", $time ) + 3;

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	for ( $i = $start; $i <= $end; $i ++ ) {
		$value = $i;
		$label = $i;

		$select_options[] = [ 'label' => $label, 'value' => $value ];
	}

	return $select_options;
}


/**
 * Get a list of hours
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_hour( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	for ( $i = 1; $i <= 12; $i ++ ) {
		if ( $i < 10 )
			$value = '0' . $i;
		else
			$value = $i;
		$label = $i;

		$select_options[] = [ 'label' => $label, 'value' => $value ];
	}

	return $select_options;
}


/**
 * Get a list of minutes
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_minute( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	for ( $i = 0; $i <= 55; $i = $i + 5 ) {
		if ( $i < 10 ) {
			$value = '0' . $i;
			$label = '0' . $i;
		} else {
			$value = $i;
			$label = $i;
		}

		$select_options[] = [ 'label' => $label, 'value' => $value ];
	}

	return $select_options;
}


/**
 * Get am/pm
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_lct_standard_ampm( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	$select_options[] = [ 'label' => 'AM', 'value' => 'AM' ];
	$select_options[] = [ 'label' => 'PM', 'value' => 'PM' ];

	return $select_options;
}


/**
 * Get a list of states
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_states( $hide, $type, $v ) {
	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	$select_options[] = [ 'label' => 'Maryland', 'value' => 'MD' ];
	$select_options[] = [ 'label' => 'Virginia', 'value' => 'VA' ];

	return $select_options;
}


/**
 * Get a list of numbers
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 */
function lct_select_options_number( $hide, $type, $v ) {
	$v['number_start'] ? $start = $v['number_start'] : $start = 1;
	$v['number_end'] ? $end = $v['number_end'] : $end = 1;
	$v['number_increment'] ? $increment = $v['number_increment'] : $increment = 1;

	$select_options = [ ];

	if ( ! $hide )
		$select_options[] = lct_get_select_blank();

	for ( $i = $start; $i <= $end; $i = $i + $increment ) {
		if ( $i < 10 && $v['number_leading_zero'] )
			$value = '0' . $i;
		else
			$value = $i;
		$label = $i;

		$select_options[] = [ 'label' => $label, 'value' => $value ];
	}

	return $select_options;
}
