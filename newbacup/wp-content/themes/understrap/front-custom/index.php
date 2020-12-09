<?php
require_once get_template_directory() . '/front-custom/front-ajax.php';
require_once get_template_directory() . '/front-custom/my-orders.php';
require_once get_template_directory() . '/front-custom/my-redemptions.php';

function enqueue_front_custom_woocommerce()
{
	wp_enqueue_style('front-custom-css', get_template_directory_uri() . '/css/front-custom.css');
	wp_enqueue_script('front-custom-js', get_template_directory_uri() . '/js/front-custom.js');
}
add_action('wp_enqueue_scripts', 'enqueue_front_custom_woocommerce');