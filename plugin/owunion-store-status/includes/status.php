<?php
/**
 * Status storage and lookups.
 *
 * A store's status lives in term meta on its Brand. Launch states: active, hidden.
 * The closed state is built second (spec step 12) and is not part of this version.
 */

defined( 'ABSPATH' ) || exit;

const OWUNION_TAXONOMY    = 'product_brand';
const OWUNION_STATUS_META = '_owunion_store_status';
const OWUNION_HIDDEN_OPT  = 'owunion_hidden_store_ids';

/**
 * Allowed statuses, value => label.
 *
 * @return array<string,string>
 */
function owunion_store_statuses() {
	return array(
		'active' => __( 'Active', 'owunion-store-status' ),
		'hidden' => __( 'Hidden', 'owunion-store-status' ),
	);
}

/**
 * Status of one store. Anything missing or unknown counts as active.
 *
 * @param int $term_id Brand term ID.
 * @return string
 */
function owunion_get_store_status( $term_id ) {
	$status = get_term_meta( (int) $term_id, OWUNION_STATUS_META, true );
	return isset( owunion_store_statuses()[ $status ] ) ? $status : 'active';
}

/**
 * IDs of Hidden stores. Stored in an option so every request avoids a meta query.
 *
 * @return int[]
 */
function owunion_hidden_store_ids() {
	if ( isset( $GLOBALS['owunion_hidden_memo'] ) ) {
		return $GLOBALS['owunion_hidden_memo'];
	}
	$ids = get_option( OWUNION_HIDDEN_OPT, null );
	if ( ! is_array( $ids ) ) {
		return owunion_rebuild_hidden_store_ids();
	}
	$GLOBALS['owunion_hidden_memo'] = array_map( 'intval', $ids );
	return $GLOBALS['owunion_hidden_memo'];
}

/**
 * Rebuild the Hidden list from term meta and save it.
 *
 * @return int[]
 */
function owunion_rebuild_hidden_store_ids() {
	$GLOBALS['owunion_bypass_term_filter'] = true;
	$ids                                   = get_terms(
		array(
			'taxonomy'   => OWUNION_TAXONOMY,
			'hide_empty' => false,
			'fields'     => 'ids',
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => OWUNION_STATUS_META,
					'value' => 'hidden',
				),
			),
		)
	);
	$GLOBALS['owunion_bypass_term_filter'] = false;

	$ids = is_wp_error( $ids ) ? array() : array_map( 'intval', $ids );
	update_option( OWUNION_HIDDEN_OPT, $ids );
	$GLOBALS['owunion_hidden_memo'] = $ids;
	return $ids;
}

/**
 * Whether Hidden rules apply to the current visitor.
 *
 * Shop managers can preview Hidden stores while logged in. Define
 * OWUNION_STRICT_HIDDEN as true to apply the rules to everyone, for testing.
 *
 * @return bool
 */
function owunion_hidden_applies() {
	if ( defined( 'OWUNION_STRICT_HIDDEN' ) && OWUNION_STRICT_HIDDEN ) {
		return true;
	}
	$applies = ! current_user_can( 'manage_woocommerce' );
	return (bool) apply_filters( 'owunion_hidden_applies', $applies );
}

/**
 * Hidden store IDs that apply to this visitor right now.
 *
 * @return int[]
 */
function owunion_active_hidden_ids() {
	return owunion_hidden_applies() ? owunion_hidden_store_ids() : array();
}

/**
 * Whether a product belongs to a Hidden store. Variations use their parent.
 *
 * @param int $product_id Product or variation ID.
 * @return bool
 */
function owunion_product_is_hidden( $product_id ) {
	static $memo = array();
	$original    = (int) $product_id;
	if ( isset( $memo[ $original ] ) ) {
		return $memo[ $original ];
	}
	$hidden = owunion_hidden_store_ids();
	if ( ! $hidden ) {
		return false;
	}
	$lookup_id = $original;
	if ( 'product_variation' === get_post_type( $lookup_id ) ) {
		$parent = (int) wp_get_post_parent_id( $lookup_id );
		if ( $parent ) {
			$lookup_id = $parent;
		}
	}
	$terms = wp_get_object_terms( $lookup_id, OWUNION_TAXONOMY, array( 'fields' => 'ids' ) );
	$found = ! is_wp_error( $terms ) && (bool) array_intersect( array_map( 'intval', $terms ), $hidden );

	$memo[ $original ] = $found;
	return $found;
}

/**
 * Purge page caches after a status change. WP Engine runs its own page cache.
 */
function owunion_purge_caches() {
	if ( class_exists( 'WpeCommon' ) ) {
		if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
			WpeCommon::purge_memcached();
		}
		if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
			WpeCommon::purge_varnish_cache();
		}
	}
	do_action( 'owunion_caches_purged' );
}

/**
 * Save a new status and run side effects.
 *
 * @param int    $term_id Brand term ID.
 * @param string $status  active or hidden.
 * @return bool True when the status changed.
 */
function owunion_set_store_status( $term_id, $status ) {
	if ( ! isset( owunion_store_statuses()[ $status ] ) ) {
		return false;
	}
	$old = owunion_get_store_status( $term_id );
	update_term_meta( (int) $term_id, OWUNION_STATUS_META, $status );
	owunion_rebuild_hidden_store_ids();
	if ( $old === $status ) {
		return false;
	}
	owunion_purge_caches();
	do_action( 'owunion_store_status_changed', (int) $term_id, $old, $status );
	return true;
}

/**
 * Keep the Hidden list correct when a Brand is deleted.
 */
add_action( 'delete_' . OWUNION_TAXONOMY, 'owunion_rebuild_hidden_store_ids' );
