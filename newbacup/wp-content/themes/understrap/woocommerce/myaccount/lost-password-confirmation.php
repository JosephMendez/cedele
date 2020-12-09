<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;


?>

<div class="lostPasswordRequest">
<?php do_action( 'woocommerce_before_lost_password_confirmation_message' );
wc_print_notice( esc_html__( 'Password reset email has been sent.', 'woocommerce' ) );
?>

<p><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'We have sent you an email with instructions on how to reset your password. Please check your inbox in a few minutes and click on the link provided. Have a great day!', 'woocommerce' ) ) ); ?></p>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#full-width-page-wrapper').prepend('<div class="login-banner-lostpassword" style="<?php echo $styleBg; ?>"></div>');
		$('#full-width-page-wrapper').addClass('lostpassword');
		$('header.entry-header').remove();
	});
</script>
