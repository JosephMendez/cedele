<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$bannerId = get_option('sdls_login_image');
$banner = $bannerId ? wp_get_attachment_url($bannerId) : '';

$buy = get_query_var('buy-membership');
if (!session_id()) {
    session_start();
}
if($buy === 'true') {
    $_SESSION['buy_membership'] = true;
}
?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set row" id="customer_login">

	<div class="u-column1 col-12 col-sm-8 offset-sm-2 col-lg-5 offset-lg-0">

<?php endif; ?>

		<h1 class="cdl-heading"><?php esc_html_e( 'Login', 'understrap' ); ?></h1>

		<p class="font-body">Have an account? Login to manage your orders, check rebates and redeem promotions.</p>
		<form class="woocommerce-form woocommerce-form-login login" method="post">
			<?php do_action( 'woocommerce_login_form_start' ); ?>
			<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
			<p class="font-body font-weight-semibold mb-2"><?php esc_html_e( 'Enter your login details:', 'understrap' ); ?></p>
			<div class="form-group">
				<input placeholder="<?php esc_html_e( 'Email address or phone number', 'understrap' ); ?>" type="text" class="woocommerce-Input woocommerce-Input--text form-control form-control-lg" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</div>
			<div class="form-group">
				<input placeholder="<?php esc_html_e( 'Password', 'understrap' ); ?>" class="woocommerce-Input woocommerce-Input--text form-control form-control-lg" type="password" name="password" id="password" autocomplete="current-password" />
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>
			<?php
			$remember_me_enabled = get_option( 'user_registration_login_options_remember_me', 'yes' );
			if ( 'yes' === $remember_me_enabled ) {
				?>
				<label class="user-registration-form__label user-registration-form__label-for-checkbox inline">
					<input class="user-registration-form__input user-registration-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php echo esc_html( get_option( 'user_registration_label_remember_me', __( 'Remember me', 'user-registration' ) ) ); ?></span>
				</label>
				<?php
			}
			?>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<button type="submit" class="btn btn-primary btn-shadow btn-login btn-lg w-100" name="login" value="<?php esc_attr_e( 'Login', 'understrap' ); ?>">
				<?php esc_html_e( 'Login', 'understrap' ); ?>
				<svg id="reset-filter" class="icon icon-Arrow">
					<use xlink:href="<?php echo get_stylesheet_directory_uri()?>/assets/symbol/sprite.svg#Arrow"></use>
				</svg>
			</button>

			<p class="woocommerce-LostPassword lost_password my-4">
				<a class="font-body font-weight-semibold" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'understrap' ); ?></a>
			</p>

			<p class="font-body font-weight-semibold mb-3"><?php esc_html_e( 'Log in via social media:', 'understrap' ); ?></p>

			<!-- <div class="mb-4 mt-2 group-btn">
				<button class="btn btn-shadow btn-social login-google font-body font-weight-semibold">Google</button>
				<button class="btn btn-shadow btn-social login-facebook font-body font-weight-semibold">Facebook</button>
			</div> -->
			<div class="mb-4 mt-2 group-btn">
				<?php echo do_shortcode('[woocommerce_social_login_buttons]');?>
			</div>

			<a href="<?php echo get_permalink( get_page_by_path( 'signup' ) ); ?>" class="mt-2 btn btn-outline-primary btn-signup w-100"><?php esc_html_e( 'Don\'t have an account?', 'understrap' ); ?> <span><?php esc_html_e('SIGN UP', 'understrap' ); ?></span></a>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>


	</div>

	<div class="u-column2 col-md-6">

		<h2><?php esc_html_e( 'Register', 'understrap' ); ?></h2>

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'understrap' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'understrap' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'understrap' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
				</p>

				<?php else : ?>

				<p><?php esc_html_e( 'A password will be sent to your email address.', 'understrap' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="woocommerce-FormRow form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="btn btn-outline-primary" name="register" value="<?php esc_attr_e( 'Register', 'understrap' ); ?>"><?php esc_html_e( 'Register', 'understrap' ); ?></button>
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>
<script type="text/javascript">
	jQuery('#full-width-page-wrapper').prepend('<div class="login-banner" style="background-image: url(<?php echo $banner; ?>)"></div>');
    		if (jQuery('.woocommerce-error').length){
    			jQuery('#username').addClass('is-invalid');
    			jQuery('#password').addClass('is-invalid');
    		}
</script>
<?php
do_action( 'woocommerce_after_customer_login_form' );
