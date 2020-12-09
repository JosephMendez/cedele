<?php
add_filter( 'woocommerce_endpoint_orders_title', 'change_my_account_orders_title', 10, 2 );
function change_my_account_orders_title( $title, $endpoint ) {
    $title = __( "Orders", "woocommerce" );

    return $title;
}

add_filter( 'woocommerce_my_account_my_orders_query', 'custom_my_account_orders', 10, 1 );
function custom_my_account_orders( $args ) {
    $args['posts_per_page'] = 8;
    return $args;
}

// ajax
add_action('wp_ajax_custom_cancel_order', 'func_custom_cancel_order');
add_action('wp_ajax_nopriv_custom_cancel_order', 'func_custom_cancel_order');
function func_custom_cancel_order() {
    $nonce = $_POST['nonce'];
    if (wp_verify_nonce($nonce, 'custom_cancel_order_nonce')) {
      $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : '';

      if ($order_id) {
        $order = wc_get_order($order_id);
        $order->update_status('cancelled');
      }
    }

    ob_start();
    $current_page    = empty($_POST['current_page']) ? 1 : absint($_POST['current_page']);
    $customer_orders = wc_get_orders(
      apply_filters(
        'woocommerce_my_account_my_orders_query',
        array(
          'customer' => get_current_user_id(),
          'page'     => $current_page,
          'paginate' => true,
        )
      )
    );

    wc_get_template(
      'myaccount/orders.php',
      array(
        'current_page'    => absint( $current_page ),
        'customer_orders' => $customer_orders,
        'has_orders'      => 0 < $customer_orders->total,
      )
    );
    echo ob_get_clean();
    die();
}

// -----------
// Redemtions
// ajax
add_action('wp_ajax_custom_get_list_redemptions', 'func_custom_get_list_redemptions');
add_action('wp_ajax_nopriv_custom_get_list_redemptions', 'func_custom_get_list_redemptions');
function func_custom_get_list_redemptions() {
    $nonce = $_POST['nonce'];
    $redemption_code = isset($_POST['redemption_code']) ? $_POST['redemption_code'] : '';

    $data = [
      'redemption_event_code' => $redemption_code,
    ];

    $html_result = '';
    if (wp_verify_nonce($nonce, 'custom_my_redemptions_nonce')) {
      $result = getRedemptionEventDetail($data);
      if ($result->code == 2000) {
        ob_start();
        $redemptions_data = $result->result;
        require_once get_template_directory() . '/front-custom/template/redemtions-table.php';
        $html_result = ob_get_contents();
        ob_end_clean();
      }
    }

    echo $html_result;
    die();
}

add_action('wp_ajax_custom_my_redemptions_action', 'func_custom_my_redemptions_action');
add_action('wp_ajax_nopriv_custom_my_redemptions_action', 'func_custom_my_redemptions_action');
function func_custom_my_redemptions_action() {
    $nonce = $_POST['nonce'];
    $isSuccess = 0;

    $redemption_type = $_POST['redemption_type'] == 'coupon' ? 2 : 1;

    $order_details = [];
    if ($_POST['redemption_type'] == 'coupon') {
      $order_details = [
        ['coupon_code' => $_POST['coupon_code'], 'redemption_type' => 2, 'quantity' => 1]
      ];
    } else {
      $order_details = [
        ['gift_code' => $_POST['coupon_code'], 'redemption_type' => 1, 'quantity' => 1]
      ];
    }

    $data = [
      'redemption_event_code' => 'redeem_default_catalog',
      'member_code' => get_current_user_id(),
      'channel_code' => 'EC',
      'order_time' => gmdate("Y-m-d\TH:i:s\Z"),
      'order_details' => $order_details
    ];

    if (wp_verify_nonce($nonce, 'custom_my_redemptions_nonce')) {
      
      $result = createRedemptionOrderForMember($data);
      if ($result->code == 2000) {
        $isSuccess = 1;
      }
    }

    echo json_encode(['isSuccess' => $isSuccess]);
    die();
}

// Rewards & Vouchers
add_action('wp_ajax_custom_my_rewards_vouchers', 'custom_my_rewards_vouchers_func');
add_action('wp_ajax_nopriv_custom_my_rewards_vouchers', 'custom_my_rewards_vouchers_func');
function custom_my_rewards_vouchers_func() {
  $nonce = $_POST['nonce'];
  $current_page = empty($_POST['current_page']) ? 1 : absint($_POST['current_page']);
  if (wp_verify_nonce($nonce, 'custom_my_rewards_nonce')) {
    ob_start();
    $future_day = new DateTime();
    $future_day->add(new DateInterval('P10D'));
    $future_day = $future_day->format(DATE_RFC3339_EXTENDED);
    $coupons = queryMemberCouponList('2020-08-04T10:04:46.709+00:00', $future_day, 9, $current_page, 'expired_date');
    require_once get_template_directory() . '/front-custom/template/my-coupon-template.php';
    echo ob_get_clean();
  }
  die();
}