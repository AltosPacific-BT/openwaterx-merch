<?php
/**
 * Store in the product breadcrumb: OWUnion / Swim Alcatraz / Hoodies.
 *
 * Blocksy builds product breadcrumbs from product categories only, so the store
 * (Brand) is missing. This adds it before the category, through Blocksy's
 * blocksy:breadcrumbs:items-array filter, which feeds every Blocksy breadcrumb.
 * Theme change 1.1-02, docs/theme/v1.1-changes.md.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Store slugs that never get a crumb. The parent store is already the home item.
 *
 * @return string[]
 */
function owunion_breadcrumb_skip_stores() {
	return (array) apply_filters( 'owunion_breadcrumb_skip_stores', array( 'owunion' ) );
}

/**
 * The product's store. Same term as the store-{slug} body class: the first Brand.
 *
 * @param int $product_id Product ID.
 * @return WP_Term|null
 */
function owunion_product_store( $product_id ) {
	$terms = wp_get_object_terms( (int) $product_id, OWUNION_TAXONOMY );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return null;
	}
	return $terms[0];
}

/**
 * Insert the store crumb on single product pages.
 *
 * Position: before the first category crumb. With no category, before the product
 * title crumb. With neither, at the end.
 *
 * @param array[] $items Blocksy breadcrumb items, home first.
 * @return array[]
 */
function owunion_breadcrumb_add_store( $items ) {
	if ( ! is_array( $items ) || ! function_exists( 'is_product' ) || ! is_product() ) {
		return $items;
	}

	$store = owunion_product_store( get_queried_object_id() );
	if ( ! $store || in_array( $store->slug, owunion_breadcrumb_skip_stores(), true ) ) {
		return $items;
	}

	$items = array_values( $items );
	$at    = count( $items );
	$title = null;

	foreach ( $items as $i => $item ) {
		if ( isset( $item['taxonomy'] ) && OWUNION_TAXONOMY === $item['taxonomy'] ) {
			return $items;
		}
		if ( $at === count( $items ) && isset( $item['taxonomy'] ) && 'product_cat' === $item['taxonomy'] ) {
			$at = $i;
		}
		if ( null === $title && isset( $item['post_type'] ) && 'product' === $item['post_type'] ) {
			$title = $i;
		}
	}
	if ( $at === count( $items ) && null !== $title ) {
		$at = $title;
	}

	$url = get_term_link( $store, OWUNION_TAXONOMY );

	array_splice(
		$items,
		$at,
		0,
		array(
			array(
				'type'     => 'taxonomy',
				'name'     => $store->name,
				'id'       => $store->term_id,
				'url'      => is_wp_error( $url ) ? '' : $url,
				'taxonomy' => OWUNION_TAXONOMY,
			),
		)
	);

	return $items;
}
add_filter( 'blocksy:breadcrumbs:items-array', 'owunion_breadcrumb_add_store' );
