<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com
 * @since             3.0
 * @package           Advance_Ecommerce_Tracking
 *
 * @wordpress-plugin
 * Plugin Name: Advance Ecommerce Tracking
 * Plugin URI:        https://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking
 * Description:       Allows you to use Enhanced Ecommerce tracking without adding any new complex codes on your WooCommerce.
 * Version:           3.2
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advance-ecommerce-tracking
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.5
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'aet_fs' ) ) {
    // Create a helper function for easy SDK access.
    function aet_fs()
    {
        global  $aet_fs ;
        
        if ( !isset( $aet_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $aet_fs = fs_dynamic_init( array(
                'id'              => '3475',
                'slug'            => 'advance-ecommerce-tracking',
                'type'            => 'plugin',
                'public_key'      => 'pk_0dbe70558f17f7a0881498011f656',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'       => 'aet-et-settings',
                'first-path' => 'admin.php?page=aet-et-settings',
                'contact'    => false,
                'support'    => false,
                'network'    => true,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $aet_fs;
    }
    
    // Init Freemius.
    aet_fs();
    // Signal that SDK was initiated.
    do_action( 'aet_fs_loaded' );
    aet_fs()->get_upgrade_url();
    aet_fs()->add_action( 'after_uninstall', 'aet_fs_uninstall_cleanup' );
}

/**
 * Currently plugin version.
 * Start at version 3.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( !defined( 'AET_VERSION' ) ) {
    define( 'AET_VERSION', '3.2' );
}
if ( !defined( 'AET_PLUGIN_URL' ) ) {
    define( 'AET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'AET_PLUGIN_DIR' ) ) {
    define( 'AET_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'AET_PLUGIN_DIR_PATH' ) ) {
    define( 'AET_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'AET_PLUGIN_NAME' ) ) {
    define( 'AET_PLUGIN_NAME', 'Ecommerce Tracking' );
}
if ( !defined( 'AET_VERSION_NAME' ) ) {
    define( 'AET_VERSION_NAME', 'Free Version' );
}
if ( !defined( 'DEBUG_OPTION' ) ) {
    define( 'DEBUG_OPTION', false );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advance-ecommerce-tracking-activator.php
 */
function activate_advance_ecommerce_tracking()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-advance-ecommerce-tracking-activator.php';
    Advance_Ecommerce_Tracking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advance-ecommerce-tracking-deactivator.php
 */
function deactivate_advance_ecommerce_tracking()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-advance-ecommerce-tracking-deactivator.php';
    Advance_Ecommerce_Tracking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advance_ecommerce_tracking' );
register_deactivation_hook( __FILE__, 'deactivate_advance_ecommerce_tracking' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advance-ecommerce-tracking.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0
 */
function run_advance_ecommerce_tracking()
{
    $plugin = new Advance_Ecommerce_Tracking();
    $plugin->run();
}

add_action( 'plugins_loaded', 'aet_plugin_init' );
function aet_plugin_init()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    
    if ( current_user_can( 'activate_plugins' ) && false === $wc_active ) {
        add_action( 'admin_notices', 'aet_plugin_admin_notice' );
        add_action( 'admin_init', 'aet_deactivate_plugin' );
    } else {
        run_advance_ecommerce_tracking();
    }

}

/**
 * Show admin notice in case of WooCommerce plguin is missing
 */
function aet_plugin_admin_notice()
{
    $aet_plugin = AET_PLUGIN_NAME;
    $wc_plugin = 'WooCommerce';
    echo  '<div class="error"><p>' . sprintf( wp_kses_post( '%1$s is deactivated as it requires %2$s  to be installed and active.' ), '<strong>' . esc_html( $aet_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' ) . '</p></div>' ;
}

/**
 * Deactivate the plugin.
 */
function aet_deactivate_plugin()
{
    deactivate_plugins( plugin_basename( __FILE__ ) );
    $activate_plugin_unset = filter_input( INPUT_GET, 'activate', FILTER_SANITIZE_STRING );
    unset( $activate_plugin_unset );
}

/**
 * Admin notice for plugin activation.
 *
 * @since    3.0
 */
function aet_admin_notice_function()
{
    $screen = get_current_screen();
    $screen_id = ( $screen ? $screen->id : '' );
    
    if ( strpos( $screen_id, 'dotstore-plugins_page' ) || strpos( $screen_id, 'plugins' ) ) {
        $aet_admin = filter_input( INPUT_GET, 'aet-hide-notice', FILTER_SANITIZE_STRING );
        $wc_notice_nonce = filter_input( INPUT_GET, '_aet_notice_nonce', FILTER_SANITIZE_STRING );
        if ( isset( $aet_admin ) && $aet_admin === 'aet_admin' && wp_verify_nonce( sanitize_text_field( $wc_notice_nonce ), 'aet_hide_notices_nonce' ) ) {
            delete_transient( 'aet-admin-notice' );
        }
        /* Check transient, if available display notice */
        
        if ( get_transient( 'aet-admin-notice' ) ) {
            ?>
			<div id="message"
			     class="updated woocommerce-message woocommerce-admin-promo-messages welcome-panel aet-panel">
				<a class="woocommerce-message-close notice-dismiss"
				   href="<?php 
            echo  esc_url( wp_nonce_url( add_query_arg( 'aet-hide-notice', 'aet_admin' ), 'aet_hide_notices_nonce', '_aet_notice_nonce' ) ) ;
            ?>">
				</a>
				<p>
					<?php 
            echo  sprintf( wp_kses( __( '<strong>Advance Ecommerce Tracking is successfully installed and ready to go.</strong>', 'advance-ecommerce-tracking' ), array(
                'strong' => array(),
            ), esc_url( admin_url( 'options-general.php' ) ) ) ) ;
            ?>
				</p>
				<p>
					<?php 
            echo  wp_kses_post( __( 'Click on settings button and do your setting as per your requirement.', 'advance-ecommerce-tracking' ) ) ;
            ?>
				</p>
				<?php 
            $url = add_query_arg( array(
                'page' => 'aet-pro-list',
            ), admin_url( 'admin.php' ) );
            ?>
				<p>
					<a href="<?php 
            echo  esc_url( $url ) ;
            ?>" class="button button-primary">
						<?php 
            esc_html_e( 'Settings', 'advance-ecommerce-tracking' );
            ?>
					</a>
				</p>
			</div>
			<?php 
        }
    
    } else {
        return;
    }

}

function aet_upgrade_completed( $upgrader_object, $options )
{
    $our_plugin = plugin_basename( __FILE__ );
    if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
        foreach ( $options['plugins'] as $plugin ) {
            
            if ( $plugin === $our_plugin ) {
                do_action( 'aet_migrate_option' );
                delete_transient( 'aet_updated' );
            }
        
        }
    }
}

add_action(
    'upgrader_process_complete',
    'aet_upgrade_completed',
    10,
    2
);
add_action( 'aet_migrate_option', 'aet_migrate_code' );
function aet_migrate_code()
{
    /*ET UA code*/
    $fr_pl_opt_et = get_option( 'ecommerce_tracking_settings_conversion_id' );
    $pr_pl_opt_et = get_option( 'advance_ecommerce_tracking_section_google_uid' );
    if ( $fr_pl_opt_et ) {
        update_option( 'selected_data_ua_et', $fr_pl_opt_et );
    }
    if ( $pr_pl_opt_et ) {
        update_option( 'selected_data_ua_et', $pr_pl_opt_et );
    }
    /*FA UA code*/
    $fr_pl_opt_ft = get_option( 'ecommerce_tracking_settings_facebook_track_id' );
    $pr_pl_opt_ft = get_option( 'advance_ecommerce_tracking_section_facebook_tracking_id' );
    if ( $fr_pl_opt_ft ) {
        update_option( 'selected_data_ua_ft', $fr_pl_opt_ft );
    }
    if ( $pr_pl_opt_ft ) {
        update_option( 'selected_data_ua_ft', $pr_pl_opt_ft );
    }
    /*ET Setting code*/
    $et_settings_array = array();
    $fr_pl_opt_et_gs = get_option( 'ecommerce_tracking_settings_load_ecommerce_tracking_code' );
    $pr_pl_opt_et_gs = get_option( 'advance_ecommerce_tracking_section_enable' );
    
    if ( 'yes' === $fr_pl_opt_et_gs ) {
        $et_settings_array['at_enable'] = 'on';
        $et_settings_array['enhance_ecommerce_tracking'] = 'on';
    }
    
    
    if ( 'yes' === $pr_pl_opt_et_gs ) {
        $et_settings_array['at_enable'] = 'on';
        $et_settings_array['enhance_ecommerce_tracking'] = 'on';
    }
    
    if ( $fr_pl_opt_et_gs || $pr_pl_opt_et_gs ) {
        update_option( 'aet_et_tracking_settings', wp_json_encode( $et_settings_array ) );
    }
    /*FT Setting code*/
    $ft_settings_array = array();
    $fr_pl_opt_ft_gs = get_option( 'ecommerce_tracking_settings_facebook_conversion_code' );
    $pr_pl_opt_ft_gs = get_option( 'advance_ecommerce_tracking_facebook_section_enable' );
    
    if ( 'yes' === $fr_pl_opt_ft_gs ) {
        $ft_settings_array['ft_enable'] = 'on';
        $ft_settings_array['fb_ecommerce_tracking'] = 'on';
    }
    
    
    if ( 'yes' === $pr_pl_opt_ft_gs ) {
        $ft_settings_array['ft_enable'] = 'on';
        $ft_settings_array['fb_ecommerce_tracking'] = 'on';
    }
    
    $fb_add_to_cart_shop = get_option( 'fb_add_to_cart_shop' );
    $fb_add_to_cart_single_prd = get_option( 'fb_add_to_cart_single_prd' );
    $fb_purchase = get_option( 'fb_purchase' );
    $fb_view_content = get_option( 'fb_view_content' );
    $fb_view_product_category = get_option( 'fb_view_product_category' );
    $flag = 0;
    if ( 'yes' === $fb_add_to_cart_shop || 'yes' === $fb_add_to_cart_single_prd || 'yes' === $fb_purchase || 'yes' === $fb_view_content || 'yes' === $fb_view_product_category ) {
        $flag = 1;
    }
    if ( 1 === $flag ) {
        $ft_settings_array['fb_ecommerce_tracking'] = 'on';
    }
    if ( $fr_pl_opt_ft_gs || $pr_pl_opt_ft_gs ) {
        update_option( 'aet_ft_tracking_settings', wp_json_encode( $ft_settings_array ) );
    }
    /*GC Setting code*/
    $gc_settings_array = array();
    $fr_pl_opt_gc_id = get_option( 'ecommerce_tracking_settings_google_conversion_id' );
    $fr_pl_opt_gc_lbl = get_option( 'ecommerce_tracking_settings_google_conversion_label' );
    $fr_pl_opt_gc_enb = get_option( 'ecommerce_tracking_settings_google_conversion_code' );
    $advance_ecommerce_tracking_google_conversion_enable = get_option( 'advance_ecommerce_tracking_google_section_enable' );
    $advance_ecommerce_tracking_settings_google_conversion_label = get_option( 'advance_ecommerce_tracking_section_google_conversion_label' );
    $advance_ecommerce_tracking_settings_google_conversion_id = get_option( 'advance_ecommerce_tracking_section_google_conversion_id' );
    if ( $fr_pl_opt_gc_id ) {
        $gc_settings_array['gc_id'] = $fr_pl_opt_gc_id;
    }
    if ( $fr_pl_opt_gc_lbl ) {
        $gc_settings_array['gc_label'] = $fr_pl_opt_gc_lbl;
    }
    if ( 'yes' === $fr_pl_opt_gc_enb ) {
        $gc_settings_array['gc_enable'] = 'on';
    }
    if ( 'yes' === $advance_ecommerce_tracking_google_conversion_enable ) {
        $gc_settings_array['gc_enable'] = 'on';
    }
    if ( $advance_ecommerce_tracking_settings_google_conversion_label ) {
        $gc_settings_array['gc_label'] = $advance_ecommerce_tracking_settings_google_conversion_label;
    }
    if ( $advance_ecommerce_tracking_settings_google_conversion_id ) {
        $gc_settings_array['gc_id'] = $advance_ecommerce_tracking_settings_google_conversion_id;
    }
    if ( $fr_pl_opt_gc_enb || $fr_pl_opt_gc_lbl || $fr_pl_opt_gc_id || $advance_ecommerce_tracking_google_conversion_enable || $advance_ecommerce_tracking_settings_google_conversion_label || $advance_ecommerce_tracking_settings_google_conversion_id ) {
        update_option( 'aet_gc_tracking_settings', wp_json_encode( $gc_settings_array ) );
    }
}
