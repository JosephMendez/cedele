<?php
function cdls_validate_placeholder($item)
{
    $messages = array();

    $validation = new CDLS_Validation();
    $validation->name('Placeholder')->value($item['cdls_placeholder'])->max(50)->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function cdls_form_placeholder() {
    global $wpdb;

    $message = '';
    $notice = '';

    $default = array(
        'id'               => 0,
        'cdls_placeholder' => '',
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item_valid = cdls_validate_placeholder($item);

        if ($item_valid === true) {
            update_option('cdls_placeholder', $item['cdls_placeholder']);
            $message = 'Item was successfully saved';
        } else {
            $notice = $item_valid;
        }
    }
    else {
        $item = $default;

        $item['cdls_placeholder'] = get_option('cdls_placeholder', $item['cdls_placeholder']);
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
            <ul class="subsubsub">
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=home-setting&section=highlight'); ?>">Highlighted Categories</a> | 
                </li>
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=home-setting&section=placeholder'); ?>" class="current">Placeholder for Delivery</a>
                </li>
            </ul>
            <br>
            <br>
            <fieldset>
                
            </fieldset>
            <table class="cdls-table-placeholder" cellpadding="8">
                <tbody>
                    <tr>
                        <td>Placeholder for Delivery:</td>
                        <td>
                            <input type="text" name="cdls_placeholder" maxlength="50" value="<?php echo esc_attr($item['cdls_placeholder'])?>" placeholder="placeholder" required>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" id="cdls-button-submit-placeholder" class="button-primary">Save changes</button>
        </form>
    </div>
<?php
}