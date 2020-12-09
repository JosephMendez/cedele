<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php
function changeFormatDatetime($inputDate, $formatDate = 'Y-m-d') {
    $output = null;

    if ($date = DateTime::createFromFormat('Y-m-d', $inputDate)) {
        $output = $date;
    } else if ($date = DateTime::createFromFormat('Y/m/d', $inputDate)) {
        $output = $date;
    } else {
        return;
    }

    return $output->format($formatDate);
}

function cut_end_time_collection_time($collection_time) {
    if ($collection_time) {
        $array_time = explode(" ", $collection_time);
        $result = array_pop($array_time);
        return strlen($result) > 4 ? $result : ('0' . $result);
    }
    return ;
}
?>

<?php if ( $has_orders ) : ?>

    <div class="myorder-panel-list" data-current-page="<?php echo $current_page ?>">
        <?php
        foreach ( $customer_orders->orders as $customer_order ) {
            $order = wc_get_order($customer_order);

            // order info
            $order_id = $order->get_id();
            $order_items = $order->get_items();
            $order_status  = $order->get_status();
            $is_cancelled_failed = $order_status == 'cancelled' || $order_status == 'failed';
            $order_note = $order->get_customer_note();
            $line_items_fee = $order->get_items('fee');

            // order method & address, time
            $order_method = get_post_meta($order_id, 'wp_custom_order_method', true);
            $order_delivery_address = get_post_meta($order_id, 'wp_custom_order_delivery_address', true);
            $order_delivery_date = get_post_meta($order_id, 'wp_custom_order_delivery_date', true);
            $order_delivery_collection_time = get_post_meta($order_id, 'wp_custom_order_delivery_collection_time', true);

            // price
            $sub_total = $order->get_subtotal();
            $discount_total = $order->get_discount_total();
            $shipping_total = $order->get_shipping_total();
            $total_price = $order->get_total();
			$redemp_point = 0;
			if ($order_status !== 'processing') {
				$redemp_point = get_post_meta($order_id, 'used_redemp_point', true);
			}

			// order method
            $order_method_title = '';
            if ($order_method == 'delivery') {
                $order_method_title = 'Delivery';
            } else if ($order_method == 'both') {
                $order_method_title = 'Delivery & Self-Collection';
            } else {
                $order_method_title = 'Self-Collection';
            }

            // address pickup order or delivery address
            $delivery_or_pickup_address = '';
            if ($order_method == 'delivery' || $order_method == 'both') {
                $delivery_or_pickup_address = esc_html($order_delivery_address);
            } else {
                global $wpdb;
                $pickup_store = null;
                $table_store = $wpdb->prefix . 'store_location';
                $pickup_order_id = get_post_meta($order_id, 'wc_order_pickup_store', true);
                if ($pickup_order_id) {
                    $pickup_store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $pickup_order_id", ARRAY_A);
                }
                if ( $pickup_store ) {
                    if ($pickup_store['store_name']) {
                        $delivery_or_pickup_address .= esc_html($pickup_store['store_name']) . ', ';
                    }
                    if ($pickup_store['number_house'] || $pickup_store['street_name']) {
                        $delivery_or_pickup_address .= trim(esc_html($pickup_store['number_house']) . ' ' . esc_html($pickup_store['street_name']) . ', ');
                    }
                    if ($pickup_store['floor_unit']) {
                        $delivery_or_pickup_address .= esc_html($pickup_store['floor_unit']) . ', ';
                    }
                    if ($pickup_store['building']) {
                        $delivery_or_pickup_address .= esc_html($pickup_store['building']) . ', ';
                    }
                    $delivery_or_pickup_address .= esc_html($billing_country) . ' ';
                    if ($pickup_store['zipcode']) {
                        $delivery_or_pickup_address .= esc_html($pickup_store['zipcode']);
                    }
                }
            }
            ?>
            <div class="myorder-panel-item <?php echo $is_cancelled_failed ? 'is-cancelled-failed' : '' ?>" data-order-id="<?php echo $order_id; ?>">
                <div class="myorder-panel-info">
                    <div>
                        <span class="panel-item-dot dot-<?php echo strtolower($order_status) ?>"></span>
                        <span class="panel-item-label panel-item-label-<?php echo strtolower($order_status) ?>"><?php echo $order_status ?></span>
                    </div>
                    <div>
                        <span class="panel-item-title">Order No.</span>
                        <span class="panel-item-label">#<?php echo $order_id ?></span>
                    </div>
                    <div>
                        <span class="panel-item-title">Coll. method</span>
                        <span class="panel-item-label">
                            <?php
                                echo $order_method_title;
                            ?>
                        </span>
                    </div>
                    <div>
                        <span class="panel-item-title">Pickup time</span>
                        <span class="panel-item-label">
                            <span><?php echo $order_delivery_date; ?></span>
                            <span><?php echo $order_delivery_collection_time; ?></span>
                        </span>
                    </div>
                    <div class="panel-item-address">
                        <span class="panel-item-title">Delivery/Pickup Address</span>
                        <span class="panel-item-label"><?php echo $delivery_or_pickup_address; ?></span>
                    </div>
                    <div>
                        <span class="panel-item-title">Total</span>
                        <span class="panel-item-label">$<?php echo $total_price - $redemp_point ?></span>
                    </div>
                </div>
                <div class="myorder-panel-info-phone">
                    <div class="myorder-panel-info-phone-header">
                        <div>
                            <span class="panel-item-dot dot-<?php echo strtolower($order_status) ?>"></span>
                            <span class="panel-item-label panel-item-label-<?php echo strtolower($order_status) ?>"><?php echo $order_status ?></span>
                        </div>
                        <div>Order No: #<?php echo $order_id; ?></div>
                    </div>
                    <div class="myorder-panel-info-phone-content">
                        <div class="myorder-panel-info-phone-content-row">
                            <div class="myorder-panel-info-phone-content-left">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 14C13.6569 14 15 12.6569 15 11C15 9.34315 13.6569 8 12 8C10.3431 8 9 9.34315 9 11C9 12.6569 10.3431 14 12 14Z" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17.657 16.6572L13.414 20.9002C13.2284 21.0859 13.0081 21.2333 12.7656 21.3338C12.523 21.4344 12.2631 21.4861 12.0005 21.4861C11.738 21.4861 11.478 21.4344 11.2354 21.3338C10.9929 21.2333 10.7726 21.0859 10.587 20.9002L6.343 16.6572C5.22422 15.5384 4.46234 14.1129 4.15369 12.5611C3.84504 11.0092 4.00349 9.40071 4.60901 7.93893C5.21452 6.47714 6.2399 5.22774 7.55548 4.3487C8.87107 3.46967 10.4178 3.00049 12 3.00049C13.5822 3.00049 15.1289 3.46967 16.4445 4.3487C17.7601 5.22774 18.7855 6.47714 19.391 7.93893C19.9965 9.40071 20.155 11.0092 19.8463 12.5611C19.5377 14.1129 18.7758 15.5384 17.657 16.6572V16.6572Z" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span><?php echo $delivery_or_pickup_address; ?></span>
                            </div>
                            <div class="myorder-panel-info-phone-content-right">
                                Total Price
                                <span class="myorder-panel-info-phone-content-right-price">$<?php echo $total_price - $redemp_point ?></span>
                            </div>
                        </div>
                        <div class="myorder-panel-info-phone-content-row">
                            <div class="myorder-panel-info-phone-content-left">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 7V12L15 15" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>
                                    <?php echo esc_html("$order_delivery_date, $order_delivery_collection_time"); ?>
                                </span>
                            </div>
                            <div class="myorder-panel-info-phone-content-right">
                                <?php echo $order_method_title ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="myorder-table">
                    <div class="myorder-table-header">
                        <div class="myorder-table-label">Item</div>
                        <div class="myorder-table-amount">Price</div>
                        <div class="myorder-table-amount">Quantity</div>
                        <div class="myorder-table-amount myorder-table-subtotal">Subtotal</div>
                    </div>
                    <?php
                    $is_can_cancel = false;
                    $can_cancel_date = null;

                    foreach ($order_items as $item_key => $item):
                        // $item_meta_data = $item->get_meta_data();

                        // // get only All item meta data even hidden (in an unprotected array)
                        // $formatted_meta_data = $item->get_formatted_meta_data( '_', true );

                        // // Display the raw outputs (for testing)
                        // echo '<pre>'; print_r($item_meta_data); echo '</pre>';
                        // echo '<pre>'; print_r($formatted_meta_data); echo '</pre>';


                        $item_data    = $item->get_data();
                        $product      = $item->get_product();
                        $custom_field = $item->get_meta('_alg_wc_pif_local', true);
                        if (count($custom_field) > 0){
                            $custom_field_title = $custom_field[0]['title'];
                            $custom_field_value = $custom_field[0]['_value'];
                        }
						$used_redemp_point = get_post_meta($item['order_id'], 'used_redemp_point', true);
                    ?>
                        <?php
                            $product_name   = $item_data['name'];
                            $product_id   = $item_data['product_id'];
                            $variation_id = $item_data['variation_id'];
                            $quantity     = $item_data['quantity'];
                            $tax_class    = $item_data['tax_class'];
                            $line_subtotal     = $item_data['subtotal'];
                            $line_subtotal_tax = $item_data['subtotal_tax'];
                            $line_total        = $item_data['total'];
                            $line_total_tax    = $item_data['total_tax'];

                            $product_price = 0;
                            if (intval($quantity) > 0)
                                $product_price = wc_format_decimal((floatval($line_subtotal) / intval($quantity)), 2);

                            $product_lead_time_checkbox = get_post_meta($product_id, 'product-lead-time-checkbox', true);
                            $end_time = cut_end_time_collection_time($order_delivery_collection_time);
                            $end_date = changeFormatDatetime($order_delivery_date);

                            if ($end_time && $end_date) {
                                $date_order = DateTime::createFromFormat('Y-m-d H:i', "$end_date $end_time");
                                $date_condition_now = new DateTime(date('Y-m-d H:i'));
                                if ($date_order) {
                                    $date_condition_now = null;
                                    if ($product_lead_time_checkbox == 'same') {
                                        $date_order = $date_order->sub(new DateInterval("PT3H"));
                                    } else {
                                        $date_order = $date_order->sub(new DateInterval("PT36H"));
                                    }

                                    if ($date_condition_now < $date_order) {
                                        $is_can_cancel = true;

                                        if ($date_order < $can_cancel_date || $can_cancel_date == null) {
                                            $can_cancel_date = $date_order;
                                        }
                                    } else {
                                        $is_can_cancel = false;
                                    }
                                }
                            }
                        ?>
                            <div class="myorder-table-item">
                                <div class="myorder-table-label">
                                    <?php echo esc_html($product_name); ?>
                                    <span class="myorder-table-label-quantity-phone">x<?php echo intval($quantity); ?>
                                    </span>
                                    <?php if ($custom_field_value) {?>
                                        <ul class="wc-item-meta">
                                            <li>
                                                <strong class="wc-item-meta-label"><?php echo $custom_field_title; ?></strong>
                                                <p><?php echo $custom_field_value; ?></p>
                                            </li>
                                        </ul>
                                    <?php } ?>
                                    <?php
                                        wc_display_item_meta( $item );
                                    ?>
                                </div>
                                <div class="myorder-table-amount">$<?php echo floatval($product_price) == 0 ? $product_price : wc_format_decimal($product_price, 2); ?></div>
                                <div class="myorder-table-amount">
                                    <?php echo (intval($quantity) < 10 && intval($quantity) != 0) ? ('0' . intval($quantity)) : intval($quantity) ?>
                                </div>
                                <div class="myorder-table-amount myorder-table-subtotal">$<?php echo floatval($line_subtotal) == 0 ? $line_subtotal : wc_format_decimal($line_subtotal, 2); ?></div>
                            </div>
                    <?php endforeach; ?>
                    <?php if ($order_note) {?>
                    <div class="myorder-table-price">
                        <div class="myorder-table-label">
                            Note
                            <p class="customer-note"><?php echo $order_note; ?></p>
                        </div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount myorder-table-subtotal myorder-table-amount-discount">
                        </div>
                    </div>
                    <?php } ?>
                    <div class="myorder-table-price">
                        <div class="myorder-table-label">
                            Discount
                        </div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount myorder-table-subtotal myorder-table-amount-discount">-$<?php floatval($discount_total) == 0 ? esc_html_e($discount_total) : esc_html_e(wc_format_decimal($discount_total, 2)); ?></div>
                    </div>
                    <div class="myorder-table-price">
                        <div class="myorder-table-label">
                            Shipping
                        </div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount"></div>
                        <div class="myorder-table-amount myorder-table-subtotal">$<?php floatval($shipping_total) == 0 ? esc_html_e($shipping_total) : esc_html_e(wc_format_decimal($shipping_total, 2)); ?></div>
                    </div>
                    <?php foreach ( $line_items_fee as $item_id => $item ) { ?>
                        <div class="myorder-table-price">
                            <div class="myorder-table-label">
                                <?php echo esc_html( $item->get_name() ? $item->get_name() : __( 'Fee', 'woocommerce' ) ); ?>
                            </div>
                            <div class="myorder-table-amount"></div>
                            <div class="myorder-table-amount"></div>
                            <div class="myorder-table-amount myorder-table-subtotal">
                                $<?php esc_html_e(wc_format_decimal($item->get_total(), 2));?>
                            </div>
                        </div>
                    <?php } ?>
					<?php if(isset($used_redemp_point) && $used_redemp_point > 0) {?>
						<div class="myorder-table-price">
							<div class="myorder-table-label">
								Use point
							</div>
							<div class="myorder-table-amount"></div>
							<div class="myorder-table-amount"></div>
							<div class="myorder-table-amount myorder-table-subtotal myorder-table-amount-discount">
								- <?php echo $used_redemp_point;?>
							</div>
						</div>
					<?php }?>
                </div>
                <div class="myorder-panel-footer">
                    <div class="myorder-panel-footer-info">
                        <?php if($is_can_cancel): ?>
                            <span>This order is cancellable until <?php echo $can_cancel_date ? $can_cancel_date->format('D, jS F H:i') : '' ?></span>
                        <?php else: ?>
                            <span>This order isn't cancellable</span>
                        <?php endif; ?>
                    </div>
                    <?php if($is_can_cancel): ?>
                    <div class="myorder-panel-footer-action">
                        <span class="myorder-panel-footer-cancel">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18" stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 6L18 18" stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>CANCEL</span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="modal cancelOrderModal fade" tabindex="-1" role="dialog" id="cancelOrderModal" data-order-id="0">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Are you sure cancel this order?</p>
                    <div class="form-actions text-right mt-3">
                        <button class="btn btn-light heading-font btn-order-no text-uppercase">No</button>
                        <button class="btn btn-primary heading-font btn-order-yes text-uppercase ml-2">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php //do_action( 'woocommerce_before_account_orders_pagination' ); ?>
    <div class="myorder-panel-pagination">
    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
        <?php
        $args = array(
            'base'          => esc_url( wc_get_endpoint_url( 'orders') ) . '%_%',
            'format'        => '%#%',
            'total'         => $customer_orders->max_num_pages,
            'current'       => $current_page,
            'show_all'      => false,
            'end_size'      => 3,
            'mid_size'      => 3,
            'prev_next'     => true,
            'prev_text'     => 'Pre',
            'next_text'     => 'Next',
            'type'          => 'list',
            'add_args'      => false,
            'add_fragment'  => ''
        );
        echo str_replace( "<ul class='page-numbers'>", '<ul class="front-custom-pagination">', paginate_links( $args ));
        ?>
    <?php endif; ?>
    </div>

<?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="btn btn-outline-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
            <?php esc_html_e( 'Go to the shop', 'understrap' ); ?>
        </a>
        <?php esc_html_e( 'No order has been made yet.', 'understrap' ); ?>
    </div>
<?php endif; ?>

<?php
do_action( 'woocommerce_after_account_orders', $has_orders );
