<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );

$forgotpw_form_image = wp_get_attachment_image_src(get_option('sdls_forgotpw_form_image'), 'full');
$styleBg = $forgotpw_form_image ? 'background-image:url('.$forgotpw_form_image[0].')' : '';
?>

<div class="lostPasswordRequest">


	<div class="backToLogin">
		<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>">
			<i class="fa fa-long-arrow-left"></i> Back
		</a>
	</div>
	<form method="post" class="woocommerce-ResetPassword lost_reset_password">


		<div class="titleBlock">
			<h1 class="cdl-heading">Reset your password</h1>
		</div>
		<div class="formField">
			<label for="user_login"><?php esc_html_e( 'Enter your email and we’ll send a password reset link.', 'understrap' ); ?></label>
			<input class="woocommerce-Input woocommerce-Input--text input-text form-control" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="Email address" />
		</div>

		<div class="clear"></div>

		<?php do_action( 'woocommerce_lostpassword_form' ); ?>

		<div class="formField fieldSubmit">
			<input type="hidden" name="wc_reset_password" value="true" />
			<button type="submit" class="btn btn-submit" value="<?php esc_attr_e( 'Reset password', 'understrap' ); ?>"><?php esc_html_e( 'Reset password', 'understrap' ); ?></button>
		</div>

		<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

	</form>
</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#full-width-page-wrapper').prepend('<div class="login-banner-lostpassword" style="<?php echo $styleBg; ?>"></div>');
			$('#full-width-page-wrapper').addClass('lostpassword');
			$('header.entry-header').remove();
		});
	</script>

<?php
do_action( 'woocommerce_after_lost_password_form' );
