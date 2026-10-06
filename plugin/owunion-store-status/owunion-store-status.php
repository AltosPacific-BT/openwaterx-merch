<?php
/**
 * Plugin Name:       OWUnion Store Status
 * Description:       Turns an OWUnion store (WooCommerce Brand) on or off. Hidden stores disappear from the shop, search, menus, filters, and sitemaps, return 404, and cannot be bought.
 * Version:           0.2.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Pacific Open Water Swim Co.
 * License:           GPL-2.0-or-later
 * Text Domain:       owunion-store-status
 *
 * Spec: docs/OpenWaterUnion-Store-Stack-and-Design-Spec.md, section 3.
 * Stores are WooCommerce Brands (taxonomy product_brand, WooCommerce 9.6 or later).
 */

defined( 'ABSPATH' ) || exit;

define( 'OWUNION_STATUS_VERSION', '0.2.0' );
define( 'OWUNION_STATUS_DIR', plugin_dir_path( __FILE__ ) );

require_once OWUNION_STATUS_DIR . 'includes/status.php';
require_once OWUNION_STATUS_DIR . 'includes/frontend.php';
require_once OWUNION_STATUS_DIR . 'includes/shortcode.php';
require_once OWUNION_STATUS_DIR . 'includes/breadcrumbs.php';

if ( is_admin() ) {
	require_once OWUNION_STATUS_DIR . 'includes/admin.php';
}
