<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_name = AET_PLUGIN_NAME;
$plugin_version = AET_VERSION;
$aet_admin_object = new Advance_Ecommerce_Tracking_Admin( '', '' );
?>
<div id="dotsstoremain">
	<div class="all-pad">
		<header class="dots-header">
			<div class="dots-logo-main">
				<img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/WSFL.jpg' ) ;
?>">
			</div>
			<div class="dots-header-right">
				<div class="logo-detail">
					<strong><?php 
esc_html_e( $plugin_name, 'advance-ecommerce-tracking' );
?></strong>
					<span>
                        <?php 
esc_html_e( AET_VERSION_NAME, 'advance-ecommerce-tracking' );
?>&nbsp;<?php 
echo  esc_html__( $plugin_version, 'advance-ecommerce-tracking' ) ;
?>
                    </span>
				</div>
				<div class="button-group">
					<div class="button-dots-left">
						<?php 
?>
							<span>
	                                <a target="_blank"
	                                   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>">
	                                    <img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/upgrade_new.png' ) ;
?>">
	                                </a>
                                </span>
							<?php 
?>
					</div>
					<div class="button-dots">
                        <span class="support_dotstore_image">
                            <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                                <img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/support_new.png' ) ;
?>">
                            </a>
                        </span>
					</div>
				</div>
			</div>
			<?php 
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$aet_admin_object->aet_menus( $current_page );
?>
		</header>