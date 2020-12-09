<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
?>
	<div class="waet-section-left">
		<div class="waet-table res-cl">
			<h2><?php 
esc_html_e( 'Thanks For Installing Advanced Flat Rate Shipping For WooCommerce', 'advance-ecommerce-tracking' );
?></h2>
			<table class="table-outer">
				<tbody>
				<tr>
					<td class="fr-2">
						<p class="block gettingstarted">
							<strong><?php 
esc_html_e( 'Analytics Getting Started', 'advance-ecommerce-tracking' );
?></strong>
						</p>
						<p class="block textgetting">
							<?php 
esc_html_e( 'The plugin allows you to easy Integration with Analytics with your WooCommerce store.
							It will provide all the features to track your sales and product performance.', 'advance-ecommerce-tracking' );
?>
						</p>
						<p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 1: </strong>You can get your analytics ID with gmail connection using click on Start to Setup button.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/ec_step1.png' ) ;
?>">
                            </span>
						</p>
						<p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 2: </strong>You can also enter analytics ID manually. EX: UA-XXXXXXXXX-X. Please follow steps and see screenshots. Admin - Property - Property Settings.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/ec_step6.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 3: </strong>When you click on Start to setup then you will redirect to our server
									and click on Connect to gmail.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/ec_step2.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 4: </strong>After connect with gmail you will get your all analytics ID list in dropdown.
									You can selet ID in which you want see tracking data. After select analytics ID, you will redirect to plugins page.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/ec_step3.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 5: </strong>You can see here your selected analytics ID and analytics setting.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
                                <?php 
?>
	                                <img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/free_ec_step4.png' ) ;
?>">
	                                <?php 
?>
                            </span>
						</p>
						<p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 6: </strong>After plugin\'s setup, Please enable Ecommerce section from analytics section.
									Please check below screenshot for more info.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/ec_step5.png' ) ;
?>">
                            </span>
						</p>
						<p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 7: </strong>For search tracking, Please enable search section from analytics section.
									Please check below screenshot for more info.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/search.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted">
							<strong><?php 
esc_html_e( 'Facebook Getting Started', 'advance-ecommerce-tracking' );
?></strong>
						</p>
						<p class="block textgetting">
							<?php 
esc_html_e( 'The plugin allows you to easy Integration with facebook with your WooCommerce store.
							It will provide all the features to track your sales and product performance.', 'advance-ecommerce-tracking' );
?>
						</p>
						<p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 1: </strong>You can get your pixel ID with facebook connection using click on Start to Setup button. OR you can also enter pixel ID manually.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/fc_step1.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 2: </strong>When you click on Start to setup then you will redirect to our server
									and click on Connect to facebook.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
								<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/fc_step2.png' ) ;
?>">
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 3: </strong>You can see here your selected pixel ID and facebook tracking setting.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
							<span class="gettingstarted">
                                <?php 
?>
	                                <img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/free_fc_step4.png' ) ;
?>">
	                                <?php 
?>
                            </span>
						</p>
						<p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Important Note: </strong>This plugin is only compatible with WooCommerce version 3.0 and more.', 'advance-ecommerce-tracking' ), array(
    'strong' => array(),
) ) ) ;
?>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';