<?php
require_once get_template_directory() . '/product-custom/ajax-custom-woocommerce.php';
require_once get_template_directory() . '/product-custom/bundle-product.php';
require_once get_template_directory() . '/product-custom/variable-product.php';
require_once get_template_directory() . '/product-custom/group-product.php';
require_once get_template_directory() . '/product-custom/list-order-woocommerce.php';
require_once get_template_directory() . '/product-custom/form-order-woocommerce.php';
require_once get_template_directory() . '/product-custom/update_product_info_on_edenred.php';
require_once get_template_directory() . '/product-custom/before-product-import.php';
require_once get_template_directory() . '/product-custom/settings-shipping.php';
require_once get_template_directory() . '/product-custom/settings-product.php';

function enqueue_custom_woocommerce()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('custom-order-woo-js', get_template_directory_uri() . '/js/custom-order-woo.js');
    wp_localize_script('custom-order-woo-js', 'custom_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_custom_woocommerce');