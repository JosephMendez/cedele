<?php
add_action('wp_ajax_faq_custom_get_categories', 'faq_custom_get_categories_func');
add_action('wp_ajax_nopriv_faq_custom_get_categories', 'faq_custom_get_categories_func');

add_action('wp_ajax_faq_custom_add_categories', 'faq_custom_add_categories_func');
add_action('wp_ajax_nopriv_faq_custom_add_categories', 'faq_custom_add_categories_func');

function faq_custom_get_categories_func() {
    global $wpdb;
    $nonce = $_POST['nonce'];
    if (wp_verify_nonce($nonce, 'faq_custom_nonce')) {
        ob_start();
        $table = new FAQ_Categories_Custom_WP_List_Table();
        require_once get_template_directory() . '/admin-custom/faq/templates/faq-categories-list.php';
        echo ob_get_clean();
    }
    die();
}

function faq_custom_add_categories_func() {
    global $wpdb;
    $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

    $nonce = $_POST['nonce'];
    $data_insert = [
        'title' => $_POST['title']
    ];

    $json_result = json_encode(['status' => '0', 'data' => null]);
    if (wp_verify_nonce($nonce, 'faq_custom_nonce')) {
        $result = $wpdb->insert($faq_categories_custom_table, $data_insert);
        $last_id = $wpdb->insert_id;

        if (!empty($result)) {
            $json_result = json_encode(['status' => '1', 'data' => $last_id]);
        }
    }
    echo $json_result;
    wp_die();
}
