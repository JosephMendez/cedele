<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
global $product;
?>

<div class="woo-product-header">
	<h2 style="display: inline"
		class="product_title entry-title custom_title"><?php echo $product->get_name(); ?></h2>
		<!-- //icon wishlish -->
	<?php 
		if (is_user_logged_in()){
			echo do_shortcode('[yith_wcwl_add_to_wishlist]');
		}
	?>
	<!-- //icon share -->
	<?php echo do_shortcode('[addtoany url="'.get_permalink(get_the_ID()).'"]');?>
</div>
