<?php

function send_email($data)
{
    $email = isset($data[2]) ? strtolower($data[2]) : '';
    if ($email) {
        $datas = array(
            'first_name' =>$data[0],
            'last_name' =>$data[1],
            'email' =>$email,
        );
        ob_start();
        require(get_template_directory() . '/woocommerce/emails/email-sorry-for-user.php');
        $message = ob_get_contents();
        ob_end_clean();
        $email_subject = 'Our apologies for the confusion';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $result = wp_mail($email, $email_subject, $message, $headers);
        return true;
    }
    return true;
}

function read_excel_file($file_name, $start_from = 1, $limit = 500)
{
    require_once get_template_directory() . '/inc/PHPExcel.php';
    $fileObj = \PHPExcel_IOFactory::load( $file_name );
    $sheetObj = $fileObj->getActiveSheet();
    $total_rows = $sheetObj->getHighestRow();
    $total_columns = $sheetObj->getHighestColumn();
    $list_datas = [];
    foreach( $sheetObj->getRowIterator($start_from, $limit) as $row ){
        $datas = [];
        foreach( $row->getCellIterator() as $cell ){
            $cellValue = $cell->getValue();
            if ('E' > $total_columns) {
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
        if($datas[0]=='') break;
        $list_datas[] = $datas;
    }
    foreach ($list_datas as $key => $data) {
       send_email($data);
    }

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

function cdls_form_send_email() {
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
            <button type="button" class="button-primary cdls-import-file" data="send-email">Import file</button>
        </form>
    </div>
<?php
}