<?php
/**
 * Logic tests with stubbed WordPress functions. Run: php tests/run.php
 * These cover the rules in isolation. Real behavior (caching, Elementor, checkout)
 * is verified on staging, spec step 7.
 */

define( 'ABSPATH', __DIR__ );

// ---- Stubs ----------------------------------------------------------------
$GLOBALS['db'] = array(
	'options' => array(),
	'meta'    => array(),          // term_id => status.
	'objects' => array(),          // post_id => term ids.
	'types'   => array(),          // post_id => post type.
	'parents' => array(),          // variation id => parent id.
	'caps'    => array(),
	'actions' => array(),
	'terms'   => array(),          // term_id => term object, for lookups that return objects.
	'product' => false,            // is_product().
	'queried' => 0,                // get_queried_object_id().
);
function __( $s ) { return $s; }
function is_admin() { return false; }
function add_action() {}
function add_filter() {}
function add_shortcode() {}
function apply_filters( $tag, $value ) { return $value; }
function do_action( $tag ) { $GLOBALS['db']['actions'][] = $tag; }
function current_user_can( $cap ) { return ! empty( $GLOBALS['db']['caps'][ $cap ] ); }
function is_wp_error( $v ) { return false; }
function plugin_dir_path() { return ''; }
function get_option( $k, $d = false ) { return $GLOBALS['db']['options'][ $k ] ?? $d; }
function update_option( $k, $v ) { $GLOBALS['db']['options'][ $k ] = $v; }
function get_term_meta( $id, $k ) { return $GLOBALS['db']['meta'][ $id ] ?? ''; }
function update_term_meta( $id, $k, $v ) { $GLOBALS['db']['meta'][ $id ] = $v; }
function get_terms( $args ) {
	$ids = array();
	foreach ( $GLOBALS['db']['meta'] as $id => $status ) {
		if ( 'hidden' === $status ) {
			$ids[] = $id;
		}
	}
	return $ids;
}
function get_post_type( $id ) { return $GLOBALS['db']['types'][ $id ] ?? 'product'; }
function wp_get_post_parent_id( $id ) { return $GLOBALS['db']['parents'][ $id ] ?? 0; }
function wp_get_object_terms( $id, $tax = '', $args = array() ) {
	$ids = $GLOBALS['db']['objects'][ $id ] ?? array();
	if ( isset( $args['fields'] ) ) {
		return $ids;
	}
	return array_map( function ( $tid ) { return $GLOBALS['db']['terms'][ $tid ] ?? (object) array( 'term_id' => $tid, 'slug' => "t$tid", 'name' => "T$tid" ); }, $ids );
}
function is_product() { return $GLOBALS['db']['product']; }
function get_queried_object_id() { return $GLOBALS['db']['queried']; }
function get_term_link( $term ) { return '/brand/' . $term->slug . '/'; }
function sanitize_title( $s ) { return trim( strtolower( $s ) ); }

class WP_Query {
	public $vars = array();
	public $main = false;
	public function __construct( $vars = array(), $main = false ) { $this->vars = $vars; $this->main = $main; }
	public function get( $k ) { return $this->vars[ $k ] ?? ''; }
	public function set( $k, $v ) { $this->vars[ $k ] = $v; }
	public function is_main_query() { return $this->main; }
}

require __DIR__ . '/../includes/status.php';
require __DIR__ . '/../includes/frontend.php';
require __DIR__ . '/../includes/breadcrumbs.php';

// ---- Harness --------------------------------------------------------------
$fails = 0;
function check( $name, $cond ) {
	global $fails;
	echo ( $cond ? 'ok   ' : 'FAIL ' ) . $name . "\n";
	if ( ! $cond ) {
		++$fails;
	}
}

// ---- Tests ----------------------------------------------------------------
check( 'unset status is active', 'active' === owunion_get_store_status( 7 ) );
$GLOBALS['db']['meta'][8] = 'bogus';
check( 'unknown status is active', 'active' === owunion_get_store_status( 8 ) );
check( 'invalid status rejected', false === owunion_set_store_status( 7, 'closed' ) );

check( 'hiding returns changed', true === owunion_set_store_status( 10, 'hidden' ) );
check( 'hidden list rebuilt', array( 10 ) === get_option( OWUNION_HIDDEN_OPT ) );
check( 'caches purged and event fired', in_array( 'owunion_store_status_changed', $GLOBALS['db']['actions'], true ) && in_array( 'owunion_caches_purged', $GLOBALS['db']['actions'], true ) );

// Request memo is per process, so use a fresh set of IDs after the list is set.
$GLOBALS['db']['objects'][100] = array( 10 );
$GLOBALS['db']['objects'][200] = array( 11 );
$GLOBALS['db']['types'][101]   = 'product_variation';
$GLOBALS['db']['parents'][101] = 100;
check( 'product in hidden store is hidden', owunion_product_is_hidden( 100 ) );
check( 'variation follows parent', owunion_product_is_hidden( 101 ) );
check( 'product in active store is visible', ! owunion_product_is_hidden( 200 ) );

check( 'shopper: rules apply', owunion_hidden_applies() );
$GLOBALS['db']['caps']['manage_woocommerce'] = true;
check( 'shop manager previews', ! owunion_hidden_applies() );
$GLOBALS['db']['caps']['manage_woocommerce'] = false;

check( 'purchasable false for hidden', false === owunion_filter_purchasable( true, new class() { public function get_id() { return 100; } } ) );
check( 'purchasable kept for active', true === owunion_filter_purchasable( true, new class() { public function get_id() { return 200; } } ) );
check( 'visible false for hidden', false === owunion_filter_visible( true, 100 ) );
check( 'related list drops hidden', array( 200 ) === owunion_filter_product_ids( array( 100, 200, 101 ) ) );

$q = new WP_Query( array( 'post_type' => 'product' ) );
owunion_exclude_hidden_from_queries( $q );
check( 'product query gets NOT IN clause', 'NOT IN' === $q->get( 'tax_query' )[0]['operator'] && array( 10 ) === $q->get( 'tax_query' )[0]['terms'] );

$q = new WP_Query( array( 'post_type' => 'product', 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'terms' => array( 5 ) ) ) ) );
owunion_exclude_hidden_from_queries( $q );
$tq = $q->get( 'tax_query' );
check( 'existing tax_query kept, joined with AND', 'AND' === $tq['relation'] && 'product_cat' === $tq[0][0]['taxonomy'] && 'NOT IN' === $tq[1]['operator'] );

$q = new WP_Query( array( 'post_type' => 'post' ) );
owunion_exclude_hidden_from_queries( $q );
check( 'non product query untouched', '' === $q->get( 'tax_query' ) );

$args = owunion_exclude_hidden_terms( array( 'exclude' => '', 'include' => array() ), array( 'product_brand' ) );
check( 'brand term list excludes hidden', array( 10 ) === $args['exclude'] );
$args = owunion_exclude_hidden_terms( array( 'exclude' => '', 'include' => array() ), array( 'product_cat' ) );
check( 'other taxonomies untouched', '' === $args['exclude'] );

$menu = array(
	(object) array( 'type' => 'taxonomy', 'object' => 'product_brand', 'object_id' => 10 ),
	(object) array( 'type' => 'taxonomy', 'object' => 'product_brand', 'object_id' => 11 ),
	(object) array( 'type' => 'post_type', 'object' => 'page', 'object_id' => 10 ),
);
check( 'menu drops hidden store link only', 2 === count( owunion_filter_menu_items( $menu ) ) );

check( 're-enable reports changed', true === owunion_set_store_status( 10, 'active' ) );
check( 're-enable restores', array() === get_option( OWUNION_HIDDEN_OPT ) );

// ---- Breadcrumb: store crumb (theme change 1.1-02) -------------------------
$GLOBALS['db']['terms'][20]   = (object) array( 'term_id' => 20, 'slug' => 'swim-alcatraz', 'name' => 'Swim Alcatraz' );
$GLOBALS['db']['terms'][21]   = (object) array( 'term_id' => 21, 'slug' => 'owunion', 'name' => 'OWUnion' );
$GLOBALS['db']['objects'][300] = array( 20 );
$GLOBALS['db']['objects'][301] = array( 21 );
$home  = array( 'name' => 'OWUnion', 'url' => '/', 'type' => 'front_page' );
$shop  = array( 'name' => 'Shop', 'url' => '/shop/' );
$cat   = array( 'type' => 'taxonomy', 'name' => 'Hoodies', 'url' => '/product-category/hoodies/', 'taxonomy' => 'product_cat' );
$title = array( 'type' => 'post', 'post_type' => 'product', 'name' => 'Escape Hoodie', 'url' => '/product/escape-hoodie/' );
$names = function ( $items ) { return implode( ' / ', array_column( $items, 'name' ) ); };

$GLOBALS['db']['queried'] = 300;
check( 'crumb: not a product page, unchanged', array( $home, $cat ) === owunion_breadcrumb_add_store( array( $home, $cat ) ) );
$GLOBALS['db']['product'] = true;
$out = owunion_breadcrumb_add_store( array( $home, $cat, $title ) );
check( 'crumb: store before category', 'OWUnion / Swim Alcatraz / Hoodies / Escape Hoodie' === $names( $out ) );
check( 'crumb: links to the store page', '/brand/swim-alcatraz/' === $out[1]['url'] && 'product_brand' === $out[1]['taxonomy'] );
check( 'crumb: after the shop item', 'OWUnion / Shop / Swim Alcatraz / Hoodies' === $names( owunion_breadcrumb_add_store( array( $home, $shop, $cat ) ) ) );
check( 'crumb: no category, before the title', 'OWUnion / Swim Alcatraz / Escape Hoodie' === $names( owunion_breadcrumb_add_store( array( $home, $title ) ) ) );
check( 'crumb: no category or title, at the end', 'OWUnion / Swim Alcatraz' === $names( owunion_breadcrumb_add_store( array( $home ) ) ) );
check( 'crumb: no duplicate when present', $out === owunion_breadcrumb_add_store( $out ) );
$GLOBALS['db']['queried'] = 301;
check( 'crumb: parent store skipped', array( $home, $cat ) === owunion_breadcrumb_add_store( array( $home, $cat ) ) );
$GLOBALS['db']['queried'] = 302;
check( 'crumb: product with no store, unchanged', array( $home, $cat ) === owunion_breadcrumb_add_store( array( $home, $cat ) ) );
check( 'body class lookup still returns ids', array( 20 ) === wp_get_object_terms( 300, 'product_brand', array( 'fields' => 'ids' ) ) );

echo $fails ? "\n$fails failed\n" : "\nall passed\n";
exit( $fails ? 1 : 0 );
