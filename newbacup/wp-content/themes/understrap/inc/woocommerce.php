<?php
/**
 * Add WooCommerce support
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'understrap_woocommerce_support' );
if ( ! function_exists( 'understrap_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function understrap_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add Product Gallery support.
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add Bootstrap classes to form fields.
		add_filter( 'woocommerce_form_field_args', 'understrap_wc_form_field_args', 10, 3 );
	}
}

// First unhook the WooCommerce content wrappers.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Then hook in your own functions to display the wrappers your theme requires.
add_action( 'woocommerce_before_main_content', 'understrap_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'understrap_woocommerce_wrapper_end', 10 );

if ( ! function_exists( 'understrap_woocommerce_wrapper_start' ) ) {
	/**
	 * Display the theme specific start of the page wrapper.
	 */
	function understrap_woocommerce_wrapper_start() {
		$container = get_theme_mod( 'understrap_container_type' );
		echo '<div class="wrapper" id="woocommerce-wrapper">';
		echo '<div class="' . esc_attr( $container ) . '" id="content" tabindex="-1">';
		echo '<div class="row">';
		get_template_part( 'global-templates/left-sidebar-check' );
		echo '<main class="site-main" id="main">';
	}
}

if ( ! function_exists( 'understrap_woocommerce_wrapper_end' ) ) {
	/**
	 * Display the theme specific end of the page wrapper.
	 */
	function understrap_woocommerce_wrapper_end() {
		echo '</main><!-- #main -->';
		get_template_part( 'global-templates/right-sidebar-check' );
		echo '</div><!-- .row -->';
		echo '</div><!-- Container end -->';
		echo '</div><!-- Wrapper end -->';
	}
}

if ( ! function_exists( 'understrap_wc_form_field_args' ) ) {
	/**
	 * Filter hook function monkey patching form classes
	 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
	 *
	 * @param string $args Form attributes.
	 * @param string $key Not in use.
	 * @param null   $value Not in use.
	 *
	 * @return mixed
	 */
	function understrap_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			// Targets all select input type elements, except the country and state select input types.
			case 'select':
				/*
				 * Add a class to the field's html element wrapper - woocommerce
				 * input types (fields) are often wrapped within a <p></p> tag.
				 */
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class'] = array( 'form-control' );
				// Add custom data attributes to the form input itself.
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			/*
			 * By default WooCommerce will populate a select with the country names - $args
			 * defined for this specific input type targets only the country select element.
			 */
			case 'country':
				$args['class'][] = 'form-group single-country';
				break;
			/*
			 * By default WooCommerce will populate a select with state names - $args defined
			 * for this specific input type targets only the country select element.
			 */
			case 'state':
				$args['class'][] = 'form-group';
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password':
			case 'text':
			case 'email':
			case 'tel':
			case 'number':
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control' );
				break;
			case 'textarea':
				$args['input_class'] = array( 'form-control' );
				break;
			case 'checkbox':
				// Add a class to the form input's <label> tag.
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input' );
				break;
			case 'radio':
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input' );
				break;
			default:
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control' );
				break;
		} // End of switch ( $args ).
		return $args;
	}
}

if ( ! is_admin() && ! function_exists( 'wc_review_ratings_enabled' ) ) {
	/**
	 * Check if reviews are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_reviews_enabled() {
		return 'yes' === get_option( 'woocommerce_enable_reviews' );
	}

	/**
	 * Check if reviews ratings are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_review_ratings_enabled() {
		return wc_reviews_enabled() && 'yes' === get_option( 'woocommerce_enable_review_rating' );
	}
}

function modify_query($query) {
  if( ! is_admin() && $query->is_main_query() && $query->query_vars['post_type'] == 'product') {
    if( isset( $_GET['filter_by'] ) ) {
    	$filter_by = $_GET['filter_by'];
      if ($filter_by == 'sale') {
      	$exclude_ids = getSesonalProducts();
      	$sale_products = wc_get_product_ids_on_sale();
        if (count($sale_products) > 0){
        	$query->set('post__in', array_diff($sale_products, $exclude_ids));
        } else {
        	$query->set('post__in', array(0));
        }
      }
      if ($filter_by == 'popular') {
      	$exclude_ids = getSesonalProducts();
        $popular_posts = new WP_Query( array(
          'fields'     => 'ids',
          'post_status' => 'publish',
          'post_type' => array('product'),
          'posts_per_page' => -1,
          'meta_query' => array(
              array(
                  'key'   => 'popular',
                  'value' => '1',
              )
          )
        ) );
        if (count($popular_posts->posts) > 0){
        	$query->set('post__in', array_diff($popular_posts->posts, $exclude_ids));
        } else {
        	$query->set('post__in', array(0));
        }
      }
      if ($filter_by == 'new') {
      	$exclude_ids = getSesonalProducts();
        $new_label_age = get_option('cdls_product_new_label_age', 0);
        $new_posts = new WP_Query( array(
            'fields'     => 'ids',
            'post_status' => 'publish',
            'post_type' => array('product'),
            'posts_per_page' => -1,
            'date_query'    => array(
                'column'  => 'post_date',
                'after'   => '- '.$new_label_age.'days'
            )
        ) );
        if (count($new_posts->posts) > 0){
        	$query->set('post__in', array_diff($new_posts->posts, $exclude_ids));
        } else {
        	$query->set('post__in', array(0));
        }
      }
    }
    if( isset( $_GET['orderby'] ) ) {
    	$orderby = $_GET['orderby'];
    	global $wpdb;
    	if ($orderby == 'price-desc'){
    		$query->set('orderby', 'meta_value_num {$wpdb->posts}.ID');
    		$query->set('order', 'DESC');
    		$query->set('meta_key', '_price');
    	}
    	if ($orderby == 'price'){
    		$query->set('orderby', 'meta_value_num {$wpdb->posts}.ID');
    		$query->set('order', 'ASC');
    		$query->set('meta_key', '_price');
    	}
    }
  }
}
add_action( 'pre_get_posts', 'modify_query' );

add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
function new_loop_shop_per_page( $cols ) {
	$cols = 9;
	return $cols;
}

add_action( 'wp_ajax_nopriv_checking_cart_items', 'checking_cart_items' );
add_action( 'wp_ajax_checking_cart_items', 'checking_cart_items' );
function checking_cart_items() {
	if( isset($_POST['added']) ){
		echo json_encode( WC()->cart->get_cart_contents_count() );
	}
	die();
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action( 'woocommerce_before_shop_loop', 'woocommerce_filter_products', 20);

if ( !function_exists( 'woocommerce_filter_products' ) ) {
	function woocommerce_filter_products() {
		echo '<div class="woocommerce-filter-product">
			<label>Filter by:</label>
			<a data-filter-type="all" class="filter-option selected">All</a>
			<a data-filter-type="popular" class="filter-option">Most popular</a>
			<a data-filter-type="sale" class="filter-option">Sale</a>
			<a data-filter-type="new" class="filter-option">New</a>
		</div>';
	}
}

function understrap_generate_delivery_method_text($deliveryMethod, $checkedDate, $typeChoosen) {
	$availableDays = array();
	$method = $deliveryMethod ? $deliveryMethod[0] : '';
	$methodText = '';
	if (!$method || $method == 'both') {
		$methodText .= '';
	}else if ($method == 'delivery') {
		$methodText .= 'Available for delivery';
	} else if ($method == 'self') {
		$methodText .= 'Available for Self-pickup';
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
	$availableDaysText = '';
	if ($typeChoosen == 'daily-product'){
		foreach ($listDay as $key=>$date) {
			if (isset($checkedDate[$key]) && $checkedDate[$key][0] == 'yes'){
				array_push($availableDays, $date);
			}
		}
		$connectText = (!$method || $method == 'both') ? 'Available on ' : ' on ';
		$availableDaysText .= count($availableDays) == 7 ? '' : $connectText.join(', ', $availableDays) ;
	}
	return strlen($methodText.$availableDaysText) > 0 ? $methodText.$availableDaysText : '&nbsp;';
}

function cedele_woocommerce_catalog_orderby( $orderby ) {
	unset($orderby["popularity"]);
	unset($orderby["rating"]);
	unset($orderby["date"]);
	return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "cedele_woocommerce_catalog_orderby", 20 );

add_filter( 'woocommerce_product_add_to_cart_text', 'out_of_stock_read_more_url', 50, 2 );
function out_of_stock_read_more_url( $text  ) {
	global $product;
	$productLeadTime = get_post_meta($product->get_id(), 'product-lead-time-checkbox');
	$isAdvancedProduct = count($productLeadTime) > 0 && $productLeadTime[0] == 'advance';

	if( $product->get_stock_status() == 'outofstock' ){
		$text = __('Sold Out', 'understrap');
	}
	if ($isAdvancedProduct){
		$text = __('Advance Order', 'understrap');
	}
	return $text;
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'replacing_add_to_cart_button', 10, 3 );
function replacing_add_to_cart_button( $button, $product) {
	if ($product->get_type() == 'bundle'){
	  $button_text = __("Select Options", "understrap");
	  $button = '<a class="add_to_cart_button product_type_bundle single_add_to_cart_button btn btn-outline-primary btn-block" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
	}

  return $button;
}

function validateDateTime($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
}

function getSesonalProducts() {
	$today = new DateTime(current_time('Y-m-d H:i:s'));

	global $wpdb;
	$queryDateRange = "SELECT wp_posts.ID, wp_postmeta.*,
	MAX(CASE WHEN mt1.meta_key = 'date-range-from' then mt1.meta_value ELSE NULL END) as date_range_from,
	MAX(CASE WHEN mt2.meta_key = 'date-range-to' then mt2.meta_value ELSE NULL END) as date_range_to,
	MAX(CASE WHEN mt3.meta_key = 'time-range-from' then mt3.meta_value ELSE NULL END) as time_range_from,
	MAX(CASE WHEN mt4.meta_key = 'time-range-to' then mt4.meta_value ELSE NULL END) as time_range_to
	FROM wp_posts
	INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
	INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )
	INNER JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )
	INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )
	INNER JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id ) WHERE 1=1  AND (
	( wp_postmeta.meta_key = '_type' AND wp_postmeta.meta_value = 'season-product-date-range'
		AND mt1.meta_key = 'date-range-from'
		AND mt2.meta_key = 'date-range-to'
		AND mt3.meta_key = 'time-range-from'
		AND mt4.meta_key = 'time-range-to'
	))
	AND wp_posts.post_type = 'product' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";

	$queryOneDay = "SELECT wp_posts.ID ,
	MAX(CASE WHEN mt2.meta_key = 'one-day-date-picker' then mt2.meta_value ELSE NULL END) as one_day_date,
	MAX(CASE WHEN mt3.meta_key = 'one-day-time-from' then mt3.meta_value ELSE NULL END) as time_range_from,
	MAX(CASE WHEN mt4.meta_key = 'one-day-time-to' then mt4.meta_value ELSE NULL END) as time_range_to
	FROM wp_posts
	INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
	INNER JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )
	INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )
	INNER JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id ) WHERE 1=1  AND (
	( wp_postmeta.meta_key = '_type' AND wp_postmeta.meta_value = 'season-product-one-day-only'
		AND mt2.meta_key = 'one-day-date-picker'
		AND mt3.meta_key = 'one-day-time-from'
		AND mt4.meta_key = 'one-day-time-to'))
	AND wp_posts.post_type = 'product' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";

	$queryDateRangeVariation = "SELECT wp_posts.ID, wp_postmeta.*,
	MAX(CASE WHEN mt1.meta_key = '_date_range_from_variation' then mt1.meta_value ELSE NULL END) as date_range_from,
	MAX(CASE WHEN mt2.meta_key = '_date_range_to_variation' then mt2.meta_value ELSE NULL END) as date_range_to,
	MAX(CASE WHEN mt3.meta_key = '_time_range_from_variation' then mt3.meta_value ELSE NULL END) as time_range_from,
	MAX(CASE WHEN mt4.meta_key = '_time_range_to_variation' then mt4.meta_value ELSE NULL END) as time_range_to
	FROM wp_posts
	INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
	INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )
	INNER JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )
	INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )
	INNER JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id ) WHERE 1=1  AND (
	( wp_postmeta.meta_key = '_type' AND wp_postmeta.meta_value = 'season-product-date-range-variation'
		AND mt1.meta_key = '_date_range_from_variation'
		AND mt2.meta_key = '_date_range_to_variation'
		AND mt3.meta_key = '_time_range_from_variation'
		AND mt4.meta_key = '_time_range_to_variation'
	))
	AND wp_posts.post_type = 'product_variation' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";

	$queryOneDayVariation = "SELECT wp_posts.ID ,
	MAX(CASE WHEN mt2.meta_key = '_one_day_date_picker_variation' then mt2.meta_value ELSE NULL END) as one_day_date,
	MAX(CASE WHEN mt3.meta_key = '_one_day_time_from_variation' then mt3.meta_value ELSE NULL END) as time_range_from,
	MAX(CASE WHEN mt4.meta_key = '_one_day_time_to_variation' then mt4.meta_value ELSE NULL END) as time_range_to
	FROM wp_posts
	INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
	INNER JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )
	INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )
	INNER JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id ) WHERE 1=1  AND (
	( wp_postmeta.meta_key = '_type' AND wp_postmeta.meta_value = 'season-product-one-day-only-variation'
		AND mt2.meta_key = '_one_day_date_picker_variation'
		AND mt3.meta_key = '_one_day_time_from_variation'
		AND mt4.meta_key = '_one_day_time_to_variation'))
	AND wp_posts.post_type = 'product_variation' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC";

	$queryOneDayVariation2 = "SELECT * FROM wp_posts INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1 = 1
        AND wp_posts.ID=2344";

	$seasonal_products_single_range = $wpdb->get_results($queryDateRange);
	$seasonal_products_single_day = $wpdb->get_results($queryOneDay);
	$seasonal_products_variations_day = $wpdb->get_results($queryOneDayVariation);
	$seasonal_products_variations_range = $wpdb->get_results($queryDateRangeVariation);

	$seasonal_products_range = array_merge($seasonal_products_single_range, $seasonal_products_variations_range);
	$seasonal_products_day = array_merge($seasonal_products_single_day, $seasonal_products_variations_day);

	$exclude_ids = array();

	foreach ($seasonal_products_range as $key => $p) {
		$time_from = strlen($p->time_range_from) == 5 ? $p->time_range_from.':00' : $p->time_range_from;
		$time_to = strlen($p->time_range_to) == 5 ? $p->time_range_to.':00' : $p->time_range_to;
		$startDateStr = $p->date_range_from . ' ' . $time_from;
		$toDateStr = $p->date_range_to . ' ' . $time_to;
		if (validateDateTime($startDateStr) && validateDateTime($toDateStr)){
			$startDate = new DateTime( $startDateStr );
			$toDate = new DateTime( $toDateStr );
			if ($startDate > $today || $today > $toDate){
				array_push($exclude_ids, $p->ID);
			}
		}
	}
	foreach ($seasonal_products_day as $key => $p) {
		$time_from = strlen($p->time_range_from) == 5 ? $p->time_range_from.':00' : $p->time_range_from;
		$time_to = strlen($p->time_range_to) == 5 ? $p->time_range_to.':00' : $p->time_range_to;
		$startDateStr = $p->one_day_date . ' ' . $time_from;
		$toDateStr = $p->one_day_date . ' ' . $time_to;
		if (validateDateTime($startDateStr) && validateDateTime($toDateStr)){
			$startDate = new DateTime( $startDateStr );
			$toDate = new DateTime( $toDateStr );
			if ($startDate > $today || $today > $toDate){
				array_push($exclude_ids, $p->ID);
			}
		}
	}

	return $exclude_ids;
}

function checkSeasonalProductAvailability($product_type, $product_id) {
	$is_seasonal_available = false;
	if ( $product_type == 'season-product-one-day-only'){
		$oneDayDatepicker = get_post_meta($product_id, 'one-day-date-picker', true);
		$oneDayTimeFrom = get_post_meta($product_id, 'one-day-time-from', true);
		$oneDayTimeTo = get_post_meta($product_id, 'one-day-time-to', true);
		$from = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeFrom);
		$to = DateTime::createFromFormat('Y-m-d H:i:s', $oneDayDatepicker.' '.$oneDayTimeTo);
		$is_seasonal_available = $from <= $now && $now <= $to;

	} elseif ( $product_type == 'season-product-date-range' ) {
		$dateRangeFrom = get_post_meta($product_id, 'date-range-from', true);
		$dateRangeTo = get_post_meta($product_id, 'date-range-to', true);
		$timeRangeFrom = get_post_meta($product_id, 'time-range-from', true);
		$timeRangeTo = get_post_meta($product_id, 'time-range-to', true);
		$from = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeFrom.' '.$timeRangeFrom);
		$to = DateTime::createFromFormat('Y-m-d H:i:s', $dateRangeTo.' '.$timeRangeTo);
		$is_seasonal_available = $from <= $now && $now <= $to;
	}
	return $is_seasonal_available;
}

function custom_meta_query( $q ){
	$exclude_ids = getSesonalProducts();
	if (count($exclude_ids) > 0){
		$q->set('post__not_in', $exclude_ids);
	}
	return $q;
}

// The main shop and archives meta query
add_filter( 'woocommerce_product_query', 'custom_product_query_meta_query', 10, 2 );
function custom_product_query_meta_query( $q ) {
	if( ! is_admin() )
		return custom_meta_query( $q );
	return $q;
}

function filter_woocommerce_cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
	return str_replace('[Remove]', '<i class="fa fa-trash"></i>', $coupon_html);
};
add_filter( 'woocommerce_cart_totals_coupon_html', 'filter_woocommerce_cart_totals_coupon_html', 10, 3 );

// ---------------------------------------------
// Remove Cross Sells From Default Position
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
// ---------------------------------------------
// Add them back UNDER the Cart Table
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

add_filter( 'woocommerce_cross_sells_columns', 'cedele_change_cross_sells_columns' );
function cedele_change_cross_sells_columns( $columns ) {
	return 4;
}

add_filter( 'woocommerce_cross_sells_total', 'cedele_change_cross_sells_product_no' );
	function cedele_change_cross_sells_product_no( $columns ) {
	return 4;
}

function cedele_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	if( isset( $_POST['product_addition_0'] ) && isset( $_POST['quantity_addition_0'] ) && $_POST['quantity_addition_0'] > 0 ) {
		$cart_item_data['product_addition_0'] = sanitize_text_field( $_POST['product_addition_0'] );
		$cart_item_data['quantity_addition_0'] = sanitize_text_field( $_POST['quantity_addition_0'] );
	}
	if( isset( $_POST['product_addition_1'] ) && isset( $_POST['quantity_addition_1'] ) && $_POST['quantity_addition_1'] > 0) {
		$cart_item_data['product_addition_1'] = sanitize_text_field( $_POST['product_addition_1'] );
		$cart_item_data['quantity_addition_1'] = sanitize_text_field( $_POST['quantity_addition_1'] );
	}
	if( isset( $_POST['bundle_data'] ) ) {
		$cart_item_data['bundle_data'] = sanitize_text_field( $_POST['bundle_data'] );
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'cedele_add_cart_item_data', 10, 3 );

add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta_as_order_item_meta', 10, 4 );
function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {

  $_product = $item->get_product();
  $quantity = $item->get_quantity();
  $product_id = $_product->get_id();
  $bundle_products_meta = array();
  $additional_products_meta = array();

  if ( isset($values['bundle_data']) ) {
    $bundle_data = json_decode(stripslashes($values['bundle_data']));
    foreach ($bundle_data as $key => $data) {
      $value_arr = array();
      if ($data->is_user_can_define){
        foreach ($data->linked_products as $key => $pr) {
          if ($pr->quantity){
            $_product = wc_get_product($pr->product_id);
            array_push($value_arr, $pr->quantity.' x '.$_product->get_name());
            array_push($bundle_products_meta, array(
              "product_id" => $pr->product_id,
              "product_name" => $_product->get_name(),
              "quantity" => $pr->quantity
            ));
          }
        }
      } else {
        foreach ($data->linked_products as $key => $pr) {
          if ($pr->selected){
            $_product = wc_get_product($pr->product_id);
            array_push($value_arr, $pr->quantity.' x '.$_product->get_name() . (floatval($pr->price) ? (' (+$' . floatval($pr->price) . ')') : ''));
            array_push($bundle_products_meta, array(
              "product_id" => $pr->product_id,
              "product_name" => $_product->get_name(),
              "quantity" => $pr->quantity,
              "price" => floatval($pr->price)
            ));
          }
        }
      }
      $value = join(", ", $value_arr);
      $item->update_meta_data( $data->title, $value );
    }
  }

  $add_key = 'Additional Products';
  $add_value = '';
  $add_value_arr = array();
  if( isset( $values['product_addition_0'] ) ) {
    array_push($add_value_arr, $values['quantity_addition_0'].' x '.$values['product_addition_0']);
    array_push($additional_products_meta, array(
      "product_name" => $values['product_addition_0'],
      "quantity" => $values['quantity_addition_0'],
    ));
  }
  if( isset( $values['product_addition_1'] ) ) {
    array_push($add_value_arr, $values['quantity_addition_1'].' x '.$values['product_addition_1']);
    array_push($additional_products_meta, array(
      "product_name" => $values['product_addition_1'],
      "quantity" => $values['quantity_addition_1'],
    ));
  }
  $add_value = join(", ", $add_value_arr);
  if (strlen($add_value) > 0){
    $item->update_meta_data( $add_key, $add_value );
  }

  $gift_card_value = get_post_meta($product_id, 'gift_card_value', true);
  $expiry_duration = get_post_meta($product_id, 'expiry_duration', true);

  $order_coupons = array();

  if ( isset($gift_card_value) && $gift_card_value > 0 ){
    for ( $i = 0; $i < $quantity; $i++ ) {
      $coupon_code = create_coupon($gift_card_value, isset($expiry_duration) ? $expiry_duration : 0);
      $new_coupon = array(
        'code' => $coupon_code,
        'value' => $gift_card_value,
        'expiry' => date('Y-m-d', strtotime('+'.($expiry_duration*30).' days')),
        'product_id' => $product_id
      );
      array_push($order_coupons, $new_coupon);
    }
  }

  $order->add_meta_data('_gift_cards', $order_coupons);
  $item->add_meta_data('_bundle_products_meta', $bundle_products_meta);
  $item->add_meta_data('_additional_products_meta', $additional_products_meta);
}

function generated_counpon_code() {
  for ( $i = 0; $i < 1; $i++ ) {
    $coupon_code = 'cdl'.strtolower( wp_generate_password( 6, false ) );

    // Check that the generated code doesn't exist yet
    if( coupon_exists( $coupon_code ) ) $i--; // continue
    else break; // Stop the loopp
  }
  return $coupon_code;
}

function coupon_exists( $coupon_code ) {
    global $wpdb;
    return $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts
        WHERE post_type = 'shop_coupon' AND post_name = '%s'", $coupon_code));
}

function create_coupon ($value, $expiry) {
    // Get a random unique coupon code
    $coupon_code = generated_counpon_code();

    $coupon = array(
			'post_title' => $coupon_code,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'     => 'shop_coupon'
		);

		$new_coupon_id = wp_insert_post( $coupon );
		$expiry_date = date('Y-m-d', strtotime('+'.($expiry*30).' days'));
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', wc_format_decimal($value, 2) );
		update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' );
		update_post_meta( $new_coupon_id, 'expiry_date', $expiry_date );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'yes' );
		update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
    return $coupon_code;
}

function cedele_get_item_data( $item_data, $cart_item_data ) {
	if( isset( $cart_item_data['bundle_data'] ) ) {
		$bundle_data = json_decode(stripslashes($cart_item_data['bundle_data']));
		foreach ($bundle_data as $key => $data) {
			$value_arr = array();
			if ($data->is_user_can_define){
				foreach ($data->linked_products as $key => $pr) {
					if ($pr->quantity){
						$_product = wc_get_product($pr->product_id);
						array_push($value_arr, $pr->quantity.' x '.$_product->get_name());
					}
				}
			} else {
				foreach ($data->linked_products as $key => $pr) {
					if ($pr->selected){
						$_product = wc_get_product($pr->product_id);
						array_push($value_arr, $pr->quantity.' x '.$_product->get_name() . (floatval($pr->price) ? (' (+$' . $pr->price . ')') : ''));
					}
				}
			}
			$value = join(", ", $value_arr);
			$item_data[] = array(
				'key' => $data->title,
				'value' => $value
			);
		}
	}

	$add_key = 'Additional Products';
	$add_value = '';
	$add_value_arr = array();
	if( isset( $cart_item_data['product_addition_0'] ) ) {
		array_push($add_value_arr, $cart_item_data['quantity_addition_0'].' x '.$cart_item_data['product_addition_0']);
	}
	if( isset( $cart_item_data['product_addition_1'] ) ) {
		array_push($add_value_arr, $cart_item_data['quantity_addition_1'].' x '.$cart_item_data['product_addition_1']);
	}
	$add_value = join(", ", $add_value_arr);
	if (strlen($add_value) > 0){
		$item_data[] = array(
			'key' => $add_key,
			'value' => wc_clean( $add_value )
		);
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'cedele_get_item_data', 10, 2 );

add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );


//CART PAGE HOOKS & FILTERS

//disable shipping cache
add_action('woocommerce_checkout_update_order_review', 'checkout_update_refresh_shipping_methods', 10, 1);
add_action('woocommerce_cart_calculate_fees', 'checkout_update_refresh_shipping_methods', 10, 1);
function checkout_update_refresh_shipping_methods( $post_data ) {
  $packages = WC()->cart->get_shipping_packages();
  foreach ($packages as $package_key => $package ) {
    WC()->session->set( 'shipping_for_package_' . $package_key, false ); // Or true
  }
  $surcharge = WC()->session->get('surcharge');
  if (isset($surcharge) && $surcharge > 0){
  	WC()->cart->add_fee('Surcharge', $surcharge, false);
  }
}

// CHECKOUT PAGE HOOKS & FILTERS

add_filter( 'woocommerce_checkout_fields', 'cedele_fields_order' );
function cedele_fields_order( $checkout_fields ) {
	unset($checkout_fields['billing']['billing_city']);
	$checkout_fields['billing']['billing_phone']['priority'] = 24;
	$checkout_fields['billing']['billing_email']['priority'] = 25;
	$checkout_fields['billing']['billing_phone']['class'] = array('form-row-first');
	$checkout_fields['billing']['billing_email']['class'] = array('form-row-last');
	$checkout_fields['billing']['billing_phone']['label'] = 'Phone Number';
	$checkout_fields['billing']['billing_email']['label'] = 'Email Address';
	$checkout_fields['billing']['billing_postcode']['required'] = false;
	$checkout_fields['billing']['billing_address_1']['required'] = false;
	$checkout_fields['billing']['billing_phone']['required'] = true;
	$checkout_fields['billing']['billing_email']['required'] = true;
	$checkout_fields['billing']['billing_state']['required'] = false;
	//$checkout_fields['shipping']['shipping_address_1']['required'] = false;

  $checkout_fields['order']['order_comments']['placeholder'] = 'Write your instructions for the delivery';
  $checkout_fields['order']['order_comments']['label'] = 'Delivery Instructions';
  $customerAddress = WC()->session->get( 'customerAddress' );
  if ($customerAddress->deliveryType == 'self-collection'){
  	unset($checkout_fields['shipping']['shipping_first_name']);
  	unset($checkout_fields['shipping']['shipping_last_name']);
  	unset($checkout_fields['shipping']['shipping_address_1']);
  	unset($checkout_fields['shipping']['shipping_address_2']);
  	unset($checkout_fields['shipping']['shipping_company']);
  	unset($checkout_fields['shipping']['shipping_country']);
  	unset($checkout_fields['shipping']['shipping_state']);
  	unset($checkout_fields['shipping']['shipping_postcode']);
  } else if ($customerAddress->deliveryType == 'delivery'){
  	unset($checkout_fields['shipping']['shipping_first_name']);
  	unset($checkout_fields['shipping']['shipping_last_name']);
    unset($checkout_fields['shipping']['shipping_state']);
  }
	return $checkout_fields;
}

remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_payment', 10 );


add_filter( 'woocommerce_before_order_notes', 'cedele_shipping_info' );
function cedele_shipping_info() {
	wc_get_template('checkout/form-address.php');
}

add_action( 'woocommerce_cart_totals_before_shipping', 'cedele_force_calculate_shipping', 1, 2550 );
function cedele_force_calculate_shipping() {
  WC()->shipping->calculate_shipping(WC()->shipping->packages);
  WC()->cart->calculate_totals();
}

add_action( 'woocommerce_after_order_notes', 'add_custom_checkout_hidden_field' );
function add_custom_checkout_hidden_field( $checkout ) {
    $assigned_store = WC()->session->get( 'assigned_store');
    echo '<input type="hidden" class="input-hidden" name="assigned_store" id="assigned_store" value="' . $assigned_store . '">';
}

add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_checkout_hidden_field' );
function save_custom_checkout_hidden_field( $order_id ) {
	$customerAddress = WC()->session->get('customerAddress');
    $deliveryType = $customerAddress->deliveryType;
	$deliveryAddress = json_decode(stripslashes($customerAddress->deliveryAddress));
//    var_dump($_POST['send-email']);die;

	if ( $deliveryType == 'delivery'){
	  if ( ! empty( $_POST['assigned_store'] ) ) {
	    update_post_meta( $order_id, 'wc_order_assigned_store', sanitize_text_field( $_POST['assigned_store'] ) );
	  }
	  update_post_meta( $order_id, 'wp_custom_order_delivery_address', sanitize_text_field( $deliveryAddress->formatted_address ) );
	}
	if ( $deliveryType == 'self-collection') {
		if ( ! empty( $_POST['assigned_store'] ) ) {
	    update_post_meta( $order_id, 'wc_order_pickup_store', sanitize_text_field( $_POST['assigned_store'] ) );
	  }
	}

    if(Store_Locator_Manager()) {
        global $wpdb;
        $user = wp_get_current_user();
        $table_store = $wpdb->prefix . 'store_location';
        $storeMapEmail = $wpdb->get_results("SELECT * FROM $table_store WHERE email_address = '$user->user_email'");
        if(count($storeMapEmail) > 1 || empty($storeMapEmail) ) {
            $stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 1");
            update_post_meta( $order_id, 'wc_order_assigned_store', $stores[0]->id );
        } else {
            update_post_meta( $order_id, 'wc_order_assigned_store', $storeMapEmail[0]->id );
        }
    }

    if($_POST['send-email']) {
        add_post_meta( $order_id, 'wc_order_custom_email', true );
    }

    // Order source
    if(Store_Locator_Manager()) {
        $user_id = get_current_user_id();
        $user_first_name = get_user_meta( $user_id, 'first_name', true );
        $user_last_name = get_user_meta( $user_id, 'last_name', true );
        add_post_meta( $order_id, 'wc_order_source', $user_first_name .' '. $user_last_name );
    } else {
        add_post_meta( $order_id, 'wc_order_source', 'Online' );
    }

	update_post_meta( $order_id, 'wp_custom_order_method', sanitize_text_field( $deliveryType ) );
	update_post_meta( $order_id, 'wp_custom_order_delivery_date', sanitize_text_field( $customerAddress->date ) );
	update_post_meta( $order_id, 'wp_custom_order_delivery_collection_time', sanitize_text_field( $customerAddress->time ) );

    if($deliveryType == 'self-collection') {
        update_post_meta( $order_id, '_shipping_address_1', sanitize_text_field( $customerAddress->pickupStoreOnlyAddress ) );
    }
    if ( $deliveryType == 'delivery' || $deliveryType == 'self-collection'){
        //switch billing and shipping address in order because we show billing address fields as shipping address in checkout form
        update_post_meta( $order_id, '_shipping_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_post_meta( $order_id, '_shipping_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        update_post_meta( $order_id, '_shipping_email', sanitize_text_field( $_POST['billing_email'] ) );
        update_post_meta( $order_id, '_shipping_phone', sanitize_text_field( $_POST['billing_phone'] ) );

        $user_id = get_current_user_id();
        $user_data = get_userdata($user_id);
        $email = $user_data->user_email;
        $user_first_name = get_user_meta( $user_id, 'first_name', true );
        $user_last_name = get_user_meta( $user_id, 'last_name', true );
        $phone = get_user_meta($user_id,'user_registration_phone_number',true);

        // Updating Billing info
        if($user_first_name) {
          update_post_meta( $order_id, '_billing_first_name', $user_first_name );
        }
        if($user_last_name) {
          update_post_meta( $order_id, '_billing_last_name', $user_last_name );
        }
        if($email) {
          update_post_meta( $order_id, '_billing_email', $email );
        }
        if($phone) {
          update_post_meta( $order_id, '_billing_phone', $phone );
        }
    }

    $items = WC()->cart->get_cart();
    $hasAdvanceProduct = false;
    foreach($items as $key => $item) {
        $_product = wc_get_product( $item['data']->get_id());
        $productLeadTime = get_post_meta($_product->get_id(), 'product-lead-time-checkbox', true);
        $isAdvancedProduct = $productLeadTime == 'advance';
        if ($isAdvancedProduct){
            $hasAdvanceProduct = true;
        }
    }
    if($hasAdvanceProduct) {
        global $wpdb;
        $table_store = $wpdb->prefix . 'store_location';
        $stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 1");
        update_post_meta( $order_id, 'wc_order_assigned_store', $stores[0]->id );
    }
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'custom_checkout_field_display_admin_order_meta', 10, 1 );
function custom_checkout_field_display_admin_order_meta( $order ){
    $shipping_email = get_post_meta( $order->get_id(), '_shipping_email', true );
    if( ! empty( $shipping_email ) )
    echo '<div class="address" style="margin-bottom: 15px"><a href="mailto:'.$shipping_email.'">'.$shipping_email.'</a></div><div class="edit_address"><p class="form-field form-field-wide">
      <label for="_shipping_email">'._e( 'Email address:', 'woocommerce' ).'</label>
      <input type="text" class="short" style="" name="_shipping_email" id="_shipping_email" value="'.$shipping_email.'" placeholder="">
    </p></div>';
    $shipping_phone = get_post_meta( $order->get_id(), '_shipping_phone', true );
    if( ! empty( $shipping_phone ) )
    echo '<div class="address"><a href="tel:'.$shipping_phone.'">'.$shipping_phone.'</a></div><div class="edit_address"><p class="form-field form-field-wide">
      <label for="_shipping_phone">'._e( 'Phone:', 'woocommerce' ).'</label>
      <input type="text" class="short" style="" name="_shipping_phone" id="_shipping_phone" value="'.$shipping_phone.'" placeholder="">
    </p></div>';
}

add_action( 'woocommerce_order_details_after_customer_details', 'display_assigned_store', 10 );
function display_assigned_store( $order ) {
	global $wpdb;
    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    $assigned_store_id = get_post_meta( $order_id, 'wc_order_assigned_store', true );
    $assigned_store = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}store_location WHERE store_id = {$assigned_store_id}");
    if ( isset($assigned_store) ){
    	echo '<p class="assigned-store"><strong>'.__('Assigned Store', 'woocommerce') . ':</strong> ' . $assigned_store->store_name .'</p>';
    } else {
    	echo '';
    }
}

function customize_wc_errors( $error ) {
  if ( strpos( $error, 'is not in stock' ) !== false ) {
    return '';
  } else {
    return $error;
  }
}
add_filter( 'woocommerce_add_error', 'customize_wc_errors' );

function customize_wc_notices( $notice ) {
  if ( strpos( $notice, 'Product successfully removed' ) !== false ) {
    return '';
  } else {
    return $notice;
  }
}
add_filter( 'woocommerce_add_success', 'customize_wc_notices' );

add_action( 'woocommerce_customer_save_address', 'after_save_shipping', 10, 2 );
function after_save_shipping( $user_id, $load_address ) {
	if ($load_address == 'shipping'){
		if ( isset($_POST['shipping_same_address']) && $_POST['shipping_same_address'] == '1' ){
			$current_user = wp_get_current_user();
			update_user_meta($user_id, 'billing_state', $current_user->shipping_state);
			update_user_meta($user_id, 'billing_postcode', $current_user->shipping_postcode);
			update_user_meta($user_id, 'billing_address_1', $current_user->shipping_address_1);
			update_user_meta($user_id, 'billing_address_2', $current_user->shipping_address_2);
		}
	}
	if ($load_address == 'billing'){
		if ( isset($_POST['shipping_same_address'])){
			update_user_meta($user_id, 'shipping_same_address', $_POST['shipping_same_address']);
		}
	}
}

add_filter( 'woocommerce_billing_fields' , 'custom_override_billing_fields' );
function custom_override_billing_fields( $fields ) {
  unset($fields['billing_phone']);
  unset($fields['billing_email']);
  unset($fields['billing_company']);
  unset($fields['billing_city']);
	$fields['billing_state']['class'] = array('form-row-first');
	$fields['billing_postcode']['class'] = array('form-row-last');
  $fields['billing_state']['required'] = true;
  $fields['billing_state']['label'] = 'State';
  $fields['billing_address_2']['placeholder'] = 'Buiding Name/ Floor Number/ Unit Number';
  return $fields;
}

add_filter( 'woocommerce_shipping_fields' , 'custom_override_shipping_fields' );
function custom_override_shipping_fields( $fields ) {
  unset($fields['shipping_phone']);
  unset($fields['shipping_email']);
  unset($fields['shipping_company']);
  unset($fields['shipping_city']);
	$fields['shipping_state']['class'] = array('form-row-first');
	$fields['shipping_postcode']['class'] = array('form-row-last');
  $fields['shipping_state']['required'] = true;
  $fields['shipping_state']['label'] = 'State';
  $fields['shipping_address_2']['placeholder'] = 'Buiding Name/ Floor Number/ Unit Number';

  $fields['shipping_same_address'] = array(
      'label' => __('Billing address is the same as Delivery address', 'woocommerce'),
      'required' => false, // if field is required or not
      'type' => 'checkbox', // add field type
      'class' => array('form-row-wide')    // add class name
  );
  return $fields;
}

/***** MY ACCOUNT *******/

add_filter( 'woocommerce_my_account_get_addresses', 'cedele_change_title_account' );
function cedele_change_title_account( $account_title ) {
	$account_title = array(
		'billing' => __( 'Billing Address', 'understrap' ),
		'shipping' => __( 'Delivery Address', 'understrap' ),
	);

	return $account_title;
}

add_filter( 'profile_update' , 'custom_update_checkout_fields', 10, 2 );
function custom_update_checkout_fields($user_id, $old_user_data ) {
  $current_user = wp_get_current_user();

  // Updating Billing info
  if($current_user->user_firstname != $current_user->billing_first_name)
    update_user_meta($user_id, 'billing_first_name', $current_user->user_firstname);
  if($current_user->user_lastname != $current_user->billing_last_name)
    update_user_meta($user_id, 'billing_last_name', $current_user->user_lastname);
  if($current_user->user_email != $current_user->billing_email)
    update_user_meta($user_id, 'billing_email', $current_user->user_email);

  // Updating Shipping info
  if($current_user->user_firstname != $current_user->shipping_first_name)
    update_user_meta($user_id, 'shipping_first_name', $current_user->user_firstname);
  if($current_user->user_lastname != $current_user->shipping_last_name)
    update_user_meta($user_id, 'shipping_last_name', $current_user->user_lastname);
  if($current_user->user_email != $current_user->shipping_email)
    update_user_meta($user_id, 'shipping_email', $current_user->user_email);
}

add_action( 'user_register', 'save_user_info_to_address', 10, 1 );
function save_user_info_to_address($user_id) {
	$form_data = json_decode(stripslashes( $_POST['form_data'] ));
  $first_name = '';
  $last_name = '';
  $phone_number = '';
  foreach($form_data as $item) {
		if ($item->field_name == 'first_name'){
    	$first_name = $item->value;
    }
		if ($item->field_name == 'last_name'){
    	$last_name = $item->value;
    }
		if ($item->field_name == 'phone_number'){
    	$phone_number = $item->value;
    }
	}

	// Updating Billing info
  update_user_meta($user_id, 'billing_first_name', $first_name);
  update_user_meta($user_id, 'billing_last_name', $last_name);
  update_user_meta($user_id, 'billing_phone', $phone_number);

  // Updating Shipping info
  update_user_meta($user_id, 'shipping_first_name', $first_name);
  update_user_meta($user_id, 'shipping_last_name', $last_name);
  update_user_meta($user_id, 'billing_phone', $phone_number);
}

add_action( 'woocommerce_save_account_details', 'save_custom_account_details', 12, 1 );
function save_custom_account_details( $user_id ) {
  if( isset( $_POST['user_registration_user_birthday'] ) )
      update_user_meta( $user_id, 'user_registration_user_birthday', sanitize_text_field( $_POST['user_registration_user_birthday'] ) );

  if( isset( $_POST['user_registration_user_gender'] ) )
      update_user_meta( $user_id, 'user_registration_user_gender', sanitize_text_field( $_POST['user_registration_user_gender'] ) );
}

/********** BREADCRUMB ***********/
add_filter( 'woocommerce_get_breadcrumb', 'custom_breadcrumb', 10, 2 );
function custom_breadcrumb( $crumbs, $object_class ){
  foreach( $crumbs as $key => $crumb ){
    $taxonomy = 'product_cat';
    $term_array = term_exists( $crumb[0], $taxonomy );
    if ( $term_array !== 0 && $term_array !== null ) {
      $term = get_term( $term_array['term_id'], $taxonomy );
      $crumbs[$key][1] = get_permalink( wc_get_page_id('shop') ).'?swoof=1&paged=1&product_cat='.$term->slug;
    }
  }

  return $crumbs;
}

/********** WISHLIST **************/
if( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ){
	function yith_wcwl_ajax_update_count(){
		wp_send_json( array(
			'count' => yith_wcwl_count_all_products()
		) );
	}
	add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
	add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
}

function add_custom_total_price( $cart_object ) {

  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
    return;

  if (is_cart()) {
    $enable_redemp_point = isset($_POST['enable_redemp_point']) ? $_POST['enable_redemp_point'] : '';
    WC()->session->set( 'enable_redemp_point', $enable_redemp_point);
  }

  if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

  foreach ( $cart_object->get_cart() as $cart_key => $value ) {
		$original_price = $value['data']->get_price();
    $_product =  wc_get_product( $value['data']->get_id());
    $bundle_datas = json_decode(stripslashes($value['bundle_data']));

    $total_addition_price = 0;
    if (is_array($bundle_datas) && count($bundle_datas) > 0) {
      foreach ($bundle_datas as $bundle_key => $bundle) {
        if (!$bundle->is_user_can_define) {
          if (is_array($bundle->linked_products) && count($bundle->linked_products)) {
            foreach($bundle->linked_products as $item_key => $item){
              if (isset($item->selected) && $item->selected) {
                $total_addition_price += floatval($item->price);
              }
            }
          }
        }
      }
    }
    $value['data']->set_price($original_price + $total_addition_price);
  }
}
add_action( 'woocommerce_before_calculate_totals', 'add_custom_total_price', 10, 2);

// after calculator
add_filter( 'woocommerce_calculated_total', 'custom_calculated_total', 10, 2 );
function custom_calculated_total( $total, $cart ){
    $enable_redemp_point = WC()->session->get( 'enable_redemp_point');

    if ($enable_redemp_point) {
		$user_id = get_current_user_id();
		$getPointMember = getPointMember($user_id);

		$new_total = $total;
		$apply_point = 0;
		if (!empty($getPointMember)) {
			$apply_point = $getPointMember->point_balance;
			$final_point = $apply_point;

			if (ceil($new_total) <= $apply_point) {
				$final_point = ceil($new_total);
			}

			$total = $new_total - $final_point;
			WC()->session->set( 'used_redemp_point', $final_point );

			if (floatval($total) < 0)
				$total = 0;
		}
    }
    return $total;
}

/***************** ACCOUNT PASSWORD ****************/
add_filter( 'woocommerce_min_password_strength', 'reduce_min_strength_password_requirement', 10 );
function reduce_min_strength_password_requirement( $strength ) {
  // 3 => Strong (default) | 2 => Medium | 1 => Weak | 0 => Very Weak (anything).
  return 0; 
}

/***************** EXPORTS *************************/
add_filter('woe_get_order_value_wc_order_pickup_store',function ($value, $order, $fieldname) {
  global $wpdb;
  $table_store = $wpdb->prefix . 'store_location';
  $store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $value", ARRAY_A);
  return $store['store_name'];
},10,3);

add_filter('woe_get_order_value_wc_order_assigned_store',function ($value, $order, $fieldname) {
  global $wpdb;
  $table_store = $wpdb->prefix . 'store_location';
  $store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $value", ARRAY_A);
  return $store['store_name'];
},10,3);

add_filter('woe_get_order_value_wp_custom_order_delivery_address',function ($value, $order, $fieldname) {
	$line2 = $order->get_shipping_address_2();
	return $value.($line2 ? "\n".$line2 : '');
},10,3);

add_filter('woe_get_order_product_value_product_variation',function ($value, $order, $item, $product, $item_meta) {
	$value = str_replace(', ', "\n", $value);
	$value = str_replace(': ', "\n", $value);
	return $value;
},10,5);

add_filter('woe_get_order_value_wp_custom_order_rider',function ($value, $order, $fieldname) {
  global $wpdb;
  $table_rider = $wpdb->prefix . 'cedele_setting_riders';
  $rider = $wpdb->get_row("SELECT * FROM $table_rider WHERE id = $value", ARRAY_A);
  return $rider['rider_name'];
},10,3);

add_filter('woe_get_order_value_order_notes',function ($value, $order,$fieldname) {
	return $order->get_customer_note();
},10,3);

add_filter('woe_get_order_product_value_department',function ($value, $order, $item, $product,$item_meta) {
	$product_id = $product->get_id();
	$department = get_post_meta($product_id, 'department', true);
	return join(",  ",  $department);
},10,5);

add_filter( "woe_fetch_order_products", function ($products, $order, $labels, $format, $static_vals) {
  $new_products = array();
  foreach($order->get_items() as $pos=>$item) {
    $product_clone = array_merge(array(), $products[$pos]);
    $bundle_products_meta = $item->get_meta('_bundle_products_meta');
    $additional_products_meta = $item->get_meta('_additional_products_meta');

    // get data for main product
    if (count($bundle_products_meta) > 0) {
      $product_clone['product_variation'] = '';
    }
    if (count($additional_products_meta) > 0) {
      $product_variation_parts = explode('|', $product_clone['product_variation']);
      foreach ($product_variation_parts as $key => $element) {
        if (strpos($element, 'Additional Products') !== false) {
          unset($product_variation_parts[$key]);
        }
      }
      $product_clone['product_variation'] = join('|', $product_variation_parts);
    }
    $new_products[] = $product_clone;

    //add new rows for bundle & additonal products
    if (count($bundle_products_meta) > 0) {
      foreach($bundle_products_meta as $meta) {
        $product_with_meta = array_merge(array(), $products[$pos]);
        $product_with_meta['product_variation'] = $meta['product_name']; 
        $product_with_meta['qty'] = $meta['quantity'] * $product_with_meta['qty']; 
        $new_products[] = $product_with_meta;
      }
    }
    if (count($additional_products_meta) > 0) {
      foreach($additional_products_meta as $meta) {
        $product_with_meta = array_merge(array(), $products[$pos]);
        $product_with_meta['product_variation'] = $meta['product_name']; 
        $product_with_meta['qty'] = $meta['quantity'] * $product_with_meta['qty']; 
        $new_products[] = $product_with_meta;
      }
    }
  }

  return $new_products;
} , 10, 5);