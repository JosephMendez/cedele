<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
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
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	$deliveryMethod = get_post_meta($post->ID, 'delivery_method');
	$productLeadTime = get_post_meta($post->ID, 'product-lead-time-checkbox');
	$leadTimeDays = get_post_meta($post->ID, 'product-lead-time-days');
	$isAdvancedProduct = count($productLeadTime) > 0 && $productLeadTime[0] == 'advance';
	$checkedDate = get_post_custom($post->ID);
	$typeChoosen = get_post_meta($post->ID, '_type', true);

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

	// remove a

	echo '<div class="product-image-wrapper">';
	if (is_user_logged_in()){
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
	}
	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	echo '<a href="'.esc_url( $link ).'" ><div class="product-image">';
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/** echo Advance leading text */
	if ( $isAdvancedProduct && $leadTimeDays[0] > 0 ) {
		echo '<span class="advance-notice">'.$leadTimeDays[0].__(' days advance notice').'</span>';
	}
	echo '</div></a>';

	echo '</div>';

	$deliveryText = understrap_generate_delivery_method_text($deliveryMethod, $checkedDate, $typeChoosen);
	/** echo delivery text */
	echo '<p class="delivery-method" title="'.$deliveryText.'">'.$deliveryText.'</p>';

	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	echo '<p class="product-description">'.wp_strip_all_tags($product->get_short_description()).'</p>';

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
