<?php
/*
* Plugin Name: Store Location 
* Description: Store location
* Version:     1.0.0
* Author:      lamjs
* Author URI:  http://www.cedelegroup.com/
* License:     GPLv2
*/

defined('ABSPATH') or die('Stop!');

$table_store = $wpdb->prefix . 'store_location';
$table_master_data = $wpdb->prefix . 'store_master_data';
$table_working_time = $wpdb->prefix . 'store_working_time';
$table_holiday = $wpdb->prefix . 'store_holiday';
$table_store_holiday = $wpdb->prefix . 'store_holiday_related';

/*
 * import lib & includes
 */
require plugin_dir_path( __FILE__ ) . 'libs/install.php';
require plugin_dir_path( __FILE__ ) . 'libs/helpers.php';
require plugin_dir_path( __FILE__ ) . 'libs/database.php';
require plugin_dir_path( __FILE__ ) . 'libs/Validation.php';
require plugin_dir_path( __FILE__ ) . 'includes/list.php';
require plugin_dir_path( __FILE__ ) . 'includes/form.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-setting.php';
// holiday
require plugin_dir_path( __FILE__ ) . 'includes/list-holiday.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-holiday.php';
require plugin_dir_path( __FILE__ ) . 'ajax/ajax-check-data-exist.php';

/*
 * install session
 */
function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session');
/*
 * install database
 */
$sldb_custom_version = '0.0.1';
function wpsl_install1()
{
    global $sldb_custom_version;
    if ($sldb_custom_version != get_option('sldb_custom_version')) {
        install_table();
        update_option('sldb_custom_version', $sldb_custom_version);
    }
}
register_activation_hook(__FILE__, 'wpsl_install1');

/*
 * import style and scripts
 */
function wpsl_install_styles_and_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_media();

    wp_enqueue_style('custom-styles', plugins_url('/css/styles.css', __FILE__ ));
    wp_enqueue_style('custom-holiday-styles', plugins_url('/css/holiday.css', __FILE__ ));
    wp_enqueue_style('timepicker-css', plugins_url('/css/jquery.datetimepicker.css', __FILE__ ));
    // datetimepicker
    wp_enqueue_script('timepicker-js', plugins_url('/js/jquery.datetimepicker.full.js', __FILE__ ));
    wp_register_script('mainjs', plugins_url( '/js/main.js', __FILE__ ));
    wp_enqueue_script('mainjs');
    wp_register_script('settingjs', plugins_url( '/js/setting.js', __FILE__ ));
    wp_enqueue_script('settingjs');
    wp_register_script('holidayjs', plugins_url( '/js/holiday.js', __FILE__ ));
    wp_enqueue_script('holidayjs');
    /*
     * Call Ajax WordPress
     */
    global $wp_query;
    wp_localize_script('settingjs', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'query_vars' => json_encode( $wp_query->query )
    ));
}
add_action('admin_enqueue_scripts', 'wpsl_install_styles_and_scripts');
function store_admin_menu()
{
    add_menu_page('Store Locations Management', 'Store Locations', 'activate_plugins', 'locations', 'wpsl_list_page_handle', 'dashicons-location');
    add_submenu_page('locations', 'Locations', 'Locations', 'activate_plugins', 'locations', 'wpsl_list_page_handle');
    add_submenu_page('locations', 'Add new', 'Add new', 'activate_plugins', 'locations_form', 'wpsl_form_page_handle');
    add_submenu_page('locations', 'Setting', 'Settings', 'activate_plugins', 'locations_setting', 'wpsl_setting_page_handle');

    add_submenu_page('locations', 'Holidays', 'Holidays', 'activate_plugins', 'holidays', 'wpsl_list_holiday_page_handle');
    add_submenu_page('locations', 'Add holiday', 'Add holiday', 'activate_plugins', 'holidays_form', 'wpsl_form_holiday_page_handle');
}
add_action('admin_menu', 'store_admin_menu');
