<?php
/**
 * Status field on the Brand screens, and a status column in the Brand list.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Select field markup.
 *
 * @param string $current Current status.
 */
function owunion_status_select( $current ) {
	wp_nonce_field( 'owunion_store_status', 'owunion_store_status_nonce' );
	echo '<select name="owunion_store_status" id="owunion_store_status">';
	foreach ( owunion_store_statuses() as $value => $label ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
	}
	echo '</select>';
}

/**
 * Add screen.
 */
function owunion_status_add_field() {
	echo '<div class="form-field"><label for="owunion_store_status">' . esc_html__( 'Store status', 'owunion-store-status' ) . '</label>';
	owunion_status_select( 'active' );
	echo '<p>' . esc_html__( 'Hidden stores do not exist for shoppers: pages return 404 and products cannot be bought.', 'owunion-store-status' ) . '</p></div>';
}
add_action( OWUNION_TAXONOMY . '_add_form_fields', 'owunion_status_add_field' );

/**
 * Edit screen.
 *
 * @param WP_Term $term Brand term.
 */
function owunion_status_edit_field( $term ) {
	echo '<tr class="form-field"><th scope="row"><label for="owunion_store_status">' . esc_html__( 'Store status', 'owunion-store-status' ) . '</label></th><td>';
	owunion_status_select( owunion_get_store_status( $term->term_id ) );
	echo '<p class="description">' . esc_html__( 'Hidden stores do not exist for shoppers: pages return 404 and products cannot be bought. Shop managers can still preview them while logged in. Nothing is deleted.', 'owunion-store-status' ) . '</p></td></tr>';
}
add_action( OWUNION_TAXONOMY . '_edit_form_fields', 'owunion_status_edit_field' );

/**
 * Save on create and edit.
 *
 * @param int $term_id Brand term ID.
 */
function owunion_status_save( $term_id ) {
	if ( ! isset( $_POST['owunion_store_status_nonce'], $_POST['owunion_store_status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	$nonce = sanitize_text_field( wp_unslash( $_POST['owunion_store_status_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'owunion_store_status' ) || ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}
	owunion_set_store_status( $term_id, sanitize_key( wp_unslash( $_POST['owunion_store_status'] ) ) );
}
add_action( 'created_' . OWUNION_TAXONOMY, 'owunion_status_save' );
add_action( 'edited_' . OWUNION_TAXONOMY, 'owunion_status_save' );

/**
 * Status column.
 *
 * @param array $columns List table columns.
 * @return array
 */
function owunion_status_column( $columns ) {
	$columns['owunion_status'] = __( 'Status', 'owunion-store-status' );
	return $columns;
}
add_filter( 'manage_edit-' . OWUNION_TAXONOMY . '_columns', 'owunion_status_column' );

/**
 * Status column cell.
 *
 * @param string $content   Current content.
 * @param string $column    Column key.
 * @param int    $term_id   Term ID.
 * @return string
 */
function owunion_status_column_content( $content, $column, $term_id ) {
	if ( 'owunion_status' === $column ) {
		$labels  = owunion_store_statuses();
		$content = esc_html( $labels[ owunion_get_store_status( $term_id ) ] );
	}
	return $content;
}
add_filter( 'manage_' . OWUNION_TAXONOMY . '_custom_column', 'owunion_status_column_content', 10, 3 );
