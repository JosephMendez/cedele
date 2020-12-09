<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>

	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<h2><?php esc_html_e( 'Quick info', 'advance-ecommerce-tracking' ); ?></h2>
			<table class="table-outer">
				<tbody>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Product Type', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2"><?php esc_html_e( 'WooCommerce Plugin', 'advance-ecommerce-tracking' ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Product Name', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2"><?php esc_html_e( $plugin_name, 'advance-ecommerce-tracking' ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Installed Version', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2"><?php esc_html_e( AET_VERSION_NAME, 'advance-ecommerce-tracking' ); ?>
						&nbsp;<?php echo esc_html_e( $plugin_version, 'advance-ecommerce-tracking' ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'License & Terms of use', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2">
						<a target="_blank" href="<?php echo esc_url( 'www.thedotstore.com/terms-and-conditions' ); ?>">
							<?php esc_html_e( 'Click here', 'advance-ecommerce-tracking' ); ?>
						</a>
						<?php esc_html_e( ' to view license and terms of use.', 'advance-ecommerce-tracking' ); ?>
					</td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Help & Support', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2">
						<ul>
							<li>
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'aet-get-started' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Quick Start', 'advance-ecommerce-tracking' ); ?></a>
							</li>
							<li><a target="_blank"
							       href="<?php echo esc_url( 'http://www.thedotstore.com/docs/plugin/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking/' ); ?>"><?php esc_html_e( 'Guide Documentation', 'advance-ecommerce-tracking' ); ?></a>
							</li>
							<li><a target="_blank"
							       href="<?php echo esc_url( 'www.thedotstore.com/support' ); ?>"><?php esc_html_e( 'Support Forum', 'advance-ecommerce-tracking' ); ?></a>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Localization', 'advance-ecommerce-tracking' ); ?></td>
					<td class="fr-2"><?php esc_html_e( 'English, German', 'advance-ecommerce-tracking' ); ?></td>
				</tr>

				</tbody>
			</table>
		</div>
	</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' );