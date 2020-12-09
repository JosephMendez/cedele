<?php
add_action( 'woocommerce_product_import_inserted_product_object', 'inserted_import_product_func', 10, 2);
function inserted_import_product_func( $object, $data ){
    $update_existing = isset($_GET['update_existing']) ? $_GET['update_existing'] : 0;

    $id = $object->get_id();
    $type = $object->get_type();
    $title = $object->get_title();
    $regular_price = $object->get_regular_price();

    $array_type = ["variation", "variable", "simple", "bundle"];
    if (in_array($type, $array_type)) {
        $data = [
          'product_code' => $id,
          'product_name' => $title,
        ];

        if (is_numeric($regular_price)) {
            $data['unit_price'] = $regular_price;
        }

        update_post_meta($id, '_data_import_products', $data);
    }
}

sync_to_edenred_with_data_import();
function sync_to_edenred_with_data_import()
{
    global $wpdb;
    $table_post_meta = $wpdb->prefix . 'postmeta';
    $data_imports = $wpdb->get_results("SELECT * FROM $table_post_meta WHERE meta_key = '_data_import_products'", ARRAY_A);

    $list_n_variations = [];
    if ($data_imports) {
        foreach ($data_imports as $key => $data) {
            $list_n_variations[] = maybe_unserialize($data['meta_value']);
        }
    }

    if (count($list_n_variations) > 0) {
        batchCreateOrUpdateProducts(['products' => $list_n_variations]);
        $wpdb->delete($table_post_meta, ['meta_key' => '_data_import_products']);
    }
}