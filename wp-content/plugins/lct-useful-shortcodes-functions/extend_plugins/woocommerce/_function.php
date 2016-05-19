<?php /*~~~*/
/**
 * Get an array of product_ids from and order
 *
 * @param $order
 *
 * @return array
 */
function lct_get_order_product_ids( $order ) {
	if ( is_int( $order ) )
		$order = new WC_Order( $order );

	$items       = $order->get_items();
	$product_ids = [ ];


	foreach ( $items as $item ) {
		$product_ids[] = $item['product_id'];
	}


	return $product_ids;
}


/**
 * Get an array of product_id terms from and order
 *
 * @param $order
 *
 * @return array
 */
function lct_get_order_product_id_terms( $order ) {
	$product_ids = lct_get_order_product_ids( $order );

	$terms = [ ];

	foreach ( $product_ids as $product_id ) {
		$product_terms = wc_get_product_terms( $product_id, 'product_cat' );
		foreach ( $product_terms as $product_term ) {
			$terms[] = $product_term->term_id;
		}
	}

	array_unique( $terms );


	return $terms;
}
