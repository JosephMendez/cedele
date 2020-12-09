<div class="xoo-ml-form-placeholder">
	<form class="xoo-ml-otp-form">

		<div class="xoo-ml-otp-sent-txt">
			<span class="xoo-ml-otp-no-txt"></span>
			<span class="xoo-ml-otp-no-change"> <?php _e( "Change", 'mobile-login-woocommerce' ); ?></span>
		</div>

		<div class="xoo-ml-otp-notice-cont">
			<div class="xoo-ml-notice"></div>
		</div>

		<div class="xoo-ml-otp-input-cont">
			<?php for ( $i= 0; $i < $otp_length; $i++ ): ?>
				<input type="text" maxlength="1" autocomplete="off" name="xoo-ml-otp[]" class="xoo-ml-otp-input">
			<?php endfor; ?>
		</div>

		<input type="hidden" name="xoo-ml-otp-phone-no" >
		<input type="hidden" name="xoo-ml-otp-phone-code" >

		<button type="submit" class="button btn xoo-ml-otp-verify-btn"><?php _e( 'Verify', 'mobile-login-woocommerce' ); ?> </button>

		<div class="xoo-ml-otp-resend">
			<a class="xoo-ml-otp-resend-link"><?php _e( 'Not received your code? Resend code', 'mobile-login-woocommerce' ); ?></a>
			<span class="xoo-ml-otp-resend-timer"></span>
		</div>

		<input type="hidden" name="xoo-ml-form-token" value="">

	</form>

</div>