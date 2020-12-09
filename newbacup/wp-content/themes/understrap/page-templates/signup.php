<?php
/**
 * Template Name: SignUp
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$sdls_signup_form_image = wp_get_attachment_image_src(get_option('sdls_signup_form_image'), 'full');
$styleBg = $sdls_signup_form_image ? 'background-image:url('.$sdls_signup_form_image[0].')' : '';

get_header();
?>

<div class="signupPage wrapper">
	<div class="signupBackground" style="<?php echo $styleBg; ?>"></div>
	<div class="container">
		<div class="signupPageInner">
			<div class="signupPageContent">
				<div class="titleBLock">
					<h1 class="cdl-heading">Sign Up</h1>
					<div class="desc">
						Save your favourite items, checkout faster and get the latest news and deals sent straight to you!
					</div>
				</div>
				<div class="socialSignup">
					<div class="desc">
						Sign up via social media:
					</div>
					<div class="btnGroups mb-4 mt-2">
					    <!-- <button class="btnSocialLogin btnGoogle" type="button">Google</button>
						<button class="btnSocialLogin btnFacebook" type="button">Facebook</button> -->
						<?php echo do_shortcode('[woocommerce_social_login_buttons]');?>
					</div>
					<div class="desc2">
						Or sign up with your particulars:
					</div>
				</div>
				<div class="contentPage">
					<?php
					while ( have_posts() ) {
						the_post();
						the_content();
					}
					?>
				</div>
			</div>
	</div>
	</div>

</div>


<?php
get_footer();
