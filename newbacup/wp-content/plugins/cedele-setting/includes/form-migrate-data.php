<?php

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function create_row_wc_customer($data)
{
    global $wpdb;
    $email = isset($data[2]) ? strtolower($data[2]) : '';
    $username = is_array(explode('@', $email)) ? explode('@', $email)[0] : '';
    $username = strtolower($username);
    $new_random_password = randomPassword();
    if ($email && $username) {
        $user = get_user_by('login',$username);
        $user_login = get_user_by('login',$email);
        $user_email = get_user_by('email',$email);
        if(empty($user) && empty($user_login) && empty($user_email)){
            $sendEmail = false;
            $user_id = wc_create_new_customer($email, $username, $new_random_password, [], $sendEmail, $data[13]);
            $userInsert = get_user_by('email',$email);
            $datas = array(
              'user_id' =>$user_id,
              'username' =>$userInsert->user_login,
              'first_name' =>$data[0],
              'last_name' =>$data[1],
              'email' =>$email,
              'date_last_active' =>$userInsert->user_registered,
              'date_registered' =>$userInsert->user_registered,
              'country' =>$data[11],
              'postcode' =>$data[12],
              'city' =>$data[9],
              'state' =>$data[10]
            );
            $wpdb->insert( 'wp_wc_customer_lookup', $datas);
            if ($user_id && is_numeric($user_id)) {
                update_user_meta( $user_id, "first_name", $data[0]);
                update_user_meta( $user_id, "last_name", $data[1]);
                update_user_meta( $user_id, "billing_first_name", $data[0]);
                update_user_meta( $user_id, "billing_last_name", $data[1]);
                update_user_meta( $user_id, "billing_company", '');
                update_user_meta( $user_id, "billing_email", $data[2]);
                update_user_meta( $user_id, "billing_address_1", $data[7]);
                update_user_meta( $user_id, "billing_address_2", $data[8]);
                update_user_meta( $user_id, "billing_city", $data[9]);
                update_user_meta( $user_id, "billing_postcode", $data[12]);
                update_user_meta( $user_id, "billing_country", $data[11]);
                update_user_meta( $user_id, "billing_state", $data[10]);
                update_user_meta( $user_id, "billing_phone", $data[3]);
                update_user_meta( $user_id, "shipping_first_name", '' );
                update_user_meta( $user_id, "shipping_last_name", '');
                update_user_meta( $user_id, "shipping_company", '' );
                update_user_meta( $user_id, "shipping_address_1", '');
                update_user_meta( $user_id, "shipping_address_2", '');
                update_user_meta( $user_id, "shipping_city", '');
                update_user_meta( $user_id, "shipping_postcode", '');
                update_user_meta( $user_id, "shipping_country", '');
                update_user_meta( $user_id, "shipping_state", '');
                // generate random pasword
                update_user_meta( $user_id, "new_random_password", $new_random_password);
                updateMemberInfo($user_id, $data);
            }
        }
    }
}

function read_and_create_excel_file($file_name, $start_from = 1, $limit = 500)
{
    global $wpdb;
    require_once get_template_directory() . '/inc/PHPExcel.php';
    $fileObj = \PHPExcel_IOFactory::load( $file_name );
    $sheetObj = $fileObj->getActiveSheet();
    $sheetData = $sheetObj->toArray(null,true,true,true);
    $total_rows = $sheetObj->getHighestRow();
    $total_columns = $sheetObj->getHighestColumn();
    $list_datas = [];
    foreach( $sheetObj->getRowIterator($start_from, $limit) as $row ){
        $datas = [];
        foreach( $row->getCellIterator() as $cell ){
            $cellValue = $cell->getValue();
            // ------------
            // not normal
            if ('E' > $total_columns) {
                //$rowDataExplode = explode("\"", $cellValue);
                $rowDataExplode =  explode("	", $cellValue);
                $newRowData = [];
                for ($i = 0; $i < count($rowDataExplode); $i++) {
                    if (trim($rowDataExplode[$i])) {
                        $str= trim($rowDataExplode[$i]) == 'N/A' ? '' : trim($rowDataExplode[$i]);
                        $newRowData[] = str_replace('"','',$str);
                    }
                }
                $datas = $newRowData;
            } else {
                if ($cellValue == 'N/A') {
                    $cellValue = '';
                }
                $datas[] = $cellValue;
            }
        }
        // Normal
        if (is_numeric($datas[4])) {
            $datas[4] = date('m-d-Y', $datas[4]);
        }
        if($datas[0]=='') break;
        $list_datas[] = $datas;
    }
    foreach ($list_datas as $key => $data) {
        create_row_wc_customer($data);
    }
    $wpdb->query("DELETE FROM wp_options WHERE option_name LIKE '%_transient_wc_report_customers_%'");
    $is_remain = 1;
    if ($total_rows < ($start_from + $limit)) {
        $is_remain = 0;
    }

    return [
        'total_rows' => $total_rows,
        'readable_rows' => count($list_datas),
        'is_remain' => $is_remain,
        'start' => $list_datas[0],
    ];
}

function cdls_form_migrate_data() {
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
        <form class="cdls-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <img id="img-load" src="<?php echo admin_url()?>images/loading_1.gif" style="width: 80px; display: none;"/>
            <button type="button" class="button-primary cdls-import-file">Import file</button>
        </form>
    </div>
<?php
}