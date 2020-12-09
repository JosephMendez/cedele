<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
global $post;
/*
if ($product->is_type( 'variable' ) && !empty($product->get_variation_attributes())) {
	echo '<table class="table"><tbody>';
	foreach ($product->get_variation_attributes() as $attribute_name => $options) : ?>
		<?php
		echo '<tr>';
		echo '<td><label class="title_label">' . wc_attribute_label($attribute_name) . ': </label></td>';
		$selected = isset($_REQUEST['attribute_' . sanitize_title($attribute_name)]) ? wc_clean(urldecode($_REQUEST['attribute_' . sanitize_title($attribute_name)])) : $product->get_variation_default_attribute($attribute_name);
		wc_dropdown_variation_attribute_options(array('class' => 'select', 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected));
		echo '</tr>';
		?>
	<?php endforeach;
	echo '</tbody></table>';
} else {
	*/
echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'woocommerce_loop_add_to_cart_link',
	sprintf(
		'<div class="add-to-cart-container"><a href="%s" data-quantity="%s" class="%s product_type_%s single_add_to_cart_button btn btn-outline-primary btn-block %s" %s> %s</a></div>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		esc_attr( $product->get_type() ),
		$product->get_type() === 'simple' ? 'ajax_add_to_cart' : '',
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	),
	$product,
	$args
);
// }
