<?php
$logoImg = get_template_directory_uri().'/images/logoLoginCart.png';

$sdls_login_form_image = wp_get_attachment_image_src(get_option('sdls_login_form_image'), 'full');
$styleBg = $sdls_login_form_image ? 'background-image:url('.$sdls_login_form_image[0].')' : '';
?>
<div id="ajaxLoginModal" class="modal ajaxLoginModal cartFormModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">

		<div class="modal-content">
			<button type="button" class="closeModal" data-dismiss="modal" aria-label="Close">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M19 1L1 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M1 1L19 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<div class="popup-form">
				<div class="formModal ajaxLoginForm">


					<div class="colLeft colSmall" style="<?php echo $styleBg; ?>; min-height: 450px;">
						<div class="colInner">
							<div class="logoField">
								<img src="<?php echo $logoImg; ?>" alt="" />
							</div>
							<div class="btnGroups">
								<a href="<?php echo get_permalink( get_page_by_path( 'signup' ) ); ?>" class="btnWhiteLarge">Sign up</a>
								<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btnWhiteLarge" >Continue as guest</a>
							</div>
						</div>
					</div>


					<div class="colRight colLarge">
						<div class="colInner">
							<form id="ajaxLogin" class="ajaxLogin" action="#" method="post">
								<div class="titleBlock">
									<h3 class="title">Have an account?</h3>
									<div class="desc">Log in to access your account information, vouchers and earn rebates if you’re a Cedele Rewards member.</div>
								</div>
								<div class="form-fields">
									<div class="login-response-msg"></div>
									<div class="field">
	<!--									<div class="field-label">--><?php //echo esc_html__('Username', 'template2020'); ?><!--</div>-->
										<div class="field-value">
											<input id="username" placeholder="Email address or phone number"
												   class="txt_text"
												   type="text" name="username">
										</div>
										<div class="error"></div>
									</div>
									<div class="field">
	<!--									<div class="field-label">--><?php //echo esc_html__('Password', 'template2020'); ?><!--</div>-->
										<div class="field-value">
											<input id="password" placeholder="Password"
												   class="txt_text"
												   type="password" name="password">
										</div>
										<div class="error"></div>
									</div>
									<div class="field fieldSubmit">
										<button id="btnLoginSubmit" class="btn-submit" type="submit" name="submit">
											<?php echo esc_html__('Login', 'template2020'); ?>
											<i class="fa fa-long-arrow-right"></i>
										</button>
									</div>
									<div class="field lostPassword">
										<a href="<?php echo wp_lostpassword_url(); ?>">
											<?php esc_html_e('Forgot your password?', 'template2020'); ?>
										</a>
									</div>

								</div>

								<?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
								<div class="socialLogin">
									<div class="socialLoginDesc">Login via social media:</div>
									<!-- <div class="row">
										<div class="col-md-6 col-12">
											<button class="btnSocialLogin btnGoogle"
											type="button">Google</button>
										</div>
										<div class="col-md-6 col-12">
											<button class="btnSocialLogin btnFacebook"
													type="button">Facebook</button>
										</div>
									</div> -->
									<div class="text-center"><?php echo do_shortcode('[woocommerce_social_login_buttons]');?></div>
								</div>
							</form>
						</div>
					</div>


				</div>


			</div>
		</div>
	</div>
</div>









