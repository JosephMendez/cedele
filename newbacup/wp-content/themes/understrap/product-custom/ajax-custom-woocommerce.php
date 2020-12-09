<?php
add_action('wp_ajax_json_custom_search_rider', 'json_custom_search_rider');
add_action('wp_ajax_nopriv_json_custom_search_rider', 'json_custom_search_rider');

/**
 * Search for customers and return json.
 */
function json_custom_search_rider() {
    global $wpdb;
    ob_start();

    $term  = isset( $_GET['term'] ) ? (string) wc_clean( wp_unslash( $_GET['term'] ) ) : '';
    $limit = 0;

    if ( empty( $term ) ) {
        wp_die();
    }

    $table_rider_name = $wpdb->prefix . 'cedele_setting_riders';
    $table_partner_name = $wpdb->prefix . 'cedele_setting_shipping_partner';
    $list_rider = $wpdb->get_results(
        $wpdb->prepare("SELECT *, partners.partner_name as partner_name
        FROM $table_rider_name
        LEFT JOIN (
            SELECT id as partner_id, partner_name FROM $table_partner_name
            WHERE status = 1
        ) as partners
        ON $table_rider_name.partner_id = partners.partner_id
        WHERE status = 1 AND rider_name like %s", "%$term%")
    );
    $found_riders = [];
    foreach ( $list_rider as $rider ) {
        $partner_name = !empty($rider->partner_name) ? (' &ndash; ' . $rider->partner_name) : '';
        $found_riders[ $rider->id ] = sprintf(
            /* translators: $1: customer name, $2 customer id, $3: customer email */
            esc_html__( '%1$s (%2$s%3$s)', 'woocommerce' ),
            $rider->rider_name,
            $rider->contact_number,
            $partner_name
        );
    }
    wp_send_json($found_riders);
}