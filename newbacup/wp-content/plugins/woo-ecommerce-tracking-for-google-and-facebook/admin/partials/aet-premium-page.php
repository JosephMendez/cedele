<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<div class="afrsm-premium-features">
				<div class="section section-odd clear">
					<h2><?php esc_html_e( 'Free vs Premium', 'advance-ecommerce-tracking' ); ?></h2>
					<table class="form-table table-outer premium-free-table" align="center">
						<thead>
						<tr class="blue">
							<th><?php echo esc_html__( 'KEY FEATURES LIST', 'advance-ecommerce-tracking' ); ?></th>
							<th><?php echo esc_html__( 'FREE', 'advance-ecommerce-tracking' ); ?></th>
							<th><?php echo esc_html__( 'PREMIUM', 'advance-ecommerce-tracking' ); ?></th>
						</tr>
						</thead>
						<tbody>
						<tr class="dark">
							<td class="pad">
								<?php echo esc_html__( 'Analytics Tracking (Purchase Event)', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
							<td>
								<img src=" <?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr class="dark">
							<td class="pad">
								<?php echo esc_html__( 'Facebook Conversion Tracking (Purchase Event)', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Google Conversion Tracking (Purchase Event)', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr class="dark">
							<td class="pad">
								<?php echo esc_html__( 'Analytics Tracking (Product View, Add to Cart, Remove From Cart, Apply Coupon, Increase and Decrease Cart Qty and Multiple Events)', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src=" <?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr class="dark">
							<td class="pad">
								<?php echo esc_html__( 'Facebook Conversion Tracking (Product View, Add to Cart, Remove From Cart, Apply Coupon and Multiple Events)', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr class="dark">
							<td class="pad">
								<?php echo esc_html__( 'Search Tracking - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'IP Anonymization - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( ' Google Analytics Opt Out - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Demographics and Interests Reports for Remarketing and Advertising - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Enhanced Link Attribution - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'User ID Tracking - Analytics', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Track 404 (Not found) Errors - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'File Downloads - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Form Tracking - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Comment Tracking - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Custom Event - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Excluding traking for roles - Analytics and Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Advance Tracking - Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Taxonomy Tracking - Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr>
							<td class="pad">
								<?php echo esc_html__( 'Advanced Matching - Facebook', 'advance-ecommerce-tracking' ); ?>
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/trash.jpg" ); ?>">
							</td>
							<td>
								<img src="<?php echo esc_url( AET_PLUGIN_URL . "admin/images/check-mark.jpg" ); ?>">
							</td>
						</tr>
						<tr class="pad radius-s">
							<td class="pad"></td>
							<td></td>
							<td class="green red">
								<a href="https://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking" target="_blank">
									<?php echo esc_html__( 'UPGRADE TO PREMIUM VERSION', 'advance-ecommerce-tracking' ); ?>
								</a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>