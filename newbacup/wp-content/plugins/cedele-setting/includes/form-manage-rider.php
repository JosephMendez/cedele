<?php
function cdls_form_manager_rider() {
    global $wpdb;

    $message = '';
    $notice = '';
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
        <form class="cdls-form cdls-form-rider" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <ul class="subsubsub">
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=manage-driver&section=manage-rider'); ?>" class="current">Riders management</a> | 
                </li>
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=manage-driver&section=shipping-partner'); ?>">Shipping partner</a>
                </li>
            </ul>
            <br>
            <br>
            <button type="button" class="button-new-rider add-new-h2">New rider</button>
            <table class="cdls-table-rider wp-list-table widefat striped" cellpadding="8" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="25%">Rider name</th>
                        <th width="25%">Contact number</th>
                        <th width="15%">Shipping partner</th>
                        <th width="15%">Number of orders</th>
                        <th width="10%">Status</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="clearfix"></div>
        </form>
    </div>
<?php
}