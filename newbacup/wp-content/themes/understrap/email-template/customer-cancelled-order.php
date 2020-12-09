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
  $total_price = $order->get_total() - floatval($used_redemp_point);
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
        .wpmail-container a.maillink {
            color: #2F80ED;
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
            padding: 24px 24px 92px 24px;
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

        /* voucher */
        .wpmail-voucher {
            width: 455px;
            height: 152px;
            margin: auto;
            border-radius: 4px;
            position: relative;
        }

        .wpmail-voucher-left {
            position: absolute;
            width: 260px;
            height: 120px;
            left: 16px;
            top: 12px;
            padding: 27px 0px 29px 29px;
            box-sizing: border-box;
        }

        .wpmail-voucher-left .wpmail-voucher-id {
            height: 17px;
            font-family: 'Gotham Regular';
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 17px;
            letter-spacing: -0.25px;
            color: #3F3F3F;
            white-space: nowrap;
        }

        .wpmail-voucher-left .wpmail-voucher-name {
            height: 23px;
            font-family: 'Gotham Regular';
            font-style: normal;
            font-weight: bold;
            font-size: 24px;
            line-height: 23px;
            letter-spacing: 0.5px;
            color: #3C1605;
            white-space: nowrap;
        }

        .wpmail-voucher-left .wpmail-voucher-date {
            height: 16px;
            font-family: 'Gotham Regular';
            font-style: normal;
            font-weight: 300;
            font-size: 12px;
            line-height: 16px;
            color: #000000;
            white-space: nowrap;
        }

        .wpmail-voucher-right {
            position: absolute;
            width: 163px;
            height: 120px;
            left: 276px;
            top: 12px;
            padding: 43px 0px 45px 45px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .wpmail-voucher-right .wpmail-voucher-price {
            font-family: 'Gotham Regular';
            font-style: normal;
            font-weight: bold;
            font-size: 34px;
            line-height: 32px;
            letter-spacing: 0.75px;
            color: #6DAD48;
        }

        .wpmail-voucher-right img {
            position: absolute;
            top: 61px;
            right: 0px;
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
			padding: 0px 5px;
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
            <p class="wpmail-text-header" style="margin-bottom: 24px;">Your order has been cancelled</p>
<!--            <p class="wpmail-text-regular"  style="margin-bottom: 26px;">Your order <b>--><?php //echo $order_id ?><!--</b> has been cancelled. You can use the button below to view your order. You can always see your order at <a href="--><?php //echo wc_get_account_endpoint_url('orders'); ?><!--" class="maillink">My Account/My Order</a> at Cedele's website at anytime</p>-->
            <p class="wpmail-text-regular"  style="margin-bottom: 26px;">Your payment will be fully refunded as a Cedele e-voucher — no further action is needed. You can view a record of your order here and at My Orders page. We hope to serve you again.</p>
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
                                    echo trim(esc_html($pickup_store['number_house']) . ' ' . esc_html($pickup_store['street_name']) . ', ');
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
                    <p>Cancellation Date</p>
                    <p style="float: right; text-align: right">Order Date</p>
                </div>
                <div class="wpmail-content-info-text">
                    <p>
                        <?php
                            $date_delivery = new DateTime($order->modified_date);
                            echo $date_delivery ? $date_delivery->format('D, d M Y, H:i') : '';
                        ?>
                    </p>
                    <p style="float: right; text-align: right">
                        <?php
                            $date = new DateTime($date_created);
                            echo $date ? $date->format('D, d M Y') : '';
                        ?>
                    </p>
                </div>
            </div>
            <div class="clearfix"></div>
            <p class="wpmail-pharse-title" style="margin-bottom: 10px;">Order Summary</p>
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
				<?php if ( 0 < $order->get_total_fees() ) : ?>
					<div class="wpmail-section-order-info">
						<span class="float-left">Surcharge </span>
						<span class="float-right"><?php echo wc_price( $order->get_total_fees(), array( 'currency' => $order->get_currency() ) ); // WPCS: XSS ok. ?></span>
					</div>
					<div class="clearfix"></div>
				<?php endif; ?>

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
				<?php
				if(isset($used_redemp_point) && $used_redemp_point > 0) {?>
					<div class="wpmail-section-order-info">
						<span class="float-left">Use point</span>
						<span class="float-right red">-<?php echo $used_redemp_point; ?></span>
					</div>
					<div class="clearfix"></div>
				<?php } ?>


                <div class="wpmail-section-order-info total">
                    <span class="float-left">Total</span>
                    <span class="float-right">$<?php floatval($total_price) == 0 ? esc_html_e($total_price) : esc_html_e(wc_format_decimal($total_price, 2)); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
			<?php if (is_user_logged_in() == 1) { ?>
				<div class="wpmail-shopping" style="margin-bottom: 21px;">
					<a class="wpmail-button-shopping-text" target="_blank"
					   href="<?php echo wc_get_account_endpoint_url('orders'); ?>">VIEW ORDER</a>
				</div>
			<?php } ?>
<!--            <p class="wpmail-pharse-title" style="margin-bottom: 6px;">You have been refunded $--><?php //floatval($total_price) == 0 ? esc_html_e($total_price) : esc_html_e(wc_format_decimal($total_price, 2)); ?><!-- as Cedele’s Voucher!</p>-->
            <p class="wpmail-pharse-title" style="margin-bottom: 6px;">Here’s your Cedele e-voucher:</p>
            <div class="wpmail-voucher" style="margin-bottom: 36px;">
                <svg width="455" height="152" viewBox="0 0 455 152" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g filter="url(#filter0_d)">
                    <g clip-path="url(#clip0)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16 16C16 13.7909 17.7909 12 20 12H265.201C266.262 12 267.279 12.4214 268.029 13.1716L273.314 18.4558C274.876 20.0179 277.408 20.0179 278.971 18.4558L284.255 13.1716C285.005 12.4214 286.022 12 287.083 12H435C437.209 12 439 13.7909 439 16V128C439 130.209 437.209 132 435 132H285.799C284.738 132 283.721 131.579 282.971 130.828L278.971 126.828C277.408 125.266 274.876 125.266 273.314 126.828L269.314 130.828C268.564 131.579 267.546 132 266.485 132H20C17.7909 132 16 130.209 16 128V16Z" fill="white"/>
                    <rect x="387.308" y="91.9358" width="95.4304" height="95.4304" transform="rotate(-11.0964 387.308 91.9358)" fill="url(#pattern0)"/>
                    <rect x="16" y="10" width="5" height="122" fill="#914204"/>
                    <line opacity="0.5" x1="276.5" y1="23" x2="276.5" y2="125" stroke="#AAAAAA" stroke-dasharray="5 5"/>
                    </g>
                    </g>
                    <defs>
                    <filter id="filter0_d" x="0" y="0" width="455" height="152" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                    <feOffset dy="4"/>
                    <feGaussianBlur stdDeviation="8"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                    </filter>
                    <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                    <use xlink:href="#image0" transform="scale(0.00540541)"/>
                    </pattern>
                    <clipPath id="clip0">
                    <rect x="16" y="12" width="423" height="120" rx="4" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
                <div class="wpmail-voucher-left">
                    <div class="wpmail-voucher-wrap">
                        <p class="wpmail-voucher-id" style="margin-bottom: 4px">Voucher ID: <?php echo $coupon ?></p>
                        <p class="wpmail-voucher-name" style="margin-bottom: 4px">Cedele Store Voucher</p>
                        <p class="wpmail-voucher-date">Voucher expires on <?php echo $expiry_date ?></p>
                    </div>
                </div>
                <div class="wpmail-voucher-right">
                    <span class="wpmail-voucher-price">$<?php floatval($total_price) == 0 ? esc_html_e($total_price) : esc_html_e(wc_format_decimal($total_price, 2)); ?></span>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/emails/lime2.png">
                </div>
            </div>
            <p class="wpmail-text-regular" style="color: #000000">This e-voucher can be used at our online store or any of our outlets. You can also view this e-voucher at our Voucher & Rewards page. If you have any questions, please contact <a class="maillink" href="mailto:contactus@cedeledepot.com">contactus@cedeledepot.com</a> for assistance.</p>
            <br>
            <p class="wpmail-text-regular" style="color: #000000">Best Regards,</p>
            <p class="wpmail-text-regular" style="color: #000000">The Cedele Team.</p>
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
