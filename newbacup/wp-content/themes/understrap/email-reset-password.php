<?php
//@hook: fire when user reset password.
add_filter( 'wp_password_change_notification_email', function ($wp_password_change_notification_email, $user, $blogname) {
	$email = $wp_password_change_notification_email['to'];
	send_email_success($email, $user->user_login);
}, 10, 3 );

// @hook: fire when user update password.
add_filter( 'password_change_email', function ( $pass_change_email, $user, $userdata ) {
	send_email_success($pass_change_email["to"], $user['user_login']);
}, 10, 3 );

function send_email_success($email, $display_name) {
	ob_start();
	$display_name;
	$time_date = new DateTime();
	require_once get_template_directory() . '/woocommerce/emails/customer-changed-password.php';
	$message = ob_get_contents();
	ob_end_clean();

	$email_subject = 'Your password has been updated';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail($email, $email_subject, $message, $headers);
}
