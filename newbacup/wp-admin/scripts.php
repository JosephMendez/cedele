<?php
require_once __DIR__ . '/admin.php';
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$table_name = $wpdb->prefix . 'users';
$time = isset($_GET['time']) ? $_GET['time'] : 1;
$from = isset($_GET['from']) ? $_GET['from'] : 1;
$to = isset($_GET['to']) ? $_GET['to'] : 1;
$is_change_pass = isset($_GET['is_change_pass']) ? $_GET['is_change_pass'] : 0;
$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id >= $from AND id <= $to AND is_change_pass = $is_change_pass", ARRAY_A);

foreach ($result as $row) {
    $password = generateRandomString(8);
    $hash = wp_hash_password($password);
    $wpdb->update(
        $table_name,
        array(
            'user_pass' => $hash,
            'user_activation_key' => '',
            'is_change_pass' => 1,
        ),
        array('ID' => $row['ID'])
    );
    $user_firstname = get_user_meta($row['ID'], 'first_name', true );
    $result = send_email_change_password($row, $password, $user_firstname);
    sleep($time);
}
echo 'done';
die;
function send_email_change_password($users, $newpass = '', $user_firstname)
{
    ob_start();
    require(get_template_directory() . '/woocommerce/emails/changed-password-for-user.php');
    $message = ob_get_contents();
    ob_end_clean();
    $email_subject = 'Introducing the new CEDELEMARKET.COM';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $result = wp_mail($users['user_email'], $email_subject, $message, $headers);
    return true;
}
