<?php
defined('ABSPATH') or die('Stop!');
/*
 * import lib & includes
 */
require_once get_template_directory() . '/admin-custom/faq/libs/Validation.php';
require_once get_template_directory() . '/admin-custom/faq/libs/install.php';
require_once get_template_directory() . '/admin-custom/faq/inc/list.php';
require_once get_template_directory() . '/admin-custom/faq/inc/form.php';
require_once get_template_directory() . '/admin-custom/faq/inc/list-cat.php';
require_once get_template_directory() . '/admin-custom/faq/inc/form-cat.php';

require_once get_template_directory() . '/admin-custom/faq/inc/ajax.php';

/*
 * install database
 */
$db_version = '0.0.4';
faq_custom_install();
function faq_custom_install()
{
    global $db_version;
    if ($db_version != get_option('faq_custom_db_version')) {
        $result = faq_custom_install_table();

        if ($result) {
            update_option('faq_custom_db_version', $db_version);
        }
    }
}

/*
 * import style and scripts
 */
function faq_custom_styles_and_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('faq-custom-css', get_template_directory_uri() . '/admin-custom/faq/assets/css/faq-custom.css');
    wp_enqueue_script('faq-custom-js', get_template_directory_uri() . '/admin-custom/faq/assets/js/faq-custom.js', array('jquery-blockui'));

    wp_localize_script('faq-custom-js', 'faq_custom_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('faq_custom_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'faq_custom_styles_and_scripts');
function faq_custom_admin_menu()
{
    add_menu_page('FAQ', 'FAQ', 'manage-faq', 'faq-custom', 'faq_custom_list_faq_handle', 'dashicons-format-status', 9);
    add_submenu_page('faq-custom', 'All FAQ', 'All FAQ', 'manage-faq', 'faq-custom', 'faq_custom_list_faq_handle');
    add_submenu_page('faq-custom', 'Add New', 'Add new', 'manage-faq', 'faq-custom-form', 'faq_custom_form_faq_handle');
    add_submenu_page('faq-custom', 'Categories FAQ', 'Categories FAQ', 'manage-faq', 'faq-c-custom', 'faq_custom_categories_list_handle');
    add_submenu_page('faq-custom', 'Add Categories FAQ', 'Add Categories FAQ', 'manage-faq', 'faq-c-custom-form', 'faq_custom_categories_form_handle');
}
add_action('admin_menu', 'faq_custom_admin_menu');

function faq_custom_add_capabilities() {
    $role = get_role('administrator');
    $role->add_cap( 'manage-faq', true );
}
add_action( 'init', 'faq_custom_add_capabilities', 11 );