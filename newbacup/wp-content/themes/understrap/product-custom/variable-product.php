<?php
add_action('woocommerce_save_product_variation', 'add_variation_product_availability', 10, 2);
function add_variation_product_availability($variation_id, $i)
{
	$listDay = [
			'monday_variation' => __('Monday', 'woocommerce'),
			'tuesday_variation' => __('Tuesday', 'woocommerce'),
			'wednesday_variation' => __('Wednesday', 'woocommerce'),
			'thursday_variation' => __('Thursday', 'woocommerce'),
			'friday_variation' => __('Friday', 'woocommerce'),
			'saturday_variation' => __('Saturday', 'woocommerce'),
			'sunday_variation' => __('Sunday', 'woocommerce'),
	];

	if ($_POST['type-choosen-variation'][$i] === 'season-product-date-range-variation') {
		update_post_meta(
				$variation_id,
				'_type',
				$_POST['type-choosen-variation'][$i]
		);
		if (array_key_exists('date-range-from-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_date_range_from_variation',
					$_POST['date-range-from-variations'][$i]
			);
		};

		if (array_key_exists('date-range-to-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_date_range_to_variation',
					$_POST['date-range-to-variations'][$i]
			);
		};

		if (array_key_exists('time-range-from-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_time_range_from_variation',
					$_POST['time-range-from-variations'][$i]
			);
		};

		if (array_key_exists('time-range-to-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_time_range_to_variation',
					$_POST['time-range-to-variations'][$i]
			);
		};

		foreach ($listDay as $key => $value) {
			update_post_meta($variation_id, $key, 'no');
		}

		delete_post_meta(
				$variation_id,
				'_daily_product_available_time_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_daily_product_available_time_to_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_date_picker_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_time_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_time_to_variation'
		);
	} else if ($_POST['type-choosen-variation'][$i] === 'season-product-one-day-only-variation') {
		update_post_meta(
				$variation_id,
				'_type',
				$_POST['type-choosen-variation'][$i]
		);
		if (array_key_exists('one-day-date-picker-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_one_day_date_picker_variation',
					$_POST['one-day-date-picker-variations'][$i]
			);
		}

		if (array_key_exists('one-day-time-to-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_one_day_time_to_variation',
					$_POST['one-day-time-to-variations'][$i]
			);
		}

		if (array_key_exists('one-day-time-from-variations', $_POST)) {
			update_post_meta(
					$variation_id,
					'_one_day_time_from_variation',
					$_POST['one-day-time-from-variations'][$i]
			);
		}

		//remove

		foreach ($listDay as $key => $value) {
			update_post_meta($variation_id, $key, 'no');
		}

		delete_post_meta(
				$variation_id,
				'_daily_product_available_time_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_daily_product_available_time_to_variation'
		);

		delete_post_meta(
				$variation_id,
				'_date_range_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_date_range_to_variation'
		);

		delete_post_meta(
				$variation_id,
				'_time_range_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_time_range_to_variation'
		);
	} else {
		foreach ($listDay as $key => $value) {
			if (isset($_POST[$key][$i])) {
				update_post_meta($variation_id, $key, 'yes');
			} else {
				update_post_meta($variation_id, $key, 'no');
			}
		}

		update_post_meta(
				$variation_id,
				'_type',
				$_POST['type-choosen-variation'][$i]
		);

		if (array_key_exists('daily-product-available-time-from-variation', $_POST)) {
			update_post_meta(
					$variation_id,
					'_daily_product_available_time_from_variation',
					$_POST['daily-product-available-time-from-variation'][$i]
			);
		}

		if (array_key_exists('daily-product-available-time-to-variation', $_POST)) {
			update_post_meta(
					$variation_id,
					'_daily_product_available_time_to_variation',
					$_POST['daily-product-available-time-to-variation'][$i]
			);
		}

		delete_post_meta(
				$variation_id,
				'_date_range_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_date_range_to_variation'
		);

		delete_post_meta(
				$variation_id,
				'_time_range_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_time_range_to_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_date_picker_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_time_from_variation'
		);

		delete_post_meta(
				$variation_id,
				'_one_day_time_to_variation'
		);
	}

    $old_product_title = isset($_POST['_old_product_title']) ? $_POST['_old_product_title'] : '';
    $variable_post_id = isset($_POST['variable_post_id']) ? $_POST['variable_post_id'] : [];
    $variable_regular_price = isset($_POST['variable_regular_price']) ? $_POST['variable_regular_price'] : [];

    $list_n_variations = [];
    foreach ($variable_post_id as $key => $variable_id) {
      $new_price = isset($variable_regular_price[$key]) ? floatval($variable_regular_price[$key]) : 0;
      $v_data = [
        'product_code' => $variable_id,
        'product_name' => $old_product_title,
        'unit_price' => $new_price,
      ];
      $list_n_variations[] = $v_data;
    }

    if (count($list_n_variations) > 0) {
      batchCreateOrUpdateProducts(['products' => $list_n_variations]);
    }
}

add_action( 'woocommerce_new_product_variation', 'woocommerce_new_product_variation_func');
function woocommerce_new_product_variation_func($variant_id)
{
	$variant = new WC_Product_Variation($variant_id);
	$parent = $variant->get_parent_data();
	$products_data = [
		'product_code' => $variant_id,
		'product_name' => $parent['title'],
		'unit_price' => 0,
	];
	createOrUpdateProduct($products_data);
}
