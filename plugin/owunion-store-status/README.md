# OWUnion Store Status

Turns an OWUnion store (a WooCommerce Brand) on or off. Spec: `docs/OpenWaterUnion-Store-Stack-and-Design-Spec.md`, section 3.

## States

| State | Shoppers see |
|---|---|
| Active | Normal store |
| Hidden | The store does not exist. Brand page and its products return 404. Gone from shop, search, filters, menus, tiles, related and upsell lists, and sitemaps. Products cannot be bought |
| Closed | Not built yet (spec step 12) |

Nothing is deleted in any state. Set the state on the Brand edit screen, Products, Brands.

## Install

Copy `owunion-store-status/` to `wp-content/plugins/` on staging, then activate. Needs WooCommerce 9.6 or later (Brands in core).

## Behavior

* Cart and checkout remove Hidden store items with a message. Add to cart is refused.
* Past orders, emails, and refunds are untouched. Nothing runs in wp-admin.
* Shop managers (`manage_woocommerce`) can preview Hidden stores while logged in. Add `define( 'OWUNION_STRICT_HIDDEN', true );` to `wp-config.php` to apply the rules to everyone when testing.
* A status change purges WP Engine caches when `WpeCommon` exists, and fires `owunion_store_status_changed( $term_id, $old, $new )` and `owunion_caches_purged` for other cache layers.
* Adds a `store-{slug}` body class on store and product pages, for per store CSS.

## Breadcrumb

On product pages the Blocksy breadcrumb gains the store: OWUnion / Swim Alcatraz / Hoodies. Blocksy builds product breadcrumbs from categories only, so the plugin inserts the product's store before the category, linked to the store page. It uses Blocksy's `blocksy:breadcrumbs:items-array` filter, so it reaches every Blocksy breadcrumb, including the Breadcrumbs layer in the product summary.

Requires Customize, General, Breadcrumbs, Breadcrumbs Source set to Default. With Yoast or Rank Math as the source, Blocksy renders that plugin's trail and this filter does not run.

The parent store is left out because it is already the home item. Its slug is assumed to be `owunion`; if it differs, filter `owunion_breadcrumb_skip_stores`. Set the home item text to "OWUnion" in Customize, General, Breadcrumbs.

## Shortcode

`[owunion_active_stores layout="list|tiles" exclude="owunion"]` lists Active stores. Put it in an Elementor Shortcode widget for tiles and menus. Use `exclude` to leave out the parent store. Hand built menu links to a Brand are also dropped when it is Hidden.

## Tests

`php tests/run.php` checks the rules with stubbed WordPress functions. It does not replace staging tests.

## Verify on staging (marked K in the spec)

1. Hidden brand page and its product URLs return 404 logged out, with `OWUNION_STRICT_HIDDEN` on when logged in.
2. Elementor Loop Grid, Products widget, and filter widgets honor the exclusion.
3. Cart with an item from a newly hidden store is cleaned at cart and checkout.
4. WP Engine cache purges on save, and the 404 shows without a manual purge.
5. Your SEO plugin sitemap omits Hidden stores. WordPress core sitemaps do. Yoast, Rank Math, and AIOSEO use their own queries.
6. WooCommerce Store API (blocks) product queries. Not filtered yet. Elementor widgets use classic queries.
7. Stripe wallet buttons are unaffected.
8. A Swim Alcatraz product shows OWUnion / Swim Alcatraz / Hoodies, and the store crumb opens the store page.

## Not in this version

Closed state, open and close dates, Store API filtering.
