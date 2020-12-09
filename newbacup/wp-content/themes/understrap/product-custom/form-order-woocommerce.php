<?php
add_action('woocommerce_admin_order_data_after_order_details', 'custom_delivery_metabox');
function custom_delivery_metabox($order)
{
	global $wpdb;
	?>
	<!-- Delivery section -->
	<br class="clear"/>
	<h4>Delivery information</h4>
	<div class="delivery-address">
		<?php
		$wp_custom_order_method = get_post_meta($order->get_id(), 'wp_custom_order_method', true);
		$delivery_date = get_post_meta($order->get_id(), 'wp_custom_order_delivery_date', true);
		$time = get_post_meta($order->get_id(), 'wp_custom_order_delivery_collection_time', true);
		?>
		<p class="form-field form-field-wide">
			<strong><?php _e('Method:', 'woocommerce'); ?></strong>
			<br>
			<?php
			if ($wp_custom_order_method == 'delivery') {
				echo 'Delivery';
			} else if ($wp_custom_order_method == 'self-collection') {
				echo 'Self-collection';
			}
			?>
		</p>
		<?php
		if ($wp_custom_order_method == 'delivery'):
			$delivery_address = get_post_meta($order->get_id(), 'wp_custom_order_delivery_address', true);
			$store_id = get_post_meta($order->get_id(), 'wc_order_assigned_store', true);
			$table_store = $wpdb->prefix . 'store_location';
			if ($store_id)
				$store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $store_id", ARRAY_A);
			?>
			<!-- delivery -->
			<p class="form-field form-field-wide">
				<strong><?php _e('Delivery address:', 'woocommerce'); ?></strong>
				<br>
				<?php echo esc_attr_e($delivery_address); ?>
			</p>
			<p class="form-field form-field-wide">
				<strong><?php _e('Delivery time:', 'woocommerce'); ?></strong>
				<br>
				<?php
                    if ($delivery_date)
                        echo esc_attr($delivery_date);
                    if ($delivery_date && $time)
                        echo ', ';
                    if ($time)
                        echo esc_attr($time);

                    $date2 = strtotime($delivery_date);
                    $time = explode('-',$time);
                ?>

			</p>
            <p class="form-field form-field-wide">
                <h2></h2>
                <input type="text" class="date-picker__custom" name="delivery-date" value="<?= date('Y-m-d', $date2) ?>">
                <label>@</label>
                <input type="time" class="" name="delivery-time-from" value="<?= trim($time[0]) ?>">
                <label>:</label>
                <input type="time" class="" name="delivery-time-to" value="<?= trim($time[1]) ?>">
            </p>
			<p class="form-field form-field-wide">
				<strong><?php _e('Assigned Store:', 'woocommerce'); ?></strong>
				<br>
				<?php

                $user = wp_get_current_user();
				$post_id = get_the_ID();
				$table_store = $wpdb->prefix . 'store_location';
				$check_role = true;
				if (in_array('administrator', (array)$user->roles) || in_array('order_fulfillment', (array)$user->roles)) {
					$stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1");
				} else{
					$table_post_meta = $wpdb->prefix . 'postmeta';
					$table_store_location = $wpdb->prefix . 'store_location';
					$check = $wpdb->get_results("SELECT $table_store_location.email_address
											FROM $table_store_location
											INNER JOIN $table_post_meta
											ON $table_store_location.id = $table_post_meta.meta_value
											WHERE $table_post_meta.post_id  = $post_id AND $table_post_meta.meta_key = 'wc_order_assigned_store'");
					if (isset($check[0]->email_address) && $check[0]->email_address == $user->user_email) {
						$stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 1");
					} else {
						$check_role = false;
					}
				}
                if ($check_role) {
					?>
					<select class="wc-enhanced-select" id="wc_order_assigned_store" name="wc_order_assigned_store">
						<option value="">Choose store</option>
						<?php foreach ($stores as $row) { ?>
							<option
								value="<?php echo $row->id ?>" <?php if ($store['id'] == $row->id) echo 'selected="selected"'; ?> ><?php echo $row->store_name ?></option>
						<?php } ?>
					</select>
				<?php } else { ?>
					<?php echo $store['store_name']; ?>
				<?php } ?>
			</p>
		<?php else:
			$table_store = $wpdb->prefix . 'store_location';
			$pickup_store_id = get_post_meta($order->get_id(), 'wc_order_pickup_store', true);
			$pickup_store = null;
			if ($pickup_store_id) {
				$pickup_store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $pickup_store_id", ARRAY_A);
			}
			?>

            <p class="form-field form-field-wide">
                <strong><?php _e('Pick up store:', 'woocommerce'); ?></strong>
                <br>
                <?php

                $store_id = get_post_meta($order->get_id(), 'wc_order_pickup_store', true);
                $table_store = $wpdb->prefix . 'store_location';
                if ($store_id)
                    $store = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $store_id", ARRAY_A);

                $user = wp_get_current_user();
                $post_id = get_the_ID();
                $table_store = $wpdb->prefix . 'store_location';
                $check_role = true;
                if (in_array('administrator', (array)$user->roles) || in_array('order_fulfillment', (array)$user->roles)) {
                    $stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1");
                } else {
                    $table_post_meta = $wpdb->prefix . 'postmeta';
                    $table_store_location = $wpdb->prefix . 'store_location';
                    $check = $wpdb->get_results("SELECT $table_store_location.email_address
											FROM $table_store_location
											INNER JOIN $table_post_meta
											ON $table_store_location.id = $table_post_meta.meta_value
											WHERE $table_post_meta.post_id  = $post_id AND $table_post_meta.meta_key = 'wc_order_assigned_store'");
                    if (isset($check[0]->email_address) && $check[0]->email_address == $user->user_email) {
                        $stores = $wpdb->get_results("SELECT * FROM $table_store WHERE status = 1 AND central_kitchen = 1");
                    } else {
                        $check_role = false;
                    }
                }
                if ($check_role) {
                    ?>
                    <select class="wc-enhanced-select" id="wc_order_pick_store" name="wc_order_pick_store">
                        <option value="">Choose store</option>
                        <?php foreach ($stores as $row) { ?>
                            <option
                                    value="<?php echo $row->id ?>" <?php if ($store['id'] == $row->id) echo 'selected="selected"'; ?> ><?php echo $row->store_name ?></option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <?php echo $store['store_name']; ?>
                <?php } ?>
            </p>

			<p class="form-field form-field-wide">
				<strong><?php _e('Collection time:', 'woocommerce'); ?></strong>
				<br>
				<?php
				if ($delivery_date)
					echo esc_attr($delivery_date);
				if ($delivery_date && $time)
					echo ', ';
				if ($time)
					echo esc_attr($time);

                $date2 = strtotime($delivery_date);
                $time = explode('-',$time);
				?>
            </p>
            <p class="form-field form-field-wide">
                <h2></h2>
                <input type="text" class="date-picker__custom" name="delivery-date" value="<?= date('Y-m-d', $date2) ?>">
                <label>@</label>
                <input type="time" class="" name="delivery-time-from" value="<?= trim($time[0]) ?>">
                <label>:</label>
                <input type="time" class="" name="delivery-time-to" value="<?= trim($time[1]) ?>">
            </p>
		<?php endif; ?>
	</div>

	<!-- Rider section -->
	<br class="clear"/>
	<h4><?php esc_html_e('Rider information', 'woocommerce'); ?></h4>
	<p class="form-field form-field-wide wc-customer-user">
		<label for="customer_user">
			<?php _e('Rider name:', 'woocommerce'); ?>
		</label>
		<?php
		$wp_custom_order_shipping_cost = get_post_meta($order->get_id(), 'wp_custom_order_shipping_cost', true);
		$wp_custom_order_rider = get_post_meta($order->get_id(), 'wp_custom_order_rider', true);

		$rider_string = '';
		$rider_id = '';
		$table_rider_name = $wpdb->prefix . 'cedele_setting_riders';
		$table_partner_name = $wpdb->prefix . 'cedele_setting_shipping_partner';
		if ($wp_custom_order_rider) {
			$rider_id = absint($wp_custom_order_rider);
			$rider = $wpdb->get_row(
				"SELECT *, partners.partner_name as partner_name
                FROM $table_rider_name
                LEFT JOIN (
                    SELECT id as partner_id, partner_name FROM $table_partner_name
                    WHERE status = 1
                ) as partners
                ON $table_rider_name.partner_id = partners.partner_id
                WHERE id = $rider_id"
			);
			if (!empty($rider)) {
				$partner_name = !empty($rider->partner_name) ? (' &ndash; ' . $rider->partner_name) : '';
				$rider_string = sprintf(
					esc_html__('%1$s (%2$s%3$s)', 'woocommerce'),
					$rider->rider_name,
					$rider->contact_number,
					$partner_name
				);
			}
		}
		$check_status = $order->get_status('edit');
		$check_status = $check_status == 'completed' || $check_status == 'failed' || $check_status == 'cancelled';

		$is_can_not_update_order = '';
		$current_date_time = new DateTime();
		$last_time_update_status = get_post_meta($order->get_id(), 'last_time_update_status', true);
		if ($last_time_update_status) {
			$last_modified_after_24h = new DateTime($last_time_update_status);
			$last_modified_after_24h->add(new DateInterval('P1D'));
		} else {
			$last_modified_after_24h = new DateTime();
			$last_modified_after_24h->sub(new DateInterval('P2D'));
		}

		if ($current_date_time > $last_modified_after_24h && $check_status) {
			$is_can_not_update_order = 1;
		}
		?>
		<input type="hidden" id="is_can_not_update_order" value="<?php echo $is_can_not_update_order; ?>">
		<select class="wp_custom_order_rider" id="wp_custom_order_rider" name="wp_custom_order_rider"
				data-placeholder="<?php esc_attr_e('Rider name', 'woocommerce'); ?>"
				data-allow_clear="true" <?php echo $is_can_not_update_order ? 'disabled' : '' ?>>
			<option value="<?php echo esc_attr($wp_custom_order_rider); ?>"
					selected="selected"><?php echo htmlspecialchars(wp_kses_post($rider_string)); ?></option>
		</select>
	</p>
	<p class="form-field form-field-wide">
		<label for="order_date"><?php _e('Shipping cost:', 'woocommerce'); ?></label>
		<input type="number" class="wp_custom_order_shipping_cost" style="width: 90%" placeholder="shipping cost"
			   name="wp_custom_order_shipping_cost" min="0" step="0.01"
			   value="<?php echo $wp_custom_order_shipping_cost; ?>"
			   pattern="[0-9]{0,}" <?php echo $is_can_not_update_order ? 'disabled' : '' ?>/> $
	</p>
    <p class="form-field form-field-wide wc-customer-user">
        <label for="customer_user">
            <?php _e('Order Source:', 'woocommerce'); ?>
        </label>
        <?php
            $order_source = get_post_meta($order->get_id(), 'wc_order_source', true);
        ?>
        <input type="text" class="wp_custom_order_shipping_cost" placeholder="" value="<?= $order_source ?>" disabled />
    </p>
<?php }

add_action('woocommerce_process_shop_order_meta', 'custom_process_store_location');

function custom_process_store_location($ord_id)
{
	$wc_order_assigned_store = isset($_POST['wc_order_assigned_store']) ? $_POST['wc_order_assigned_store'] : '';
	if($wc_order_assigned_store) {
        update_post_meta($ord_id, 'wc_order_assigned_store', wc_clean(wp_unslash($wc_order_assigned_store)));
    }
}

add_action('woocommerce_process_shop_order_meta', 'custom_process_failed_reason');

function custom_process_failed_reason($ord_id)
{
	$failed_reason = isset($_POST['failed_reason']) ? $_POST['failed_reason'] : '';
	$order_status = isset($_POST['order_status']) ? $_POST['order_status'] : '';
	$order = wc_get_order( $ord_id );
	$order_status_old = $order->get_status( 'edit' );
	if($order_status=='wc-failed' && empty($failed_reason)){
		echo '<script>alert("Failed reason is not empty!"); window.history.back();</script>';die;
	}
	if($order_status != 'wc-'.$order_status_old){
		update_post_meta($ord_id, 'last_time_update_status', date('Y-m-d H:i:s'));
	}
	if($failed_reason !=''){
		update_post_meta($ord_id, 'failed_reason', wc_clean(wp_unslash($failed_reason)));
	}
}

add_action('woocommerce_process_shop_order_meta', 'custom_process_shop_order_meta');

function custom_process_shop_order_meta($ord_id)
{
	// Update district.
	$_shipping_postcode_value = isset($_POST['_shipping_postcode']) ? $_POST['_shipping_postcode'] : '';
	$prefix_d = substr($_shipping_postcode_value, 0, 2);
	$district_name_by_prefix = wp_custom_get_district_name($prefix_d);
	if (!empty($ord_id)) {
		update_post_meta($ord_id, 'wp_custom_district', wc_clean(wp_unslash($district_name_by_prefix)));
	}

	// Update rider.
	$custom_rider_id = isset($_POST['wp_custom_order_rider']) ? $_POST['wp_custom_order_rider'] : '';
	$custom_shipping_cost = isset($_POST['wp_custom_order_shipping_cost']) ? floatval($_POST['wp_custom_order_shipping_cost']) : 0;
	$custom_shipping_cost = $custom_shipping_cost > 0 ? $custom_shipping_cost : 0;
	$status_check = $_POST['order_status'];

	$order = wc_get_order($ord_id);
	$order_status = $order->get_status();
	$array_cannot_wc = ['wc-cancelled', 'wc-failed', 'wc-completed'];
	$array_cannot = ['cancelled', 'failed', 'completed'];

	$current_date_time = new DateTime();
	$current_date_time_str = $current_date_time->format('Y-m-d H:i:s');

	if ($order_status) {
		if (in_array($order_status, $array_cannot)) {

			$last_time_update_status = get_post_meta($ord_id, 'last_time_update_status', true);
			if ($last_time_update_status) {
				$last_modified_after_24h = new DateTime($last_time_update_status);
				$last_modified_after_24h->add(new DateInterval('P1D'));
			} else {
				$last_modified_after_24h = new DateTime();
				$last_modified_after_24h->sub(new DateInterval('P2D'));
			}

			if ($current_date_time < $last_modified_after_24h) {
				update_post_meta($ord_id, 'wp_custom_order_rider', wc_clean(wp_unslash($custom_rider_id)));
				update_post_meta($ord_id, 'wp_custom_order_shipping_cost', wc_clean(wp_unslash($custom_shipping_cost)));
			}
		} else {
			update_post_meta($ord_id, 'wp_custom_order_rider', wc_clean(wp_unslash($custom_rider_id)));
			update_post_meta($ord_id, 'wp_custom_order_shipping_cost', wc_clean(wp_unslash($custom_shipping_cost)));
		}
	}

	if (in_array($status_check, $array_cannot_wc) && !is_numeric(strpos($status_check, $order_status))) {
		update_post_meta($ord_id, 'last_time_update_status', $current_date_time_str);
	}
}


add_action('woocommerce_process_shop_order_meta', 'update_delivery_pickup_time');

function update_delivery_pickup_time($ord_id)
{
    // Pickup Store
    global $wpdb;
    $table_store = $wpdb->prefix . 'store_location';
    $pickup_store_id = get_post_meta($ord_id, 'wc_order_pickup_store', true);
    $pickupStore = null;
    if ($pickup_store_id) {
        $pickupStore = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $pickup_store_id", ARRAY_A);
    }
    $assigned_store_id = get_post_meta($ord_id, 'wc_order_assigned_store', true);
    $order = null;
    if ($assigned_store_id) {
        $order = $wpdb->get_row("SELECT * FROM $table_store WHERE id = $assigned_store_id", ARRAY_A);
    }
    $wc_order_assigned_store = isset($_POST['wc_order_pick_store']) ? $_POST['wc_order_pick_store'] : '';

    // Collection time
    $dateOld = get_post_meta($ord_id, 'wp_custom_order_delivery_date', true);
    $timeOld = get_post_meta($ord_id, 'wp_custom_order_delivery_collection_time', true);

    $time = explode('-',$timeOld);
    $timeFromOld = strtotime(trim($time[0]));
    $timeToOld = strtotime(trim($time[1]));

    $date = $_POST['delivery-date'] ? $_POST['delivery-date']:'';
    $timeFrom = $_POST['delivery-time-from'] ? $_POST['delivery-time-from']:'';
    $timeTo = $_POST['delivery-time-to'] ? $_POST['delivery-time-to']:'';
    if($timeFromOld != strtotime($timeFrom) || $timeToOld != strtotime($timeTo) || strtotime($dateOld) != strtotime($date) || $pickup_store_id != $wc_order_assigned_store) {
        update_post_meta($ord_id, 'wp_custom_order_delivery_date', wc_clean(wp_unslash(date('d M Y', strtotime($date)))));
        update_post_meta($ord_id, 'wp_custom_order_delivery_collection_time', wc_clean(wp_unslash($timeFrom.'-'.$timeTo)));

        update_post_meta($ord_id, 'wc_order_pickup_store', wc_clean(wp_unslash($wc_order_assigned_store)));
        if($pickupStore['id'] == $order['id']) {
            update_post_meta($ord_id, 'wc_order_assigned_store', wc_clean(wp_unslash($wc_order_assigned_store)));
        }

        $order = wc_get_order($ord_id);
        ob_start();
        require_once get_template_directory() . '/woocommerce/emails/admin-update-time-store-order.php';
        $message = ob_get_contents();
        ob_end_clean();

        $emails_list = array();
        $wc_order_custom_email = get_post_meta($ord_id, 'wc_order_custom_email', true);
        if($wc_order_custom_email) {
            $emailShipping = get_post_meta( $ord_id, '_shipping_email', true );
            array_push($emails_list, $emailShipping);
        }

        $order_billing_email = $order->get_billing_email();
        array_push($emails_list, $order_billing_email);

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $result = wp_mail($emails_list, 'Your order has been updated', $message, $headers);
    }

}

function wp_custom_get_district_name($dictrict_prefix)
{
	if (empty($dictrict_prefix))
		return '';
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

	foreach ($district_map as $key => $name) {
		if (is_numeric(strpos($key, $dictrict_prefix))) {
			return $name;
		}
	}
}
