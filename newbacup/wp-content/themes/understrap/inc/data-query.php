<?php
/**
 * UnderStrap Data Helper
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$table_store = $wpdb->prefix . 'store_location';
$table_master_data = $wpdb->prefix . 'store_master_data';
$table_working_time = $wpdb->prefix . 'store_working_time';
$table_holiday = $wpdb->prefix . 'store_holiday';
$table_store_holiday = $wpdb->prefix . 'store_holiday_related';

if ( ! function_exists( 'get_store_locations' ) ) {
  function get_store_locations()
    {
      global $wpdb, $table_store;
      $results = $wpdb->get_results( "SELECT * FROM $table_store WHERE status = 1", OBJECT );
      return $results ? $results : [];
    }
}

if ( ! function_exists( 'get_master_data' ) ) {
  function get_master_data()
    {
      global $wpdb, $table_master_data;
      $result = $wpdb->get_results("SELECT * FROM $table_master_data", OBJECT );
      if ($result) {
        return $result;
      }
      return [];
    }
}

if ( ! function_exists( 'find_master_data' ) ) {
  function find_master_data($id)
    {
      global $wpdb, $table_master_data;
      $result = $wpdb->get_row("SELECT * FROM $table_master_data WHERE id = $id");
      if ($result) {
        return $result->data_name;
      }
      return '';
    }
}

if ( ! function_exists( 'get_working_time' ) ) {
  function get_working_time($id)
    {
      global $wpdb, $table_working_time;
      $result = $wpdb->get_results("SELECT * FROM $table_working_time WHERE store_id = $id", OBJECT);
      return $result;
    }
}

if ( ! function_exists( 'cedele_get_featured_categories' ) ) {
  function cedele_get_featured_categories()
    {
      global $wpdb;
      $table_term = $wpdb->prefix . 'termmeta';
      $features = $wpdb->get_results("SELECT * FROM $table_term WHERE meta_key = 'cdls_homes_etting_featured_product'");
      return $features;
    }
}

if ( ! function_exists( 'cedele_get_highlight_categories' ) ) {
  function cedele_get_highlight_categories()
    {
      global $wpdb;
      $table_term = $wpdb->prefix . 'termmeta';
      $highlights = $wpdb->get_results("SELECT * FROM $table_term WHERE meta_key = 'cdls_homes_etting_highlight'");
      return $highlights;
    }
}

add_action( 'wp_ajax_list_store', 'get_list_store' );
add_action( 'wp_ajax_nopriv_list_store', 'get_list_store' );
function get_list_store() {
  global $wpdb, $table_store;

  $outlet = '';
  if (isset($_POST['outlet'])){
    $outlet = 'AND outlet_type = '.$_POST['outlet'];
  }
  if (isset($_POST['district'])) {
    $district = $_POST['district'];
    $results = $wpdb->get_results( "SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 0 AND district IN ($district) $outlet", OBJECT );
  } elseif (isset($_POST['area'])) {
    $area = $_POST['area'];
    $results = $wpdb->get_results( "SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 0 AND area IN ($area) $outlet", OBJECT );
  } else {
    $results = $wpdb->get_results( "SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 0 $outlet", OBJECT );
  }

  $items = array();
  foreach ($results as $key=>$location) {
    $last_time_order = get_option('last_time_order');
    $district = find_master_data($location->district);
    $area = find_master_data($location->area);
    $building = ($location->floor_unit || $location->building) ? ', '.$location->floor_unit.' '.$location->building : '';
    $working_time = get_working_time($location->id);
    $img = $location->image_id ? wp_get_attachment_image_src($location->image_id, 'medium') : '';
    $file = $location->file_id ? wp_get_attachment_url($location->file_id) : '#';

    $currentDay = strtolower(date('l'));

    $working_item_today = array_filter($working_time, function ($w) use ($currentDay) {
      return $w->working_day == $currentDay;
    });

    $last_order = count($working_item_today) > 0 ? array_values($working_item_today)[0]: null;

    $hours = floor($last_time_order/60);
    $minutes = $last_time_order % 60;

    $last_time_order = $hours . ':' . $minutes . ':00';

    $last_time_order_to_time = strtotime($last_time_order);
    $end_time = strtotime($last_order->end_working_time);
    $data = ($end_time - $last_time_order_to_time) / 3600;

    $h = (floor($data) < 10 && floor($data) > 0) ? '0'. floor($data) : floor($data);

    $m = ( ($data-floor($data)) * 60 );

    $last_order_finish = $h . ':' . $m;

    $item = (object) [
      'location' => $location,
      'district' => $district,
      'area' => $area,
      'img' => $img,
      'file' => $file,
      'working_time' => $working_time,
      'last_order' => $last_order_finish,
    ];
    array_push($items, $item);
  }

  wp_send_json_success(json_encode($items));

  die();
}

add_action( 'wp_ajax_get_store_working_days', 'get_store_working_days' );
add_action( 'wp_ajax_nopriv_get_store_working_days', 'get_store_working_days' );
function get_store_working_days() {
  global $wpdb;
  $object = new stdClass();
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $holidays = $wpdb->get_results("SELECT start_date, end_date FROM `{$wpdb->prefix}store_holiday` WHERE id in (SELECT holiday_id FROM wp_store_holiday_related WHERE store_id = ($id) )");
    $date_working = $wpdb->get_results("SELECT * from {$wpdb->prefix}store_working_time WHERE store_id = ($id) ");
    $object->holidays = $holidays;
    $object->date_working = $date_working;
  }
  wp_send_json_success(json_encode($object));
  die();
};

add_action( 'wp_ajax_get_store_products', 'get_store_products' );
add_action( 'wp_ajax_nopriv_get_store_products', 'get_store_products' );
function get_store_products() {
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
};
