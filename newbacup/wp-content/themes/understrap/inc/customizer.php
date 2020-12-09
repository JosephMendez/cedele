<?php
/**
 * UnderStrap Theme Customizer
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if (!function_exists('understrap_customize_register')) {
	/**
	 * Register basic customizer support.
	 *
	 * @param object $wp_customize Customizer reference.
	 */
	function understrap_customize_register($wp_customize)
	{
		$wp_customize->get_setting('blogname')->transport = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport = 'postMessage';
		$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
	}
}
add_action('customize_register', 'understrap_customize_register');

if (!function_exists('understrap_theme_customize_register')) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function understrap_theme_customize_register($wp_customize)
	{

		// Theme layout settings.
		$wp_customize->add_section(
			'understrap_theme_layout_options',
			array(
				'title' => __('Theme Layout Settings', 'understrap'),
				'capability' => 'edit_theme_options',
				'description' => __('Container width and sidebar defaults', 'understrap'),
				'priority' => apply_filters('understrap_theme_layout_options_priority', 160),
			)
		);

		/**
		 * Select sanitization function
		 *
		 * @param string $input Slug to sanitize.
		 * @param WP_Customize_Setting $setting Setting instance.
		 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
		 */
		function understrap_theme_slug_sanitize_select($input, $setting)
		{

			// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
			$input = sanitize_key($input);

			// Get the list of possible select options.
			$choices = $setting->manager->get_control($setting->id)->choices;

			// If the input is a valid key, return it; otherwise, return the default.
			return (array_key_exists($input, $choices) ? $input : $setting->default);

		}

		$wp_customize->add_setting(
			'understrap_container_type',
			array(
				'default' => 'container',
				'type' => 'theme_mod',
				'sanitize_callback' => 'understrap_theme_slug_sanitize_select',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'understrap_container_type',
				array(
					'label' => __('Container Width', 'understrap'),
					'description' => __('Choose between Bootstrap\'s container and container-fluid', 'understrap'),
					'section' => 'understrap_theme_layout_options',
					'settings' => 'understrap_container_type',
					'type' => 'select',
					'choices' => array(
						'container' => __('Fixed width container', 'understrap'),
						'container-fluid' => __('Full width container', 'understrap'),
					),
					'priority' => apply_filters('understrap_container_type_priority', 10),
				)
			)
		);

		$wp_customize->add_setting(
			'understrap_sidebar_position',
			array(
				'default' => 'right',
				'type' => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'understrap_sidebar_position',
				array(
					'label' => __('Sidebar Positioning', 'understrap'),
					'description' => __(
						'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
						'understrap'
					),
					'section' => 'understrap_theme_layout_options',
					'settings' => 'understrap_sidebar_position',
					'type' => 'select',
					'sanitize_callback' => 'understrap_theme_slug_sanitize_select',
					'choices' => array(
						'right' => __('Right sidebar', 'understrap'),
						'left' => __('Left sidebar', 'understrap'),
						'both' => __('Left & Right sidebars', 'understrap'),
						'none' => __('No sidebar', 'understrap'),
					),
					'priority' => apply_filters('understrap_sidebar_position_priority', 20),
				)
			)
		);
	}
} // End of if function_exists( 'understrap_theme_customize_register' ).
add_action('customize_register', 'understrap_theme_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if (!function_exists('understrap_customize_preview_js')) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function understrap_customize_preview_js()
	{
		wp_enqueue_script(
			'understrap_customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array('customize-preview'),
			'20130508',
			true
		);
	}
}
add_action('customize_preview_init', 'understrap_customize_preview_js');


if (function_exists('WC')) {

	// add wishlist item to menu my account page
	add_filter('woocommerce_account_menu_items', 'add_my_wishlist_menu_account', 40);
	function add_my_wishlist_menu_account($items)
	{
		$logout = $items['customer-logout'];
		unset($items['customer-logout']);
		$items['my-wishlist'] = __('Wishlist', 'woocommerce');
		$items['customer-logout'] = $logout;
		return $items;
	}


	function my_wishlist_add_endpoint()
	{
		add_rewrite_endpoint('my-wishlist', EP_ROOT | EP_PAGES);
	}

	add_action('init', 'my_wishlist_add_endpoint');


	add_action('woocommerce_account_my-wishlist_endpoint', 'my_wishlist_endpoint_content');
	function my_wishlist_endpoint_content()
	{
		wc_get_template(
			'myaccount/my-wishlist.php',
			array(
				'user' => get_user_by('id', get_current_user_id())
			)
		);
	}


	add_filter('woocommerce_endpoint_my-wishlist_title', 'change_my_account_edit_wishlist_title', 10, 2);
	function change_my_account_edit_wishlist_title($title)
	{
		foreach (wc_get_account_menu_items() as $endpoint => $label) {
			if ($endpoint == 'my-wishlist') {
				$title = "Wish list"; // change your entry-title
			}
		}
		return $title;
	}


// update counter wishlist with ajax
	if (defined('YITH_WCWL') && !function_exists('yith_wcwl_ajax_update_count')) {
		function yith_wcwl_ajax_update_count()
		{
			wp_send_json(array(
				'count' => yith_wcwl_count_all_products()
			));
		}

		add_action('wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count');
		add_action('wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count');
	}


	function my_account_menu_order()
	{
		$menuOrder = array(
			'edit-account' => __('Account Details', 'woocommerce'),
			'orders' => __('My Orders', 'woocommerce'),
			'my-wishlist' => __('My Wishlist', 'woocommerce'),
			'edit-address' => __('Addresses', 'woocommerce'),
			'list-coupon' => __('Rewards & Vouchers', 'woocommerce'),
			'customer-logout' => __('Logout', 'woocommerce'),
		);
		return $menuOrder;
	}

	add_filter('woocommerce_account_menu_items', 'my_account_menu_order');

	//Remove required field requirement for first/last name in My Account Edit form
  add_filter('woocommerce_save_account_details_required_fields', 'remove_required_fields');

  function remove_required_fields( $required_fields ) {
    unset($required_fields['account_email']);

    return $required_fields;
  }

	// update user custom field in edit account detail
	add_action('woocommerce_save_account_details', 'template2020_update_profile_fields');
	function updateMetaField($user_id, $field)
	{
		if (!empty($_POST[$field])) {
			if (get_user_meta($user_id, $field, true)) {
				update_user_meta($user_id, $field, $_POST[$field]);
			} else {
				add_user_meta($user_id, $field, $_POST[$field], true);
			}
		}
	}

	function template2020_update_profile_fields($user_id)
	{
		if (!current_user_can('edit_user', $user_id)) {
			return false;
		}
		updateMetaField($user_id, 'user_registration_phone_number');
		updateMetaField($user_id, 'user_registration_user_birthday');
		updateMetaField($user_id, 'user_registration_user_gender');
	}

	// delete display name in edit account
	add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields');
	function wc_save_account_details_required_fields($required_fields)
	{
		unset($required_fields['account_display_name']);
		return $required_fields;
	}
}


function ajax_filter_career_init()
{
	wp_register_script('ajax-filter-career-script', get_template_directory_uri() . '/js/ajax-filter-career.js', array('jquery'));
	wp_enqueue_script('ajax-filter-career-script');

	wp_localize_script('ajax-filter-career-script', 'ajax_filter_object', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	));

	// Enable the user with no privileges to run ajax_filter_career() in AJAX
	add_action('wp_ajax_nopriv_ajaxFilterCareer', 'ajax_filter_career');
}
add_action('init', 'ajax_filter_career_init');


add_action('wp_ajax_filder_career', 'filter_career_function');
add_action('wp_ajax_nopriv_filder_career', 'filter_career_function');

function filter_career_function()
{

	check_ajax_referer('ajax-filter-career-nonce', 'career-security');

	$tax_query = array();

	if (isset($_POST['career-filter-cat']) || isset($_POST['career-filter-tag'])) {
		$tax_query = array(
			'relation' => 'AND',
		);
		if (isset($_POST['career-filter-cat']) && $_POST['career-filter-cat']!='') {
			$tax_query[] = array(
				'taxonomy' => 'career_category',
				'field' => 'term_id',
				'terms' => $_POST['career-filter-cat']
			);
		}
		if (isset($_POST['career-filter-tag']) && $_POST['career-filter-tag']!='') {
			$tax_query[] = array(
				'taxonomy' => 'career_tag',
				'field' => 'term_id',
				'terms' => $_POST['career-filter-tag']
			);
		}
	}

	$args = array(
		'orderby' => 'date',
		'posts_per_page' => -1,
		'post_type' => 'career',
		'post_status' => 'publish',
		'tax_query' => $tax_query,
	);

	$query = new WP_Query($args);

	if ($query->have_posts()) :
		while ($query->have_posts()): $query->the_post();
			get_template_part('content-career');
		endwhile;
		wp_reset_postdata();
	else :
		echo 'There is no result';
	endif;

	die();
}

add_action( 'init', function () {
	add_rewrite_endpoint( 'list-coupon', EP_ROOT | EP_PAGES );
} );

function my_custom_endpoint_content() {
	wc_get_template( 'myaccount/my-coupon.php' );
}

add_action( 'woocommerce_account_list-coupon_endpoint', 'my_custom_endpoint_content' );
