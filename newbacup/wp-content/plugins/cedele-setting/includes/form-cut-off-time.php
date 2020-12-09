<?php
function cdls_validate_cutofftime($item)
{
    $messages = array();

    $validation = new CDLS_Validation();
    $validation->name('Cut-off time for pickup')->value($item['cot_pickup'])->min(0)->max(300)->required();
    $validation->name('Cut-off time for Delivery')->value($item['cot_delivery'])->min(0)->max(300)->required();
    $validation->name('Cut-off time for order')->value($item['cot_order'])->required();
    $validation->name('Last time for order')->value($item['last_time_order'])->min(0)->max(300)->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function cdls_form_cut_of_time() {
    global $wpdb;

    $message = '';
    $notice = '';

    $default = array(
        'id'           => 0,
        'cot_pickup'   => 0,
        'cot_delivery' => 0,
        'cot_order'    => '',
        'last_time_order' => 0
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item['cot_pickup'] = intval($item['cot_pickup']);
        $item['cot_delivery'] = intval($item['cot_delivery']);
        $item['last_time_order'] = intval($item['last_time_order']);
        $item_valid = cdls_validate_cutofftime($item);

        if ($item_valid === true) {
            update_option('cot_pickup', $item['cot_pickup']);
            update_option('cot_delivery', $item['cot_delivery']);
            update_option('cot_order', $item['cot_order']);
            update_option('last_time_order', $item['last_time_order']);
            $message = 'Item was successfully saved';
        } else {
            $notice = $item_valid;
        }
    }
    else {
        $item = $default;

        $item['cot_pickup'] = get_option('cot_pickup', $item['cot_pickup']);
        $item['cot_delivery'] = get_option('cot_delivery', $item['cot_delivery']);
        $item['cot_order'] = get_option('cot_order', $item['cot_order']);
        $item['last_time_order'] = get_option('last_time_order', $item['last_time_order']);
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
            <table class="cdls-cut-off-time-form" cellpadding="8">
                <tbody>
                    <tr>
                        <td colspan="2"><b>Same day product:</b></td>
                    </tr>
                    <tr>
                        <td>Cut-off time for pickup:</td>
                        <td>
                            <input type="number" name="cot_pickup" min="0" max="300" value="<?php echo esc_attr($item['cot_pickup'])?>" placeholder="minutes" required>
                            minutes before closing time
                        </td>
                    </tr>
                    <tr>
                        <td>Cut-off time for delivery:</td>
                        <td>
                            <input type="number" name="cot_delivery" min="0" max="300" value="<?php echo esc_attr($item['cot_delivery'])?>" placeholder="minutes" required>
                            minutes before closing time
                        </td>
                    </tr>
                    <tr>
                        <td>Last time for order:</td>
                        <td>
                            <input type="number" name="last_time_order" min="0" max="300" value="<?php echo esc_attr($item['last_time_order'])?>" placeholder="minutes" required>
                            minutes before closing time
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Advanced product:</b></td>
                    </tr>
                    <tr>
                        <td>Cut-off time for order:</td>
                        <td>
                            <input type="text" name="cot_order" class="timepicker input-end-time" value="<?php echo show_time($item['cot_order']) ?>" placeholder="00:00" required autocomplete="off">
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="button-primary">Save changes</button>
        </form>
    </div>
<?php
}