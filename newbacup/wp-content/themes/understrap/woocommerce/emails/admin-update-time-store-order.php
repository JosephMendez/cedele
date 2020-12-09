<?php
  global $woocommerce, $wpdb;
  $order_id = $order->get_id();
  // delivery collection time & delivery address
  $order_method = get_post_meta($order_id, 'wp_custom_order_method', true);
  $order_delivery_address = get_post_meta($order_id, 'wp_custom_order_delivery_address', true);
  $order_delivery_date = get_post_meta($order_id, 'wp_custom_order_delivery_date', true);
  $order_delivery_collection_time = get_post_meta($order_id, 'wp_custom_order_delivery_collection_time', true);
  $used_redemp_point = get_post_meta($order_id, 'used_redemp_point', true);
  // order data
  $order_items = $order->get_items();
  $order_data = $order->get_data();
$shipping = array_filter($order->get_data()['shipping']) ? $order->get_data()['shipping']:$order->get_data()['billing'];

  $order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
  $currency      = $order->get_currency(); // Get the currency used
  $order_payment_method = $order->get_payment_method(); // Get the payment method ID
  $order_payment_method_title = $order->get_payment_method_title(); // Get the payment method title
  $date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
  $date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
  $billing_email = $order->get_billing_email();
  $billing_country = $order->get_billing_country(); // Customer billing country

  // BILLING INFORMATION:
  $order_billing_first_name = $order_data['billing']['first_name'];
  $billing_country = WC()->countries->countries[$billing_country];

  $sub_total = $order->get_subtotal();
  $discount_total = $order->get_discount_total();
  $shipping_total = $order->get_shipping_total();
  $total_price = $order->get_total();

  $gift_cards = array();
  $gift_cards_meta = $order->get_meta('_gift_cards', false);
  foreach ($gift_cards_meta as $key => $value) {
    $_gift_cards = $value->get_data();
    if ( isset($_gift_cards['value']) && count($_gift_cards['value']) > 0 ) {
      $product_id = $_gift_cards['value'][0]['product_id'];
      if ($product_id) {
        $gift_cards[$product_id] = $_gift_cards['value'];
      }
    }
  }

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8 ;">
    <meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *;font-src 'self';">
    <style>
        body, html {
            padding: 0;
            margin: 0;
        }
        @font-face {
        	font-family: 'Gotham Regular';
        	src: url('fonts/Gotham-Light.eot');
        	src: url('fonts/Gotham-Light.eot?#iefix') format('embedded-opentype'),
        	url('fonts/Gotham-Light.woff2') format('woff2'),
        	url('fonts/Gotham-Light.woff') format('woff'),
        	url('fonts/Gotham-Light.ttf') format('truetype'),
        	url('fonts/Gotham-Light.svg#Gotham-Light') format('svg');
        	font-weight: 300;
        	font-style: normal;
        	font-display: swap;
        }

        @font-face {
        	font-family: 'Gotham Regular';
        	src: url('fonts/Gotham-Book.eot');
        	src: url('fonts/Gotham-Book.eot?#iefix') format('embedded-opentype'),
        	url('fonts/Gotham-Book.woff2') format('woff2'),
        	url('fonts/Gotham-Book.woff') format('woff'),
        	url('fonts/Gotham-Book.ttf') format('truetype'),
        	url('fonts/Gotham-Book.svg#Gotham-Book') format('svg');
        	font-weight: normal;
        	font-style: normal;
        	font-display: swap;
        }

        @font-face {
        	font-family: 'Gotham Regular';
        	src: url('fonts/Gotham-Medium.eot');
        	src: url('fonts/Gotham-Medium.eot?#iefix') format('embedded-opentype'),
        	url('fonts/Gotham-Medium.woff2') format('woff2'),
        	url('fonts/Gotham-Medium.woff') format('woff'),
        	url('fonts/Gotham-Medium.ttf') format('truetype'),
        	url('fonts/Gotham-Medium.svg#Gotham-Medium') format('svg');
        	font-weight: 500;
        	font-style: normal;
        	font-display: swap;
        }

        @font-face {
        	font-family: 'Gotham Regular';
        	src: url('fonts/Gotham-Bold.eot');
        	src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
        	url('fonts/Gotham-Bold.woff2') format('woff2'),
        	url('fonts/Gotham-Bold.woff') format('woff'),
        	url('fonts/Gotham-Bold.ttf') format('truetype'),
        	url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
        	font-weight: bold;
        	font-style: normal;
        	font-display: swap;
        }

        @font-face {
        	font-family: 'Gotham Bold';
        	src: url('fonts/Gotham-Bold.eot');
        	src: url('fonts/Gotham-Bold.eot?#iefix') format('embedded-opentype'),
        	url('fonts/Gotham-Bold.woff2') format('woff2'),
        	url('fonts/Gotham-Bold.woff') format('woff'),
        	url('fonts/Gotham-Bold.ttf') format('truetype'),
        	url('fonts/Gotham-Bold.svg#Gotham-Bold') format('svg');
        	font-weight: bold;
        	font-style: normal;
        	font-display: swap;
        }
        .wpmail-container {
            width: 100%;
            background-color: #F3F3F3;
        }
        .wpmail-container p,
        .wpmail-container h5 {
            margin: 0px;
        }
        .float-right {
            float: right;
        }
        .float-left {
            float: left;
        }
        .clearfix {
            clear: both;
        }

        /* HEADER */
        .wpmail-logo {
            padding: 8px 0px 16px 0px;
            text-align: center;
        }
        .wpmail-logo img {
            width: 174px;
            height: 76px;
        }
        .wpmail-line {
            display: block;
            position: relative;
        }
        .wpmail-line:before {
            position: absolute;
            content: "";
            width: 370px;
            border-top: 1px solid #914204;
            margin-left: -185px;
        }

        /* CONTENT */
        .wpmail-content {
            position: relative;
            padding: 24px 24px 98px 24px;
            width: 568px;
            margin: auto;
            background-color: #FFFFFF;
            border-radius: 4px;
            box-shadow: 0 4px 4px rgba(0,0,0,.25);
            box-sizing: border-box;
            overflow: hidden;
        }
        .wpmail-content .wpmail-text-header {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 24px;
            line-height: 23px;
            text-align: center;
            letter-spacing: 0.5px;
            color: #3F3F3F;
            margin-bottom: 8px;
        }
        .wpmail-content .wpmail-text-header span {
            color: #3C1605;
        }
        .wpmail-content .wpmail-pharse-title {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #3C1605;
        }
        .wpmail-content .wpmail-text-regular {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            letter-spacing: -0.25px;
            color: #3F3F3F;
        }
        /*----------*/
        .wpmail-content-info {
            width: 100%;
        }
        .wpmail-content-info-title {
            width: 100%;
            margin-bottom: 4px;
        }
        .wpmail-content-info-title p {
            display: inline-block;
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #3C1605;
        }
        .wpmail-content-info-text p {
            display: inline-block;
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            align-items: center;
            letter-spacing: -0.25px;
            color: #3F3F3F;
            width: 50%;
        }
        /*----------*/
        .wpmail-line-in-content {
            display: block;
            position: relative;
            width: 100%;
        }
        .wpmail-line-in-content:before {
            position: absolute;
            content: "";
            width: 100%;
            border-top: 1px solid #914204;
        }
        /*----------*/
        .wpmail-order-list {
            display: grid;
            grid-row-gap: 12px;
        }
        .wpmail-order-item {
            display: flex;
        }
        .wpmail-order-item .wpmail-order-item-thumbnail {
            width: 116px;
            height: 92px;
            flex-shrink: 0;
        }
        .wpmail-order-item .wpmail-order-item-thumbnail img {
            width: 100%;
            height: 100%;
        }
        .wpmail-order-item .wpmail-order-item-content {
            width: 100%;
            margin-left: 9px;
        }
        .wpmail-order-title div {
            display: inline-block;
        }
        .wpmail-order-title div:nth-child(1) {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #3F3F3F;
            width: 82%;
        }
        .wpmail-order-title.subtitle div:nth-child(1) {
            font-size: 16px;
            line-height: 28px;
        }
        .wpmail-order-title div:nth-child(2) {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 30px;
            text-align: right;
            letter-spacing: -0.25px;
            color: #000000;
            width: 18%;
            float: right;
        }
        .wpmail-order-item-content p {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            color: #3F3F3F;
            margin: 0px 0px;
        }
        .wpmail-order-item-content p {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            color: #3F3F3F;
            margin: 0px 0px;
        }
        /*----------*/
        /*----------*/
        .wpmail-section-order-info {
            display: block;
        }
        .wpmail-section-order-info.total span {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            letter-spacing: 0.15px;
            color: #000000;
        }
        .wpmail-section-order-info span {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            letter-spacing: -0.25px;
            color: #3F3F3F;
        }
        .wpmail-section-order-info span.red {
            color: #F44336;
        }
        /*----------*/
        .wpmail-shopping {
            text-align: center;
            z-index: 999;
            position: relative
        }
        .wpmail-button-shopping-text {
            margin: auto;
            width: 181px;
            height: 49px;
            /*padding: 16px 31px;*/
            padding: 16px 4px;
            /* Primary/Brown */
            background: #914204;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            box-sizing: border-box;
            color: white !important;
            text-decoration: none;
            cursor: pointer;
            display: block;
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-weight: bold;
            font-size: 16px;
            line-height: 17px;
            /* identical to box height */
            letter-spacing: 0.75px;
            text-transform: uppercase;
        }
        .wpmail-section-footerbg {
            position: absolute;
            bottom: -10px;
            width: 100%;
            height: 139px;
            margin: 0px -24px;
            background-image: url(<?php echo get_template_directory_uri(); ?>/images/emails/fruits.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 0;
        }

        /* Footer */
        .wpmail-footer {
            text-align: center;
            padding: 58px 0px 36px 0px;
        }
        .wpmail-followus {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif , -apple-system, san-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 20px;
            line-height: 28px;
            /* identical to box height, or 140% */
            text-align: center;
            letter-spacing: 0.15px;
            margin-bottom: 12px !important;
            color: #000000;
        }
         .wpmail-social {
            width: 140px;
            display: inline-block;
            height: 36px;
            margin-bottom: 16px;
        }
		.wpmail-social a{
			float: left;
		}
        .wpmail-info {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif, -apple-system, san-serif;
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            text-align: center;
            color: #3F3F3F;
        }
        .wpmail-info-fax {
            margin-left: 37px;
        }
    </style>
</head>
<body>
    <div class="wpmail-container">
        <!-- header -->
        <div class="wpmail-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/emails/logo.png">
            <span class="wpmail-line"></span>
        </div>
        <!-- /header -->
        <!-- content -->
        <div class="wpmail-content">
            <p class="wpmail-text-header">Hi <span style="text-transform: capitalize;"><?php echo $order_billing_first_name; ?>,</span></p>
            <p class="wpmail-text-header" style="margin-bottom: 24px;">Your order information has been updated.</p>
            <div class="wpmail-content-info" style="margin-bottom: 8px;">
                <div class="wpmail-content-info-title">
                    <p><?php echo $order_method == 'delivery' ? 'Delivery Address' : 'Pickup Store'?></p>
                    <p style="float: right; text-align: right">Order Number</p>
                </div>
                <div class="wpmail-content-info-text">
                    <p><?php
                        if ($order_method == 'delivery') {
                            echo $shipping['address_2'].' '.$order_delivery_address;
                        } else {
                            $table_store = $wpdb->prefix . 'store_location';
                            $pickup_order_id = get_post_meta($order_id, 'wc_order_pickup_store', true);
                            $pickup_store = null;
                            if ($pickup_order_id) {
                                $pickup_store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $pickup_order_id", ARRAY_A);
                            }
                            if ( $pickup_store ) {
                                if ($pickup_store['store_name']) {
                                    echo esc_html($pickup_store['store_name']) . ', ';
                                }
                                if ($pickup_store['number_house'] || $pickup_store['street_name']) {
                                    echo trim(esc_html($pickup_store['number_house']) . ' ' . esc_html($pickup_store['street_name']) . ', ') . ' ';
                                }
                                if ($pickup_store['floor_unit']) {
                                    echo esc_html($pickup_store['floor_unit']) . ', ';
                                }
                                if ($pickup_store['building']) {
                                    echo esc_html($pickup_store['building']) . ', ';
                                }
                                echo esc_html($billing_country) . ' ';
                                if ($pickup_store['zipcode']) {
                                    echo esc_html($pickup_store['zipcode']);
                                }
                            } else {
                                echo '';
                            }
                        }
                    ?></p>
                    <p style="float: right; text-align: right"><?php echo $order_id ?></p>
                </div>
            </div>
            <div class="wpmail-content-info" style="margin-bottom: 24px;">
                <div class="wpmail-content-info-title">
                    <p><?php echo $order_method == 'delivery' ? 'Delivery Date & Time' : 'Collection Date & Time'?></p>
                    <p style="float: right; text-align: right">Order Date</p>
                </div>
                <div class="wpmail-content-info-text">
                    <p>
                        <?php
                            $date_delivery = new DateTime($order_delivery_date);
                            $date_delivery = $date_delivery ? $date_delivery->format('l, d F Y') : '';
                            esc_html_e("$date_delivery, {$order_delivery_collection_time}");
                        ?>
                    </p>
                    <p style="float: right; text-align: right">
                        <?php
                            $date = new DateTime($date_created);
                            echo $date ? $date->format('l, d F Y') : '';
                        ?>
                    </p>
                </div>
            </div>
            <div class="clearfix"></div>
            <p class="wpmail-pharse-title">Order Summary</p>
            <span class="wpmail-line-in-content" style="margin-bottom: 12px;"></span>
            <div class="wpmail-order-list" style="margin-bottom: 12px;">
            <?php
            $total = 0;
            foreach ($order_items as $item_key => $item):
                $item_data    = $item->get_data();
                $product      = $item->get_product();

                $product_name = $item_data['name'];
                $product_id   = $item_data['product_id'];
                $variation_id = $item_data['variation_id'];
                $quantity     = $item_data['quantity'];
                $tax_class    = $item_data['tax_class'];
                $line_subtotal     = $item_data['subtotal'];
                $line_subtotal_tax = $item_data['subtotal_tax'];
                $line_total        = $item_data['total'];
                $line_total_tax    = $item_data['total_tax'];
            ?>
            <?php if($product): ?>
                <div class="wpmail-order-item">
                    <div class="wpmail-order-item-thumbnail">
                        <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="">
                    </div>
                    <div class="wpmail-order-item-content">
                        <div class="wpmail-order-title">
                            <div><?php echo $product_name; ?></div>
                            <div>$<?php echo floatval($line_subtotal) == 0 ? $line_subtotal : wc_format_decimal($line_subtotal, 2); ?></div>
                        </div>

                        <?php
                        if ($product->is_type('variation')):
                            $variation_attributes = $product->get_variation_attributes();
                            foreach($variation_attributes as $attribute_taxonomy => $term_slug ){
                                $taxonomy = str_replace('attribute_', '', $attribute_taxonomy );
                                $attribute_name = wc_attribute_label( $taxonomy, $product );

                                if( taxonomy_exists($taxonomy) ) {
                                    $attribute_value = get_term_by( 'slug', $term_slug, $taxonomy )->name;
                                } else {
                                    $attribute_value = $term_slug;
                                }
                                echo "<p>
                                    <span style='text-transform: capitalize'>$attribute_name</span>: $attribute_value
                                </p>";
                            }
                        endif; ?>

                        <p>Quantity: <?php echo $quantity; ?></p>

                        <?php if( isset($gift_cards[$product_id]) ):
                          foreach ($gift_cards[$product_id] as $item_key => $coupon): ?>
                            <div class="wpmail-order-title subtitle">
                              <div>Coupon code: <?php echo $coupon['code']; ?></div>
                              <div>Value: $<?php echo $coupon['value']; ?></div>
                              <div>Expiry Date: <?php echo $coupon['expiry']; ?></div>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
            </div>
            <div class="clearfix"></div>
            <span class="wpmail-line-in-content" style="margin-bottom: 12px;"></span>
            <div class="wpmail-section-order" style="margin-bottom: 30px;">
                <div class="wpmail-section-order-info">
                    <span class="float-left">Payment method</span>
                    <span class="float-right"><?php esc_html_e($order_payment_method_title); ?></span>
                </div>
                <div class="clearfix"></div>

                <div class="wpmail-section-order-info">
                    <span class="float-left">Sub-total</span>
                    <span class="float-right">$<?php floatval($sub_total) == 0 ? esc_html_e($sub_total) : esc_html_e(wc_format_decimal($sub_total, 2)); ?></span>
                </div>
                <div class="clearfix"></div>

                <div class="wpmail-section-order-info">
                    <span class="float-left">Discounts</span>
                    <span class="float-right red">-$<?php floatval($discount_total) == 0 ? esc_html_e($discount_total) : esc_html_e(wc_format_decimal($discount_total, 2)); ?></span>
                </div>
                <div class="clearfix"></div>

                <div class="wpmail-section-order-info">
                    <span class="float-left">Shipping fee</span>
                    <span class="float-right">$<?php floatval($shipping_total) == 0 ? esc_html_e($shipping_total) : esc_html_e(wc_format_decimal($shipping_total, 2)); ?></span>
                </div>
                <div class="clearfix"></div>


                <div class="wpmail-section-order-info total">
                    <span class="float-left">Total</span>
                    <span class="float-right">$<?php floatval($total_price) == 0 ? esc_html_e($total_price) : esc_html_e(wc_format_decimal($total_price, 2)); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="wpmail-shopping" style="margin-bottom: 24px;">
                <?php if ($order->get_user_id()) {?>
					<a class="wpmail-button-shopping-text" target="_blank" href="<?php echo wc_get_account_endpoint_url('orders'); ?>">VIEW ORDER</a>
				<?php } ?>
            </div>
<!--            <p class="wpmail-text-regular" style="color: #000000">We cannot make any change to your order, but you can cancel your order.</p>-->
            <p class="wpmail-text-regular" style="color: #000000">Thank you for shopping with Cedele! We hope that you will visit us again soon.</p>
            <p class="wpmail-text-regular" style="color: #000000">Cheers,</p>
            <p class="wpmail-text-regular" style="color: #000000">Cedele Team.</p>
            <div class="wpmail-section-footerbg"></div>
        </div>
        <!-- /content -->

        <!-- footer -->
        <div class="wpmail-footer">
            <p class="wpmail-followus">Follow Us</p>
            <div class="wpmail-social">
                <a href="https://www.facebook.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social1.png">
                </a>
                <a href="https://www.instagram.com/cedelesingapore" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/social2.png">
                </a>
				<a href="https://t.me/cedeletelegram" target="_blank">
					<img src="<?php echo get_template_directory_uri(); ?>/images/emails/social3.png">
				</a>
            </div>
            <p class="wpmail-info">1 Kaki Bukit Road 1, #02-41, Enterprise One, Singapore 415934</p>
            <p class="wpmail-info">
                Tel: 6922 9700 <span class="wpmail-info-fax">Fax: 6448 0035</span>
            </p>
        </div>
        <!-- /footer -->
    </div>
</body>
</html>
