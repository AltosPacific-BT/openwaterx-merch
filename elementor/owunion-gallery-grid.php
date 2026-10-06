<?php
/**
 * OWUnion: product gallery as a two column grid, per the Portland Gear layout.
 *
 * Paste the three hooks into the Code Snippets plugin (Run everywhere), or save this
 * file to wp-content/mu-plugins/. Staging first. The grid CSS is in owunion-site.css
 * and only applies when the owu-gallery-grid body class is present.
 *
 * Checked against WooCommerce 11.1.2: wc_get_gallery_image_html() reads both filters.
 */

defined( 'ABSPATH' ) || exit;

// Turn off the slider. Zoom and lightbox stay on.
add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false' );

// Without the slider, WooCommerce serves extra images at thumbnail size. Use the full product image size.
add_filter(
	'woocommerce_gallery_image_size',
	function () {
		return 'woocommerce_single';
	}
);

// Switch on the grid CSS.
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_singular( 'product' ) ) {
			$classes[] = 'owu-gallery-grid';
		}
		return $classes;
	}
);
