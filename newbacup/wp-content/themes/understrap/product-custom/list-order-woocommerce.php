<?php
add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column', 20);
function custom_shop_order_column($columns)
{
	$reordered_columns = array();
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'order_status') {
			$reordered_columns['rider_name'] = __('Assigned rider', 'woocommerce');
			$reordered_columns['order_source'] = __('Order source', 'woocommerce');
			$reordered_columns['store_name'] = __('Assigned store', 'woocommerce');
			$reordered_columns['pickup_store_name'] = __('Pickup store', 'woocommerce');
			$reordered_columns['shipping_cost'] = __('Shipping Fee', 'woocommerce');
			$reordered_columns['delivery_address'] = __('Delivery Address', 'woocommerce');
			$reordered_columns['delivery_time'] = __('Delivery Time', 'woocommerce');
		}
	}
	return $reordered_columns;
}

add_action('manage_shop_order_posts_custom_column', 'custom_orders_list_column_content', 20, 2);
function custom_orders_list_column_content($column, $post_id)
{
	global $wpdb;
	switch ($column) {
		case 'rider_name':
			$table_rider = $wpdb->prefix . 'cedele_setting_riders';

			$rider_id = get_post_meta($post_id, 'wp_custom_order_rider', true);
			$rider = null;
			if ($rider_id)
				$rider = $wpdb->get_row("SELECT * FROM $table_rider WHERE id = $rider_id", ARRAY_A);

			if ($rider) {
				echo '<span class="rider">' . $rider['rider_name'] . '</span>';
			} else {
				echo '&ndash;';
			}
			break;

		case 'store_name':
			$table_store = $wpdb->prefix . 'store_location';

			$assigned_store_id = get_post_meta($post_id, 'wc_order_assigned_store', true);
			$pickup_store_id = get_post_meta($post_id, 'wc_order_pickup_store', true);
			$order = null;
            // Display pickup store when assigned store null.
            if ($assigned_store_id) {
                $order = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $assigned_store_id", ARRAY_A);
            } elseif ($pickup_store_id) {
                $order = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $pickup_store_id", ARRAY_A);
            }

			if ($order) {
				echo '<span class="store">' . $order['store_name'] . '</span>';
			} else {
				echo '&ndash;';
			}
			break;

		case 'pickup_store_name':
			$table_store = $wpdb->prefix . 'store_location';

			$order_id = get_post_meta($post_id, 'wc_order_pickup_store', true);
			$order = null;
			if ($order_id)
				$order = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $order_id", ARRAY_A);

			if ($order) {
				echo '<span class="store">' . $order['store_name'] . '</span>';
			} else {
				echo '&ndash;';
			}
			break;

		case 'shipping_cost':
			$order_obj = wc_get_order($post_id);
			// delivery fee
			$shipping_cost = $order_obj->get_shipping_total();

			// surcharge fee
			$surcharge = 0;
			$items_fee = $order_obj->get_items('fee');
			foreach ($items_fee as $item_id => $item) {
				$surcharge += floatval($item->get_total());
			}

			$total_fee = floatval($shipping_cost) + floatval($surcharge);
			if ($total_fee) {
				echo '<span class="store">' . ($total_fee) . '$</span>';
			} else {
				echo '&ndash;';
			}
			break;

		case 'delivery_address':
			$_order_delivery_address = get_post_meta($post_id, 'wp_custom_order_delivery_address', true);
			if (!empty($_order_delivery_address))
				echo $_order_delivery_address;
			else
				echo '&ndash;';
			break;

		case 'delivery_time':
			$delivery_date = get_post_meta($post_id, 'wp_custom_order_delivery_date', true);
			$collection_time = get_post_meta($post_id, 'wp_custom_order_delivery_collection_time', true);
			if (!empty($delivery_date))
				echo "$delivery_date, $collection_time";
			else
				echo '&ndash;';
			break;
        case 'order_source':
            $source = get_post_meta($post_id, 'wc_order_source', true);
            if (!empty($source))
                echo "$source";
            else
                echo '&ndash;';
            break;
	}
}

add_action('admin_head', 'fix_table_overflow_add_admin_css');
function fix_table_overflow_add_admin_css()
{
	echo '<style>
        body {overflow-x: hidden}
        #posts-filter table.wp-list-table.fixed{table-layout:auto;}

        #posts-filter table.wp-list-table th#title{min-width:350px;}
        #posts-filter table.wp-list-table th#categories{min-width:150px;}

        #posts-filter table.wp-list-table.fixed{table-layout:auto;}

        #posts-filter table.wp-list-table th#title{min-width:350px;}
        #posts-filter table.wp-list-table th#categories{min-width:150px;}
    </style>';
}

/**
 * Render custom filter field
 */
// Custom function where metakeys / labels pairs are defined
function get_filter_shop_order_meta($domain = 'woocommerce')
{
	// Add below the metakey / label pairs to filter orders
	return [
		'_billing_company' => __('Billing company', $domain),
		'_order_total' => __('Gran total', $domain),
	];
}

// Add a dropdown to filter orders by meta
add_action('restrict_manage_posts', 'render_custom_filters');
function render_custom_filters()
{
	global $pagenow, $typenow;

	if ('shop_order' === $typenow && 'edit.php' === $pagenow) {
		render_filters_district();
		render_filters_rider();
		render_filters_store_location();
		render_filters_pickup_store_location();
		render_filters_delivery_date();
	}
}

// filters_district
function render_filters_district()
{
	$district = '';

	$district_map = [
		'01,02,03,04,05,06' => 'D01 - Boat Quay / Raffles Place / Marina',
		'07, 08' => 'D02 - Chinatown / Tanjong Pagar',
		'14, 15, 16' => 'D03 - Alexandra / Commonwealth',
		'09, 10' => 'D04 - Harbourfront / Telok Blangah',
		'11, 12, 13' => 'D05 - Buona Vista / West Coast / Clementi',
		'17' => 'D06 - City Hall / Clarke Quay',
		'18, 19' => 'D07 - Beach Road / Bugis / Rochor',
		'20, 21' => 'D08 - Farrer Park / Serangoon Rd',
		'22, 23' => 'D09 - Orchard / River Valley',
		'24, 25, 26, 27' => 'D10 - Tanglin / Holland',
		'28, 29, 30' => 'D11 - Newton / Novena',
		'31, 32, 33' => 'D12 - Balestier / Toa Payoh',
		'34, 35, 36, 37' => 'D13 - Macpherson / Potong Pasir',
		'38, 39, 40, 41' => 'D14 - Eunos / Geylang / Paya Lebar',
		'42, 43, 44, 45' => 'D15 - East Coast / Marine Parade',
		'46, 47, 48' => 'D16 - Bedok / Upper East Coast',
		'49, 50, 81' => 'D17 - Changi Airport / Changi Village',
		'51, 52' => 'D18 - Pasir Ris / Tampines',
		'53, 54, 55, 82' => 'D19 - Hougang / Punggol / Sengkang',
		'56, 57' => 'D20 - Ang Mo Kio / Bishan / Thomson',
		'58, 59' => 'D21 - Clementi Park / Upper Bukit Timah',
		'60, 61, 62, 63, 64' => 'D22 - Boon Lay / Jurong / Tuas',
		'65, 66, 67, 68' => 'D23 - Bukit Batok / Bukit Panjang / Choa Chu Kang',
		'69, 70, 71' => 'D24 - Lim Chu Kang / Tengah',
		'72, 73' => 'D25 - Admiralty / Woodlands',
		'77, 78' => 'D26 - Mandai / Upper Thomson',
		'75, 76' => 'D27 - Sembawang / Yishun',
		'79, 80' => 'D28 - Seletar / Yio Chu Kang',
	];

	if (!empty($_GET['_district'])) {
		$district = isset($_GET['_district']) ? $_GET['_district'] : '';
	}
	?>
	<select style="width: 200px !important" class="wc-enhanced-select" id="wc-enhanced-select-district-postcode"
			name="_district" data-placeholder="<?php esc_attr_e('Filter by shipping district', 'woocommerce'); ?>"
			data-allow_clear="true">
		<option value="0" selected>All district</option>
		<?php foreach ($district_map as $dname): ?>
			<option
				value="<?php echo $dname; ?>" <?php echo $district == $dname ? 'selected' : ''; ?>><?php echo $dname; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

// filters_rider
function render_filters_rider()
{
	global $wpdb;
	$rider_string = '';
	$rider_id = isset($_GET['_rider']) ? $_GET['_rider'] : '';
	$table_rider_name = $wpdb->prefix . 'cedele_setting_riders';
	$table_partner_name = $wpdb->prefix . 'cedele_setting_shipping_partner';
	$list_riders = $wpdb->get_results(
		"SELECT *, partners.partner_name as partner_name
        FROM $table_rider_name
        LEFT JOIN (
            SELECT id as partner_id, partner_name FROM $table_partner_name
            WHERE status = 1
        ) as partners
        ON $table_rider_name.partner_id = partners.partner_id"
	);
	?>

	<select class="wc-enhanced-select" id="wp_custom_order_rider" name="_rider"
			data-placeholder="<?php esc_attr_e('Rider name', 'woocommerce'); ?>" data-search_all="1"
			data-allow_clear="true">
		<option value="0" selected>All rider</option>
		<?php
		foreach ($list_riders as $key => $rider):
			$partner_name = !empty($rider->partner_name) ? (' &ndash; ' . $rider->partner_name) : '';
			$option_name = sprintf(
				esc_html__('%1$s (%2$s%3$s)', 'woocommerce'),
				$rider->rider_name,
				$rider->contact_number,
				$partner_name
			);
			?>
			<option
				value="<?php echo $rider->id; ?>" <?php echo $rider->id === $rider_id ? 'selected' : ''; ?>><?php echo $option_name; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

// filters_store_location
function render_filters_store_location()
{
	global $wpdb;
	$table_store = $wpdb->prefix . 'store_location';

	$search_store = '';
	$list_stores = $wpdb->get_results("SELECT id, store_name FROM $table_store", ARRAY_A);
	if (!empty($_GET['_store'])) {
		$search_store = isset($_GET['_store']) ? $_GET['_store'] : '';
	}
	?>
	<select style="width: 200px !important" class="wc-enhanced-select" id="wc-enhanced-select-store" name="_store"
			data-placeholder="<?php esc_attr_e('Filter by assigned store', 'woocommerce'); ?>" data-allow_clear="true">
		<option value="0" selected>All assigned store</option>
		<?php foreach ($list_stores as $store): ?>
			<option
				value="<?php echo $store['id']; ?>" <?php echo $search_store == $store['id'] ? 'selected' : ''; ?>><?php echo $store['store_name']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

// filters_pickup_store_location
function render_filters_pickup_store_location()
{
	global $wpdb;
	$table_store = $wpdb->prefix . 'store_location';

	$search_store = '';
	$list_stores = $wpdb->get_results("SELECT id, store_name FROM $table_store", ARRAY_A);
	if (!empty($_GET['_pickup_store'])) {
		$search_store = isset($_GET['_pickup_store']) ? $_GET['_pickup_store'] : '';
	}
	?>
	<select style="width: 200px !important" class="wc-enhanced-select" id="wc-enhanced-select-pickup-store"
			name="_pickup_store" data-placeholder="<?php esc_attr_e('Filter by pickup store', 'woocommerce'); ?>"
			data-allow_clear="true">
		<option value="0" selected>All pickup store</option>
		<?php foreach ($list_stores as $store): ?>
			<option
				value="<?php echo $store['id']; ?>" <?php echo $search_store == $store['id'] ? 'selected' : ''; ?>><?php echo $store['store_name']; ?></option>
		<?php endforeach; ?>
	</select>
	<div class="cdls-faded assign-to-rider-modal">
		<div class="cdls-modal">
			<div class="cdls-modal-header">
				<h4>Assign to rider</h4>
				<span class="cdls-modal-close">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
						 xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
							  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                        <path fill-rule="evenodd"
							  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                    </svg>
                </span>
			</div>
			<div class="cdls-modal-content">
				<table cellpadding="8">
					<tr>
						<td width="35%">Assign to rider: *</td>
						<td class="wrap-td-wp_custom_order_rider">
							<select style="width: 200px;" class="wp_custom_order_rider" id="assigned_riders"
									name="assigned_riders"
									data-placeholder="<?php esc_attr_e('Rider name', 'woocommerce'); ?>"
									data-allow_clear="true">
								<option value="" selected="selected"></option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="number" class="order_shipping_cost" style="width: 190px"
								   placeholder="shipping cost" min="0" step="0.01" pattern="[0-9]{0,}"/> $
						</td>
					</tr>
					<tr class="assigned_riders_notice" style="display: none;">
						<td></td>
						<td><p style="color: red; margin: 0px;">Rider is required!</p></td>
					</tr>
				</table>
			</div>
			<div class="cdls-modal-footer">
				<button type="button" class="button button-cancel-modal-assign-to-rider">Cancel</button>
				<button type="button" class="button-primary button-submit-modal-assign-to-rider">Save changes</button>
			</div>
		</div>
	</div>
	<?php
}

// filters_delivery_date
function render_filters_delivery_date()
{
	?>
	<input name="delivery_date_from" id="delivery_date_from"
		   value="<?php echo isset($_GET['delivery_date_from']) ? $_GET['delivery_date_from'] : ''; ?>"
		   placeholder="Date from"/>
	<input name="delivery_date_to" id="delivery_date_to"
		   value="<?php echo isset($_GET['delivery_date_to']) ? $_GET['delivery_date_to'] : ''; ?>"
		   placeholder="Date to"/>
	<?php
}

// Process the filter dropdown
add_filter('posts_where', function ($where) {
	if(empty($_GET['_rider'])){
		if (isset($_GET['delivery_date_from']) && !empty($_GET['delivery_date_from'])) {
			$where .= " AND STR_TO_DATE(wp_postmeta.meta_value, '%d %M %Y') >= '" . $_GET['delivery_date_from'] . "'";
		}
		if (isset($_GET['delivery_date_to']) && !empty($_GET['delivery_date_to'])) {
			$where .= " AND STR_TO_DATE(wp_postmeta.meta_value, '%d %M %Y') <= '" . $_GET['delivery_date_to'] . "'";
		}
	}

	return $where;
}, 10, 2);

add_filter('request', 'process_admin_shop_order_filtering_by_delivery_date', 90);
function process_admin_shop_order_filtering_by_delivery_date($vars)
{
	global $pagenow, $typenow;
	if ($pagenow == 'edit.php' && 'shop_order' === $typenow
		&& ((isset($_GET['delivery_date_from']) && !empty($_GET['delivery_date_from'])) || (isset($_GET['delivery_date_to']) && !empty($_GET['delivery_date_to'])))) {
		$vars['meta_key'] = 'wp_custom_order_delivery_date';
	}
	return $vars;
}

add_filter('request', 'process_admin_shop_order_filtering_by_district', 90);
function process_admin_shop_order_filtering_by_district($vars)
{
	global $pagenow, $typenow;

	$filter_id = '_district';
	if ($pagenow == 'edit.php' && 'shop_order' === $typenow
		&& isset($_GET[$filter_id]) && !empty($_GET[$filter_id])) {
		$vars['meta_key'] = 'wp_custom_district';
		$vars['meta_value'] = $_GET[$filter_id];
	}
	return $vars;
}

add_filter('request', 'process_admin_shop_order_filtering_by_rider', 91);
function process_admin_shop_order_filtering_by_rider($vars)
{
	global $pagenow, $typenow;

	$filter_id = '_rider';
	if ($pagenow == 'edit.php' && 'shop_order' === $typenow
		&& isset($_GET[$filter_id]) && !empty($_GET[$filter_id])) {
		$vars['meta_key'] = 'wp_custom_order_rider';
		$vars['meta_value'] = $_GET[$filter_id];
	}
	return $vars;
}

add_filter('request', 'process_admin_shop_order_filtering_by_store', 92);
function process_admin_shop_order_filtering_by_store($vars)
{
	global $pagenow, $typenow;

	$filter_id = '_store';
	if ($pagenow == 'edit.php' && 'shop_order' === $typenow
		&& isset($_GET[$filter_id]) && !empty($_GET[$filter_id])) {
		$vars['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key' => 'wc_order_assigned_store',
				'compare' => '=',
				'value' => $_GET[$filter_id],
			),
			array(
				'key' => 'wc_order_pickup_store',
				'compare' => '=',
				'value' => $_GET[$filter_id]
			),
		);
	}
	return $vars;
}

add_filter('request', 'process_admin_shop_order_filtering_by_pickup_store', 93);
function process_admin_shop_order_filtering_by_pickup_store($vars)
{
	global $pagenow, $typenow;

	$filter_id = '_pickup_store';
	if ($pagenow == 'edit.php' && 'shop_order' === $typenow
		&& isset($_GET[$filter_id]) && !empty($_GET[$filter_id])) {
		$vars['meta_key'] = 'wc_order_pickup_store';
		$vars['meta_value'] = $_GET[$filter_id];
	}
	return $vars;
}

//
// Bulk action in list order woocommerce
// Adding to admin order list bulk dropdown a custom action 'custom_downloads'
add_filter('bulk_actions-edit-shop_order', 'downloads_bulk_actions_edit_product', 20, 1);
function downloads_bulk_actions_edit_product($actions)
{
	$actions['assign_to_rider'] = __('Assign to rider', 'woocommerce');
	return $actions;
}

// Make the action from selected orders
add_filter('handle_bulk_actions-edit-shop_order', 'assign_handle_bulk_action_edit_shop_order', 10, 3);
function assign_handle_bulk_action_edit_shop_order($redirect_to, $action, $post_ids)
{

	$array_action = ['mark_completed', 'mark_failed', 'mark_cancelled'];
	$array_cannot = ['completed', 'failed', 'cancelled'];

	if (is_numeric(strpos($action, 'mark_'))) {
		foreach ($post_ids as $post_id) {
			$order = wc_get_order($post_id);
			$order_status = $order->get_status();

			$current_date_time = new DateTime();
			$current_date_time_str = $current_date_time->format('Y-m-d H:i:s');

			if (in_array($action, $array_action) && !is_numeric(strpos($action, $order_status))) {
				update_post_meta($post_id, 'last_time_update_status', $current_date_time_str);
			}
		}
	}

	if ($action !== 'assign_to_rider')
		return $redirect_to; // Exit

	$processed_ids = array();
	$processed_error_ids = array();
	$assigned_riders = isset($_REQUEST['assigned_riders']) ? $_REQUEST['assigned_riders'] : '';
	$order_shipping_cost = isset($_REQUEST['order_shipping_cost']) ? $_REQUEST['order_shipping_cost'] : 0;

	foreach ($post_ids as $post_id) {
		$order = wc_get_order($post_id);
		$order_data = $order->get_data();
		$order_status = $order->get_status();

		$current_date_time = new DateTime();

		// Update rider
		if ($order_status == 'completed' || $order_status == 'failed' || $order_status == 'cancelled') {

			$last_time_update_status = get_post_meta($post_id, 'last_time_update_status', true);
			if ($last_time_update_status) {
				$last_modified_after_24h = new DateTime($last_time_update_status);
				$last_modified_after_24h->add(new DateInterval('P1D'));
			} else {
				$last_modified_after_24h = new DateTime();
				$last_modified_after_24h->sub(new DateInterval('P2D'));
			}

			if ($current_date_time < $last_modified_after_24h) {
				update_post_meta($post_id, 'wp_custom_order_rider', $assigned_riders);
				update_post_meta($post_id, 'wp_custom_order_shipping_cost', $order_shipping_cost);
				$processed_ids[] = $post_id;
			} else {
				$processed_error_ids[] = $post_id;
			}
		} else {
			update_post_meta($post_id, 'wp_custom_order_rider', $assigned_riders);
			update_post_meta($post_id, 'wp_custom_order_shipping_cost', $order_shipping_cost);
			$processed_ids[] = $post_id;
		}
	}

	return $redirect_to = add_query_arg(array(
		'assign_to_rider' => '1',
		'processed_count' => count($processed_ids),
		'processed_ids' => implode(',', $processed_ids),
		'processed_error_count' => count($processed_error_ids),
		'processed_error_ids' => implode(',', $processed_error_ids),
	), $redirect_to);
}

// The results notice from bulk action on orders
add_action('admin_notices', 'assign_rider_bulk_action_admin_notice');
function assign_rider_bulk_action_admin_notice()
{
	if (empty($_REQUEST['assign_to_rider'])) return; // Exit

	$count = intval($_REQUEST['processed_count']);
	$count = intval($_REQUEST['processed_count']);

	printf('<div id="message" class="updated fade"><p>' .
		_n('%s Order for assign.',
			'%s Orders for assign.',
			$count,
			'assign_to_rider'
		) . '</p></div>', $count);
}

