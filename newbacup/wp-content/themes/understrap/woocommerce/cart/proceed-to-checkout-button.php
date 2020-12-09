<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php
    $customerAddress = json_decode(stripslashes($_COOKIE['customerAddress']));
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
        $product_type = get_post_meta($product_id, '_type', true);
        $post_meta = get_post_custom($product_id);

        $availableTimeFrom = get_post_meta($product_id, 'daily-product-available-time-from', true);
        $availableTimeTo = get_post_meta($product_id, 'daily-product-available-time-to', true);
        $timeDelivery = explode('-',$customerAddress->time);

        $productLeadTime = get_post_meta($cart_item['product_id'], 'product-lead-time-checkbox', true);
        $isAdvancedProduct = $productLeadTime == 'advance';
        if ($isAdvancedProduct){
            date_default_timezone_set("Asia/Singapore");
            $cot_order = get_option('cot_order', $item['cot_order']);
            $extraLeadDay = 0;
            if ($cot_order){
                $cot = strtotime($cot_order.':00');
                $curr_time = time();
                if ($curr_time >= $cot){
                    $extraLeadDay += 1;
                }
            }
            $productLeadTimeDays = get_post_meta($cart_item['product_id'], 'product-lead-time-days', true);
            $address_session = WC()->session->get('customerAddress');
            $totalLeadTimeDays = $productLeadTimeDays + $extraLeadDay;
            if ($address_session->date) {
                $today = date('d M Y');
                $availableDate = date_create_from_format('d M Y', $today)->modify('+'.$totalLeadTimeDays.' day');
                $customerChoosenDate = date_create_from_format('d M Y', $address_session->date);
            }
        }
        if ($availableDate > $customerChoosenDate && $isAdvancedProduct){
            WC()->session->set('cart_valid', false);
        }
        if(!(strtotime($timeDelivery[0]) >= strtotime($availableTimeFrom) &&  strtotime($timeDelivery[0]) <=  strtotime($availableTimeTo)) && !(strtotime($timeDelivery[1]) >= strtotime($availableTimeFrom) &&  strtotime($timeDelivery[1]) <=  strtotime($availableTimeTo)))  {
            WC()->session->set('cart_valid', false);
        }

        $listDay = [
            'monday' => __('Mon', 'understrap'),
            'tuesday' => __('Tue', 'understrap'),
            'wednesday' => __('Wed', 'understrap'),
            'thursday' => __('Thu', 'understrap'),
            'friday' => __('Fri', 'understrap'),
            'saturday' => __('Sat', 'understrap'),
            'sunday' => __('Sun', 'understrap'),
        ];
        $checked_date = array();
        foreach ($listDay as $key => $value) {
            if (isset($post_meta[$key]) && $post_meta[$key][0] == 'yes'){
                array_push($checked_date, $value);
            }
        }

        if ($customerAddress->date) {
            $delivery_date = DateTime::createFromFormat('j M Y', $customerAddress->date);
            $delivery_day = $delivery_date->format('D');
            $is_available_on_delivery_day = in_array($delivery_day, $checked_date);
        }
        if ($product_type == 'daily-product' && isset($is_available_on_delivery_day) && !$is_available_on_delivery_day){
            WC()->session->set('cart_valid', false);
        }

    }

    $isCartValid = WC()->session->get('cart_valid');
    $deliveryAddress = json_decode(stripslashes($customerAddress->deliveryAddress));
    $noZipCode = $customerAddress->deliveryType == 'delivery' && !isset($deliveryAddress->zipcode);
    $isDisableCheckout = $customerAddress->deliveryType == 'delivery' && !$customerAddress->deliveryAddress || !$isCartValid || $noZipCode ||!$customerAddress->time;
?>
<?php if(!is_user_logged_in()){
	?>
	<a id="btnCartLogin" href="#" class="btn btn-primary btn-lg btn-block heading-font text-uppercase <?php echo $isDisableCheckout ? 'disabled' : ''?>">
		<?php esc_html_e( 'Proceed to checkout', 'understrap' ); ?>
	</a>
<?php
} else {
	?>
	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary btn-lg btn-block heading-font text-uppercase <?php echo $isDisableCheckout ? 'disabled' : ''?>">
		<?php esc_html_e( 'Proceed to checkout', 'understrap' ); ?>
	</a>
<?php
} ?>

