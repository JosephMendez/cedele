<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
WC()->session->set('cart_valid', true); ?>

<div class="cart-items-count">
	<span class="cart-content-count"><?php echo WC()->cart->cart_contents_count.'</span> '.__( 'Items', 'understrap' );?>
</div>
<div class="cart-title-subtotal">
	<?php wc_cart_totals_subtotal_html(); ?>
	<h4>Subtotal</h4>
</div>

<form id="woocommerce-cart" class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<ul class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
        if(Rewards_Product_Exits()) {
            $cartId = WC()->cart->generate_cart_id( Rewards_Product_Exits());
            $cartItemKey = WC()->cart->find_product_in_cart( $cartId );
            WC()->cart->remove_cart_item( $cartItemKey );
        }
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

				$now = new DateTime();
				$today_date = $now->format('D');
				$customerAddress = json_decode(stripslashes($_COOKIE['customerAddress']));
				$delivery_method = get_post_meta($product_id, 'delivery_method', true);
				$post_meta = get_post_custom($product_id);
				$product_type = get_post_meta($product_id, '_type', true);
				$variation_type = get_post_meta($cart_item['variation_id'], '_type', true);
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
				$is_in_stock = $_product->is_in_stock();

				if ( $product_type == 'season-product-one-day-only'){
					$oneDayDatepicker = get_post_meta($product_id, 'one-day-date-picker', true);
					$oneDayTimeFrom = get_post_meta($product_id, 'one-day-time-from', true);
					$oneDayTimeTo = get_post_meta($product_id, 'one-day-time-to', true);
					$from = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeFrom);
					$to = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeTo);
					$is_seasonal_available = $from <= $now && $now <= $to;

				} elseif ( $product_type == 'season-product-date-range' ) {
					$dateRangeFrom = get_post_meta($product_id, 'date-range-from', true);
					$dateRangeTo = get_post_meta($product_id, 'date-range-to', true);
					$timeRangeFrom = get_post_meta($product_id, 'time-range-from', true);
					$timeRangeTo = get_post_meta($product_id, 'time-range-to', true);
					$from = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeFrom.' '.$timeRangeFrom);
					$to = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeTo.' '.$timeRangeTo);
					$is_seasonal_available = $from <= $now && $now <= $to;
				}

				if ( $variation_type && $variation_type == 'season-product-one-day-only-variation'){
					$oneDayDatepicker = get_post_meta($cart_item['variation_id'], '_one_day_date_picker_variation', true);
					$timeFrom = get_post_meta($cart_item['variation_id'], '_one_day_time_from_variation', true);
					$timeTo = get_post_meta($cart_item['variation_id'], '_one_day_time_to_variation', true);
					$oneDayTimeFrom = strlen($timeFrom) == 5 ? $timeFrom.':00' : $timeFrom;
					$oneDayTimeTo = strlen($timeTo) == 5 ? $timeTo.':00' : $timeTo;
					$from = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeFrom);
					$to = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeTo);
					$is_seasonal_available = $from <= $now && $now <= $to;
				}

				if ( $variation_type && $variation_type == 'season-product-date-range-variation'){
					$dateRangeFrom = get_post_meta($cart_item['variation_id'], '_date_range_from_variation', true);
					$dateRangeTo = get_post_meta($cart_item['variation_id'], '_date_range_to_variation', true);
					$timeFrom = get_post_meta($cart_item['variation_id'], '_time_range_from_variation', true);
					$timeTo = get_post_meta($cart_item['variation_id'], '_time_range_to_variation', true);

					$timeRangeFrom = strlen($timeFrom) == 5 ? $timeFrom.':00' : $timeFrom;
					$timeRangeTo = strlen($timeTo) == 5 ? $timeTo.':00' : $timeTo;
					$from = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeFrom.' '.$timeRangeFrom);
					$to = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeTo.' '.$timeRangeTo);
					$is_seasonal_available = $from <= $now && $now <= $to;
				}

				$availableInStore = true;
				if (property_exists($customerAddress, 'deliveryType')){
					if ($customerAddress->deliveryType == 'self-collection'){
						// $result_location = $wpdb->get_results("select id from wp_store_location where id IN(select store_id from wp_store_location_post where post_id = $product_id)");
						$storeId = $customerAddress->pickupStoreId;
						$list_stores = get_list_stores($product_id);
						if (count($list_stores) > 0){
							$exist = filter_array($list_stores, 'store_id', $storeId);
							if (count($exist) < 1){
								$availableInStore = false;
							}
						}
					}
					if ($customerAddress->date) {
						$delivery_date = DateTime::createFromFormat('j M Y', $customerAddress->date);
						$delivery_day = $delivery_date->format('D');
						$is_available_on_delivery_day = in_array($delivery_day, $checked_date);
					}
				}

				//$cart_item['product_id'] = parent product of variation
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

				$gift_card_value = get_post_meta($product_id, 'gift_card_value', true);
				$expiry_duration = get_post_meta($product_id, 'expiry_duration', true);

				$availableTimeFrom = get_post_meta($product_id, 'daily-product-available-time-from', true);
                $availableTimeTo = get_post_meta($product_id, 'daily-product-available-time-to', true);
                $timeDelivery = explode('-',$customerAddress->time);
				?>
				<li class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>"
					>
					<div class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>

					<div class="product-content w-100">

						<div class="d-flex position-relative">
							<div class="product-name" data-title="<?php esc_attr_e( 'Product', 'understrap' ); ?>">

								<div class="w-100 invalid-message">
									<?php
									$sub_msg = 'Please change <span class="change-address" style="text-decoration: underline; color: #2f80ed; cursor: pointer;">HERE</span></br>';

									if (!$is_in_stock){
											WC()->session->set('cart_valid', false);
											echo '<p>Product is out of stock</p>';
										}
										if ($customerAddress->deliveryType == 'delivery' && $delivery_method == 'self'){
											WC()->session->set('cart_valid', false);
											echo '<span>Product is available for Self-Collection only. '.$sub_msg.' </span>';
										}
										if ($customerAddress->deliveryType == 'self-collection' && $delivery_method == 'delivery'){
											WC()->session->set('cart_valid', false);
//											echo '<p>Product is available for Delivery only. Please change <a class="change-address">HERE</a></p>';
											echo '<span>Product is available for Delivery only. '.$sub_msg.'</span>';
										}
										if ($product_type == 'daily-product' && isset($is_available_on_delivery_day) && !$is_available_on_delivery_day){
											WC()->session->set('cart_valid', false);
//											echo '<p>Product is available on '.implode(', ', $checked_date).'Please change <a class="change-address">HERE</a></p>';
											echo '<span>Product is available on '.implode(', ', $checked_date).'. '.$sub_msg.'</span>';
										}
										if ( ($product_type == 'season-product-date-range' || $product_type == 'season-product-one-day-only' || $variation_type == 'season-product-one-day-only-variation' || $variation_type == 'season-product-date-range-variation') && !$is_seasonal_available ){
											WC()->session->set('cart_valid', false);
											echo '<p>Product is not available anymore</p>';
										}
										if (!$availableInStore){
											WC()->session->set('cart_valid', false);
											echo '<p>Product is not available anymore</p>';
										}
										if ($availableDate > $customerChoosenDate && $isAdvancedProduct){
											WC()->session->set('cart_valid', false);
											echo '<p>The earliest delivery date of this product is '.date_format($availableDate, 'd/m/Y').'. '.$sub_msg.'</p>';
										}
                                        if(!(strtotime($timeDelivery[0]) >= strtotime($availableTimeFrom) &&  strtotime($timeDelivery[0]) <=  strtotime($availableTimeTo)) && !(strtotime($timeDelivery[1]) >= strtotime($availableTimeFrom) &&  strtotime($timeDelivery[1]) <=  strtotime($availableTimeTo)))  {
                                            WC()->session->set('cart_valid', false);
                                            echo '<p>This product is only available from '.$availableTimeFrom .' to '. $availableTimeTo.'. '.$sub_msg.'</p>';
                                        }
									?>
								</div>
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
								?>
								<div class="product-info d-desktop">
									<?php
									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</div>
								<?php
								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'understrap' ) . '</p>', $product_id ) );
								}
								?>
							</div>

							<div class="product-modify text-md-right">
								<div class="product-wishlist d-inline-block">
									<?php
										if (is_user_logged_in()){
											echo do_shortcode( "[yith_wcwl_add_to_wishlist product_id=".$product_id." label='Buy Later']");
										}
									?>
								</div>
								<div class="product-remove d-inline-block">
									<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash"></i><span>Remove</span></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'understrap' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
									?>
								</div>
							</div>
						</div>
						<div class="d-none">
							<?php if (!$_product->is_type( 'simple' )){ ?>
								<a class="change-product" href="<?php echo get_permalink( $_product->get_id() ); ?>">Change</a>
							<?php } ?>
						</div>
						<div class="product-price-group">
							<div class="product-quantity-modify">
								<div class="product-price d-inline-block align-middle" data-title="<?php esc_attr_e( 'Price', 'understrap' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
									<span class="multiplier">&times;</span>
								</div>

								<div class="product-quantity d-inline-block align-middle" data-title="<?php esc_attr_e( 'Quantity', 'understrap' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}
									echo '<span class="qtt-changer qtt-minus"></span>';
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo '<span class="qtt-changer qtt-plus"></span>';
									?>
								</div>
							</div>

							<div class="product-subtotal text-right" data-title="<?php esc_attr_e( 'Subtotal', 'understrap' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
						</div>

					</div>
					<div class="product-info d-mobile">
						<?php
						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
				</li>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_contents' ); ?>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</ul>

	<div class="actions text-center text-sm-right">

		<button type="submit" class="btn btn-primary heading-font" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'understrap' ); ?>"><?php esc_html_e( 'Update cart', 'understrap' ); ?></button>

		<?php do_action( 'woocommerce_cart_actions' ); ?>

		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="row price-row">
	<div class="col-12 col-lg-6">
		<?php if ( wc_coupons_enabled() ) { ?>
			<div class="coupon">
				<label class="d-block" for="coupon_code"><?php esc_html_e( 'Voucher Code', 'understrap' ); ?></label>
				<input form="woocommerce-cart" type="text" name="coupon_code" class="input-text form-control d-inline-block" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter Voucher Code', 'understrap' ); ?>" />
				<button form="woocommerce-cart" type="submit" class="btn btn-outline-primary heading-font" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'understrap' ); ?>"><?php esc_attr_e( 'Apply', 'understrap' ); ?></button>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
		<?php } ?>
	</div>
	<div class="col-12 col-lg-6">
		<div class="cart-collaterals">
			<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>
	</div>
</div>

<div class="modal fade address-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<?php get_template_part( 'home-templates/delivery' );?>
			</div>
		</div>
	</div>
</div>
<?php
do_action( 'woocommerce_after_cart' );
