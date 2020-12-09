<?php
function wpsl_validate_holiday($item)
{
    $messages = array();

    $validation = new WPSL_Validation();
    $validation->name('Description')->value($item['description'])->max(100)->required();
    $validation->name('Start date')->value($item['start_date'])->required();
    $validation->name('End date')->value($item['end_date'])->required();

    $check_start_date = validateDate($item['start_date'], 'Y-m-d') ||
                        validateDate($item['start_date'], 'm-d-Y') ||
                        validateDate($item['start_date'], 'Y/m/d') ||
                        validateDate($item['start_date'], 'm/d/Y');

    $check_end_date = validateDate($item['end_date'], 'Y-m-d') ||
                        validateDate($item['end_date'], 'm-d-Y') ||
                        validateDate($item['end_date'], 'Y/m/d') ||
                        validateDate($item['end_date'], 'm/d/Y');

    if (!$check_start_date) {
        $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
        $error = array('start_date' => "Start date is not valid");
        return implode('<br/>', array_merge($messages, $error));
    }

    if (!$check_end_date) {
        $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
        $error = array('end_date' => "End date is not valid");
        return implode('<br/>', array_merge($messages, $error));
    }

    if ($item['start_date'] > $item['end_date']) {
        $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
        $error = array('start_date' => "Start date must be less than or equal to End date");
        return implode('<br/>', array_merge($messages, $error));
    }

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function convert_store_holiday_to_insert($holiday_id = 0, $stores) {
    $new_data = [];
    foreach ($stores as $key => $s) {
        $data = [
            'store_id' => $s,
            'holiday_id' => $holiday_id,
        ];

        $new_data[] = $data;
    }

    return $new_data;
}

function wpsl_form_holiday_page_handle() {
    global $wpdb, $table_store, $table_holiday, $table_store_holiday;

    $message = '';
    $notice = '';

    $default = array(
        'id'          => 0,
        'start_date'  => '',
        'end_date'    => '',
        'description' => ''
    );
    $list_stores = [];
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item['start_date'] = formatDate($item['start_date']);
        $item['end_date'] = formatDate($item['end_date']);
        $item['description'] = stripslashes($item['description']);
        // convert data
        $list_stores = !empty($_REQUEST['stores']) ? $_REQUEST['stores'] : [];
        $item_valid = wpsl_validate_holiday($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                // insert
                $result = $wpdb->insert($table_holiday, $item);
                $item['id'] = $wpdb->insert_id;
                
                $list_stores = convert_store_holiday_to_insert($item['id'], $list_stores);
                db_multiple_insert($table_store_holiday, $list_stores);
                if ($result) {
                    $message = 'Item was successfully saved';
                    $_SESSION['wpsl_alert'] = $message;
                    wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=holidays'));
                } else {
                    $notice = 'There was an error while saving item';
                }
            } else {
                // update
                $result = $wpdb->update($table_holiday, $item, array('id' => $item['id']));

                // update table holiday store
                $wpdb->delete($table_store_holiday, ['holiday_id' => $_REQUEST['id']]);
                $list_stores = convert_store_holiday_to_insert($_REQUEST['id'], $list_stores);
                db_multiple_insert($table_store_holiday, $list_stores);
                if ($result || $result === 0) {
                    $message = 'Item was successfully updated';
                    $_SESSION['wpsl_alert'] = $message;
                    wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=holidays'));
                } else {
                    $notice = 'There was an error while updating item';
                }
            }
        } else {
            $list_stores = convert_store_holiday_to_insert(0, $list_stores);
            $notice = $item_valid;
        }
    } else {
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = db_get_row(db_prepare("SELECT * FROM $table_holiday WHERE id = %d", $_REQUEST['id']));
            $list_stores = db_get_list(db_prepare("SELECT * FROM $table_store_holiday WHERE holiday_id = %d", $_REQUEST['id']));
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }

    $list_option_stores = $wpdb->get_results("SELECT * FROM $table_store ORDER BY store_name asc", ARRAY_A);
    $data = [
        'item' => $item,
        'list_stores' => $list_stores,
        'list_option_stores' => $list_option_stores,
    ];
    
    $title_form = !empty($_REQUEST['id']) ? 'Edit holiday' : 'New holiday';
    add_meta_box('form-setting-area', $title_form, 'wpsl_meta_box_holiday', 'form_holiday_handle');
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            HOLIDAY
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=holidays');?>">back to list</a> 
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

        <form class="wpsl-form-holiday" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
            <div class="metabox-holder">
                <?php do_meta_boxes('form_holiday_handle', 'advanced', $data); ?>
            </div>
        </form>
    </div>
<?php
}

function show_date($date)
{
    return !empty($date) ? date('m/d/Y', strtotime($date)) : '';
}

function wpsl_meta_box_holiday($data) {
    $item = $data['item'];
    $list_stores = $data['list_stores'];
    $list_option_stores = $data['list_option_stores'];
?>
    <div class="wpsl-holiday-container-form">
        <table cellpadding="8">
            <tbody>
                <tr>
                    <td width="20%"><b>Start date</b></td>
                    <td>
                        <span>
                            <input type="text" class="datepicker" name="start_date" value="<?php echo show_date($item['start_date']); ?>" placeholder="mm/dd/yyyy" required autocomplete="off">
                        </span>
                        <span class="span-end-time">
                            <b>End date</b>
                            <input type="text" class="datepicker" name="end_date" value="<?php echo show_date($item['end_date']); ?>" placeholder="mm/dd/yyyy" required autocomplete="off">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width=""><b>Description</b></td>
                    <td>
                        <textarea name="description" cols="50" rows="6" required><?php echo esc_attr($item['description']); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td width=""><b>Apply to store</b></td>
                    <td class="wrap-location">
                        <?php if(!empty($list_option_stores)):
                            $description = $item['description'];
                            $checkall = false;

                            if (empty($description)) {
                                $checkall = true;
                            }
                        ?>
                        <div class="item-outlet">
                            <input type="checkbox" class="holiday-checkbox-all">
                            <span>all locations</span>
                        </div>
                        <?php foreach ($list_option_stores as $key => $store):
                            $exist = filter_array($list_stores, 'store_id', $store['id']);
                        ?>
                            <div class="item-outlet">
                                <input type="checkbox" class="holiday-checkbox" name="stores[]"
                                <?php echo !empty($exist) || $checkall ? 'checked' : '' ?>
                                value="<?php echo $store['id']; ?>"
                                >
                                <span><?php echo $store['store_name']; ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="button-submit button-primary">Save Changes</button>
    </div>
<?php } ?>