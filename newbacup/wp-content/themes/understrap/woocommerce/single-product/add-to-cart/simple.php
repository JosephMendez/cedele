<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.
$isOutOfStock = $product->get_stock_status() == 'outofstock';
?>
<div class="row">
	<div class="col-12">
		<?php do_action('woocommerce_before_add_to_cart_form'); ?>
		<form class="cart"
			  action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
			  method="post" enctype='multipart/form-data'>
			<?php do_action('woocommerce_before_add_to_cart_button'); ?>
			<div class="div_border"></div>
    	<?php wc_get_template('/single-product/product-summary/banner.php');?>


			<div class="product-add-cart">
				<div class="row">
					<div class="col-6 col-md-4">
						<h5 class="title_sub">Price</h5>
						<h2 class="title_price"><?php echo $product->get_price_html(); ?></h2>
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
					<div class="col-12 col-md-5">
						<div class="text-center status-text">
							<p class="mb-0">
								<?php echo $isOutOfStock ? 'Item out of stock' : ''; ?>
							</p>
						</div>
						<?php if ($isOutOfStock) { ?>
							<button name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
									class="single_add_to_cart_button btn btn-header btn-secondary heading-font outofstock"><?php echo __('Sold Out', 'understrap'); ?></button>
						<?php } else { ?>
							<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
									class="single_add_to_cart_button btn btn-header btn-shadow btn-primary heading-font"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
						<?php } ?>

						<?php if (is_user_logged_in()){ ?>
							<div class="footer-wishlist <?php echo $isOutOfStock ? '' : 'd-none' ?>">
								<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
							</div>
						<?php } ?>
						<?php do_action('woocommerce_after_add_to_cart_button'); ?>
				</div>
			</div>

		</form>
	<?php do_action('woocommerce_after_add_to_cart_form'); ?>

</div>
