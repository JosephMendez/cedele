<?php
function cdls_validate_collection_time($item)
{
    $messages = array();

    $validation = new CDLS_Validation();
    $validation->name('Start time for pickup')->value($item['pickup_start_time'])->min(0)->max(300)->required();
    $validation->name('Slot duration for pickup')->value(intval($item['pickup_slot_duration']))->min(0)->max(300)->required();
    $validation->name('Start time for delivery')->value(intval($item['delivery_start_time']))->min(0)->max(300)->required();
    $validation->name('Slot duration for delivery')->value(intval($item['delivery_slot_duration']))->min(0)->max(300)->required();
    $validation->name('Gap time beetween slot')->value(intval($item['delivery_gap_time']))->min(0)->max(300)->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function cdls_form_collection_timeslot() {
    global $wpdb, $table_store, $table_master_data, $table_working_time;

    $message = '';
    $notice = '';

    $default = array(
        'id'                     => 0,
        'pickup_start_time'      => 0,
        'pickup_slot_duration'   => 0,
        'delivery_start_time'    => 0,
        'delivery_slot_duration' => 0,
        'delivery_gap_time'      => 0,
    );


    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item = array_map('intval', $item);
        $item_valid = cdls_validate_collection_time($item);

        if ($item_valid === true) {
            update_option('pickup_start_time', $item['pickup_start_time']);
            update_option('pickup_slot_duration', $item['pickup_slot_duration']);
            update_option('delivery_start_time', $item['delivery_start_time']);
            update_option('delivery_slot_duration', $item['delivery_slot_duration']);
            update_option('delivery_gap_time', $item['delivery_gap_time']);
            $message = 'Item was successfully saved';
        } else {
            $notice = $item_valid;
        }
    } else {
        $item = $default;
        $item['pickup_start_time'] = get_option('pickup_start_time', $item['pickup_start_time']);
        $item['pickup_slot_duration'] = get_option('pickup_slot_duration', $item['pickup_slot_duration']);
        $item['delivery_start_time'] = get_option('delivery_start_time', $item['delivery_start_time']);
        $item['delivery_slot_duration'] = get_option('delivery_slot_duration', $item['delivery_slot_duration']);
        $item['delivery_gap_time'] = get_option('delivery_gap_time', $item['delivery_gap_time']);
    }
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            CEDELE SETTING
        </h2>

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error">
                <p><?php echo $notice ?></p>
            </div>
        <?php endif;?>

        <?php if (!empty($message)): ?>
            <div id="message" class="updated">
                <p><?php echo $message ?></p>
            </div>
        <?php endif;?>
        <?php require_once "tab.php" ?>
        <form class="cdls-form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <table class="cdls-timeslot-form" cellpadding="8">
                <tbody>
                    <tr>
                        <td colspan="2"><b>Pickup time slot:</b></td>
                    </tr>
                    <tr>
                        <td>Start time for pickup:</td>
                        <td>
                            <input type="number" name="pickup_start_time" min="0" max="300" value="<?php echo esc_attr($item['pickup_start_time'])?>" placeholder="minutes" required>
                            minutes after opening time
                        </td>
                    </tr>
                    <tr>
                        <td>Slot duration:</td>
                        <td>
                            <input type="number" name="pickup_slot_duration"  min="0" max="300" value="<?php echo esc_attr($item['pickup_slot_duration'])?>" placeholder="minutes" required>
                            minutes
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Delivery time slot:</b></td>
                    </tr>
                    <tr>
                        <td>Start time for delivery:</td>
                        <td>
                            <input type="number" name="delivery_start_time" min="0" max="300" value="<?php echo esc_attr($item['delivery_start_time'])?>" placeholder="minutes" required>
                            minutes after opening time
                        </td>
                    </tr>
                    <tr>
                        <td>Slot duration:</td>
                        <td>
                            <input type="number" name="delivery_slot_duration" min="0" max="300" value="<?php echo esc_attr($item['delivery_slot_duration'])?>" placeholder="minutes" required>
                            minutes
                        </td>
                    </tr>
                    <tr>
                        <td>Gap time between slot:</td>
                        <td>
                            <input type="number" name="delivery_gap_time" min="0" max="300" value="<?php echo esc_attr($item['delivery_gap_time'])?>" placeholder="minutes" required>
                            minutes
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="button-primary">Save changes</button>
        </form>
    </div>
<?php
}
