<?php
/**
 * UnderStrap functions and definitions
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$GLOBALS['gmapKey'] = 'AIzaSyCLVaWET2nIrbcrEM1Ub8ReGmFsX0jgeSY';

require_once get_template_directory() . '/inc/cartLoginRegister/ajax_login.php';
require_once get_template_directory() . '/inc/post_type_career.php';

$understrap_includes = array(
		'/theme-settings.php',                  // Initialize theme default settings.
		'/setup.php',                           // Theme setup and custom theme supports.
		'/widgets.php',                         // Register widget area.
		'/enqueue.php',                         // Enqueue scripts and styles.
		'/template-tags.php',                   // Custom template tags for this theme.
		'/pagination.php',                      // Custom pagination for this theme.
		'/hooks.php',                           // Custom hooks.
		'/extras.php',                          // Custom functions that act independently of the theme templates.
		'/customizer.php',                      // Customizer additions.
		'/custom-comments.php',                 // Custom Comments file.
		'/jetpack.php',                         // Load Jetpack compatibility file.
		'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
		'/cedele-shipping.php',                 // Load shipping functions.
		'/cedele-grouped-product.php',
		'/cedele-gift-card-product.php',
		'/woocommerce.php',                     // Load WooCommerce functions.
		'/editor.php',                          // Load Editor functions.
		'/deprecated.php',                      // Load deprecated functions.
		'/data-query.php',
);

foreach ($understrap_includes as $file) {
	require_once get_template_directory() . '/inc' . $file;
}

function additional_product_tabs_metabox()
{
	add_meta_box(
			'add_product_metabox_additional_tabs',
			'
            <div class="product-availability" style="display:flex">
                <div class="product-availability-title">
                    Product availability
                </div>
            </div>
        ',
			'additional_product_tabs_metabox_content',
			'product',
			'advanced',
			'default',
			null
	);
}

function show_input_time($input, $default = '00:00')
{
	$format = DateTime::createFromFormat('H:i:s', '00:00:00');
	if ($time = DateTime::createFromFormat('H:i', $input)) {
		$format = $time;
	} else if ($time = DateTime::createFromFormat('H:i:s', $input)) {
		$format = $time;
	} else {
		return $default;
	}

	return $format->format('H:i');
}

function additional_product_tabs_metabox_content($post)
{
	global $post;

	$typeChoosen = get_post_meta($post->ID, '_type', true);

	$listDay = [
			'monday' => __('Monday', 'woocommerce'),
			'tuesday' => __('Tuesday', 'woocommerce'),
			'wednesday' => __('Wednesday', 'woocommerce'),
			'thursday' => __('Thursday', 'woocommerce'),
			'friday' => __('Friday', 'woocommerce'),
			'saturday' => __('Saturday', 'woocommerce'),
			'sunday' => __('Sunday', 'woocommerce'),
	];

	$checkedDate = get_post_custom($post->ID);

	if (empty($typeChoosen)) {
		$typeChoosen = 'daily-product';
	};

	$timeFrom = get_post_meta($post->ID, 'daily-product-available-time-from', true);
	$timeTo = get_post_meta($post->ID, 'daily-product-available-time-to', true);


	$oneDayTimeFrom = get_post_meta($post->ID, 'one-day-time-from', true);
	$oneDayTimeTo = get_post_meta($post->ID, 'one-day-time-to', true);

	$timeRangeFrom = get_post_meta($post->ID, 'time-range-from', true);
	$timeRangeTo = get_post_meta($post->ID, 'time-range-to', true);


	$oneDayDatepicker = get_post_meta($post->ID, 'one-day-date-picker');
	$dateRangeFrom = get_post_meta($post->ID, 'date-range-from');
	$dateRangeTo = get_post_meta($post->ID, 'date-range-to');
	$deliveryMethod = get_post_meta($post->ID, 'delivery_method');

	// product lead time
	$productLeadTime = get_post_meta($post->ID, 'product-lead-time-checkbox');
	$leadTimeMinutes = get_post_meta($post->ID, 'product-lead-time-minutes');
	$leadTimeDays = get_post_meta($post->ID, 'product-lead-time-days');

	// store location
	require_once __DIR__ . '/store_location.php';

	$list_stores = get_list_stores($post->ID);
	$list_option_stores = get_list_option_stores();
	?>
	<div class="wp-product-avaiable product-availability-type-product">
		<b>Product frequently:</b>
		<input id="daily-product" type="radio" value="daily-product" name="product-avalability-type"
			   onclick="checkDailyProduct()"/>
		<label>Normal Product</label>
		<input id="season-product" type="radio" name="product-avalability-type" value="season-product"
			   onclick="checkSeasonProduct()"/>
		<label>Seasonal Product</label>
	</div>
	<input type="hidden" id="type-choosen" name="type-choosen" value="<?php echo $typeChoosen ?>"/>
	<div class="wp-product-avaiable daily-product-info">
		<div class="wp-product-avaiable-sub">
			<div class="daily-product-available">
				<label>Available day(s):</label>
				<div class="daily-product-list-date-available" style="display:flex; margin-top:10px">
					<?php
					foreach ($listDay as $key => $value) {
						$existFieldType = get_post_meta($post->ID, '_type', true);
						if (empty($existFieldType)) {
							echo '<div>
                                    <input type="checkbox" checked value="' . $key . '" name="' . $key . '"/>&nbsp;
                                        <label>' . $value . '</label>
                                    </div>&nbsp;&nbsp;';
						} else {
							echo '<div>
                                    <input type="checkbox" value="' . $key . '" name="' . $key . '"';
							?>
							<?php
							if (isset($checkedDate[$key])) checked($checkedDate[$key][0], 'yes');
							echo '/>&nbsp;
                                    <label>' . $value . '</label>
                                </div>&nbsp;&nbsp;';
						}
					}
					?>
				</div>
			</div>

			<div class="daily-product-available" style="margin-top:30px">
				<label>Available time:</label>
				<div class="daily-product-available-time" style="margin-top:15px; display:flex">
					<div class="daily-product-available-time-from" style="display:flex;align-items:center">
						<label>From: </label>
						<input name="daily-product-available-time-from" class="product-availability-timedatepicker"
							   type="text" value="<?php echo show_input_time($timeFrom); ?>"/>
					</div>
					<div class="daily-product-available-time-to" style="margin-left:30px">
						<label>To:</label>
						<input name="daily-product-available-time-to" class="product-availability-timedatepicker"
							   type="text" value="<?php echo show_input_time($timeTo, '23:59'); ?>"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="wp-product-avaiable season-product-info">
		<div class="wp-product-avaiable-sub">
			<div class="season-product-available" style="display:flex; align-items:center">
				<label>Available date(s):</label>
				<div class="season-product-list-date-available" style="display:flex">
					<select onchange="handleChangeType(this)" id="season-product-list-date-available-select"
							name="season-product-list-date-available-select" style="margin-left:10px">
						<option id="one-day-only" value="one-day-only" name="one-day-only"> One Day Only</option>
						<option id="time-range" value="time-range" name="time-range"> Time Range</option>
					</select>
					<input id="one-day-date-picker" type="date" value="<?php echo $oneDayDatepicker[0] ?>"
						   name="one-day-date-picker" style="margin-left:10px;" required>
					<div class="date-range-from" style="display:flex;align-items: center;margin-left:20px">
						<label for="date-range-from">From:</label>
						<input type="date" id="date-range-from" value="<?php echo $dateRangeFrom[0] ?>"
							   name="date-range-from" style="margin-left:10px" required>
					</div>
					<div class="date-range-to" style="display:flex;align-items: center;margin-left:20px">
						<label for="date-range-to">To:</label>
						<input type="date" id="date-range-to" value="<?php echo $dateRangeTo[0] ?>" name="date-range-to"
							   style="margin-left:10px" required>
					</div>
				</div>
			</div>

			<div class="season-product-available-one-day" style="margin-top:30px;">
				<label>Available time:</label>
				<div class="season-product-available-time" style="margin-top:15px; display:flex">
					<div class="season-product-available-time-from" style="display:flex;align-items:center">
						<label>From: </label>
						<input name="one-day-time-from" class="product-availability-timedatepicker" type="text"
							   value="<?php echo show_input_time($oneDayTimeFrom); ?>"/>
					</div>
					<div class="season-product-available-time-to" style="margin-left:30px">
						<label>To:</label>
						<input name="one-day-time-to" class="product-availability-timedatepicker" type="text"
							   value="<?php echo show_input_time($oneDayTimeTo, '23:59'); ?>"/>
					</div>
				</div>
			</div>

			<div class="season-product-available-time-range" style="margin-top:30px">
				<label>Available time:</label>
				<div class="season-product-available-time" style="margin-top:15px; display:flex">
					<div class="season-product-available-time-from" style="display:flex;align-items:center">
						<label>From: </label>
						<input name="time-range-from" class="product-availability-timedatepicker" type="text"
							   value="<?php echo show_input_time($timeRangeFrom); ?>"/>
					</div>
					<div class="season-product-available-time-to" style="margin-left:30px">
						<label>To:</label>
						<input name="time-range-to" class="product-availability-timedatepicker" type="text"
							   value="<?php echo show_input_time($timeRangeTo, '23:59'); ?>"/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wp-product-avaiable">
		<b>Product lead time:</b>
		<div class="wp-product-lead-time">
			<div class="lead-time-row">
				<input type="radio" name="product-lead-time-checkbox" value="same"
						<?php echo $productLeadTime[0] !== 'advance' ? 'checked' : '' ?>> Same day product
				<span>
                        Leadtime for order: <input type="number" name="product-lead-time-minutes" min="0" max="9999"
												   value="<?php echo $leadTimeMinutes[0] ?>" placeholder="minutes"> minutes
                    </span>
			</div>
			<div class="lead-time-row">
				<input type="radio" name="product-lead-time-checkbox" value="advance"
						<?php echo $productLeadTime[0] === 'advance' ? 'checked' : '' ?>> Advance product
				<span>
                        Leadtime for order: <input type="number" name="product-lead-time-days" min="0" max="9999"
												   value="<?php echo $leadTimeDays[0] ?>" placeholder="day(s)"> day(s)
                    </span>
			</div>
		</div>
	</div>
	<div class="wp-product-avaiable">
		<b>Delivery method:</b>
		<div class="wp-product-delivery-method">
			<div>
				<input type="radio" name="delivery_method" value="both" checked> Both Delivery and Self-collect
			</div>
			<div>
				<input type="radio" name="delivery_method" value="delivery" <?php echo $deliveryMethod[0] === 'delivery' ? 'checked' : '' ?>> Only Delivery
			</div>
			<div>
				<input type="radio" name="delivery_method" value="self" <?php echo $deliveryMethod[0] === 'self' ? 'checked' : '' ?>> Only Self-collect
			</div>
		</div>
	</div>
	<input type="hidden" name="_old_product_title" value="<?php echo $post->post_title; ?>">
	<div class="wp-product-avaiable wp-product-store-location">
		<b>Product available for location:</b>
		<div class="wp-product-list-location">
			<?php
			if (!empty($list_option_stores)):
				$checkall = false;
				if (empty($deliveryMethod[0]))
					$checkall = true
				?>
				<div class="wp-product-location">
					<input type="checkbox" class="wp-product-checkbox-all" <?php echo $checkall ? 'checked' : '' ?>>
					<span>all locations</span>
				</div>
				<?php foreach ($list_option_stores as $key => $store):
				if($store['central_kitchen']==0) {
				$exist = filter_array($list_stores, 'store_id', $store['id']);
				?>
				<div class="wp-product-location">
					<input type="checkbox" class="wp-product-checkbox" name="product_stores[]"
							<?php echo !empty($exist) || $checkall ? 'checked' : '' ?>
						   value="<?php echo $store['id']; ?>"
					>
					<span><?php echo $store['store_name']; ?></span>
				</div>
			<?php } endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

add_action('template_redirect', 'remove_shop_breadcrumbs');
function remove_shop_breadcrumbs() {
    if (is_shop())
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

}

add_action('add_meta_boxes', 'additional_product_tabs_metabox');

function convert_time_to_save($input)
{
	$format = DateTime::createFromFormat('H:i:s', '00:00:00');
	if ($time = DateTime::createFromFormat('H:i', $input)) {
		$format = $time;
	} else if ($time = DateTime::createFromFormat('H:i:s', $input)) {
		$format = $time;
	} else if ($date = DateTime::createFromFormat('F d , Y H:i', $input)) {
		$format = $date;
	} else if ($date = DateTime::createFromFormat('F d , Y H:i:s', $input)) {
		$format = $date;
	} else {
		return '00:00:00';
	}

	return $format->format('H:i:s');
}

function save_data_custom_meta_box($post_id)
{
	$daily_product_from_string = 'daily-product-available-time-from';
	$daily_product_to_string = 'daily-product-available-time-to';
	$date_range_from = 'date-range-from';
	$date_range_to = 'date-range-to';
	$time_range_from = 'time-range-from';
	$time_range_to = 'time-range-to';
	$type_choosen = 'type-choosen';

	$delivery_method = 'delivery_method';
	$product_stores = 'product_stores';

	$listDay = [
			'monday' => __('Monday', 'woocommerce'),
			'tuesday' => __('Tuesday', 'woocommerce'),
			'wednesday' => __('Wednesday', 'woocommerce'),
			'thursday' => __('Thursday', 'woocommerce'),
			'friday' => __('Friday', 'woocommerce'),
			'saturday' => __('Saturday', 'woocommerce'),
			'sunday' => __('Sunday', 'woocommerce'),
	];

	$product_stores = isset($_POST[$product_stores]) ? $_POST[$product_stores] : [];
	update_post_meta($post_id, $delivery_method, $_POST[$delivery_method]);
	// update table holiday store
	require_once __DIR__ . '/store_location.php';
	$list_stores = get_list_stores($post_id);
	delete_option_stores($post_id);
	multiple_insert_store_post($list_stores, $post_id, $product_stores);

	// product lead time
	$leadTimeCheckbox = 'product-lead-time-checkbox';
	$leadTimeMinutes = 'product-lead-time-minutes';
	$leadTimeDays = 'product-lead-time-days';
	update_post_meta($post_id, $leadTimeCheckbox, $_POST[$leadTimeCheckbox]);
	update_post_meta($post_id, $leadTimeMinutes, intval($_POST[$leadTimeMinutes]));
	update_post_meta($post_id, $leadTimeDays, intval($_POST[$leadTimeDays]));

	if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'daily-product') {
		update_post_meta(
				$post_id,
				'_type',
				$_POST['type-choosen']
		);
		// save
		foreach ($listDay as $key => $value) {
			if (isset($_POST[$key])) {
				update_post_meta($post_id, $key, 'yes');
			} else {
				update_post_meta($post_id, $key, 'no');
			}
		}

		if (array_key_exists($daily_product_from_string, $_POST)) {
			update_post_meta(
					$post_id,
					$daily_product_from_string,
					$_POST[$daily_product_from_string]
			);
		}

		if (array_key_exists($daily_product_to_string, $_POST)) {
			update_post_meta(
					$post_id,
					$daily_product_to_string,
					$_POST[$daily_product_to_string]
			);
		}

		// == remove

		delete_post_meta(
				$post_id,
				$date_range_from
		);

		delete_post_meta(
				$post_id,
				$time_range_from
		);

		delete_post_meta(
				$post_id,
				$time_range_to
		);

		delete_post_meta(
				$post_id,
				$date_range_to
		);

		delete_post_meta(
				$post_id,
				'one-day-date-picker'
		);

		delete_post_meta(
				$post_id,
				'one-day-time-to'
		);

		delete_post_meta(
				$post_id,
				'one-day-time-from'
		);
	};

	if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'season-product-one-day-only') {
		update_post_meta(
				$post_id,
				'_type',
				$_POST['type-choosen']
		);
		if (array_key_exists('one-day-date-picker', $_POST)) {
			update_post_meta(
					$post_id,
					'one-day-date-picker',
					$_POST['one-day-date-picker']
			);
		}

		if (array_key_exists('one-day-time-to', $_POST)) {
			$time = !empty($_POST['one-day-time-to']) ? $_POST['one-day-time-to'] : '';
			$time = convert_time_to_save($time);
			update_post_meta(
					$post_id,
					'one-day-time-to',
					$time
			);
		}

		if (array_key_exists('one-day-time-from', $_POST)) {
			$time = !empty($_POST['one-day-time-from']) ? $_POST['one-day-time-from'] : '';
			$time = convert_time_to_save($time);
			update_post_meta(
					$post_id,
					'one-day-time-from',
					$time
			);
		}

		//remove

		foreach ($listDay as $key => $value) {
			update_post_meta($post_id, $key, 'no');
		}

		delete_post_meta(
				$post_id,
				$daily_product_from_string
		);

		delete_post_meta(
				$post_id,
				$daily_product_to_string
		);

		delete_post_meta(
				$post_id,
				$date_range_from
		);

		delete_post_meta(
				$post_id,
				$date_range_to
		);

		delete_post_meta(
				$post_id,
				$time_range_from
		);

		delete_post_meta(
				$post_id,
				$time_range_to
		);
	}

	if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'season-product-date-range') {
		update_post_meta(
				$post_id,
				'_type',
				$_POST['type-choosen']
		);
		if (array_key_exists($date_range_from, $_POST)) {
			update_post_meta(
					$post_id,
					$date_range_from,
					$_POST[$date_range_from]
			);
		};

		if (array_key_exists($date_range_to, $_POST)) {
			update_post_meta(
					$post_id,
					$date_range_to,
					$_POST[$date_range_to]
			);
		};

		if (array_key_exists($time_range_from, $_POST)) {
			$time = !empty($_POST[$time_range_from]) ? $_POST[$time_range_from] : '';
			$time = convert_time_to_save($time);
			update_post_meta(
					$post_id,
					$time_range_from,
					$time
			);
		}

		if (array_key_exists($time_range_to, $_POST)) {
			$time = !empty($_POST[$time_range_to]) ? $_POST[$time_range_to] : '';
			$time = convert_time_to_save($time);
			update_post_meta(
					$post_id,
					$time_range_to,
					$time
			);
		}

		foreach ($listDay as $key => $value) {
			update_post_meta($post_id, $key, 'no');
		}

		delete_post_meta(
				$post_id,
				$daily_product_from_string
		);

		delete_post_meta(
				$post_id,
				$daily_product_to_string
		);

		delete_post_meta(
				$post_id,
				'one-day-date-picker'
		);

		delete_post_meta(
				$post_id,
				'one-day-time-to'
		);

		delete_post_meta(
				$post_id,
				'one-day-time-from'
		);
	}
}

add_action('save_post', 'save_data_custom_meta_box');

function custom_js_product_avalability($hook)
{
	wp_enqueue_script('jquery');
	wp_enqueue_style('pa-timepicker-css', get_template_directory_uri() . '/css/jquery.datetimepicker.css');
	wp_enqueue_script('pa-timepicker-js', get_template_directory_uri() . '/js/jquery.datetimepicker.full.js');
	wp_enqueue_style('custom-product-woo-css', get_template_directory_uri() . '/css/custom-product-woo.css');
	wp_enqueue_script('custom-product-woo-js', get_template_directory_uri() . '/js/custom-product-woo.js');
	?>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			if (document.getElementById('type-choosen').value === 'daily-product') {
				document.getElementById('one-day-date-picker').removeAttribute('required');
				document.getElementById('date-range-from').removeAttribute('required');
				document.getElementById('date-range-to').removeAttribute('required');
				document.getElementById('daily-product').checked = true;
				document.getElementById('season-product').checked = false;
				document.getElementById('type-choosen').value = 'daily-product';
				document.getElementsByClassName('season-product-info')[0].style.display = 'none';
				document.getElementsByClassName('daily-product-info')[0].style.display = 'block';
			} else if (document.getElementById('type-choosen').value === 'season-product-one-day-only') {
				document.getElementById('daily-product').checked = false;
				document.getElementById('season-product').checked = true;
				document.getElementsByClassName('daily-product-info')[0].style.display = 'none';
				document.getElementById('one-day-date-picker').setAttribute('required', 'required');
				document.getElementById('date-range-from').removeAttribute('required');
				document.getElementById('date-range-to').removeAttribute('required');
				document.getElementsByClassName('date-range-from')[0].style.display = 'none';
				document.getElementsByClassName('date-range-to')[0].style.display = 'none';
				document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'none';
				document.getElementById('one-day-only').selected = "true";
			} else {
				document.getElementById('daily-product').checked = false;
				document.getElementById('season-product').checked = true;
				document.getElementsByClassName('daily-product-info')[0].style.display = 'none';
				document.getElementById('one-day-date-picker').removeAttribute('required', 'required');
				document.getElementById('date-range-from').setAttribute('required', 'required');
				document.getElementById('date-range-to').setAttribute('required', 'required');
				document.getElementById('one-day-date-picker').style.display = 'none';
				document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'block';
				document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'none';
				document.getElementsByClassName('date-range-from')[0].style.display = 'block';
				document.getElementsByClassName('date-range-to')[0].style.display = 'block';
				document.getElementById('time-range').selected = "true";
			}
			document.getElementById('one-day-date-picker').removeAttribute('required');
			document.getElementById('date-range-from').removeAttribute('required');
			document.getElementById('date-range-to').removeAttribute('required');
		});

		function checkDailyProduct() {
			document.getElementById('one-day-date-picker').removeAttribute('required');
			document.getElementById('date-range-from').removeAttribute('required');
			document.getElementById('date-range-to').removeAttribute('required');
			document.getElementById('type-choosen').value = 'daily-product';
			document.getElementsByClassName('season-product-info')[0].style.display = 'none';
			document.getElementsByClassName('daily-product-info')[0].style.display = 'block';
		};

		function checkSeasonProduct() {
			document.getElementById('one-day-date-picker').setAttribute('required', 'required');
			document.getElementById('date-range-from').removeAttribute('required');
			document.getElementById('date-range-to').removeAttribute('required');
			document.getElementById('type-choosen').value = 'season-product-one-day-only';
			document.getElementsByClassName('season-product-info')[0].style.display = 'block';
			document.getElementsByClassName('daily-product-info')[0].style.display = 'none';

			const value = document.getElementById('season-product-list-date-available-select').value;
			if (value === 'one-day-only') {
				document.getElementById('one-day-date-picker').setAttribute('required', 'required');
				document.getElementById('date-range-from').removeAttribute('required');
				document.getElementById('date-range-to').removeAttribute('required');
				document.getElementById('type-choosen').value = 'season-product-one-day-only';
				document.getElementsByClassName('date-range-from')[0].style.display = 'none';
				document.getElementsByClassName('date-range-to')[0].style.display = 'none';
				document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'none';
			} else {
				document.getElementById('one-day-date-picker').removeAttribute('required', 'required');
				document.getElementById('date-range-from').setAttribute('required');
				document.getElementById('date-range-to').setAttribute('required');
				document.getElementById('type-choosen').value = 'season-product-date-range';
				document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'none';
				document.getElementsByClassName('date-range-from')[0].style.display = 'block';
				document.getElementsByClassName('date-range-to')[0].style.display = 'block';
			}
		};

		function handleChangeType(data) {
			if (data.value === 'time-range') {
				document.getElementById('one-day-date-picker').removeAttribute('required', 'required');
				document.getElementById('date-range-from').setAttribute('required', 'required');
				document.getElementById('date-range-to').setAttribute('required', 'required');
				document.getElementById('type-choosen').value = 'season-product-date-range';
				document.getElementById('one-day-date-picker').style.display = 'none';
				document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'block';
				document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'none';
				document.getElementsByClassName('date-range-from')[0].style.display = 'block';
				document.getElementsByClassName('date-range-to')[0].style.display = 'block';
			} else {
				document.getElementById('one-day-date-picker').setAttribute('required', 'required');
				document.getElementById('date-range-from').removeAttribute('required');
				document.getElementById('date-range-to').removeAttribute('required');
				document.getElementById('type-choosen').value = 'season-product-one-day-only';
				document.getElementById('one-day-date-picker').style.display = 'block';
				document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'none';
				document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'block';
				document.getElementsByClassName('date-range-from')[0].style.display = 'none';
				document.getElementsByClassName('date-range-to')[0].style.display = 'none';
			}
		}
	</script>
	<?php
}


add_action('woocommerce_after_main_content', 'show_welcome', 50);
function show_welcome()
{
	if (is_home()) {
		get_template_part('welcome-templates/welcome', 'main');
	}
}

add_action('admin_enqueue_scripts', 'custom_js_product_avalability');


add_action('wp_enqueue_scripts', 'add_css_custom');

function add_css_custom()
{
	wp_enqueue_style('home-page', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_style('blog-page-custom', get_stylesheet_directory_uri() . '/css/custom-blog.css');
	wp_enqueue_script('custom-blog-theme', get_template_directory_uri() . '/js/custom-blog.js');
}

//Customize details single
/**
 * Hook: woocommerce_single_product_summary.
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 * @hooked WC_Structured_Data::generate_product_data() - 60
 */
//do_action( 'woocommerce_single_product_summary' );
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'WC_Structured_Data::generate_product_data()', 50);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

remove_action('woocommerce_after_add_to_cart_button', 'jvm_woocommerce_add_to_wishlist', 20);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_product_thumbnails', 'jvm_woocommerce_add_to_wishlist', 20);

add_action('woocommerce_single_product_summary', 'addBoxDetail', 10);

function custom_related_list()
{
	wc_get_template('/single-product/custom_related.php');
}

add_action('woocommerce_after_single_product_summary', 'custom_related_list', 20);


add_filter('jvm_add_to_wishlist_class', function ($class) {
	return 'jvm_add_to_wishlist text';
});
function addBoxDetail()
{
	global $product;
	if ($product->get_type() == 'simple' || $product->get_type() == 'giftcard') {
		wc_get_template('/single-product/product-summary/single.php');
	}
	if ($product->get_type() == 'variable') {
		wc_get_template('/single-product/product-summary/variation.php');
	}
	if ($product->get_type() == 'bundle') {
		wc_get_template('/single-product/product-summary/grouped.php');
	}
}

function action_woocommerce_before_add_to_cart_button() {
  wc_get_template('/single-product/product-summary/additional.php');
};
// add the action
add_action( 'woocommerce_before_add_to_cart_button', 'action_woocommerce_before_add_to_cart_button', 10, 0 );

//Custom quantity
add_action('woocommerce_after_add_to_cart_quantity', 'bbloomer_display_quantity_minus');

function bbloomer_display_quantity_minus()
{
	echo '<button type="button" class="plus"></button>';

}

add_action('woocommerce_before_add_to_cart_quantity', 'bbloomer_display_quantity_plus');

function bbloomer_display_quantity_plus()
{
	echo '<button type="button" class="minus"></button>';
}

require_once get_template_directory() . '/edenred-system-user.php';
//function control additional product
require_once get_template_directory() . '/admin-custom/index.php';
require_once get_template_directory() . '/emails.php';
require_once get_template_directory() . '/email-reset-password.php';
require_once get_template_directory() . '/email-transaction-edenred.php';
// custom woocommerce
require_once get_template_directory() . '/product-custom/index.php';
require_once get_template_directory() . '/front-custom/index.php';
//

// Display Fields
add_action('woocommerce_product_options_related', 'woocommerce_product_custom_fields');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields()
{
	global $products;

	$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish'
	);
	$all_products = wc_get_products($args);
	$arrData = [];
	foreach ($all_products as $key => $product) {
		if ($product->get_type() == "simple") {
			$arrData[$product->get_id()] = $product->get_title();
		}
	}

	woocommerce_wp_select_multiple(array(
					'id' => '_custom_product_additional_linked',
					'name' => '_custom_product_additional_linked[]',
					'class' => 'newoptions',
					'label' => __('Additional Product', 'woocommerce'),
					'options' => $arrData)
	);;
	//Custom Product Number Field
	woocommerce_wp_text_input(
			array(
					'id' => '_custom_product_quality_linked',
					'placeholder' => 'Quantity',
					'label' => __('Quantity', 'woocommerce'),
					'type' => 'number',
					'custom_attributes' => array(
							'step' => 'any',
							'min' => '0'
					)
			)
	);
}

function woocommerce_wp_select_multiple($field)
{
	global $thepostid, $post, $woocommerce;

	$thepostid = empty($thepostid) ? $post->ID : $thepostid;
	$product_meta = get_post_meta($post->ID, '_custom_product_additional_linked');
	$field['class'] = isset($field['class']) ? $field['class'] : 'select short';
	$field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
	$field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
	$field['value'] = isset($field['value']) ? $field['value'] : (get_post_meta($thepostid, $field['id'], true) ? get_post_meta($thepostid, $field['id'], true) : array());

	echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
        <label>Additional products</label>
        <select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" class="wc-enhanced-select" multiple="multiple" onchange="validateLeng()">';

	foreach ($field['options'] as $key => $value) {
		echo '<option value="' . esc_attr($key) . '" ' . (in_array($key, $field['value']) ? 'selected="selected"' : '') . '>' . esc_html($value) . '</option>';
	}
	echo '</select> ';
	echo '&nbsp; <span>Maximum 2 products</span>';
	if (!empty($field['description'])) {

		if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
			echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
		} else {
			echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
		}

	}
	echo '</p>';
}

function woocommerce_product_custom_fields_save($post_id)
{
	// Custom Product Text Field
	$woocommerce_custom_product_select_field = $_POST['_custom_product_additional_linked'];
	$newArr = [];
	foreach ($woocommerce_custom_product_select_field as $value) {
		$newArr[] = $value;
	}
	update_post_meta($post_id, '_custom_product_additional_linked', $newArr);


// Custom Product Number Field
	$woocommerce_custom_product_number_field = $_POST['_custom_product_quality_linked'];
	update_post_meta($post_id, '_custom_product_quality_linked', esc_attr($woocommerce_custom_product_number_field));

}

// add_action('woocommerce_template_single_add_to_cart', 'show_attributes', 5);
// function show_attributes()
// {
// 	global $product;
// 	do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
// }

// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);

// add_action('woocommerce_after_add_to_cart_form', 'custom_after_add_to_cart_btn', 10, 4);

// function custom_after_add_to_cart_btn($product_id_small, $quantity_small, $product_id_big, $quantity_small_big)
// {
// 		$cart = wc()->cart;
// 		$cart->add_to_cart($product_id_small, $quantity_small);
// 		$cart->add_to_cart($product_id_big, $quantity_small_big);
// }

function theme_custom_woocommerce_is_purchasable_filter($can, $product)
{
	if ('' == $product->get_price()) {
		$can = $product->exists() && ('publish' === $product->get_status() || current_user_can('edit_post', $product->get_id()));
	}

	return $can;
}
add_filter('woocommerce_is_purchasable', 'theme_custom_woocommerce_is_purchasable_filter', 10, 2);

function theme_wc_product_data_filter($value, $data)
{
	if (empty($value)) {
		$value = 0;
	}

	return $value;
}
add_filter('woocommerce_product_get_price', 'theme_wc_product_data_filter', 10, 2);
function ld_custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'ld_custom_excerpt_length', 999 );

//function my_custom_endpoints() {
//    add_rewrite_endpoint( 'buy-membership', EP_ROOT | EP_PAGES );
//}
//
//add_action( 'init', 'my_custom_endpoints' );

function add_slug( $query_var ) {
    $query_var[] = 'buy-membership';
    return $query_var;
}
add_filter( 'query_vars', 'add_slug' );

function wc_login_redirect() {
    if($_SESSION['buy_membership']) {
        return home_url().'/buy-membership';
    }
    return home_url();
}
add_filter('woocommerce_login_redirect', 'wc_login_redirect');

// hide update notifications
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates'); //hide updates for WordPress itself
add_filter('pre_site_transient_update_plugins','remove_core_updates'); //hide updates for all plugins
add_filter('pre_site_transient_update_themes','remove_core_updates'); //hide updates for all themes

/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' <i class="breadcrumb-arrow"></i> ';
	return $defaults;
}

/**
 * Highlight current category in wp_list_categories
 */
function cat_active($output, $args) {
    if (is_single()) {
        global $post;
        $terms = get_the_terms($post->ID, 'category');
        if (!empty($terms)) {
            foreach( $terms as $term )
                if ( preg_match( '#cat-item-' . $term ->term_id . '#', $output ) )
                    $output = str_replace('cat-item-'.$term ->term_id, 'cat-item-'.$term ->term_id . ' current-cat', $output);
        }
    }
    return $output;
}
add_filter('wp_list_categories', 'cat_active', 10, 2);

// define the woocommerce_save_account_details callback
function action_woocommerce_save_account_details( $user_id ) {
    // make action magic happen here...
    //wp_safe_redirect(wc_get_page_permalink( 'myaccount' ).'edit-account');
    wp_safe_redirect(wc_customer_edit_account_url());
    exit;
};

// add the action
add_action( 'woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1 );

if(!is_user_logged_in()) {
    // Woocommerce not to save checkout billing fields
    add_filter('woocommerce_checkout_get_value','__return_empty_string',10);
}

// define function get store locator manager
function Store_Locator_Manager() {
    $user = wp_get_current_user();
    $role = $user->roles[0] === 'wpsl_store_locator_manager' ? true:false;
    return $role;
}

function Rewards_Product_Exits() {
    $rewardsProductId = get_option('rewards_product_id');
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $cart = [];
    foreach($items as $item => $values) {
        $cart[] = $values['product_id'];
    }
    return in_array($rewardsProductId, $cart) ? $rewardsProductId:false;
}


add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');

function woocommerce_ajax_add_to_cart() {
    $product_id = get_option('rewards_product_id');
    $quantity = 1;
    $product_status = get_post_status($product_id);

    $cartId = WC()->cart->generate_cart_id( $product_id);
    $cartItemKey = WC()->cart->find_product_in_cart( $cartId );
    WC()->cart->remove_cart_item( $cartItemKey );

    if (WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        $data = array(
            'error' => false,
            'url_redirect' => site_url().'/checkout');
        echo wp_send_json($data);
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
        echo wp_send_json($data);
    }
    wp_die();
}

add_action( 'wp_enqueue_scripts', 'script_custom_registion' );
function script_custom_registion() {
    $buy = get_query_var('buy-membership');
    if($buy === 'true') {
        ?>
        <input type="hidden" class="buy-membership" value="<?= site_url().'/buy-membership' ?>" />
        <?php
        wp_enqueue_script(
            'registration-custom',
            get_template_directory_uri() . '/js/registration-custom.js',
            array('jquery')
        );
    }
}

add_action( 'wp_ajax_nopriv_get_total_cart_ajax', 'get_total_cart_ajax' );
add_action( 'wp_ajax_get_total_cart_ajax', 'get_total_cart_ajax' );
function get_total_cart_ajax()
{
    $total = WC()->cart->get_cart_contents_total();
    echo json_encode([
        'code' => 1,
        'value' => $total,
        'mes' => 'Thành công',
    ]);
    exit();
}