<?php
$table_peak_hour = $wpdb->prefix . 'cedele_setting_peak_hour';
$table_occasion = $wpdb->prefix . 'cedele_setting_occasion';

function cdls_validate_shipping_time()
{
    # code...
}

function convert_correct_date($data_occasions)
{
    $new_data_occasions = $data_occasions;
    if (!empty($data_occasions)) {
        foreach ($data_occasions as $key => $occasion) {
            $new_data_occasions[$key]['start_date'] = cdls_formatDate($new_data_occasions[$key]['start_date']);
            $new_data_occasions[$key]['end_date'] = cdls_formatDate($new_data_occasions[$key]['end_date']);
        }
    }

    return $new_data_occasions;
}

function cdls_form_shipping_time() {
    global $wpdb, $table_occasion, $table_peak_hour;

    $message = '';
    $notice = '';

    $default = [
        'changes' => [],
        'updates' => [],
        'deletes' => [],
        'occasion_changes' => [],
        'occasion_updates' => [],
        'occasion_deletes' => [],
    ];

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);

        $peak_changes = $item['changes'];
        $peak_updates = $item['updates'];
        $peak_deletes = $item['deletes'];
        $occasion_changes = $item['occasion_changes'];
        $occasion_updates = $item['occasion_updates'];
        $occasion_deletes = $item['occasion_deletes'];

        $data_peak_insert = array_values($peak_changes);
        $result = cdls_multiple_insert($table_peak_hour, $data_peak_insert);
        cdls_multiple_update($table_peak_hour, $peak_updates);
        cdls_multiple_delete($table_peak_hour, $peak_deletes);

        $data_occasions = array_values($occasion_changes);
        $data_occasions = convert_correct_date($data_occasions);
        $occasion_updates = convert_correct_date($occasion_updates);
        $result = cdls_multiple_insert($table_occasion, $data_occasions);
        cdls_multiple_update($table_occasion, $occasion_updates);
        cdls_multiple_delete($table_occasion, $occasion_deletes);
        $message = 'Item was successfully saved';
    }

    $list_peak_hours = $wpdb->get_results("SELECT * FROM $table_peak_hour", ARRAY_A);
    $list_occasions = $wpdb->get_results("SELECT * FROM $table_occasion", ARRAY_A);
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
        <form class="cdls-form cdls-form-shipping-time" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <p style="margin-top: 0px;">
                <b>Peak Hour:</b>
            </p>
            <table class="cdls-table-shipping-time cdls-table-shipping-time-peak-hour wp-list-table widefat striped" cellpadding="8" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="90%">Peak Hours</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_peak_hours)): ?>
                    <?php foreach ($list_peak_hours as $key => $peak): ?>
                    <tr data-id="<?php echo $peak['id'] ?>">
                        <input type="hidden" name="updates[<?php echo $peak['id'] ?>][id]" value="<?php echo $peak['id'] ?>">
                        <td>
                            <span class="edited-data-span">
                                <?php echo cdls_show_time($peak['start_time']) . ' - ' . cdls_show_time($peak['end_time']); ?>
                            </span>
                            <span class="editing-data-span">
                                <input type="text" class="timepicker" name="updates[<?php echo $peak['id'] ?>][start_time]" value="<?php echo cdls_show_time($peak['start_time']) ?>" data-name="start_time" data-old="<?php echo cdls_show_time($peak['start_time']) ?>"
                                />
                                -
                                <input type="text" class="timepicker" name="updates[<?php echo $peak['id'] ?>][end_time]" value="<?php echo cdls_show_time($peak['end_time']) ?>" data-name="end_time" data-old="<?php echo cdls_show_time($peak['end_time']) ?>"
                                />
                            </span>
                        </td>
                        <td>
                            <span class="pointer cdls-noselect button-edit edited-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </span>
                            <span class="pointer cdls-noselect button-delete edited-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </span>
                            <span class="pointer cdls-noselect button-time-cancel editing-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>
                            <input type="text" class="input-start-time timepicker" placeholder="hh:mm"> - <input type="text" class="input-end-time timepicker" placeholder="hh:mm">
                        </th>
                        <th>
                            <span class="button-more pointer">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z"/>
                                </svg>
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <p><b>Occasions:</b></p>
            <table class="cdls-table-shipping-time cdls-table-shipping-time-occasion wp-list-table widefat striped" cellpadding="8" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="30%">Start Date</th>
                        <th width="30%">End Date</th>
                        <th width="30%">Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_occasions)): ?>
                    <?php foreach ($list_occasions as $key => $occasion): ?>
                    <tr data-id="<?php echo $occasion['id'] ?>">
                        <input type="hidden" name="occasion_updates[<?php echo $occasion['id'] ?>][id]" value="<?php echo $occasion['id'] ?>">
                        <td>
                            <span class="edited-data-span">
                                <?php echo !empty($occasion['start_date']) ? date('m/d/Y', strtotime($occasion['start_date'])) : '' ?>
                            </span>
                            <span class="editing-data-span">
                                <input type="text" class="timedatepicker" name="occasion_updates[<?php echo $occasion['id'] ?>][start_date]" value="<?php echo !empty($occasion['start_date']) ? date('m/d/Y', strtotime($occasion['start_date'])) : '' ?>" data-name="start_date" data-old="<?php echo !empty($occasion['start_date']) ? date('m/d/Y', strtotime($occasion['start_date'])) : '' ?>"/>
                            </span>
                        </td>
                        <td>
                            <span class="edited-data-span">
                                <?php echo !empty($occasion['end_date']) ? date('m/d/Y', strtotime($occasion['end_date'])) : '' ?>
                            </span>
                            <span class="editing-data-span">
                                <input type="text" class="timedatepicker" name="occasion_updates[<?php echo $occasion['id'] ?>][end_date]" value="<?php echo !empty($occasion['end_date']) ? date('m/d/Y', strtotime($occasion['end_date'])) : '' ?>" data-name="end_date" data-old="<?php echo !empty($occasion['end_date']) ? date('m/d/Y', strtotime($occasion['end_date'])) : '' ?>"/>
                            </span>
                        </td>
                        <td>
                            <span class="edited-data-span">
                                <?php echo esc_html_e($occasion['description']); ?>
                            </span>
                            <span class="editing-data-span">
                                <input type="text" name="occasion_updates[<?php echo $occasion['id'] ?>][description]" value="<?php echo esc_html_e($occasion['description']); ?>" data-name="description" data-old="<?php echo esc_html_e($occasion['description']); ?>"/>
                            </span>
                        </td>
                        <td>
                            <span class="pointer cdls-noselect button-edit edited-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </span>
                            <span class="pointer cdls-noselect button-delete edited-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </span>
                            <span class="pointer cdls-noselect button-time-cancel editing-data-span">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>
                            <input type="text" class="input-start-date timedatepicker" placeholder="m/d/y">
                        </th>
                        <th>
                            <input type="text" class="input-end-date timedatepicker" placeholder="m/d/y">
                        </th>
                        <th>
                            <input type="text" class="input-description" placeholder="description">
                        </th>
                        <th>
                            <span class="button-more pointer">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z"/>
                                </svg>
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="button-primary cdls-button-submit-form">Save changes</button>
        </form>
    </div>
<?php
}