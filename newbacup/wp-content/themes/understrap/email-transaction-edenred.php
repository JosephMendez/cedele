<?php
function email_transaction_edenred_service($email, $subject_mail, $body, $respond) {
	ob_start();
	$email;
	$subject_mail;
	$respond;
	$body;
	$time_date = new DateTime();
	require_once get_template_directory() . '/woocommerce/emails/customer-log-edenred.php';
	$message = ob_get_contents();
	ob_end_clean();

	$email_subject = $subject_mail;
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail(EMAIL_ADMIN, $email_subject, $message, $headers);
}
