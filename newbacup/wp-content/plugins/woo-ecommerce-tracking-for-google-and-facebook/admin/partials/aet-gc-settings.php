<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
$submit_text      = __( 'Save changes', 'advance-ecommerce-tracking' );
$track_setting    = filter_input( INPUT_POST, 'track_setting', FILTER_SANITIZE_STRING );
$aet_admin_object = new Advance_Ecommerce_Tracking_Admin( '', '' );
if ( isset( $track_setting ) ) {
	$post_wpnonce         = filter_input( INPUT_POST, 'aet_gc_conditions_save', FILTER_SANITIZE_STRING );
	$post_retrieved_nonce = isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '';
	if ( ! wp_verify_nonce( $post_retrieved_nonce, 'aet_gc_save_action' ) ) {
		die( 'Failed security check' );
	} else {
		$post_data = $_POST;
		$aet_admin_object->aet_save_settings( $post_data );
	}
}
$aet_gc_tracking_settings = $aet_admin_object->aet_ad_get_setting_option( 'gc' );
$gc_enable                = empty( $aet_gc_tracking_settings->gc_enable ) ? '' : $aet_gc_tracking_settings->gc_enable;
$gc_id                    = empty( $aet_gc_tracking_settings->gc_id ) ? '' : $aet_gc_tracking_settings->gc_id;
$gc_label                 = empty( $aet_gc_tracking_settings->gc_label ) ? '' : $aet_gc_tracking_settings->gc_label;
?>
	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<h2><?php esc_html_e( 'Google Conversion Configuration', 'advance-ecommerce-tracking' ); ?></h2>
			<div class="table-outer" id="table_outer_data">
				<form method="POST" name="aetfrm" action="">
					<?php wp_nonce_field( 'aet_gc_save_action', 'aet_gc_conditions_save' ); ?>
					<input type="hidden" name="track_save" id="track_save" value="google_conversion"/>
					<input type="hidden" name="track_type" id="track_type" value="gc"/>
					<div class="general_setting" id="general_setting">
						<table class="form-table table-outer">
							<tbody>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="onoffswitch"><?php esc_html_e( 'Enable Google Conversion', 'advance-ecommerce-tracking' ); ?></label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="gc_enable" id="gc_enable" value="on" <?php checked( $gc_enable, 'on' ); ?>/>
									<span class="advance_ecommerce_tracking_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'If you want to get data to google conversion then enable this option.', 'advance-ecommerce-tracking' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="onoffswitch"><?php esc_html_e( 'Google Conversion ID', 'advance-ecommerce-tracking' ); ?></label>
								</th>
								<td class="forminp">
									<input type="text" name="gc_id" id="gc_id" value="<?php echo esc_attr( $gc_id ); ?>"/>
									<span class="advance_ecommerce_tracking_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Enter google conversion ID.', 'advance-ecommerce-tracking' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="reconnect_to_wizard"><?php esc_html_e( 'Google Conversion Label ', 'advance-ecommerce-tracking' ); ?></label>
								</th>
								<td class="forminp">
									<input type="text" name="gc_label" id="gc_label" value="<?php echo esc_attr( $gc_label ); ?>"/>
									<span class="advance_ecommerce_tracking_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Enter google label.', 'advance-ecommerce-tracking' ); ?>
									</p>
								</td>
							</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" name="track_setting" class="button button-primary button-large" value="<?php echo esc_attr( $submit_text ); ?>">
						</p>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' );