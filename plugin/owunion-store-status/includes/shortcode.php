<?php
/**
 * [owunion_active_stores] lists Active stores for tiles and menus, so a disabled
 * store drops out with no manual edits.
 *
 * Attributes:
 *   layout   list (default) or tiles. Tiles show the Brand thumbnail when one is set.
 *   exclude  comma separated store slugs to leave out, for example the parent store.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the Active stores list.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function owunion_active_stores_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'layout'  => 'list',
			'exclude' => '',
		),
		$atts,
		'owunion_active_stores'
	);

	$layout  = 'tiles' === $atts['layout'] ? 'tiles' : 'list';
	$exclude = array_filter( array_map( 'sanitize_title', explode( ',', $atts['exclude'] ) ) );

	$terms = get_terms(
		array(
			'taxonomy'   => OWUNION_TAXONOMY,
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);
	if ( is_wp_error( $terms ) || ! $terms ) {
		return '';
	}

	$items = '';
	foreach ( $terms as $term ) {
		if ( 'active' !== owunion_get_store_status( $term->term_id ) || in_array( $term->slug, $exclude, true ) ) {
			continue;
		}
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$image = '';
		if ( 'tiles' === $layout ) {
			$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			$image    = $thumb_id ? wp_get_attachment_image( $thumb_id, 'medium', false, array( 'alt' => '' ) ) : '';
		}
		$items .= sprintf(
			'<li class="owunion-store owunion-store--%1$s"><a href="%2$s">%3$s<span class="owunion-store__name">%4$s</span></a></li>',
			esc_attr( sanitize_html_class( $term->slug ) ),
			esc_url( $link ),
			$image, // Built by wp_get_attachment_image, already escaped.
			esc_html( $term->name )
		);
	}
	if ( '' === $items ) {
		return '';
	}
	return sprintf( '<ul class="owunion-stores owunion-stores--%s">%s</ul>', esc_attr( $layout ), $items );
}
add_shortcode( 'owunion_active_stores', 'owunion_active_stores_shortcode' );
