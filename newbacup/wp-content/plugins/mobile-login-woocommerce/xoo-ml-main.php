<?php
/**
* Plugin Name: OTP Login/Signup Woocommerce
* Plugin URI: http://xootix.com/mobile-login-woocommerce
* Author: XootiX
* Version: 1.4
* Text Domain: mobile-login-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Allows user to signup/login in woocommerce
* Tags: woocommerce, OTP Login, mobile login woocommerce, phone login, signup
*/


//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}

define("XOO_ML_PATH",plugin_dir_path(__FILE__)); // Plugin path
define("XOO_ML_URL",plugins_url('',__FILE__)); // plugin url
define("XOO_ML_PLUGIN_BASENAME",plugin_basename( __FILE__ ));
define("XOO_ML_VERSION","1.4"); //Plugin version
define("XOO_ML_LITE",true);

/**
 * Initialize
 *
 * @since    1.0.0
 */
function xoo_ml_init(){
	

	do_action('xoo_ml_before_plugin_activation');

	if ( ! class_exists( 'Xoo_Ml' ) ) {
		require XOO_ML_PATH.'/includes/class-xoo-ml.php';
	}

	xoo_ml();

	
}
add_action( 'plugins_loaded','xoo_ml_init', 15 );

function xoo_ml(){
	return Xoo_Ml::get_instance();
}


/**
 * WooCommerce not activated admin notice
 *
 * @since    1.0.0
 */
function xoo_ml_install_wc_notice(){
	?>
	<div class="error">
		<p><?php _e( 'WooCommerce Login/Signup Popup is enabled but not effective. It requires WooCommerce in order to work.', 'xoo-ml-woocommerce' ); ?></p>
	</div>
	<?php
}