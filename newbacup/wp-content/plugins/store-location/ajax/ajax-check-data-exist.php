<?php
add_action('wp_ajax_check_data_exist', 'set_ajax_check_data_exist');
add_action('wp_ajax_nopriv_check_data_exist', 'set_ajax_check_data_exist');

add_action('wp_ajax_sl_check_data_exist', 'set_ajax_check_central_kitchen');
add_action('wp_ajax_nopriv_sl_check_data_exist', 'set_ajax_check_central_kitchen');

function set_ajax_check_data_exist() {
    global $wpdb, $table_store;
    $want_to_delete_id = $_POST['id'];
    $want_to_delete_type = $_POST['type'];

    $result = $wpdb->get_row("SELECT * FROM $table_store WHERE $want_to_delete_type = $want_to_delete_id");

    if (!empty($result)) {
        echo json_encode(['status' => '1']);
    } else {
        echo json_encode(['status' => '0']);
    }

    wp_die();
}

function set_ajax_check_central_kitchen() {
    global $wpdb, $table_store;
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    $result = $wpdb->get_row("SELECT * FROM $table_store WHERE id != $id and central_kitchen = 1", ARRAY_A);

    if (!empty($result)) {
        echo json_encode(['status' => '1', 'store_name' => $result['store_name']]);
    } else {
        echo json_encode(['status' => '0']);
    }

    wp_die();
}