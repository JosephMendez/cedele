<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$image_url = AET_PLUGIN_URL . 'admin/images/right_click.png';
?>
<div class="dotstore_plugin_sidebar">
	<?php 
?>
		<div class="dotstore_discount_voucher">
			<span class="dotstore_discount_title"><?php 
esc_html_e( 'Discount Voucher', 'advance-ecommerce-tracking' );
?></span>
			<span class="dotstore-upgrade"><?php 
esc_html_e( 'Upgrade to premium now and get', 'advance-ecommerce-tracking' );
?></span>
			<strong class="dotstore-OFF"><?php 
esc_html_e( '10% OFF', 'advance-ecommerce-tracking' );
?></strong>
			<span class="dotstore-with-code"><?php 
esc_html_e( 'with code', 'advance-ecommerce-tracking' );
?>
			<b><?php 
esc_html_e( 'DOT10', 'advance-ecommerce-tracking' );
?></b></span>
			<a class="dotstore-upgrade"
			   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>"
			   target="_blank"><?php 
esc_html_e( 'Upgrade Now!', 'advance-ecommerce-tracking' );
?></a>
		</div>
		<?php 
?>
	<?php 
$review_url = '';
$review_url = esc_url( 'https://wordpress.org/plugins/woo-ecommerce-tracking-for-google-and-facebook/#reviews' );
?>
	<div class="dotstore-important-link">
        <div class="image_box">
            <img src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/rate-us.png' ) ;
?>" alt="<?php 
printf( esc_attr__( '%s WordPress Plugin', 'advance-ecommerce-tracking' ), esc_attr( $this->name ) );
?>" />
        </div>
        <div class="content_box">
            <h3>Like This Plugin?</h3>
            <p>Your Review is very important to us as it helps us to grow more.</p>
            <a class="btn_style" href="<?php 
echo  esc_url( $review_url ) ;
?>" target="_blank">Review Us on theDotstore</a>
        </div>
    </div>
	<div class="dotstore-important-link">
		<h2>
			<span class="dotstore-important-link-title"><?php 
esc_html_e( 'Important link', 'advance-ecommerce-tracking' );
?></span>
		</h2>
		<div class="video-detail important-link">
			<ul>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/docs/plugin/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>"><?php 
esc_html_e( 'Plugin documentation', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/support' ) ;
?>"><?php 
esc_html_e( 'Support platform', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/suggest-a-feature' ) ;
?>"><?php 
esc_html_e( 'Suggest A Feature', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'http://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking#tab-change-log' ) ;
?>"><?php 
esc_html_e( 'Changelog', 'advance-ecommerce-tracking' );
?></a>
				</li>
			</ul>
		</div>
	</div>

	<div class="dotstore-important-link">
		<h2>
			<span class="dotstore-important-link-title"><?php 
esc_html_e( 'OUR POPULAR PLUGINS', 'advance-ecommerce-tracking' );
?></span>
		</h2>
		<div class="video-detail important-link">
			<ul>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/advance-flat-rate-2.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>"><?php 
esc_html_e( 'Advanced Flat Rate Shipping Method', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/wc-conditional-product-fees.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-conditional-product-fees-checkout' ) ;
?>"><?php 
esc_html_e( 'Conditional Product Fees For WooCommerce Checkout', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/advance-menu-manager.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/advance-menu-manager-wordpress' ) ;
?>"><?php 
esc_html_e( 'Advance Menu Manager', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/wc-enhanced-ecommerce-analytics-integration.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>"><?php 
esc_html_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/advanced-product-size-charts.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-advanced-product-size-charts' ) ;
?>"><?php 
esc_html_e( 'Advanced Product Size Charts', 'advance-ecommerce-tracking' );
?></a>
				</li>
				<li>
					<img class="sidebar_plugin_icone"
					     src="<?php 
echo  esc_url( AET_PLUGIN_URL . 'admin/images/blockers.png' ) ;
?>">
					<a target="_blank"
					   href="<?php 
echo  esc_url( 'https://www.thedotstore.com/product/woocommerce-blocker-lite-prevent-fake-orders-blacklist-fraud-customers/' ) ;
?>"><?php 
esc_html_e( 'Blocker – Prevent Fake Orders And Blacklist Fraud Customers for WooCommerce', 'advance-ecommerce-tracking' );
?></a>
				</li>
			</ul>
		</div>
		<div class="view-button">
			<a class="view_button_dotstore" target="_blank"
			   href="<?php 
echo  esc_url( 'www.thedotstore.com/plugins' ) ;
?>"><?php 
esc_html_e( 'VIEW ALL', 'advance-ecommerce-tracking' );
?></a>
		</div>
	</div>
</div>
</div>
</div>