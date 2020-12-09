<?php
add_action('wp_ajax_get_list', 'get_list_product');
add_action('wp_ajax_nopriv_get_list', 'get_list_product');

add_action('wp_ajax_search_product', 'search_product');
add_action('wp_ajax_nopriv_search_product', 'search_product');

add_action('wp_ajax_toggle_stock', 'toggle_stock');
add_action('wp_ajax_nopriv_toggle_stock', 'toggle_stock');

add_action('wp_ajax_cdls_shipping_rider', 'ajax_cdls_shipping_rider');
add_action('wp_ajax_nopriv_cdls_shipping_rider', 'ajax_cdls_shipping_rider');

add_action('wp_ajax_cdls_rider_management', 'ajax_cdls_rider_management');
add_action('wp_ajax_nopriv_cdls_rider_management', 'ajax_cdls_rider_management');

function get_list_product() {
    global $wpdb;
    $store_id = $_POST['store_id'];

    $result = $wpdb->get_results("
        SELECT wp_posts.id, wp_posts.post_title, wp_store_location_post.is_in_stock
        FROM wp_posts, wp_store_location_post
        WHERE wp_store_location_post.post_id = wp_posts.id
        AND wp_store_location_post.store_id = ${store_id} AND wp_store_location_post.post_id
        IN (SELECT post_id FROM wp_postmeta WHERE meta_key = 'delivery_method'
        AND (meta_value = 'both' OR meta_value = 'self')) ORDER BY wp_posts.id
        "
    );

    if (!empty($result)) {
        echo json_encode(['status' => '1', 'data' => $result]);
    } else {
        echo json_encode(['status' => '0', 'data' => null]);
    }

    wp_die();
}

function search_product() {
    global $wpdb;
    $store_id = $_GET['store_id'];
    $q = $_GET['q'];

    $result = $wpdb->get_results("
        SELECT wp_posts.id, wp_posts.post_title, wp_store_location_post.is_in_stock
        FROM wp_posts, wp_store_location_post
        WHERE wp_store_location_post.post_id = wp_posts.id
        AND wp_store_location_post.store_id = ${store_id} AND wp_store_location_post.post_id
        IN (SELECT post_id FROM wp_postmeta WHERE meta_key = 'delivery_method'
        AND (meta_value = 'both' OR meta_value = 'self'))
        AND wp_posts.post_title LIKE '%${q}%'
        ORDER BY wp_posts.id
    "
    );

    if (!empty($result)) {
        echo json_encode(['status' => '1', 'data' => $result]);
    } else {
        echo json_encode(['status' => '0', 'data' => null]);
    }

    wp_die();
}

function toggle_stock() {
    global $wpdb;
    $product_id = $_POST['product_id'];
    $value = $_POST['is_in_stock'];
    $store_id = $_POST['store_id'];

    $result = $wpdb->update('wp_store_location_post', array('is_in_stock' => $value), array('post_id' => $product_id, 'store_id' => $store_id));

    echo json_encode(['status' => '1', 'data' => $result]);

    wp_die();
}

function ajax_cdls_shipping_rider()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cedele_setting_shipping_partner';
    $table_name_rider = $wpdb->prefix . 'cedele_setting_riders';

    if (empty($_POST['get_list'])) {
        $input = [
            'id'             => 0,
            'partner_name'   => '',
            'short_name'     => '',
            'contact_number' => '',
            'status'         => 1,
        ];
        $data = shortcode_atts($input, $_POST);
        $data['status'] = !empty($_POST['status']) ? 1 : 0;
        $data = array_map('trim', $data);

        if (!empty($data['id'])) {
            $wpdb->update($table_name, $data, ['id' => $data['id']]);
        } else {
            $wpdb->insert($table_name, $data);
        }
    }
    $result = $wpdb->get_results("SELECT *, GROUP_CONCAT(riders.rider_name SEPARATOR ', ') as rider_name_concat FROM $table_name 
        LEFT JOIN
        (
            SELECT id as rider_id, partner_id, rider_name
            FROM $table_name_rider
            WHERE status = 1
        ) as riders
        ON $table_name.id = riders.partner_id
        GROUP BY id
    ");

    if (!empty($result)) {
        echo json_encode(['status' => '1', 'data' => $result]);
    } else {
        echo json_encode(['status' => '0', 'data' => []]);
    }

    wp_die();
}

function ajax_cdls_rider_management()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cedele_setting_riders';
    $table_name_postmeta = $wpdb->prefix . 'postmeta';

    if (empty($_POST['get_list'])) {
        $input = [
            'id'             => 0,
            'rider_name'     => '',
            'contact_number' => '',
            'partner_id'     => '',
            'status'         => 1,
        ];
        $data = shortcode_atts($input, $_POST);
        $data['status'] = !empty($_POST['status']) ? 1 : 0;
        $data = array_map('trim', $data);

        if (!empty($data['id'])) {
            $wpdb->update($table_name, $data, ['id' => $data['id']]);
        } else {
            $wpdb->insert($table_name, $data);
        }
    }
    
    $result = $wpdb->get_results("
        SELECT *, postmeta.post_id, count(postmeta.meta_value) as total_order
        FROM $table_name
        LEFT JOIN (
            SELECT post_id, meta_value FROM $table_name_postmeta
            WHERE meta_key = 'wp_custom_order_rider'
        ) as postmeta
        ON $table_name.id = postmeta.meta_value
        GROUP BY id
    ");

    if (!empty($result)) {
        echo json_encode(['status' => '1', 'data' => $result]);
    } else {
        echo json_encode(['status' => '0', 'data' => []]);
    }

    wp_die();
}


function upload_file(){
    global $wpdb, $example_file_dir;
    if(isset($_FILES["file"]["name"]) && !empty($_FILES["file"]["name"])){
        $target_file = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cedele-setting/ajax/example.xls";
        $uploadOk = 1;
    // Check file size
        if ($_FILES["file"]["size"] > 500000) {
            echo "Upload max file 5Mb";
            wp_die();
        }
        if(file_exists($target_file)){
            unlink($target_file);
        }
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $start_from = isset($_POST['start_from']) ? $_POST['start_from'] : 2;
            $limit = isset($_POST['limit']) ? $_POST['limit'] : 500;
            $result = read_and_create_excel_file($example_file_dir, $start_from, $limit);
            echo 'Success all!';
        } else {
            echo "Error upload file";
        }
        wp_die();
    }
}

add_action('wp_ajax_upload_file', 'upload_file');
add_action('wp_ajax_nopriv_upload_file', 'upload_file');

function send_email_func(){
    global $wpdb, $example_file_dir;
    if(isset($_FILES["file"]["name"]) && !empty($_FILES["file"]["name"])){
        $target_file = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cedele-setting/ajax/example.xls";
        $uploadOk = 1;
        // Check file size
        if ($_FILES["file"]["size"] > 500000) {
            echo "0";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "0";
            // if everything is ok, try to upload file
        } else {
            if(file_exists($target_file)){
                unlink($target_file);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $start_from = isset($_POST['start_from']) ? $_POST['start_from'] : 2;
                $limit = isset($_POST['limit']) ? $_POST['limit'] : 500;
                $result = read_excel_file($example_file_dir, $start_from, $limit);
                $readable_rows = $result['readable_rows'];
                $total_rows = $result['total_rows'];
                $is_remain = $result['is_remain'];
                echo '1';
                wp_die();
            } else {
                echo "0";
            }
        }
        wp_die();
    }
}

add_action('wp_ajax_send_email_func', 'send_email_func');
add_action('wp_ajax_nopriv_send_email_func', 'send_email_func');

