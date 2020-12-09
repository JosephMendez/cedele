<?php
/**
 * @snippet       Create a New Product Type @ WooCommerce Admin
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    Woo 4.3
 */

defined( 'ABSPATH' ) || exit;

// --------------------------
// #1 Add New Product Type to Select Dropdown
 
add_filter( 'product_type_selector', 'cedele_add_giftcard_product_type' );
 
function cedele_add_giftcard_product_type( $types ){
    $types[ 'giftcard' ] = 'Gift Card Product';
    return $types;
}
 
// --------------------------
// #2 Add New Product Type Class
 
add_action( 'init', 'cedele_create_giftcard_product_type' );
 
function cedele_create_giftcard_product_type(){
    class WC_Product_GiftCard extends WC_Product_Simple {
      public function get_type() {
         return 'giftcard';
      }
    }
}
 
// --------------------------
// #3 Load New Product Type Class
 
add_filter( 'woocommerce_product_class', 'cedele_woocommerce_giftcard_product_class', 10, 2 );
 
function cedele_woocommerce_giftcard_product_class( $classname, $product_type ) {
    if ( $product_type == 'giftcard' ) {
        $classname = 'WC_Product_GiftCard';
    }
    return $classname;
}

/**
 * Show pricing fields for simple_rental product.
 */
function giftcard_custom_js() {

  if ( 'product' != get_post_type() ) :
    return;
  endif;
  ?><script type='text/javascript'>
    jQuery( document ).ready( function() {

      function showGiftCardOptions() {
        if (jQuery('#product-type').val() == 'giftcard') {
          jQuery('.general_options.general_tab.hide_if_grouped').show();
        }
        jQuery( '.options_group.pricing' ).addClass( 'show_if_bundle' ).show();
        jQuery( '.inventory_options.inventory_tab.show_if_simple' ).show();
      }

      jQuery('#product-type').on('change', function(){
        showGiftCardOptions();
      });
      showGiftCardOptions();
    });

  </script><?php

}
add_action( 'admin_footer', 'giftcard_custom_js' );