<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
$aet_option   = $this->aet_ad_get_setting_option( 'et' );
$custom_event = empty( $aet_option->custom_event ) ? array() : $aet_option->custom_event;
?>
	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<?php
			if ( 'on' === $custom_event ) {
				$get_action       = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
				$get_id           = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
				$get_wpnonce      = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
				$btn_post         = filter_input( INPUT_POST, 'custom_event_setting', FILTER_SANITIZE_STRING );
				$aet_admin_object = new Advance_Ecommerce_Tracking_Admin( '', '' );
				$submit_text      = esc_html__( 'Submit', 'advance-ecommerce-tracking' );
				if ( isset( $btn_post ) ) {
					$post_wpnonce         = filter_input( INPUT_POST, 'aet_cat_conditions_save', FILTER_SANITIZE_STRING );
					$post_retrieved_nonce = isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '';
					if ( ! wp_verify_nonce( $post_retrieved_nonce, 'aet_cat_save_action' ) ) {
						die( 'Failed security check' );
					} else {
						$post_data = $_POST;
						$aet_admin_object->aet_save_custom_event_settings__premium_only( $post_data, 'cat' );
					}
				}
				$get_retrieved_nonce = isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '';
				$aetnonce            = wp_create_nonce( 'aetnonce' );
				$admin_object        = new Advance_Ecommerce_Tracking_Admin( '', '' );
				$back_to_event_list  = $admin_object->aet_pages_url( 'aet-cat-list', '', '', '', true );
				$add_event_url       = $admin_object->aet_pages_url( 'aet-cat-list', '', 'add', '', true );
				$get_all_ev          = $admin_object::aet_get_custom_event_list( $get_id, 'aet_cat' );
				if ( 'edit' === $get_action ) {
					if ( ! wp_verify_nonce( $get_retrieved_nonce, 'aetnonce' ) ) {
						die( 'Failed security check' );
					} else {
						$selector_type         = get_post_meta( $get_all_ev->ID, 'selector_type', true );
						$selector_attr         = get_post_meta( $get_all_ev->ID, 'selector_attr', true );
						$event_category        = get_post_meta( $get_all_ev->ID, 'event_category', true );
						$event_action          = get_post_meta( $get_all_ev->ID, 'event_action', true );
						$event_label           = get_post_meta( $get_all_ev->ID, 'event_label', true );
						$event_value           = get_post_meta( $get_all_ev->ID, 'event_value', true );
						$event_interation_type = get_post_meta( $get_all_ev->ID, 'event_interation_type', true );
					}
				} elseif ( 'add' === $get_action ) {
					$selector_type         = '';
					$selector_attr         = '';
					$event_category        = '';
					$event_action          = '';
					$event_label           = '';
					$event_value           = '';
					$event_interation_type = '';
				} elseif ( 'delete' === $get_action ) {
					if ( ! wp_verify_nonce( $get_retrieved_nonce, 'aetnonce' ) ) {
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
					<h2><?php esc_html_e( 'Google Analytics Custom Event Configuration', 'advance-ecommerce-tracking' ); ?>
						<a id="back-event-configuration" class="wp-core-ui button-primary" href="<?php echo esc_url( $back_to_event_list ); ?>">
							<?php esc_html_e( 'Back to Event List', 'advance-ecommerce-tracking' ); ?>
						</a>
					</h2>
					<div class="table-outer" id="table_outer_data">
						<form method="POST" name="aet_cat_frm" action="">
							<?php wp_nonce_field( 'aet_cat_save_action', 'aet_cat_conditions_save' ); ?>
							<input type="hidden" name="aet_post_type" id="aet_post_type" value="aet_cat"/>
							<input type="hidden" name="aet_post_id" id="aet_post_id" value="<?php echo esc_attr( $get_id ); ?>"/>
							<div class="general_setting" id="custom_event_general_setting">
								<table class="form-table table-outer">
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
												<option value="class" <?php echo isset( $selector_type ) && 'class' === $selector_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'Class', 'advance-ecommerce-tracking' ); ?></option>
												<option value="id" <?php echo isset( $selector_type ) && 'id' === $selector_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'ID', 'advance-ecommerce-tracking' ); ?></option>
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
											<label for="onoffswitch"><?php esc_html_e( 'Event Category', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="event_category" id="event_category" value="<?php echo esc_attr( $event_category ); ?>" required/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enter the event name which you want to display in analytics section.
											You can view this report in Behavior > Events section. (Event Category - You have entered name display here) ', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Event Action', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="event_action" id="event_action" value="<?php echo esc_attr( $event_value ); ?>" required/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enter the event action name which you want to display in analytics section.
											You can view this report in Behavior > Events section. (Event Action - You have entered name display here) ', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Event Label', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="event_label" id="event_label" value="<?php echo esc_attr( $event_label ); ?>" required/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enter the event label name which you want to display in analytics section.
											You can view this report in Behavior > Events section. (Event Label - You have entered name display here) ', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Event Value', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<input type="text" name="event_value" id="event_value" value="<?php echo esc_attr( $event_value ); ?>"/>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php esc_html_e( 'Enter the event value which will count unique event. Value must be an integer.', 'advance-ecommerce-tracking' ); ?>
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th class="titledesc" scope="row">
											<label for="onoffswitch"><?php esc_html_e( 'Non-Interaction', 'advance-ecommerce-tracking' ); ?></label>
										</th>
										<td class="forminp">
											<select name="event_interation_type" id="event_interation_type">
												<option value="true" <?php echo isset( $event_interation_type ) && 'true' === $event_interation_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'true', 'advance-ecommerce-tracking' ); ?></option>
												<option value="false" <?php echo isset( $event_interation_type ) && 'false' === $event_interation_type ? 'selected="selected"' : '' ?>><?php echo esc_html_e( 'false', 'advance-ecommerce-tracking' ); ?></option>
											</select>
											<span class="advance_ecommerce_tracking_tab_description"></span>
											<p class="description" style="display:none;">
												<?php
												$html = sprintf( '%s<br><strong>%s</strong>%s',
													esc_html__( 'Using this option, you can set event type interaction or non-interaction.', 'advance-ecommerce-tracking' ),
													esc_html__( ' Note: ', 'advance-ecommerce-tracking' ),
													esc_html__( ' Please make sure to set "Non-Interaction Hit" to "True" if you don\'t want that event to count towards the bounce rate.
												Otherwise, if the event fires on a page, analytics will think you didn\'t bounce and subsequently
												set that session\'s page bounce rate to 0.', 'advance-ecommerce-tracking' )
												);
												echo wp_kses_post( $html );
												?>
											</p>
										</td>
									</tr>
									</tbody>
								</table>
								<p class="submit">
									<input type="submit" name="custom_event_setting" class="button button-primary button-large" value="<?php echo esc_attr( $submit_text ); ?>">
								</p>
							</div>
						</form>
					</div>
					<?php
				} else {
					?>
					<div class="product_header_title">
						<h2>
							<?php esc_html_e( 'Google Analytics Custom Event', 'advance-ecommerce-tracking' ); ?>
							<a class="wp-core-ui button-primary" href="<?php echo esc_url( $add_event_url ); ?>">
								<?php esc_html_e( 'Add New Event', 'advance-ecommerce-tracking' ); ?>
							</a>
							<a id="delete-custom-event" class="wp-core-ui button-primary" data-attr="Analytics">
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
								<th class="th_en"><?php esc_html_e( 'Event Name', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_ec"><?php esc_html_e( 'Event Category', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_ea"><?php esc_html_e( 'Event Action', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_el"><?php esc_html_e( 'Event Label', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_ev"><?php esc_html_e( 'Event Value', 'advance-ecommerce-tracking' ); ?></th>
								<th class="th_action"><?php esc_html_e( 'Actions', 'advance-ecommerce-tracking' ); ?></th>
							</tr>
							</thead>
							<tbody>
							
							<?php
							foreach ( $get_all_ev as $ev_value ) {
								$selector_type  = get_post_meta( $ev_value->ID, 'selector_type', true );
								$selector_attr  = get_post_meta( $ev_value->ID, 'selector_attr', true );
								$event_category = get_post_meta( $ev_value->ID, 'event_category', true );
								$event_action   = get_post_meta( $ev_value->ID, 'event_action', true );
								$event_label    = get_post_meta( $ev_value->ID, 'event_label', true );
								$event_value    = get_post_meta( $ev_value->ID, 'event_value', true );
								if ( empty( $event_value ) ) {
									$event_value = 'N/A';
								}
								$event_interation_type = get_post_meta( $ev_value->ID, 'event_interation_type', true );
								$edit_event_url        = $admin_object->aet_pages_url( 'aet-cat-list', $ev_value->ID, 'edit', $aetnonce, true );
								$delete_event_url      = $admin_object->aet_pages_url( 'aet-cat-list', $ev_value->ID, 'delete', $aetnonce, true );
								?>
								<tr>
									<td>
										<input type="checkbox" name="multiple_delete_chk[]" class="multiple_delete_chk" value="<?php echo esc_attr( $ev_value->ID ); ?>">
									</td>
									<td><?php echo wp_kses_post( $selector_type ); ?></td>
									<td><?php echo wp_kses_post( $selector_attr ); ?></td>
									<td><?php echo wp_kses_post( $event_category ); ?></td>
									<td><?php echo wp_kses_post( $event_action ); ?></td>
									<td><?php echo wp_kses_post( $event_label ); ?></td>
									<td><?php echo wp_kses_post( $event_value ); ?></td>
									<td><?php echo wp_kses_post( $event_interation_type ); ?></td>
									<td>
										<a class="fee-action-button button-primary"
										   href="<?php echo esc_url( $edit_event_url ); ?>">
											<?php esc_html_e( 'Edit', 'advance-ecommerce-tracking' ); ?>
										</a>
										<a class="fee-action-button button-primary"
										   href="<?php echo esc_url( $delete_event_url ); ?>"
										   onclick="return confirm('<?php esc_html_e( 'Are you sure you want to delete this analytics event?', 'advance-ecommerce-tracking' ) ?>');">
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
									esc_html_e( 'No analytics event found', 'advance-ecommerce-tracking' );
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
				esc_html_e( 'Please enable custom event option from Google Ecommerce setting.', 'advance-ecommerce-tracking' );
			}
			?>
		</div>
	</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>