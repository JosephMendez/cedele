<?php
function cdls_validate_product_label($item)
{
    $messages = array();

    $validation = new CDLS_Validation();
    $validation->name('"New" label')->value($item['cdls_product_new_label_age'])->min(0)->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function cdls_form_product_label() {
    global $wpdb;

    $message = '';
    $notice = '';

    $default = array(
        'id'                         => 0,
        'cdls_product_new_label_age' => 0,
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item['cdls_product_new_label_age'] = intval($item['cdls_product_new_label_age']);
        $item_valid = cdls_validate_product_label($item);

        if ($item_valid === true) {
            update_option('cdls_product_new_label_age', $item['cdls_product_new_label_age']);
            $message = 'Item was successfully saved';
        } else {
            $notice = $item_valid;
        }
    }
    else {
        $item = $default;
        $item['cdls_product_new_label_age'] = get_option('cdls_product_new_label_age', $item['cdls_product_new_label_age']);
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
            <table class="cdls-product-label-form" cellpadding="8">
                <tbody>
                    <tr>
                        <td colspan="2"><b>"New" label:</b></td>
                    </tr>
                    <tr>
                        <td>Product's age is equal or less than:</td>
                        <td>
                            <input type="number" name="cdls_product_new_label_age" min="0" value="<?php echo esc_attr($item['cdls_product_new_label_age'])?>" placeholder="day(s)" required>
                            day(s)
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="button-primary">Save changes</button>
        </form>
    </div>
<?php
}