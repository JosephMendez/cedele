<?php
function convert_data_to_insert($data) {
    $new_data = [];
    $update_data = [];
    $data_name = $data['data_name'];
    $id = $data['id'];
    $description = $data['data_description'];
    foreach ($data_name as $key => $d) {
        if ($id[$key] == 0) {
            $new_data[] = [
                'data_name' => trim($d),
                'data_description' => trim($description[$key]),
                'type' => $data['type']
            ];
        } else {
            $update_data[] = [
                'data_name' => trim($d),
                'id' => $id[$key],
                'data_description' => trim($description[$key]),
                'type' => $data['type']
            ];
        }
    }

    return [
        'new_data' => $new_data,
        'update_data' => $update_data
    ];
}
function wpsl_setting_page_handle() {
    global $wpdb, $table_master_data;

    $message = '';
    $notice = '';

    $default = array(
        'id'        => 0,
        'data_name' => [],
        'data_description' => [],
        'id'   => [],
        'type'      => ''

    );
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $inputs = shortcode_atts($default, $_REQUEST);
        $master_dropdown_data = convert_data_to_insert($inputs);

        $new_data = $master_dropdown_data['new_data'];
        $update_data = $master_dropdown_data['update_data'];
        $delete_ids = explode(',', trim($_REQUEST['delete_ids'], ','));

        // actions
        if ($delete_ids) {
            $result_delete = db_multiple_delete($table_master_data, $delete_ids);
        }
        $result_update = db_multiple_update($table_master_data, $update_data);
        $result_insert = db_multiple_insert($table_master_data, $new_data);

        // if ($result_update) {
            $message = 'Item was successfully updated';
        // } else {
        //     $notice = 'There was an error while updating item';
        // }
    }

    $master_dropdown_data = $wpdb->get_results("SELECT * FROM $table_master_data", ARRAY_A);
    $data = [
        'list_areas' => filter_array($master_dropdown_data, 'type', 'area'),
        'list_districts' => filter_array($master_dropdown_data, 'type', 'district'),
        'list_outlets' => filter_array($master_dropdown_data, 'type', 'outlet'),
    ];
    
    add_meta_box('form-setting-area', 'Area', 'wpsl_meta_box_setting_area', 'setting_form_area');
    add_meta_box('form-setting-district', 'District', 'wpsl_meta_box_setting_district', 'setting_form_district');
    add_meta_box('form-setting-outlet', 'Outlet', 'wpsl_meta_box_setting_outlet', 'setting_form_outlet');
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            SETTING
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

        <form class="wpsl-form-setting" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="type" value="area"/>
            <input type="hidden" class="delete_ids" name="delete_ids" value="">
            <div class="metabox-holder">
                <?php do_meta_boxes('setting_form_area', 'advanced', $data); ?>
            </div>
        </form>

        <form class="wpsl-form-setting" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="type" value="district"/>
            <input type="hidden" class="delete_ids" name="delete_ids" value="">
            <div class="metabox-holder">
                <?php do_meta_boxes('setting_form_district', 'advanced', $data); ?>
            </div>
        </form>

        <form class="wpsl-form-setting" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="type" value="outlet"/>
            <input type="hidden" class="delete_ids" name="delete_ids" value="">
            <div class="metabox-holder">
                <?php do_meta_boxes('setting_form_outlet', 'advanced', $data); ?>
            </div>
        </form>
    </div>
<?php
}
function wpsl_meta_box_setting_area($data) {
    $list_areas = $data['list_areas'];
?>
    <div class="form-setting" data-type="area">        
        <div class="form-content form-1">
            <div class="form-row">
                <button type="button" class="button-add-more">
                    Add more
                </button>
                <span class="button-action"></span>
            </div>
            <div class="list-data">
                <?php foreach ($list_areas as $key => $area): ?>
                <div class="form-row" data-id="<?php echo $area['id'] ?>">
                    <input class="input-row" name="data_name[]" type="text" pattern="[\p{L}0-9\s.,()°-]+" value="<?php echo esc_attr($area['data_name'])?>" placeholder="area" required>
                    <input class="input-id" name="id[]" type="hidden" value="<?php echo $area['id']?>">
                    <span class="button-action button-remove">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <button type="button" class="button-submit button-primary">Save Changes</button>
        <span class="warning-alert">
            <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-exclamation-triangle" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.938 2.016a.146.146 0 0 0-.054.057L1.027 13.74a.176.176 0 0 0-.002.183c.016.03.037.05.054.06.015.01.034.017.066.017h13.713a.12.12 0 0 0 .066-.017.163.163 0 0 0 .055-.06.176.176 0 0 0-.003-.183L8.12 2.073a.146.146 0 0 0-.054-.057A.13.13 0 0 0 8.002 2a.13.13 0 0 0-.064.016zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
            </svg>
            There are duplicate item!
        </span>
    </div>
<?php
}
function wpsl_meta_box_setting_district($data) {
    $list_districts = $data['list_districts'];
?>
    <div class="form-setting" data-type="district">
        <div class="form-content form-2">
            <div class="form-row">
                <button type="button" class="button-add-more">
                    Add more
                </button>
                <span class="button-action"></span>
            </div>
            <div class="list-data">
                <?php foreach ($list_districts as $key => $district): ?>
                <div class="form-row">
                    <input class="input-row" name="data_name[]" type="text" pattern="[\p{L}0-9\s.,()°-]+" value="<?php echo esc_attr($district['data_name'])?>" placeholder="district" required>
                    <input class="input-id" name="id[]" type="hidden" value="<?php echo $district['id']?>">
                    <span class="button-action button-remove">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <button type="button" class="button-submit button-primary">Save Changes</button>
        <span class="warning-alert">
            <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-exclamation-triangle" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.938 2.016a.146.146 0 0 0-.054.057L1.027 13.74a.176.176 0 0 0-.002.183c.016.03.037.05.054.06.015.01.034.017.066.017h13.713a.12.12 0 0 0 .066-.017.163.163 0 0 0 .055-.06.176.176 0 0 0-.003-.183L8.12 2.073a.146.146 0 0 0-.054-.057A.13.13 0 0 0 8.002 2a.13.13 0 0 0-.064.016zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
            </svg>
            There are duplicate item!
        </span>
    </div>
<?php
}
function wpsl_meta_box_setting_outlet($data) {
    $list_outlets = $data['list_outlets'];
?>
    <div class="form-setting" data-type="outlet">
        <div class="form-content form-3">
            <div class="form-row">
                <button type="button" class="button-add-more-outlet">
                    Add more
                </button>
                <span class="button-action"></span>
            </div>
            <div class="list-data">
                <?php foreach ($list_outlets as $key => $outlet): ?>
                <div class="form-row">
                    <div class="div-outlet">
                        <input class="input-row txt-data-name" name="data_name[]" type="text" pattern="[\p{L}0-9\s.,()°-]+" value="<?php echo esc_attr($outlet['data_name'])?>" placeholder="outlet" required>
                        <textarea class="data-description" placeholder="description" name="data_description[]"><?php echo esc_attr($outlet['data_description'])?></textarea>
                    </div>
                    <input class="input-id" name="id[]" type="hidden" value="<?php echo $outlet['id']?>">
                    <span class="button-action button-remove">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <button type="button" class="button-submit button-primary">Save Changes</button>
        <span class="warning-alert">
            <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-exclamation-triangle" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.938 2.016a.146.146 0 0 0-.054.057L1.027 13.74a.176.176 0 0 0-.002.183c.016.03.037.05.054.06.015.01.034.017.066.017h13.713a.12.12 0 0 0 .066-.017.163.163 0 0 0 .055-.06.176.176 0 0 0-.003-.183L8.12 2.073a.146.146 0 0 0-.054-.057A.13.13 0 0 0 8.002 2a.13.13 0 0 0-.064.016zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
            </svg>
            There are duplicate item!
        </span>
    </div>
<?php
}
