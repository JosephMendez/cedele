<?php
/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $post, $product;

$new_label_age = get_option('cdls_product_new_label_age', 0);
$postDate = DateTime::createFromFormat('Y-m-d H:i:s', get_the_date('Y-m-d H:i:s', $product->get_id()));
$milestone = new DateTime();
$milestone->modify('-'.$new_label_age.' day');
$isPopular = get_post_meta($product->get_id(), 'popular', true);

if( $isPopular ) {
  echo '<span class="onsale new">'. __('Popular', 'woocommerce') .'</span>';
} elseif ( $product->is_on_sale() ) { ?>

  <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale', 'woocommerce' ) . '</span>', $post, $product ); ?>

  <?php
} elseif ( $milestone < $postDate ) {
  echo '<span class="onsale new">'. __('New', 'woocommerce') .'</span>';
}

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
