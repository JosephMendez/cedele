<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
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

defined('ABSPATH') || exit;

do_action('woocommerce_before_reset_password_form');
?>

<div class="lostPasswordRequest">

	<form method="post" class="woocommerce-ResetPassword lost_reset_password">

		<div class="titleBlock">
			<h3 class="title">New Password</h3>
		</div>

		<p><?php echo apply_filters('woocommerce_reset_password_message', esc_html__('Please enter a password that you haven\'t already used before.', 'understrap')); ?></p><?php // @codingStandardsIgnoreLine ?>

		<div class="formField">
			<label for="password_1"><?php esc_html_e('New password', 'understrap'); ?>&nbsp;<span
					class="required">*</span></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1"
				   id="password_1" autocomplete="new-password" placeholder="" />
		</div>
		<div class="formField">
			<label for="password_2"><?php esc_html_e('Confirm New Password', 'understrap'); ?>&nbsp;<span
					class="required">*</span></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2"
				   id="password_2" autocomplete="new-password" placeholder=""/>
		</div>

		<input type="hidden" name="reset_key" value="<?php echo esc_attr($args['key']); ?>"/>
		<input type="hidden" name="reset_login" value="<?php echo esc_attr($args['login']); ?>"/>

		<div class="clear"></div>

		<?php do_action('woocommerce_resetpassword_form'); ?>

		<div class="formField fieldSubmit">
			<input type="hidden" name="wc_reset_password" value="true"/>
			<button type="submit" class="btn btn-submit"
					value="<?php esc_attr_e('CHANGE', 'understrap'); ?>"><?php esc_html_e('Change', 'understrap'); ?></button>
		</div>

		<?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>

	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('#full-width-page-wrapper').prepend('<div class="login-banner-lostpassword" style="<?php echo $styleBg; ?>"></div>');
		$('#full-width-page-wrapper').addClass('lostpassword');
		$('header.entry-header').remove();
	});
</script>
<?php
do_action('woocommerce_after_reset_password_form');
