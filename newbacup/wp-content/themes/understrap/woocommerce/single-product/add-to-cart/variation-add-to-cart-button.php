<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

defined('ABSPATH') || exit;

global $product;
global $woocommerce;
?>
<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
<?php wc_get_template('/single-product/product-summary/banner.php');?>

</div>
<div class="woocommerce-variation-add-to-cart variations_button" style="width: 100%">

	<div class="product-add-cart">
		<div class="div_border" style="padding-right: 0"></div>
		<div class="row">
			<div class="col-6 col-md-5 pr-0">
				<h5 class="title_sub">Price</h5>
				<h2 class="title_price all_price"><?php echo $product->get_price_html();?></h2>
				<h2 class="title_price variation_price"></h2>
			</div>
			<div class="col-6 col-md-3">
				<h5 class="title_sub">Quantity</h5>
				<div class="quantity_custom">
					<?php
					do_action('woocommerce_before_add_to_cart_quantity');

					woocommerce_quantity_input(
							array(
									'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
									'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
									'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
							)
					);

					do_action('woocommerce_after_add_to_cart_quantity');
					?>
				</div>
			</div>

			<div class="col-12 col-md-4">
				<div class="text-center status-text">

				</div>
				<button type="submit"
						class="single_add_to_cart_button btn btn-header btn-shadow btn-primary heading-font">
					<?php echo esc_html($product->single_add_to_cart_text()); ?></button>
			</div>

			<?php do_action('woocommerce_after_add_to_cart_button'); ?>
		</div>
	</div>

	<input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>"/>
	<input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>"/>
	<input type="hidden" name="variation_id" class="variation_id" value="0"/>
</div>
