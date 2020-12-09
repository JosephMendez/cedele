<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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




do_action('woocommerce_before_edit_account_form'); ?>

<?php
//	include "../../edenred-system-user.php";
	$member = getMemberInfo($user->id);
	$point = getPointMember($user->id);
	$user_meta = get_user_meta($user->id);
	$user_registration_user_birthday = get_user_meta($user->id, 'user_registration_user_birthday', true);
	$user_registration_user_gender = get_user_meta($user->id, 'user_registration_user_gender', true);
?>

	<form class="woocommerce-EditAccountForm edit-account" action=""
		  method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?> >

		<?php do_action('woocommerce_edit_account_form_start'); ?>

		<?php

		$colorBackground = sprintf("#%02x%02x%02x", rand(0, 255), rand(0, 255), rand(0, 255));

		$firstLetter = substr($user->first_name, 0, 1);

		$gavar_url = get_avatar_url($user_email);
		$avatar = '';
		if (strpos($gavar_url, 'gravatar.com')) {
			$avatar = '<div class="bgGAvatar" style="background: ' . $colorBackground . ';">' . $firstLetter . '</div>';
		} else {
			$avatar = get_avatar($user->user_email, 160, $default = '');
		}

		?>

		<div class="userAvatar">
			<div class="img">
				<?php echo $avatar; ?>
			</div>
			<div class="userDetaiil">
				<div class="userMeta">
					<h3 class="userName"><?php echo $user->first_name.' '.$user->last_name; ?></h3>
					<div class="desc"><?php echo $user->user_email; ?></div>
					<?php
						if ($member && $member->member_tier_name) {
							echo '<div class="memberEdenred">
									<img src="'.get_site_url() .  '/wp-content/themes/understrap/images/Gold.svg" />'.
									$member->member_tier_name.
									'</div>';
						}

						if ($point) {
							$_p = $point->point_balance ? $point->point_balance : 0 ;
							echo '<div class="pointEdenred">
									<img src="'.get_site_url() .  '/wp-content/themes/understrap/images/circle-star.svg" />'.
									$point->point_balance.
									'</div>';
						}
					?>
				</div>

			</div>

		</div>

		<div class="editAccount">


			<div class="formFields">
				<h3 class="titleBox">Account Information</h3>
				<div class="row">

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="account_first_name"><?php esc_html_e('First Name', 'understrap'); ?>&nbsp;<span
									class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
								   name="account_first_name" id="account_first_name" autocomplete="given-name"
								   value="<?php echo esc_attr($user->first_name); ?>"/>
						</div>
					</div>

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="account_last_name"><?php esc_html_e('Last Name', 'understrap'); ?>&nbsp;<span
									class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
								   name="account_last_name" id="account_last_name" autocomplete="family-name"
								   value="<?php echo esc_attr($user->last_name); ?>"/>
						</div>
					</div>

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="account_first_name"><?php esc_html_e('Mobile Number', 'understrap'); ?></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
								   name="user_registration_phone_number" id="user_registration_phone_number"
								   autocomplete="given-name"
								   value="<?php echo esc_attr($user->user_registration_phone_number); ?>"/>
						</div>
					</div>

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="account_email"><?php esc_html_e('Email Address', 'understrap'); ?>&nbsp;<span
									class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
								   name="account_email" id="account_email" autocomplete="email"
								   value="<?php echo esc_attr($user->user_email); ?>" disabled />
						</div>
					</div>

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="user_registration_user_birthday"><?php esc_html_e('Birthday', 'understrap'); ?></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text datetimepicker"
								   name="user_registration_user_birthday" id="user_registration_user_birthday"
								   autocomplete="off"
								   value="<?php echo esc_attr($user_registration_user_birthday); ?>"/>
						</div>
					</div>

					<div class="col-md-6 col-12">
						<div class="field">
							<label for="user_registration_user_gender"><?php esc_html_e('Gender', 'understrap'); ?></label>
							<select class="txt_select" name="user_registration_user_gender" id="user_registration_user_gender">
								<option
									value="Male" <?php echo $user_registration_user_gender == "Male" ? 'selected' : ''; ?>>
									Male
								</option>
								<option
									value="Female" <?php echo $user_registration_user_gender == "Female" ? 'selected' : ''; ?>>
									Female
								</option>
							</select>
						</div>
					</div>

					<!--			<div class="col-md-6 col-12">-->
					<!--				<div class="field">-->
					<!--					<label for="account_display_name">-->
					<?php //esc_html_e( 'Display name', 'understrap' ); ?><!--&nbsp;<span class="required">*</span></label>-->
					<!--					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="-->
					<?php //echo esc_attr( $user->display_name ); ?><!--" /> <span><em>-->
					<?php //esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'understrap' ); ?><!--</em></span>-->
					<!--				</div>-->
					<!--			</div>-->


					<div class="col-md-6 col-12">

					</div>

					<div class="col-md-6 col-12">

					</div>

				</div>


			</div>


			<div class="formFields formFieldsPassword">
				<h3 class="titleBox"><?php esc_html_e('Change Password', 'understrap'); ?></h3>

				<div class="row">
					<div class="col-12 col-lg-9">
						<div class="field">
							<label for="password_current"><?php esc_html_e('Current Password', 'understrap'); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_current" id="password_current" autocomplete="off"/>
						</div>
						<div class="field">
							<label for="password_1"><?php esc_html_e('New Password', 'understrap'); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_1" id="password_1" autocomplete="off"/>
						</div>
						<div class="field">
							<label for="password_2"><?php esc_html_e('Confirm password', 'understrap'); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
								   name="password_2" id="password_2" autocomplete="off"/>
						</div>
					</div>

				</div>

			</div>

			<?php do_action('woocommerce_edit_account_form'); ?>

			<div class="fieldBtnSubmit">
				<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
				<button type="submit" class="btn btn-outline-primary" name="save_account_details"
						value="<?php esc_attr_e('Save changes', 'understrap'); ?>"><?php esc_html_e('Save changes', 'understrap'); ?></button>
				<input type="hidden" name="action" value="save_account_details"/>
			</div>

		</div>

		<?php do_action('woocommerce_edit_account_form_end'); ?>
	</form>

<?php
do_action('woocommerce_after_edit_account_form');
