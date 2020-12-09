<?php
/**
 * UnderStrap enqueue scripts
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'understrap_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function understrap_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/css/theme.min.css' );
		wp_enqueue_style( 'understrap-styles', get_template_directory_uri() . '/css/theme.min.css', array(), $css_version );
		wp_enqueue_style( 'template-styles', get_template_directory_uri() . '/css/template.css', array(), $css_version );
		wp_enqueue_style( 'cdl-google-fonts', '//fonts.googleapis.com/css?family=Roboto:wght@400;500&display=swap', false );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('jquery-ui-js', '//code.jquery.com/ui/1.12.1/jquery-ui.js');
		wp_enqueue_script( 'jscookie', '//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.4/js.cookie.min.js', array( 'jquery' ));

		wp_enqueue_script( 'mCustomScrollbar', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.concat.min.js', array(), '', true );
		wp_enqueue_script( 'select2js', get_template_directory_uri() . '/js/select2.full.min.js', array(), '', true );
		wp_enqueue_script( 'jConfirm', get_template_directory_uri() . '/js/jquery-confirm.min.js', array(), '', true );

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/js/theme.min.js' );
		wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/theme.min.js', array(), $js_version, true );
		wp_localize_script( 'understrap-scripts', 'global_ajax_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_style('account-datepicker-css', get_template_directory_uri() . '/css/jquery.datetimepicker.css');
		wp_enqueue_script('account-datepicker-js', get_template_directory_uri() . '/js/jquery.datetimepicker.min.js');


		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( is_page_template('page-templates/store-location.php') ) {
			wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . $GLOBALS['gmapKey'] . '&sensor=false', false, null, true);
			wp_enqueue_script('gmapjs', get_template_directory_uri() . '/js/gmap.min.js', null, null, true);
			wp_enqueue_script( 'lodashjs', get_template_directory_uri() . '/js/lodash.min.js', array(), '', true );
			wp_register_script(
				'store-location',
				get_template_directory_uri() . '/js/store-location.js',
				array( 'lodashjs', 'jquery' ),
				null, true
			);

			$localize = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'auth' => wp_create_nonce('_check__ajax_100')
			);
			wp_localize_script('store-location', 'custom_ajax_vars', $localize);
			wp_enqueue_script('store-location');

		}
		if ( is_page_template('home-templates/homepage.php') ) {
			wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . $GLOBALS['gmapKey'] . '&sensor=false&libraries=places&callback=initAutocomplete&language=en', false, null, true);
			wp_enqueue_style( 'bootstrap-datetimepicker-styles', get_template_directory_uri() . '/css/bootstrap-datetimepicker.min.css', array());
			wp_enqueue_script( 'lodashjs', get_template_directory_uri() . '/js/lodash.min.js', array(), '', true );
			wp_enqueue_script( 'bootstrap-datetimepicker', get_template_directory_uri() . '/js/bootstrap-datetimepicker.min.js', array('moment'), '', true );
			wp_register_script(
				'home',
				get_template_directory_uri() . '/js/home.js',
				array( 'jquery', 'owl-carousel', 'lodashjs' ),
				null, true
			);
			$localize = array(
				'ajaxurl' => admin_url('admin-ajax.php')
			);
			wp_localize_script('home', 'home_ajax_vars', $localize);
			wp_enqueue_script('home');
		}

		if ( is_product() ) {
			//wp_enqueue_script( 'bootstrap-js', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js', array(), '', false );
			wp_enqueue_script( 'product-single', get_template_directory_uri() . '/js/product.js', array('moment'), '', true );
			wp_enqueue_style( 'bootstrap-datetimepicker-styles', get_template_directory_uri() . '/css/custom_ourstory.css', array());
		}

		if ( is_cart() ){
			wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . $GLOBALS['gmapKey'] . '&sensor=false&libraries=places&language=en', false, null, true);
			wp_enqueue_style( 'bootstrap-datetimepicker-styles', get_template_directory_uri() . '/css/bootstrap-datetimepicker.min.css', array());
			wp_enqueue_script( 'lodashjs', get_template_directory_uri() . '/js/lodash.min.js', array(), '', true );
			wp_enqueue_script( 'bootstrap-datetimepicker', get_template_directory_uri() . '/js/bootstrap-datetimepicker.min.js', array('moment'), '', true );
			wp_enqueue_script( 'jquery-typeahead-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.js', array(), '', true );
			wp_enqueue_style( 'jquery-typeahead-styles', '//cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.css', array());
			wp_register_script(
				'cart-script',
				get_template_directory_uri() . '/js/cart.js',
				array( 'jquery', 'lodashjs' ),
				null, true
			);
			$localize = array(
				'ajaxurl' => admin_url('admin-ajax.php')
			);
			wp_localize_script('cart-script', 'home_ajax_vars', $localize);
			wp_enqueue_script('cart-script');
		}

		if ( is_checkout() ){
			wp_enqueue_script( 'checkout-js', get_template_directory_uri() . '/js/checkout.js', array(), '', true );
			wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . $GLOBALS['gmapKey'] . '&sensor=false&libraries=places&callback=initAutocomplete&language=en', false, null, true);
		}
		
		if (is_account_page()) {
			wp_enqueue_script( 'lodashjs', get_template_directory_uri() . '/js/lodash.min.js', array(), '', true );
			wp_enqueue_style( 'custom-my-order-css', get_template_directory_uri() . '/css/custom-myorder.css');
			wp_enqueue_script( 'custom-my-order-js', get_template_directory_uri() . '/js/custom-myorder.js', array('jquery', 'jquery-blockui'));

			wp_localize_script('custom-my-order-js', 'custom_my_order_object', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('custom_cancel_order_nonce'),
			));

			// redemptions
			wp_enqueue_script( 'custom-my-redemptions-js', get_template_directory_uri() . '/js/custom-my-redemptions.js', array('jquery', 'jquery-blockui'));
	
			wp_localize_script('custom-my-redemptions-js', 'custom_my_redemptions_object', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('custom_my_redemptions_nonce')
			));

			// Rewards & Vouchers
			$future_day = new DateTime();
			$future_day->add(new DateInterval('P10D'));
			$future_day = $future_day->format(DATE_RFC3339_EXTENDED);
			$coupons = queryMemberCouponList('2020-08-04T10:04:46.709+00:00', $future_day, 99999, 1);
			wp_enqueue_style( 'custom-my-rewards-css', get_template_directory_uri() . '/css/custom-my-rewards.css');
			wp_enqueue_script( 'custom-my-rewards-js', get_template_directory_uri() . '/js/custom-my-rewards.js', array('jquery', 'jquery-blockui'));
			wp_localize_script('custom-my-rewards-js', 'custom_my_rewards_object', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('custom_my_rewards_nonce'),
				'coupons' => $coupons,
			));

			//my address
			wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . $GLOBALS['gmapKey'] . '&sensor=false&libraries=places&callback=initAutocomplete&language=en', false, null, true);
			wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js', array('jquery'));


			wp_enqueue_script( 'wishlist-js', get_template_directory_uri() . '/js/wishlist.js', array());
		}

		$js_version2 = $theme_version . '.' . filemtime( get_template_directory() . '/js/template.js' );
		wp_enqueue_script( 'template-js', get_template_directory_uri() . '/js/template.js', array(), $js_version2, true );
	}
} // End of if function_exists( 'understrap_scripts' ).

add_action( 'wp_enqueue_scripts', 'understrap_scripts' );
