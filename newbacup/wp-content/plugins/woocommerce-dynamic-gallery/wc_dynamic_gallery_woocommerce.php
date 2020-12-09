<?php
/*
Plugin Name: Dynamic Product Gallery for WooCommerce
Plugin URI: http://a3rev.com/shop/woocommerce-dynamic-gallery/
Description: Auto adds a fully customizable dynamic images gallery to every single product page with thumbnails, caption text and lazy-load. Over 28 settings to fine tune every aspect of the gallery. Creates an image gallery manager on every product edit page - greatly simplifies managing product images. Search engine optimized images with Dynamic Product Gallery for WooCommerce Pro.
Version: 2.8.2
Author: a3rev Software
Author URI: https://a3rev.com/
Tested up to: 5.5
Text Domain: woocommerce-dynamic-gallery
Domain Path: /languages
WC requires at least: 3.0.0
WC tested up to: 4.3.1
License: GPLv2 or later
*/

/*
	WooCommerce Dynamic Gallery. Plugin for the WooCommerce plugin.
	Copyright © 2011 A3 Revolution Software Development team

	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define( 'WOO_DYNAMIC_GALLERY_FILE_PATH', dirname(__FILE__) );
define( 'WOO_DYNAMIC_GALLERY_DIR_NAME', basename(WOO_DYNAMIC_GALLERY_FILE_PATH) );
define( 'WOO_DYNAMIC_GALLERY_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'WOO_DYNAMIC_GALLERY_NAME', plugin_basename(__FILE__) );
define( 'WOO_DYNAMIC_GALLERY_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'WOO_DYNAMIC_GALLERY_DIR', WP_PLUGIN_DIR.'/'.WOO_DYNAMIC_GALLERY_FOLDER );
define( 'WOO_DYNAMIC_GALLERY_CSS_URL',  WOO_DYNAMIC_GALLERY_URL . '/assets/css' );
define( 'WOO_DYNAMIC_GALLERY_IMAGES_URL',  WOO_DYNAMIC_GALLERY_URL . '/assets/images' );
define( 'WOO_DYNAMIC_GALLERY_JS_URL',  WOO_DYNAMIC_GALLERY_URL . '/assets/js' );

if(!defined("WOO_DYNAMIC_GALLERY_DOCS_URI"))
    define("WOO_DYNAMIC_GALLERY_DOCS_URI", "http://docs.a3rev.com/user-guides/woocommerce/woo-dynamic-gallery/");

define( 'WOO_DYNAMIC_GALLERY_KEY', 'woo_dynamic_gallery' );
define( 'WOO_DYNAMIC_GALLERY_PREFIX', 'wc_dgallery_' );
define( 'WOO_DYNAMIC_GALLERY_VERSION', '2.8.2' );
define( 'WOO_DYNAMIC_GALLERY_DB_VERSION', '2.8.2' );
define( 'WOO_DYNAMIC_GALLERY_G_FONTS', true );

use \A3Rev\WCDynamicGallery\FrameWork;

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';


	/**
	 * Plugin Framework init
	 */
	$GLOBALS[WOO_DYNAMIC_GALLERY_PREFIX.'admin_interface'] = new FrameWork\Admin_Interface();

	global $wc_admin_dgallery_page;
	$wc_admin_dgallery_page = new FrameWork\Pages\Dynamic_Gallery();

	$GLOBALS[WOO_DYNAMIC_GALLERY_PREFIX.'admin_init'] = new FrameWork\Admin_Init();

	$GLOBALS[WOO_DYNAMIC_GALLERY_PREFIX.'less'] = new FrameWork\Less_Sass();

	// End - Plugin Framework init

	global $wc_dynamic_gallery_meta_boxes;
	$wc_dynamic_gallery_meta_boxes = new \A3Rev\WCDynamicGallery\MetaBoxes();

	new \A3Rev\WCDynamicGallery\Notices();

} else {
	return;
}

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/woocommerce-dynamic-gallery/woocommerce-dynamic-gallery-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/woocommerce-dynamic-gallery-LOCALE.mo
 * 	 	- /wp-content/plugins/woocommerce-dynamic-gallery/languages/woocommerce-dynamic-gallery-LOCALE.mo (which if not found falls back to)
 */
function wc_dynamic_gallery_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-dynamic-gallery' );

	load_textdomain( 'woocommerce-dynamic-gallery', WP_LANG_DIR . '/woocommerce-dynamic-gallery/woocommerce-dynamic-gallery-' . $locale . '.mo' );
	load_plugin_textdomain( 'woocommerce-dynamic-gallery', false, WOO_DYNAMIC_GALLERY_FOLDER . '/languages/' );
}

include('admin/wc_gallery_woocommerce_admin.php');

/**
* Call when the plugin is activated
*/
register_activation_hook(__FILE__,'wc_dynamic_gallery_install');
