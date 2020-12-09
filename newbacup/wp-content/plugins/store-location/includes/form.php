<?php
function check_exist_master_data($data) {
    global $table_master_data;
    $exist = db_get_row(db_prepare("SELECT * FROM $table_master_data WHERE id = %d", $data));

    return !empty($exist);
}

function check_valid_time($start, $end)
{
    $start_obj = new DateTime("$start");
    $end_obj = new DateTime("$end");

    return $start_obj <= $end_obj;
}

function wpsl_validate_location($item, $working_time_data)
{
    $messages = array();

    $validation = new WPSL_Validation();
    $validation->name('Name')->value($item['store_name'])->max(100)->required();
    $validation->name('Number house')->value($item['number_house'])->pattern('address')->max(100)->required();
    $validation->name('Street')->value($item['street_name'])->pattern('address')->max(100)->required();
    $validation->name('Zipcode')->value($item['zipcode'])->pattern('address')->max(100)->required();
    $validation->name('District')->value($item['district'])->max(100)->required();
    $validation->name('Area')->value($item['area'])->max(100)->required();
    $validation->name('Outlet')->value($item['outlet_type'])->max(100)->required();

    $validation->name('Building')->value($item['building'])->max(100);
    $validation->name('Floor-unit')->value($item['floor_unit'])->max(100);
    $validation->name('Longitude')->value($item['longitude'])->pattern('float');
    $validation->name('Latitude')->value($item['latitude'])->pattern('float');

    $validation->name('Email')->value($item['email_address'])->pattern('email')->max(100)->required();
    $validation->name('Phone')->value($item['phone_number'])->pattern('tel')->max(100)->required();

    // validate upload file
    if (!empty($_FILES['image']['tmp_name'])) {
        $validation->name('Image')->value($item['image'])
            ->ext('png')->ext('jpg')->ext('gif')->ext('jpeg')
            ->maxSize(3145728);
    }
    if (!empty($_FILES['file']['tmp_name'])) {
        $validation->name('File')->value($item['file'])->ext('pdf')->maxSize(3145728);
    }

    if (!empty($item['area'])) {
        if (!check_exist_master_data($item['area'])) {
            $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
            $error = array('Area' => "Area does not exist!");
            return implode('<br/>', array_merge($messages, $error));
        }
    }

    if (!empty($item['district'])) {
        if (!check_exist_master_data($item['district'])) {
            $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
            $error = array('District' => "District does not exist!");
            return implode('<br/>', array_merge($messages, $error));
        }
    }

    if (!empty($item['outlet_type'])) {
        if (!check_exist_master_data($item['outlet_type'])) {
            $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
            $error = array('Outlet' => "Outlet type does not exist!");
            return implode('<br/>', array_merge($messages, $error));
        }
    }

    if (!empty($item['longitude'])) {
        $check_arr = explode('.', $item['longitude']);
        $check_arr_1 = explode('-', $item['longitude']);
        if ((is_array($check_arr) && count($check_arr) > 2) || (is_array($check_arr_1) && count($check_arr_1) > 2)) {
            $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
            $error = array('Longitude' => "The field Longitude is not valid.");
            return implode('<br/>', array_merge($messages, $error));
        }
    }
    if (!empty($item['latitude'])) {
        $check_arr = explode('.', $item['latitude']);
        $check_arr_1 = explode('-', $item['latitude']);
        if ((is_array($check_arr) && count($check_arr) > 2) || (is_array($check_arr_1) && count($check_arr_1) > 2)) {
            $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
            $error = array('Latitude' => "The field Latitude is not valid.");
            return implode('<br/>', array_merge($messages, $error));
        }
    }

    if (!empty($working_time_data)) {
        foreach ($working_time_data as $key => $time_data) {
            if (!empty($time_data['opening_time'])) {
                foreach ($time_data['opening_time'] as $key => $time) {
                    if (!check_valid_time($time['from'], $time['to'])) {
                        $messages = !empty($validation->getErrors()) ? $validation->getErrors() : [];
                        $error = array('Time' => "From time must be less than or equal to To time!");
                        return implode('<br/>', array_merge($messages, $error));
                    }
                }
            }
        }
    }

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function convert_to_insert_working_time($store_id = 0, $working_time_data) {
    $new_data = [];
    foreach ($working_time_data as $key => $working_time) {
        foreach ($working_time['opening_time'] as $key => $time) {
            $data = [
                'working_day' => $working_time['working_day'],
                'start_working_time' => $time['from'],
                'end_working_time' => $time['to'],
                'store_id' => $store_id
            ];

            $new_data[] = $data;
        }
    }

    return $new_data;
}

function wpsl_form_page_handle() {
    global $wpdb, $table_store, $table_master_data, $table_working_time;

    $message = '';
    $notice = '';

    $default = array(
        'id'              => 0,
        'store_name'      => '',
        'number_house'    => '',
        'street_name'     => '',
        'zipcode'         => '',
        'area'            => '',
        'district'        => '',
        'outlet_type'     => '',
        'phone_number'    => '',
        'description'    => '',
        'email_address'   => '',
        'image_id'        => '',
        'file_id'         => '',
        'status'          => 0,
        'floor_unit'      => '',
        'building'        => '',
        'longitude'       => '',
        'latitude'        => '',
        'central_kitchen' => 0,
    );

    $working_time_data = [];
    $central_kitchen_old = 0;
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $central_kitchen_old = $_REQUEST['central_kitchen_old'];
        // convert data
        $item['central_kitchen'] = !empty($item['central_kitchen']) ? 1 : 0;
        $item['status'] = !empty($item['status']) ? 1 : 0;
        $item['store_name'] = stripslashes($item['store_name']);
        $working_time_data = convert_json_to_array($_REQUEST['working_time']);

        $item_valid = wpsl_validate_location($item, $working_time_data);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                // insert
                $result = $wpdb->insert($table_store, $item);
                $item['id'] = $wpdb->insert_id;
                // insert
                $working_time_data = convert_to_insert_working_time($item['id'], $working_time_data);
                db_multiple_insert($table_working_time, $working_time_data);
                if ($result) {
                    // update unique central_kitchen
                    if (!empty($item['central_kitchen'])) {
                        $wpdb->query("UPDATE $table_store SET central_kitchen = 0
                            WHERE id != $item[id] and central_kitchen = 1");
                    }
                    $message = 'Item was successfully saved';
                    $_SESSION['wpsl_alert'] = $message;
                    wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=locations'));
                } else {
                    $notice = 'There was an error while saving item';
                }
            } else {
                // update
                $result = $wpdb->update($table_store, $item, array('id' => $item['id']));
                $result1 = $wpdb->delete($table_working_time, ['store_id' => $item['id']]);
                // insert
                $working_time_data = convert_to_insert_working_time($item['id'], $working_time_data);
                db_multiple_insert($table_working_time, $working_time_data);
                if ($result || $result === 0) {
                    // update unique central_kitchen
                    if (!empty($item['central_kitchen'])) {
                        $wpdb->query("UPDATE $table_store SET central_kitchen = 0
                            WHERE id != $item[id] and central_kitchen = 1");
                    }
                    $message = 'Item was successfully updated';
                    $_SESSION['wpsl_alert'] = $message;
                    wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=locations'));
                } else {
                    $notice = 'There was an error while updating item';
                }
            }
        } else {
            $working_time_data = convert_to_insert_working_time(0, $working_time_data);
            $notice = $item_valid;
        }
    } else {
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = db_get_row(db_prepare("SELECT * FROM $table_store WHERE id = %d", $_REQUEST['id']));
            $central_kitchen_old = $item['central_kitchen'];
            $working_time_data = db_get_list(db_prepare("SELECT * FROM $table_working_time WHERE store_id = %d", $_REQUEST['id']));
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }
    if ($item['status'] === null)
        $item['status'] = 1;

    $master_dropdown_data = db_get_list("SELECT * FROM $table_master_data");
    $data = [
        'item' => $item,
        'central_kitchen_old' => $central_kitchen_old,
        'list_areas' => filter_array($master_dropdown_data, 'type', 'area'),
        'list_districts' => filter_array($master_dropdown_data, 'type', 'district'),
        'list_outlets' => filter_array($master_dropdown_data, 'type', 'outlet'),
        'working_time_data' => $working_time_data
    ];

    $meta_box_title = !empty($_REQUEST['id']) ? 'Edit Store Location' : 'New Store Location';
    add_meta_box('location_form_meta_box', $meta_box_title, 'wpsl_form_meta_box_handler', 'location_form', 'normal', 'default');
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            STORE LOCATIONS
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=locations');?>">back to list</a>
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

        <form id="form-location" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
            <input type="hidden" name="working_time" id="working-time-data"/>
            <input type="hidden" name="central_kitchen_old" id="central-kitchen-old" value="<?php echo $central_kitchen_old ?>"/>
            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php do_meta_boxes('location_form', 'normal', $data); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}
function check_data_name_exist($needle, $haystack)
{
    foreach ($haystack as $key => $data) {
        if ($data['data_name'] == $needle)
            return true;
    }
    return false;
}
function show_time($time)
{
    if (!empty($time)) {
        $date = new DateTime("$time");
        return $date->format('H:i');
    }
    return '';
}
function wpsl_form_meta_box_handler($data) {
    $item = $data['item'];
    $central_kitchen_old = $data['central_kitchen_old'];
    $list_areas = $data['list_areas'];
    $list_districts = $data['list_districts'];
    $list_outlets = $data['list_outlets'];
    $working_time_data = $data['working_time_data'];
    ?>
    <div class="form-location">
        <table class="form-location-info" cellpadding="8">
            <tbody>
            <tr>
                <td>Store name:</td>
                <td>
                    <input id="store_name" name="store_name" type="text" value="<?php echo esc_attr($item['store_name'])?>" placeholder="Store name" required>
                </td>
            </tr>
            <tr>
                <td>Floor-unit:</td>
                <td>
                    <input id="floor_unit" name="floor_unit" type="text" value="<?php echo esc_attr($item['floor_unit'])?>" placeholder="Floor-unit">
                </td>
            </tr>
            <tr>
                <td>Number house:</td>
                <td>
                    <input id="number_house" name="number_house" type="text" value="<?php echo esc_attr($item['number_house'])?>" placeholder="Number house" required>
                </td>
            </tr>
            <tr>
                <td>Building:</td>
                <td>
                    <input id="building" name="building" type="text" value="<?php echo esc_attr($item['building'])?>" placeholder="Building">
                </td>
            </tr>
            <tr>
                <td>Street name:</td>
                <td>
                    <input id="street_name" name="street_name" type="text" value="<?php echo esc_attr($item['street_name'])?>" placeholder="Street Name" required>
                </td>
            </tr>
            <tr>
                <td>Postal Code :</td>
                <td>
                    <input id="zipcode" name="zipcode" type="text" value="<?php echo esc_attr($item['zipcode'])?>" placeholder="Zipcode" required>
                </td>
            </tr>
            <tr>
                <td>Area:</td>
                <td>
                    <select name="area" id="area">
                        <option value="">-- Select Area --</option>
                        <?php
                        $outlet_options = [''];
                        foreach ($list_areas as $key => $area):
                            $value = $area['data_name'];
                            $id = $area['id'];
                            ?>
                            <option value="<?php echo $id; ?>"
                                <?php echo $id === $item['area'] ? 'selected' : '' ?>
                            >
                                <?php echo esc_attr($value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>District:</td>
                <td>
                    <select name="district" id="district">
                        <option value="">-- Select District --</option>
                        <?php
                        $outlet_options = [''];
                        foreach ($list_districts as $key => $district):
                            $value = $district['data_name'];
                            $id = $district['id'];
                            ?>
                            <option value="<?php echo $id ?>"
                                <?php echo $id === $item['district'] ? 'selected' : '' ?>
                            >
                                <?php echo esc_attr($value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Outlet type:</td>
                <td>
                    <select name="outlet_type" id="outlet_type">
                        <option value="">-- Select Outlet --</option>
                        <?php
                        $outlet_options = [''];
                        foreach ($list_outlets as $key => $outlet):
                            $value = $outlet['data_name'];
                            $id = $outlet['id'];
                            ?>
                            <option value="<?php echo $id ?>"
                                <?php echo $id === $item['outlet_type'] ? 'selected' : '' ?>
                            >
                                <?php echo esc_attr($value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Longitude:</td>
                <td>
                    <input id="longitude" name="longitude" type="email" value="<?php echo esc_attr($item['longitude'])?>" placeholder="Longitude">
                </td>
            </tr>
            <tr>
                <td>Latitude:</td>
                <td>
                    <input id="latitude" name="latitude" type="email" value="<?php echo esc_attr($item['latitude'])?>" placeholder="Latitude">
                </td>
            </tr>
            <tr>
                <td>Email address:</td>
                <td>
                    <input id="email_address" name="email_address" type="email" value="<?php echo esc_attr($item['email_address'])?>" placeholder="Email address" required>
                </td>
            </tr>
            <tr>
                <td>Phone number:</td>
                <td>
                    <input id="phone_number" name="phone_number" type="text" value="<?php echo esc_attr($item['phone_number'])?>" placeholder="Contact number" required>
                </td>
            </tr>
            <tr>
                <td>Enable/Disable:</td>
                <td>
                    <input id="status" name="status" type="checkbox"
                        <?php echo !empty($item['status']) ? 'checked' : '' ?>
                        <?php echo !empty($central_kitchen_old) ? 'readonly="readonly" onclick="return false;" class="disabled"' : '' ?>>
                    <b>Enable</b>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input id="central_kitchen" name="central_kitchen" type="checkbox"
                        <?php echo !empty($item['central_kitchen']) ? ' checked ' : '' ?>
                        <?php echo !empty($central_kitchen_old) ? 'readonly="readonly" onclick="return false;" class="disabled"' : '' ?>>
                    Headquarter Office (Central kitchen)
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-working-time">
            <p>
                <b>Opening hours</b>:
            </p>
            <?php
            $days_of_week = [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ];
            ?>
            <table class="table-working-time">
                <thead>
                <tr>
                    <th width="20%"></th>
                    <th width="70%"></th>
                    <th width="10%"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($days_of_week as $key => $day):
                    $data = filter_array($working_time_data, 'working_day', $day);
                    ?>
                    <tr class="day-of-week day-<?php echo $day ?>" data-day="<?php echo $day ?>">
                        <td>
                            <input class="day-checkbox" type="checkbox" <?php echo !empty($data) ? 'checked' : '' ?>>
                            <span class="day-name"><?php echo $day ?></span>
                        </td>
                        <td class="opening-time">
                            <?php
                            if (count($data) == 0)
                                $data = [''];
                            foreach ($data as $key => $time):
                                ?>
                                <div class="sub-opening-time">
                                    <div class="from">
                                        From:
                                        <input class="timepicker input-start-time" type="text"
                                               value="<?php echo show_time($time['start_working_time']) ?>">
                                    </div>
                                    <div class="to">
                                        To:
                                        <input class="timepicker input-end-time" type="text"
                                               value="<?php echo show_time($time['end_working_time']) ?>">
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <!-- <span class="button-more-time">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z"/>
                                </svg>
                            </span> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <div class="store-attachment">
                <div>
                    <?php
                    $links = wp_get_attachment_image_src($item['image_id'], 'full');
                    ?>
                    <div class="store-attachment-preview <?php echo !empty($links[0]) ? 'show' : '' ?>">
                        Current:
                        <a class="filename" href="<?php echo $links[0]; ?>" target="_blank">
                            <?php echo get_link_file($links[0]); ?>
                        </a>
                        <span class="text-red button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                    </div>
                    Choose images:
                    <input type="hidden" class="file input-image" name="image_id" value="<?php echo $item['image_id'] ?>">
                    <label class="icon-file" for="input-image">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-cloud-arrow-up-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 1 0V6.707l1.146 1.147a.5.5 0 0 0 .708-.708z"/>
                        </svg>
                    </label>
                </div>
                <div>
                    <?php
                    $url = wp_get_attachment_url($item['file_id']);
                    ?>
                    <div class="store-attachment-preview <?php echo !empty($url) ? 'show' : '' ?>">
                        Current:
                        <a href="<?php echo $url; ?>" target="_blank" download>
                            <?php echo get_link_file($url); ?>
                        </a>
                        <span class="text-red button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                    </div>
                    Choose file:
                    <input type="hidden" class="file input-file" name="file_id" value="<?php echo $item['file_id'] ?>">
                    <label class="icon-file" for="input-file">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-cloud-arrow-up-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 1 0V6.707l1.146 1.147a.5.5 0 0 0 .708-.708z"/>
                        </svg>
                    </label>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <button type="button" class="button-primary button-save-location">Save Location</button>
    <?php
}
