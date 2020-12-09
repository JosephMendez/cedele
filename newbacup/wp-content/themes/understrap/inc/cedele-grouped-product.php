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
 
add_filter( 'product_type_selector', 'cedele_add_custom_product_type' );
 
function cedele_add_custom_product_type( $types ){
    $types[ 'bundle' ] = 'Bundle Product';
    return $types;
}
 
// --------------------------
// #2 Add New Product Type Class
 
add_action( 'init', 'cedele_create_custom_product_type' );
 
function cedele_create_custom_product_type(){
    class WC_Product_Bundle extends WC_Product_Simple {
      public function get_type() {
         return 'bundle';
      }
    }
}
 
// --------------------------
// #3 Load New Product Type Class
 
add_filter( 'woocommerce_product_class', 'cedele_woocommerce_product_class', 10, 2 );
 
function cedele_woocommerce_product_class( $classname, $product_type ) {
    if ( $product_type == 'bundle' ) {
        $classname = 'WC_Product_Bundle';
    }
    return $classname;
}

/**
 * Show pricing fields for simple_rental product.
 */
function bundle_custom_js() {

  if ( 'product' != get_post_type() ) :
    return;
  endif;
  ?><script type='text/javascript'>
    jQuery( document ).ready( function() {
      if (jQuery('#product-type').val() == 'bundle') {
        jQuery('.general_options.general_tab.hide_if_grouped').show();
      }
      jQuery( '.options_group.pricing' ).addClass( 'show_if_bundle' ).show();
    });

  </script><?php

}
add_action( 'admin_footer', 'bundle_custom_js' );