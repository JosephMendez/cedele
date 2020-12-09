<?php
/*
* Plugin Name: Cedele Setting 
* Description: Cedele Setting
* Version:     1.0.0
* Author:      lamjs
* Author URI:  http://www.cedelegroup.com/
* License:     GPLv2
*/

defined('ABSPATH') or die('Stop!');

/*
 * install session
 */
function cdls_register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','cdls_register_session');
/*
 * import lib & includes
 */
require plugin_dir_path( __FILE__ ) . 'libs/Validation.php';
require plugin_dir_path( __FILE__ ) . 'libs/install.php';
require plugin_dir_path( __FILE__ ) . 'libs/database.php';
require plugin_dir_path( __FILE__ ) . 'libs/helpers.php';

require plugin_dir_path( __FILE__ ) . 'includes/form-timeslot.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-cut-off-time.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-self-collection-inventory.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-config-image.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-shipping-time.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-highlight-category.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-placeholder.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-home-setting.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-shipping-partner.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-manage-rider.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-driver-management.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-product-label.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-migrate-data.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-send-email.php';

$example_file_dir = plugin_dir_path( __FILE__ ) . 'ajax/example.xls';
require plugin_dir_path( __FILE__ ) . 'ajax/ajax.php';

/*
 * install database
 */
$db_version = '0.0.11';
function cdls_install()
{
    global $db_version;
    if ($db_version != get_option('cdls_db_version')) {
        $result = cdls_create_table_cedele_setting();

        if ($result) {
            update_option('cdls_db_version', $db_version);
        }
    }
}
register_activation_hook(__FILE__, 'cdls_install');

function cdls_update_db_check()
{
    global $db_version;
    if (get_option('cdls_db_version') != $db_version) {
        cdls_install();
    }
}
add_action('plugins_loaded', 'cdls_update_db_check');
/*
 * import style and scripts
 */
function cdls_install_styles_and_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    $page = isset($_GET['page']) ? $_GET['page'] : '';

    wp_enqueue_style('cdls-timepicker-css', plugins_url('/css/jquery.datetimepicker.css', __FILE__ ));
    wp_enqueue_style('cdls-custom-styles', plugins_url('/css/styles.css', __FILE__ ));
    wp_enqueue_style('cdls-tab-css', plugins_url('/css/tab.css', __FILE__ ));
    wp_enqueue_style('cdls-config-image-css', plugins_url( '/css/config-image.css', __FILE__ ));
    wp_enqueue_style('cdls-shipping-time-css', plugins_url( '/css/shipping-time.css', __FILE__ ));
    wp_enqueue_style('cdls-highlight-css', plugins_url( '/css/highlight.css', __FILE__ ));
    wp_enqueue_style('cdls-placeholder-css', plugins_url( '/css/placeholder.css', __FILE__ ));
    wp_enqueue_style('cdls-modal-css', plugins_url( '/css/modal.css', __FILE__ ));
    wp_enqueue_style('cdls-partner-css', plugins_url( '/css/partner.css', __FILE__ ));
    wp_enqueue_style('cdls-rider-css', plugins_url( '/css/rider.css', __FILE__ ));
    wp_enqueue_style('cdls-migrate-css', plugins_url( '/css/migrate.css', __FILE__ ));
    // datetimepicker
    if ($page == 'home-setting' || $page == 'manage-driver') {
        wp_enqueue_script('cdls-lodash-js', plugins_url( '/js/lodash.min.js', __FILE__ ));
    }
    wp_enqueue_script('cdls-timepicker-js', plugins_url('/js/jquery.datetimepicker.full.js', __FILE__ ));
    wp_enqueue_script('cdls-mainjs', plugins_url( '/js/main.js', __FILE__ ));
    wp_enqueue_script('cdls-config-image-js', plugins_url( '/js/config-image.js', __FILE__ ));
    wp_enqueue_script('cdls-shipping-time-js', plugins_url( '/js/shipping-time.js', __FILE__ ));
    wp_enqueue_script('cdls-highlight-js', plugins_url( '/js/highlight.js', __FILE__ ));
    wp_enqueue_script('cdls-partner-js', plugins_url( '/js/partner.js', __FILE__ ));
    wp_enqueue_script('cdls-rider-js', plugins_url( '/js/rider.js', __FILE__ ));
    wp_enqueue_script('cdls-migrate-js', plugins_url( '/js/migrate.js', __FILE__ ));

    global $wp_query, $wpdb;
    wp_localize_script('cdls-mainjs', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'query_vars' => json_encode( $wp_query->query )
    ));
    $table_name = $wpdb->prefix . 'cedele_setting_shipping_partner';
    $list_partners = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");
    wp_localize_script('cdls-mainjs', 'ajax_object_rider', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'cdls_list_partners' => $list_partners
    ));

    $table_term = $wpdb->prefix . 'termmeta';
    $features = $wpdb->get_results("SELECT * FROM $table_term WHERE meta_key = 'cdls_homes_etting_featured_product'");
    $highlights = $wpdb->get_results("SELECT * FROM $table_term WHERE meta_key = 'cdls_homes_etting_highlight'");
    wp_localize_script('cdls-highlight-js', 'ajax_object_highlight', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'features' => $features,
        'highlights' => $highlights
    ));

    wp_localize_script('cdls-migrate-js', 'ajax_object_migrate', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('admin_enqueue_scripts', 'cdls_install_styles_and_scripts');

function cdls_admin_menu()
{
    add_menu_page('Cedele Settings', 'Cedele Settings', 'activate_plugins', 'cedele-setting', 'cdls_form_collection_timeslot');
    add_submenu_page('cedele-setting', 'Collection Timeslot', 'Collection Timeslot', 'activate_plugins', 'cedele-setting', 'cdls_form_collection_timeslot');
    add_submenu_page('cedele-setting', 'Cut-off time', 'Cut-off time', 'activate_plugins', 'cut-off-time', 'cdls_form_cut_of_time');
    add_submenu_page('cedele-setting', 'Shipping time', 'Shipping time', 'activate_plugins', 'shipping-time', 'cdls_form_shipping_time');
    add_submenu_page('cedele-setting', 'Self-Collection Inventory Management', 'Self-Collection Inventory Management', 'activate_plugins', 'self-collection-inventory-management', 'cdls_form_self_colection_inventory');
    add_submenu_page('cedele-setting', 'Home Screen Setting', 'Home Screen Setting', 'activate_plugins', 'home-setting', 'cdls_form_home_setting');
    add_submenu_page('cedele-setting', 'Driver management', 'Driver management', 'activate_plugins', 'manage-driver', 'cdls_rider_management');
    add_submenu_page('cedele-setting', 'Product label', 'Product label', 'activate_plugins', 'product-label', 'cdls_form_product_label');
    add_submenu_page('cedele-setting', 'Media Management', 'Media Management', 'activate_plugins', 'config-image', 'cdls_form_config_image_screen');
    add_submenu_page('cedele-setting', 'Migrate data', 'Migrate data', 'activate_plugins', 'migrate-data', 'cdls_form_migrate_data');
    add_submenu_page('cedele-setting', 'Send email', 'Send email', 'activate_plugins', 'send-email', 'cdls_form_send_email');
}
add_action('admin_menu', 'cdls_admin_menu');

function wp_cronjob_update_is_in_stock_func() {
    global $wpdb;
    $table_location_post = $wpdb->prefix . 'store_location_post';
    $wpdb->query("UPDATE $table_location_post SET `is_in_stock` = 1 WHERE is_in_stock = 0");
}
add_action('wp_cronjob_update_is_in_stock', 'wp_cronjob_update_is_in_stock_func');