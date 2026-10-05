<?php
/**
 * Front end rules for Hidden stores (spec section 3.2).
 *
 * 1. Excluded from shop, search, filters, related products, store tiles, and menus.
 * 2. Brand page and its products return 404.
 * 3. Products are not purchasable.
 * 4. Cart items from a newly hidden store are removed at cart and checkout with a message.
 * 5. Past orders, emails, and refunds are untouched. Nothing here runs in wp-admin.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Drop Hidden products from any product query (shop, search, archives, Elementor loops).
 *
 * @param WP_Query $query Query being prepared.
 */
function owunion_exclude_hidden_from_queries( $query ) {
	if ( is_admin() ) {
		return;
	}
	$hidden = owunion_active_hidden_ids();
	if ( ! $hidden ) {
		return;
	}
	$post_type = (array) $query->get( 'post_type' );
	$is_wc     = $query->is_main_query() && function_exists( 'is_woocommerce' ) && is_woocommerce();
	if ( ! $is_wc && ! in_array( 'product', $post_type, true ) ) {
		return;
	}
	$clause = array(
		'taxonomy' => OWUNION_TAXONOMY,
		'field'    => 'term_id',
		'terms'    => $hidden,
		'operator' => 'NOT IN',
	);
	$tax    = $query->get( 'tax_query' );
	if ( empty( $tax ) || ! is_array( $tax ) ) {
		$tax = array( $clause );
	} else {
		$tax = array(
			'relation' => 'AND',
			$tax,
			$clause,
		);
	}
	$query->set( 'tax_query', $tax );
}
add_action( 'pre_get_posts', 'owunion_exclude_hidden_from_queries' );

/**
 * Drop Hidden stores from Brand term lists (widgets, filters, tiles, sitemaps).
 *
 * @param array        $args       get_terms arguments.
 * @param string|array $taxonomies Taxonomies requested.
 * @return array
 */
function owunion_exclude_hidden_terms( $args, $taxonomies ) {
	if ( is_admin() || ! empty( $GLOBALS['owunion_bypass_term_filter'] ) ) {
		return $args;
	}
	if ( ! in_array( OWUNION_TAXONOMY, (array) $taxonomies, true ) || ! empty( $args['include'] ) ) {
		return $args;
	}
	$hidden = owunion_active_hidden_ids();
	if ( $hidden ) {
		$args['exclude'] = array_values( array_unique( array_merge( array_filter( (array) $args['exclude'] ), $hidden ) ) );
	}
	return $args;
}
add_filter( 'get_terms_args', 'owunion_exclude_hidden_terms', 10, 2 );

/**
 * Return 404 for a Hidden store page and for its products.
 */
function owunion_block_hidden_pages() {
	$hidden = owunion_active_hidden_ids();
	if ( ! $hidden ) {
		return;
	}
	$block = false;
	if ( is_tax( OWUNION_TAXONOMY ) ) {
		$term  = get_queried_object();
		$block = $term && in_array( (int) $term->term_id, $hidden, true );
	} elseif ( is_singular( 'product' ) ) {
		$block = owunion_product_is_hidden( get_queried_object_id() );
	}
	if ( $block ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'owunion_block_hidden_pages', 1 );

/**
 * Products from a Hidden store cannot be purchased.
 *
 * @param bool       $purchasable Current value.
 * @param WC_Product $product     Product or variation.
 * @return bool
 */
function owunion_filter_purchasable( $purchasable, $product ) {
	if ( $purchasable && owunion_hidden_applies() && owunion_product_is_hidden( $product->get_id() ) ) {
		return false;
	}
	return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'owunion_filter_purchasable', 10, 2 );
add_filter( 'woocommerce_variation_is_purchasable', 'owunion_filter_purchasable', 10, 2 );

/**
 * Hide products of a Hidden store from catalog visibility checks.
 *
 * @param bool $visible    Current value.
 * @param int  $product_id Product ID.
 * @return bool
 */
function owunion_filter_visible( $visible, $product_id ) {
	if ( $visible && owunion_hidden_applies() && owunion_product_is_hidden( $product_id ) ) {
		return false;
	}
	return $visible;
}
add_filter( 'woocommerce_product_is_visible', 'owunion_filter_visible', 10, 2 );

/**
 * Block add to cart for Hidden store products.
 *
 * @param bool $passed     Validation result so far.
 * @param int  $product_id Product ID.
 * @return bool
 */
function owunion_validate_add_to_cart( $passed, $product_id ) {
	if ( $passed && owunion_hidden_applies() && owunion_product_is_hidden( $product_id ) ) {
		wc_add_notice( __( 'This product is no longer available.', 'owunion-store-status' ), 'error' );
		return false;
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'owunion_validate_add_to_cart', 10, 2 );

/**
 * Remove Hidden store items already in a cart, with a message.
 */
function owunion_clean_cart() {
	if ( ! owunion_hidden_applies() || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	foreach ( WC()->cart->get_cart() as $key => $item ) {
		if ( owunion_product_is_hidden( $item['product_id'] ) ) {
			$name = $item['data'] ? $item['data']->get_name() : __( 'An item', 'owunion-store-status' );
			WC()->cart->remove_cart_item( $key );
			wc_add_notice(
				sprintf(
					/* translators: %s: product name */
					__( '%s was removed from your cart because it is no longer available.', 'owunion-store-status' ),
					$name
				),
				'error'
			);
		}
	}
}
add_action( 'woocommerce_check_cart_items', 'owunion_clean_cart' );

/**
 * Remove Hidden products from related, upsell, and cross sell lists.
 *
 * @param int[] $ids Product IDs.
 * @return int[]
 */
function owunion_filter_product_ids( $ids ) {
	if ( ! is_array( $ids ) || ! owunion_hidden_applies() ) {
		return $ids;
	}
	return array_values(
		array_filter(
			$ids,
			static function ( $id ) {
				return ! owunion_product_is_hidden( $id );
			}
		)
	);
}
add_filter( 'woocommerce_related_products', 'owunion_filter_product_ids' );
add_filter( 'woocommerce_product_get_upsell_ids', 'owunion_filter_product_ids' );
add_filter( 'woocommerce_product_get_cross_sell_ids', 'owunion_filter_product_ids' );

/**
 * Drop menu items that point to a Hidden store.
 *
 * @param array $items Menu item objects.
 * @return array
 */
function owunion_filter_menu_items( $items ) {
	$hidden = owunion_active_hidden_ids();
	if ( ! $hidden ) {
		return $items;
	}
	return array_values(
		array_filter(
			$items,
			static function ( $item ) use ( $hidden ) {
				return ! ( 'taxonomy' === $item->type && OWUNION_TAXONOMY === $item->object && in_array( (int) $item->object_id, $hidden, true ) );
			}
		)
	);
}
add_filter( 'wp_nav_menu_objects', 'owunion_filter_menu_items' );

/**
 * Add a store-{slug} body class on store and product pages, for per store CSS.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function owunion_body_class( $classes ) {
	$slug = '';
	if ( is_tax( OWUNION_TAXONOMY ) ) {
		$term = get_queried_object();
		$slug = $term ? $term->slug : '';
	} elseif ( is_singular( 'product' ) ) {
		$terms = wp_get_object_terms( get_queried_object_id(), OWUNION_TAXONOMY, array( 'fields' => 'slugs' ) );
		$slug  = ! is_wp_error( $terms ) && $terms ? $terms[0] : '';
	}
	if ( $slug ) {
		$classes[] = 'store-' . sanitize_html_class( $slug );
	}
	return $classes;
}
add_filter( 'body_class', 'owunion_body_class' );
