<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<?php
			$aft_option      = $this->aet_ad_get_setting_option( 'ft' );
			$fb_custom_event = empty( $aft_option->fb_custom_event ) ? '' : $aft_option->fb_custom_event;
			if ( 'on' === $fb_custom_event ) {
				$get_action            = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
				$get_id                = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
				$get_wpnonce           = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
				$btn_post              = filter_input( INPUT_POST, 'custom_event_setting', FILTER_SANITIZE_STRING );
				$aet_admin_object      = new Advance_Ecommerce_Tracking_Admin( '', '' );
				$submit_text           = esc_html__( 'Submit', 'advance-ecommerce-tracking' );
				$custom_param_btn_text = esc_html__( 'Add Custom Parameter', 'advance-ecommerce-tracking' );
				if ( isset( $btn_post ) ) {
					$post_wpnonce         = filter_input( INPUT_POST, 'aet_cft_conditions_save', FILTER_SANITIZE_STRING );
					$post_retrieved_nonce = isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '';
					if ( ! wp_verify_nonce( $post_retrieved_nonce, 'aet_cft_save_action' ) ) {
						die( 'Failed security check' );
					} else {
						$post_data = $_POST;
						$aet_admin_object->aet_save_custom_event_settings__premium_only( $post_data, 'cft' );
					}
				}
				$get_retrieved_nonce = isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '';
				$aftnonce            = wp_create_nonce( 'aftnonce' );
				$admin_object        = new Advance_Ecommerce_Tracking_Admin( '', '' );
				$back_to_event_list  = $admin_object->aet_pages_url( 'aet-cft-list', '', '', '', true );
				$add_event_url       = $admin_object->aet_pages_url( 'aet-cft-list', '', 'add', '', true );
				$get_all_ev          = $admin_object::aet_get_custom_event_list( $get_id, 'aet_cft' );
				$get_ft_event_list   = $admin_object::aet_ft_default_custom_event_list__premium_only();
				if ( 'edit' === $get_action ) {
					if ( ! wp_verify_nonce( $get_retrieved_nonce, 'aftnonce' ) ) {
						die( 'Failed security check' );
					} else {
						$selector_type         = get_post_meta( $get_all_ev->ID, 'selector_type', true );
						$selector_attr         = get_post_meta( $get_all_ev->ID, 'selector_attr', true );
						$event_type            = get_post_meta( $get_all_ev->ID, 'event_type', true );
						$event_name            = get_post_meta( $get_all_ev->ID, 'event_name', true );
						$content_id            = get_post_meta( $get_all_ev->ID, 'content_id', true );
						$content_type          = get_post_meta( $get_all_ev->ID, 'content_type', true );
						$contents              = get_post_meta( $get_all_ev->ID, 'contents', true );
						$content_name          = get_post_meta( $get_all_ev->ID, 'content_name', true );
						$content_category      = get_post_meta( $get_all_ev->ID, 'content_category', true );
						$content_currency      = get_post_meta( $get_all_ev->ID, 'content_currency', true );
						$content_status        = get_post_meta( $get_all_ev->ID, 'content_status', true );
						$content_value         = get_post_meta( $get_all_ev->ID, 'content_value', true );
						$content_num_items     = get_post_meta( $get_all_ev->ID, 'content_num_items', true );
						$content_predicted_ltv = get_post_meta( $get_all_ev->ID, 'content_predicted_ltv', true );
						if ( empty( $content_currency ) ) {
							$content_currency = get_woocommerce_currency();
						}
					}
				} elseif ( 'add' === $get_action ) {
					$selector_type         = '';
					$selector_attr         = '';
					$event_type            = '';
					$event_name            = '';
					$content_id            = '';
					$content_type          = '';
					$contents              = array();
					$content_name          = '';
					$content_category      = '';
					$content_currency      = get_woocommerce_currency();
					$content_status        = '';
					$content_value         = '';
					$content_num_items     = '';
					$content_predicted_ltv = '';
				} elseif ( 'delete' === $get_action ) {
					if ( ! wp_verify_nonce( $get_retrieved_nonce, 'aftnonce' ) ) {
						die( 'Failed security check' );
					}
					$get_post_id = sanitize_text_field( $get_id );
					wp_delete_post( $get_post_id );
					wp_redirect( $back_to_event_list );
					exit;
				}
				?>
				<?php
				if ( 'add' === $get_action || 'edit' === $get_action ) {
					?>
					<h2><?php esc_html_e( 'Facebook Custom Event Configuration', 'advance-ecommerce-tracking' ); ?>
						<a id="back-event-configuration" class="wp-core-ui button-primary" href="<?php echo esc_url( $back_to_event_list ); ?>">
							<?php esc_html_e( 'Back to Event List', 'advance-ecommerce-tracking' ); ?>
						</a>
					</h2>
					<div class="table-outer" id="table_outer_data">
						<form method="POST" name="aet_cft_frm" action="">
							<?php wp_nonce_field( 'aet_cft_save_action', 'aet_cft_conditions_save' ); ?>
							<input type="hidden" name="aet_post_type" id="aet_post_type" value="aet_cft"/>
							<input type="hidden" name="aet_post_id" id="aet_post_id" value="<?php echo esc_attr( $get_id ); ?>"/>
							<div class="general_setting" id="custom_event_general_setting">
								<table class="form-table table-outer" id="ft-table-outer">
									<tbody>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Selector', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="selector_attr" id="selector_attr" value="<?php echo esc_attr( $selector_attr ); ?>" required/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php
												$html = sprintf( '%s<a href=%s target="_blank">%s</a>',
													esc_html__( 'Select element\'s selector where you want to apply this event.
												Please check screenshot for selector\'s.', 'advance-ecommerce-tracking' ),
													esc_url( AET_PLUGIN_URL . 'admin/images/selector.png' ),
													esc_html__( ' Click Here', 'advance-ecommerce-tracking' )
												);
												echo wp_kses_post( $html );
												?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Selector Type', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<select name="selector_type" id="selector_type">
												<option value="pageview" <?php echo isset( $selector_type ) && 'pageview' === $selector_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'PageView', 'advance-ecommerce-tracking' ); ?></option>
												<option value="class" <?php echo isset( $selector_type ) && 'class' === $selector_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'Click on Class Selector', 'advance-ecommerce-tracking' ); ?></option>
												<option value="id" <?php echo isset( $selector_type ) && 'id' === $selector_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'Click on ID Selector', 'advance-ecommerce-tracking' ); ?></option>
											</select>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php
												$html = sprintf( '%s<a href=%s target="_blank">%s</a>',
													esc_html__( 'Select element\'s selector type where you want to apply this event.
												Please check screenshot for selector\'s type.', 'advance-ecommerce-tracking' ),
													esc_url( AET_PLUGIN_URL . 'admin/images/selector_type.png' ),
													esc_html__( ' Click Here', 'advance-ecommerce-tracking' )
												);
												echo wp_kses_post( $html );
												?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Event List', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<select name="event_type" id="event_type">
												<option value="none"><?php echo esc_html_e( 'Select Event', 'advance-ecommerce-tracking' ); ?></option>
												<?php
												foreach ( $get_ft_event_list as $main_key => $main_value ) {
													$json_data = wp_json_encode( $main_value );
													?>
													<option value="<?php echo esc_attr( $main_key ); ?>" data-attr="<?php echo esc_attr( $json_data ); ?>" <?php
													if ( $event_type === $main_key ) {
														echo 'selected=selected';
													}
													?>><?php echo esc_html__( $main_key ); ?></option>
													<?php
												}
												?>
											</select>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Using this you can add your custom event and some standard event as per your requirement.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_custom_event" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Event Name', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="event_name" id="event_name" value="<?php echo esc_attr( $event_name ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_content_id" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Content ID', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_id" id="content_id" value="<?php echo esc_attr( $content_id ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_content_type" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Content Type', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_type" id="content_type" value="<?php echo esc_attr( $content_type ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_contents" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Contents', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<div class="ft_contents_section">
												<div class="event_list_div ft_contents_div" id="ft_contents_div">
													<?php
													if ( ! empty( $contents ) ) {
														$decode_content = json_decode( $contents, true );
														$i              = 0;
														foreach ( $decode_content as $main_key => $main_value ) {
															$i ++;
															?>
															<div id="total_custom_param_<?php echo esc_attr( $i ); ?>" class="main_div_custom_param total_custom_param">
																<div id="ip_div_1" class="ip_div ip_div_"<?php echo esc_attr( $i ); ?>>
																	<input type="text" id="key_"<?php echo esc_attr( $i ); ?> name="key_param[]" class="key_input key_"<?php echo esc_attr( $i ); ?> placeholder="key" value="<?php echo esc_attr( $main_value['key'] ); ?>">
																</div>
																<div id="ip_div_"<?php echo esc_attr( $i ); ?> class="ip_div ip_div_"<?php echo esc_attr( $i ); ?>>
																	<input type="text" id="value_1" name="value_param[]" class="value_input value_"<?php echo esc_attr( $i ); ?> placeholder="Value" value="<?php echo esc_attr( $main_value['value'] ); ?>">
																</div>
																<div class="param_delete" id="param_delete" data-id="<?php echo esc_attr( $i ); ?>">
																	<a href="javascript:void(0);" class="a_param_delete" id="a_param_delete">
																		<img src="<?php echo esc_url( AET_PLUGIN_URL . 'admin/images/rubbish-bin.png' ); ?>">
																	</a>
																</div>
															</div>
															<?php
														}
													} else {
														?>
														<div id="total_custom_param_1" class="main_div_custom_param total_custom_param">
															<div id="ip_div_1" class="ip_div ip_div_1">
																<input type="text" id="key_1" name="key_param[]" class="key_input key_1" placeholder="key" value="">
															</div>
															<div id="ip_div_1" class="ip_div ip_div_1">
																<input type="text" id="value_1" name="value_param[]" class="value_input value_1" placeholder="Value" value="">
															</div>
															<div class="param_delete" id="param_delete" data-id="1">
																<a href="javascript:void(0);" class="a_param_delete" id="a_param_delete"><img src="<?php echo esc_url( AET_PLUGIN_URL . 'admin/images/rubbish-bin.png' ); ?>"></a>
															</div>
														</div>
														<?php
													}
													?>
												</div>
												<div class="event_list_div custom_parameter_btn_div" id="custom_parameter_btn_div">
													<input type="button" name="custom_parameter_btn" class="button button-primary button-large" value="<?php echo esc_attr( $custom_param_btn_text ); ?>">
												</div>
											</div>
											<p class="description" style="display: block;">
												<?php esc_html_e( 'It allows to send multiple data to facebook.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_content_name" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Content Name', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_name" id="content_name" value="<?php echo esc_attr( $content_name ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_content_category" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Content Category', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_category" id="content_category" value="<?php echo esc_attr( $content_category ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_currency" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Currency', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_currency" id="content_currency" value="<?php echo esc_attr( $content_currency ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_status" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Status', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_status" id="content_status" value="<?php echo esc_attr( $content_status ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_value" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Value', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_value" id="content_value" value="<?php echo esc_attr( $content_value ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_num_items" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Num Items', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_num_items" id="content_num_items" value="<?php echo esc_attr( $content_num_items ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top" id="tr_ft_predicted_ltv" class="all_ft_tr">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Predicted Ltv', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="content_predicted_ltv" id="content_predicted_ltv" value="<?php echo esc_attr( $content_predicted_ltv ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enable this profile for analytics tracking.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									</tbody>
								</table>
							</div>
							<p class="submit">
								<input type="submit" name="custom_event_setting" class="button button-primary button-large" value="<?php echo esc_attr( $submit_text ); ?>">
							</p>
						</form>
					</div>
					<?php
				} else {
					?>
					<div class="product_header_title">
						<h2>
							<?php esc_html_e( 'Facebook Custom Event', 'advance-ecommerce-tracking' ); ?>
							<a class="wp-core-ui button-primary" href="<?php echo esc_url( $add_event_url ); ?>">
								<?php esc_html_e( 'Add New Event', 'advance-ecommerce-tracking' ); ?>
							</a>
							<a id="delete-custom-event" class="wp-core-ui button-primary" data-attr="Facebook">
								<?php esc_html_e( 'Delete (Selected)', 'advance-ecommerce-tracking' ); ?>
							</a>
						</h2>
					</div>
					<table id="custom-event-listing" class="table-outer form-table custom-event-listing tablesorter">
						<?php if ( ! empty( $get_all_ev ) ) {
							?>
							<thead>
							<tr class="waet-head">
								<th class="th_chk"><input type="checkbox" name="check_all" class="condition-check-all">
								</th>
								<th class="th_hs"><?php esc_html_e( 'Html Selector', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_ty"><?php esc_html_e( 'Type', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_en"><?php esc_html_e( 'Event Type', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_ec"><?php esc_html_e( 'Event Name', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_action"><?php esc_html_e( 'Actions', 'advance-ecommerce-tracking' ); ?></th>
							</tr>
							</thead>
							<tbody>
							
							<?php
							foreach ( $get_all_ev as $ev_value ) {
								$selector_type = get_post_meta( $ev_value->ID, 'selector_type', true );
								$selector_attr = get_post_meta( $ev_value->ID, 'selector_attr', true );
								$event_type    = get_post_meta( $ev_value->ID, 'event_type', true );
								$event_name    = get_post_meta( $ev_value->ID, 'event_name', true );
								if ( empty( $event_name ) ) {
									$event_name = $event_type;
								}
								$edit_event_url   = $admin_object->aet_pages_url( 'aet-cft-list', $ev_value->ID, 'edit', $aftnonce, true );
								$delete_event_url = $admin_object->aet_pages_url( 'aet-cft-list', $ev_value->ID, 'delete', $aftnonce, true );
								?>
								<tr>
									<td>
										<input type="checkbox" name="multiple_delete_chk[]" class="multiple_delete_chk" value="<?php echo esc_attr( $ev_value->ID ); ?>">
									</td>
									<td><?php echo wp_kses_post( $selector_type ); ?></td>
									<td><?php echo wp_kses_post( $selector_attr ); ?></td>
									<td><?php echo wp_kses_post( $event_type ); ?></td>
									<td><?php echo wp_kses_post( $event_name ); ?></td>
									<td>
										<a class="fee-action-button button-primary"
										   href="<?php echo esc_url( $edit_event_url ); ?>">
											<?php esc_html_e( 'Edit', 'advance-ecommerce-tracking' ); ?>
										</a>
										<a class="fee-action-button button-primary"
										   href="<?php echo esc_url( $delete_event_url ); ?>"
										   onclick="return confirm('<?php esc_html_e( 'Are you sure you want to delete this facebook event?', 'advance-ecommerce-tracking' ) ?>');">
											<?php esc_html_e( 'Delete', 'advance-ecommerce-tracking' ); ?>
										</a>
									</td>
								</tr>
								<?php
							}
							?>

							</tbody>
						<?php } else {
							?>
							<tfoot>
							<tr class="no_list">
								<td colspan="9">
									<?php
									esc_html_e( 'No facebook event found', 'advance-ecommerce-tracking' );
									?>
								</td>
							</tr>
							</tfoot>
							<?php
						}
						?>
					</table>
					<?php
				}
			} else {
				esc_html_e( 'Please enable custom event option from Facebook Tracking setting.', 'advance-ecommerce-tracking' );
			}
			?>
		</div>
	</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>