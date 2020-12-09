<?php

add_action('user_register', 'custom_new_customer_registered', 10 , 2);
function custom_new_customer_registered($user_login) {
  $firstname = '';
  $form_data = json_decode(stripslashes($_REQUEST['form_data']), true);
  if (!json_last_error()) {
    foreach ($form_data as $key => $data) {
      if ($data['field_name'] == 'first_name') {
        $firstname = $data['value'];
      }
    }
  }
  ob_start();
  $user_info = get_userdata($user_login);
  require_once get_template_directory() . '/woocommerce/emails/customer-new-account.php';
  $message = ob_get_contents();
  ob_end_clean();

  $email_subject = 'Welcome to Cedele Market!';
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($user_info->user_email, $email_subject, $message, $headers);
}

add_action('woocommerce_order_status_completed', 'change_order_status_completed');
function change_order_status_completed($order_id)
{
    $order = wc_get_order($order_id);
    ob_start();
    require_once get_template_directory() . '/email-template/admin-completed-order.php';
    $message = ob_get_contents();
    ob_end_clean();

    $emails_list = array();
    $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
    if($wc_order_custom_email) {
        $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
        array_push($emails_list, $emailShipping);
    }

    $order_billing_email = $order->get_billing_email();
    array_push($emails_list, $order_billing_email);

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $result = wp_mail($emails_list, 'Your CEDELE MARKET order is now complete', $message, $headers);
}

add_action('woocommerce_order_status_created', 'change_order_status_created');
function change_order_status_created($order_id)
{
  $order = wc_get_order($order_id);
  ob_start();
  require_once get_template_directory() . '/woocommerce/emails/admin-new-order.php';
  $message = ob_get_contents();
  ob_end_clean();

  $emails_list = array();
  $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
      array_push($emails_list, $emailShipping);
  }

  $order_billing_email = $order->get_billing_email();
  array_push($emails_list, $order_billing_email);

  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($emails_list, 'Your order has been confirmed', $message, $headers);
}

add_action('woocommerce_order_status_cancelled', 'change_order_status_cancelled');
function change_order_status_cancelled($order_id)
{
  $order = wc_get_order($order_id);
  ob_start();
  require_once get_template_directory() . '/email-template/admin-cancelled-order.php';
  $message = ob_get_contents();
  ob_end_clean();

  $emails_list = array();
  $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
      array_push($emails_list, $emailShipping);
  }

  $order_billing_email = $order->get_billing_email();
  array_push($emails_list, $order_billing_email);

  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($emails_list, "We're sorry...", $message, $headers);
}
// customer cancelled order
add_action('custom_email_customer_cancel_order', 'send_email_customer_cancel_order', 10, 1);
function send_email_customer_cancel_order($order_id)
{
  $order = wc_get_order($order_id);
  $coupon = createCouponForRefund($order_id);
  $expiry_date = date('Y-m-d', strtotime("now + 1 years"));
	$expiry_date = date("d/m/Y", strtotime($expiry_date));

	ob_start();
  require_once get_template_directory() . '/email-template/customer-cancelled-order.php';
  $message = ob_get_contents();
  ob_end_clean();

  $emails_list = array();
  $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
      array_push($emails_list, $emailShipping);
  }

  $order_billing_email = $order->get_billing_email();
  array_push($emails_list, $order_billing_email);
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($emails_list, 'Your Order has been cancelled', $message, $headers);
}

add_action('woocommerce_order_status_failed', 'change_order_status_failed');
function change_order_status_failed($order_id)
{
  $order = wc_get_order($order_id);
  ob_start();
  require_once get_template_directory() . '/email-template/admin-failed-order.php';
  $message = ob_get_contents();
  ob_end_clean();

  $emails_list = array();
  $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
      array_push($emails_list, $emailShipping);
  }

  $order_billing_email = $order->get_billing_email();
  array_push($emails_list, $order_billing_email);

  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($emails_list, 'We were eager to serve you but...', $message, $headers);
}

add_action('woocommerce_order_status_delivery', 'change_order_status_delivery');
function change_order_status_delivery($order_id)
{
  $order = wc_get_order($order_id);
  ob_start();
  require_once get_template_directory() . '/email-template/admin-delivery-order.php';
  $message = ob_get_contents();
  ob_end_clean();

  $emails_list = array();
  $wc_order_custom_email = get_post_meta($order_id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order_id, '_shipping_email', true );
      array_push($emails_list, $emailShipping);
  }

  $order_billing_email = $order->get_billing_email();
  array_push($emails_list, $order_billing_email);
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $result = wp_mail($emails_list, 'You will receive your order soon!', $message, $headers);
}

add_action('woocommerce_payment_complete', 'send_email_payment_complete');
function send_email_payment_complete($order_id)
{
  global $wpdb;
  $order = wc_get_order($order_id);
  ob_start();
  require_once get_template_directory() . '/woocommerce/emails/store-new-order.php';
  $mail_message = ob_get_contents();
  ob_end_clean();
  $assigned_store = get_post_meta( $order_id, 'wc_order_assigned_store', true );
  $pickup_store = get_post_meta( $order_id, 'wc_order_pickup_store', true );
  $store_id = $assigned_store ? $assigned_store : $pickup_store;
  $table_store = $wpdb->prefix . 'store_location';
  $store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $store_id", ARRAY_A);
  $headers = array('Content-Type: text/html; charset=UTF-8');

  $user_query = array( 'role__in' => array('order_fulfillment') );
  $order_fulfillment_users = get_users($user_query);
  $emails_list = array();
  foreach ($order_fulfillment_users as $key => $user) {
    array_push($emails_list, $user->user_email);
  }
  if (isset($store) && $store['email_address']) {
    array_push($emails_list, $store['email_address']);
  }

  if (!in_array('online@cedeledepot.com', $emails_list, true)) {
	  array_push($emails_list, 'online@cedeledepot.com');
  }

  foreach ($emails_list as $key => $email) {
    wp_mail($email, 'Order Transaction ID Created', $mail_message, $headers);
  }
  wp_mail($email, 'orders@cedeledepot.com', $mail_message, $headers);
}

add_filter( 'woocommerce_email_recipient_new_order', 'custom_new_order_email_recipient', 10, 2 );
function custom_new_order_email_recipient( $recipient, $order ) {
  // Avoiding backend displayed error in Woocommerce email settings for undefined $order
  if ( ! is_a( $order, 'WC_Order' ) )
      return $recipient;

  $emailExtra = '';
  $wc_order_custom_email = get_post_meta($order->id, 'wc_order_custom_email', true);
  if($wc_order_custom_email) {
      $emailShipping = get_post_meta( $order->id, '_shipping_email', true );
      $emailExtra = $emailShipping ? ','.$emailShipping:null;
  }

  // Check order items for a shipped product is in the order
  foreach ( $order->get_items() as $item ) {
    $product = $item->get_product(); // Get WC_Product instance Object

    // When a product needs shipping we add the customer email to email recipients
    if ( $product->needs_shipping() ) {
        return $recipient . ',' . $order->get_billing_email().$emailExtra;
    }
  }

  return $recipient;
}
?>
