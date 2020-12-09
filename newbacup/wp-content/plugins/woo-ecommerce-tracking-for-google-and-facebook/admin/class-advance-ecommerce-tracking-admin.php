<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.thedotstore.com
 * @since      3.0
 *
 * @package    Advance_Ecommerce_Tracking
 * @subpackage Advance_Ecommerce_Tracking/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advance_Ecommerce_Tracking
 * @subpackage Advance_Ecommerce_Tracking/admin
 * @author     Thedotstore <wordpress@multidots.in>
 */
class Advance_Ecommerce_Tracking_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    3.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * The server url.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $base_url = 'https://webrex.thedotstore.com' ;
    /**
     * The server path.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $base_path = 'v2' ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    3.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Get custom event list.
     *
     * @param string $get_post_id
     *
     * @param string $post_type
     *
     * @return string $default_lang
     *
     * @since  3.4
     *
     */
    public static function aet_get_custom_event_list( $get_post_id, $post_type )
    {
        $aet_args = array(
            'post_type'        => $post_type,
            'posts_per_page'   => -1,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'suppress_filters' => false,
        );
        if ( !empty($get_post_id) ) {
            $aet_args['post__in'] = array( $get_post_id );
        }
        $aet_all_event_list = new WP_Query( $aet_args );
        
        if ( !empty($get_post_id) ) {
            $aet_all_event = $aet_all_event_list->get_posts();
            if ( !empty($aet_all_event) ) {
                return $aet_all_event[0];
            }
        } else {
            $aet_all_event = $aet_all_event_list->get_posts();
            return $aet_all_event;
        }
    
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    3.0
     */
    public function aet_enqueue_styles( $hook )
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Advance_Ecommerce_Tracking_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Advance_Ecommerce_Tracking_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_aet' ) ) {
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/advance-ecommerce-tracking-admin.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'advance-ecommerce-tracking-main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->version,
                false
            );
            wp_enqueue_style(
                'advance-ecommerce-tracking-media',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                $this->version,
                false
            );
            wp_enqueue_style(
                'advance-ecommerce-tracking-webkit',
                plugin_dir_url( __FILE__ ) . 'css/webkit.css',
                array(),
                $this->version,
                false
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    3.0
     */
    public function aet_enqueue_scripts( $hook )
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Advance_Ecommerce_Tracking_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Advance_Ecommerce_Tracking_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_aet' ) ) {
            global  $wp ;
            $current_url = home_url( add_query_arg( $wp->query_vars, $wp->request ) );
            wp_enqueue_script(
                $this->plugin_name . '-api',
                esc_url( 'https://apis.google.com/js/api.js' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/advance-ecommerce-tracking-admin.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/advance-ecommerce-tracking-admin.js',
                array( 'jquery', 'jquery-ui-dialog' ),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'aet_vars', array(
                'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
                'trash_url'                 => esc_url( AET_PLUGIN_URL . 'admin/images/rubbish-bin.png' ),
                'aet_chk_nonce_ajax'        => wp_create_nonce( 'aet_chk_nonce' ),
                'aet_fetch_data_nonce_ajax' => wp_create_nonce( 'aet_fetch_data_nonce' ),
                'current_url'               => $current_url,
            ) );
        }
    
    }
    
    /**
     * Redirect to the setting page after activate plugin.
     *
     * @since    3.0
     */
    public function aet_welcome_ecommerce_tracking_do_activation_redirect()
    {
        $get_activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        // if no activation redirect
        if ( !get_transient( '_aet_welcome_ecommerce_screen_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_aet_welcome_ecommerce_screen_activation_redirect_data' );
        // if activating from network, or bulk
        if ( is_network_admin() || isset( $get_activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'aet-et-settings',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Add menu in admin side.
     *
     * @since    3.0
     */
    public function aet_admin_menu()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                'DotStore Plugins',
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_advanced_ecommerce_tracking_pro_page' ),
                plugin_dir_url( __FILE__ ) . 'images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Ecommerce Tracking',
            'Ecommerce Tracking',
            'manage_options',
            'aet-et-settings',
            array( $this, 'aet_ecommerce_tracking_settings' ),
            2
        );
        add_submenu_page(
            'dots_store',
            'Facebook Tracking',
            'Facebook Tracking',
            'manage_options',
            'aet-ft-settings',
            array( $this, 'aet_facebook_tracking_settings' ),
            3
        );
        add_submenu_page(
            'dots_store',
            'Google Conversion',
            'Google Conversion',
            'manage_options',
            'aet-gc-settings',
            array( $this, 'aet_gc_settings' ),
            3
        );
        add_submenu_page(
            'dots_store',
            'Getting Started',
            'Getting Started',
            'manage_options',
            'aet-get-started',
            array( $this, 'aet_get_started_page' ),
            4
        );
        add_submenu_page(
            'dots_store',
            'Quick info',
            'Quick info',
            'manage_options',
            'aet-information',
            array( $this, 'aet_information_page' ),
            5
        );
        add_submenu_page(
            'dots_store',
            'Premium Version',
            'Premium Version',
            'manage_options',
            'aet-premium',
            array( $this, 'aet_premium_page' ),
            6
        );
    }
    
    /**
     * Ecommerce Tracking Setting Page.
     *
     * @since    3.0
     */
    public function aet_ecommerce_tracking_settings()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-et-settings.php';
    }
    
    /**
     * Ecommerce Tracking Setting Page.
     *
     * @since    3.0
     */
    public function aet_facebook_tracking_settings()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-ft-settings.php';
    }
    
    /**
     * Google Conversion settings.
     *
     * @since 3.0
     */
    public function aet_gc_settings()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-gc-settings.php';
    }
    
    /**
     * Quick guide page.
     *
     * @since    3.0
     */
    public function aet_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-get-started-page.php';
    }
    
    /**
     * Plugin information page.
     *
     * @since    3.0
     */
    public function aet_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-information-page.php';
    }
    
    /**
     * Premium plugin page.
     *
     * @since    3.0
     */
    public function aet_premium_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/aet-premium-page.php';
    }
    
    /**
     * Remove submenu from admin screen.
     *
     * @since    3.0
     */
    public function aet_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'dots_store' );
        remove_submenu_page( 'dots_store', 'aet-get-started' );
        remove_submenu_page( 'dots_store', 'aet-information' );
        remove_submenu_page( 'dots_store', 'aet-premium' );
        remove_submenu_page( 'dots_store', 'aet-ft-settings' );
        remove_submenu_page( 'dots_store', 'aet-gc-settings' );
    }
    
    /**
     * Create a menu for plugin.
     *
     * @param string $current current page.
     *
     * @since    3.0
     */
    public function aet_menus( $current = 'aet-et-settings' )
    {
        $menu_title = '';
        $menu_url = '';
        $menu_slug = '';
        $fb_menu_title = '';
        $fb_menu_url = '';
        $fb_menu_slug = '';
        $wpfp_menus = array(
            'main_menu' => array(
            'pro_menu'  => array(
            'aet-et-settings' => array(
            'menu_title' => __( 'Google Ecommerce Tracking', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-et-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-et-settings',
            '',
            '',
            ''
        ),
        ),
            $menu_slug        => array(
            'menu_title' => $menu_title,
            'menu_slug'  => $menu_slug,
            'menu_url'   => $menu_url,
        ),
            'aet-ft-settings' => array(
            'menu_title' => __( 'Facebook Tracking', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-ft-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-ft-settings',
            '',
            '',
            ''
        ),
        ),
            $fb_menu_slug     => array(
            'menu_title' => $fb_menu_title,
            'menu_slug'  => $fb_menu_slug,
            'menu_url'   => $fb_menu_url,
        ),
            'aet-gc-settings' => array(
            'menu_title' => __( 'Google Conversion', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-gc-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-gc-settings',
            '',
            '',
            ''
        ),
        ),
            'aet-get-started' => array(
            'menu_title' => __( 'About Plugin', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-get-started',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'aet-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-get-started',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-get-started',
            '',
            '',
            ''
        ),
        ),
            'aet-information' => array(
            'menu_title' => __( 'Quick info', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-information',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'dotstore'        => array(
            'menu_title' => __( 'Dotstore', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-woo-plugins' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-wp-plugins' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
            'free_menu' => array(
            'aet-et-settings' => array(
            'menu_title' => __( 'Google Ecommerce Tracking', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-et-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-et-settings',
            '',
            '',
            ''
        ),
        ),
            'aet-ft-settings' => array(
            'menu_title' => __( 'Facebook Tracking', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-ft-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-ft-settings',
            '',
            '',
            ''
        ),
        ),
            'aet-gc-settings' => array(
            'menu_title' => __( 'Google Conversion', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-gc-settings',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-gc-settings',
            '',
            '',
            ''
        ),
        ),
            'aet-get-started' => array(
            'menu_title' => __( 'About Plugin', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-get-started',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'aet-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-get-started',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-get-started',
            '',
            '',
            ''
        ),
        ),
            'aet-information' => array(
            'menu_title' => __( 'Quick info', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-information',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'aet-premium'     => array(
            'menu_title' => __( 'Premium Version', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'aet-premium',
            'menu_url'   => $this->aet_plugins_url(
            '',
            'aet-premium',
            '',
            '',
            ''
        ),
        ),
            'dotstore'        => array(
            'menu_title' => __( 'Dotstore', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-woo-plugins' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-wp-plugins' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advance-ecommerce-tracking' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
        ),
        );
        ?>
		<div class="dots-menu-main">
			<nav>
				<ul>
					<?php 
        $main_current = $current;
        $sub_current = $current;
        foreach ( $wpfp_menus['main_menu'] as $main_menu_slug => $main_wpfp_menu ) {
            if ( 'free_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
                foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
                    if ( 'aet-information' === $main_current ) {
                        $main_current = 'aet-get-started';
                    }
                    $class = ( $menu_slug === $main_current ? 'active' : '' );
                    
                    if ( !empty($wpfp_menu['menu_title']) ) {
                        ?>
										<li>
											<a class="dotstore_plugin <?php 
                        echo  esc_attr( $class ) ;
                        ?>"
											   href="<?php 
                        echo  esc_url( $wpfp_menu['menu_url'] ) ;
                        ?>">
												<?php 
                        esc_html_e( $wpfp_menu['menu_title'], 'advance-ecommerce-tracking' );
                        ?>
											</a>
											<?php 
                        
                        if ( isset( $wpfp_menu['sub_menu'] ) && !empty($wpfp_menu['sub_menu']) ) {
                            ?>
												<ul class="sub-menu">
													<?php 
                            foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
                                $sub_class = ( $sub_menu_slug === $sub_current ? 'active' : '' );
                                
                                if ( !empty($wpfp_sub_menu['menu_title']) ) {
                                    ?>
															<li>
																<a class="dotstore_plugin <?php 
                                    echo  esc_attr( $sub_class ) ;
                                    ?>"
																   href="<?php 
                                    echo  esc_url( $wpfp_sub_menu['menu_url'] ) ;
                                    ?>">
																	<?php 
                                    esc_html_e( $wpfp_sub_menu['menu_title'], 'advance-ecommerce-tracking' );
                                    ?>
																</a>
															</li>
														<?php 
                                }
                            
                            }
                            ?>
												</ul>
											<?php 
                        }
                        
                        ?>
										</li>
										<?php 
                    }
                
                }
            }
        }
        ?>
				</ul>
			</nav>
		</div>
		<?php 
    }
    
    /**
     * Get option.
     *
     * @param string $args
     *
     * @return string $get_option_data
     *
     * @since 3.0
     */
    public function aet_ad_get_setting_option( $args )
    {
        $option_key = 'aet_' . $args . '_tracking_settings';
        $get_option_data = json_decode( get_option( $option_key ) );
        return $get_option_data;
    }
    
    /**
     * Plugins URL.
     *
     * @since    3.0
     */
    public function aet_plugins_url(
        $id,
        $page,
        $tab,
        $action,
        $nonce
    )
    {
        $query_args = array();
        if ( '' !== $page ) {
            $query_args['page'] = $page;
        }
        if ( '' !== $tab ) {
            $query_args['tab'] = $tab;
        }
        if ( '' !== $action ) {
            $query_args['action'] = $action;
        }
        if ( '' !== $id ) {
            $query_args['id'] = $id;
        }
        if ( '' !== $nonce ) {
            $query_args['_wpnonce'] = wp_create_nonce( 'afrsmnonce' );
        }
        return esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
    }
    
    /**
     * Save analytics data
     *
     * @param array $data Get all tracking data.
     *
     * @return string redirect page
     *
     * @since 3.0
     */
    public function aet_save_settings( $data )
    {
        if ( empty($data) ) {
            return false;
        }
        $aet_track_save = filter_input( INPUT_POST, 'track_save', FILTER_SANITIZE_STRING );
        $aet_track_type = filter_input( INPUT_POST, 'track_type', FILTER_SANITIZE_STRING );
        $option_key = 'aet_' . $aet_track_type . '_tracking_settings';
        $tracking_settings_array = array();
        
        if ( 'ecommerce' === $aet_track_save ) {
            $aet_et_conditions_save = filter_input( INPUT_POST, 'aet_et_conditions_save', FILTER_SANITIZE_STRING );
            
            if ( !empty($data) && wp_verify_nonce( sanitize_text_field( $aet_et_conditions_save ), 'aet_et_save_action' ) ) {
                $get_at_enable = filter_input( INPUT_POST, 'at_enable', FILTER_SANITIZE_STRING );
                $at_enable = ( isset( $get_at_enable ) ? sanitize_text_field( $get_at_enable ) : 'off' );
                $tracking_settings_array['at_enable'] = $at_enable;
                $get_enhance_ecommerce_tracking = filter_input( INPUT_POST, 'enhance_ecommerce_tracking', FILTER_SANITIZE_STRING );
                $enhance_ecommerce_tracking = ( isset( $get_enhance_ecommerce_tracking ) ? sanitize_text_field( $get_enhance_ecommerce_tracking ) : 'off' );
                $tracking_settings_array['enhance_ecommerce_tracking'] = $enhance_ecommerce_tracking;
            }
        
        }
        
        
        if ( 'facebook' === $aet_track_save ) {
            $aet_fb_conditions_save = filter_input( INPUT_POST, 'aet_fb_conditions_save', FILTER_SANITIZE_STRING );
            
            if ( !empty($data) && wp_verify_nonce( sanitize_text_field( $aet_fb_conditions_save ), 'aet_fb_save_action' ) ) {
                $get_ft_enable = filter_input( INPUT_POST, 'ft_enable', FILTER_SANITIZE_STRING );
                $ft_enable = ( isset( $get_ft_enable ) ? sanitize_text_field( $get_ft_enable ) : 'off' );
                $tracking_settings_array['ft_enable'] = $ft_enable;
                $get_fb_ecommerce_tracking = filter_input( INPUT_POST, 'fb_ecommerce_tracking', FILTER_SANITIZE_STRING );
                $fb_ecommerce_tracking = ( isset( $get_fb_ecommerce_tracking ) ? sanitize_text_field( $get_fb_ecommerce_tracking ) : 'off' );
                $tracking_settings_array['fb_ecommerce_tracking'] = $fb_ecommerce_tracking;
            }
        
        }
        
        
        if ( 'google_conversion' === $aet_track_save ) {
            $aet_gc_conditions_save = filter_input( INPUT_POST, 'aet_gc_conditions_save', FILTER_SANITIZE_STRING );
            
            if ( !empty($data) && wp_verify_nonce( sanitize_text_field( $aet_gc_conditions_save ), 'aet_gc_save_action' ) ) {
                $get_gc_enable = filter_input( INPUT_POST, 'gc_enable', FILTER_SANITIZE_STRING );
                $get_gc_id = filter_input( INPUT_POST, 'gc_id', FILTER_SANITIZE_STRING );
                $get_gc_label = filter_input( INPUT_POST, 'gc_label', FILTER_SANITIZE_STRING );
                $gc_enable = ( isset( $get_gc_enable ) ? sanitize_text_field( $get_gc_enable ) : 'off' );
                $gc_id = ( isset( $get_gc_id ) ? sanitize_text_field( $get_gc_id ) : '' );
                $gc_label = ( isset( $get_gc_label ) ? sanitize_text_field( $get_gc_label ) : '' );
                $tracking_settings_array['gc_enable'] = $gc_enable;
                $tracking_settings_array['gc_id'] = $gc_id;
                $tracking_settings_array['gc_label'] = $gc_label;
            }
        
        }
        
        if ( !empty($tracking_settings_array) ) {
            update_option( $option_key, wp_json_encode( $tracking_settings_array ) );
        }
        wp_safe_redirect( add_query_arg( array(
            'page' => 'aet-' . $aet_track_type . '-settings',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Update id manually.
     *
     * @since 3.0
     */
    public function aet_update_manually_id()
    {
        $get_val = filter_input( INPUT_GET, 'get_val', FILTER_SANITIZE_STRING );
        $get_attr = filter_input( INPUT_GET, 'get_attr', FILTER_SANITIZE_STRING );
        $get_attr_two = filter_input( INPUT_GET, 'get_attr_two', FILTER_SANITIZE_STRING );
        $this->aet_update_selected_ua_id(
            $get_val,
            $get_attr,
            $get_attr_two,
            'update',
            'ajax'
        );
    }
    
    /**
     * Update UA id.
     *
     * @param string $active_data With urlencode and base64encode data.
     *
     * @param string $request
     *
     * @param string $request_url
     *
     * @param string $action      update data.
     *
     * @since 3.0
     */
    public function aet_update_selected_ua_id(
        $active_data,
        $request,
        $request_url,
        $action,
        $save_type
    )
    {
        $option_key = 'selected_data_ua_' . $request;
        
        if ( 'ajax' === $save_type ) {
            $get_selected_data_ua = $active_data;
        } else {
            $get_selected_data_ua = urldecode( base64_decode( $active_data ) );
        }
        
        
        if ( 'update' === $action ) {
            update_option( $option_key, $get_selected_data_ua );
        } else {
            update_option( $option_key, '' );
        }
        
        $query_param = add_query_arg( array(
            'page' => 'aet-' . $request . '-settings',
        ), admin_url( 'admin.php' ) );
        
        if ( 'ajax' === $save_type ) {
            echo  wp_kses_post( $query_param ) ;
            wp_die();
        } else {
            wp_safe_redirect( $query_param );
            exit;
        }
    
    }
    
    /**
     * Setup Link.
     *
     * @since 3.0
     */
    public function aet_setup_link( $args )
    {
        $https = filter_input( INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING );
        $http_host = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING );
        $request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );
        
        if ( isset( $https ) && $https === 'on' ) {
            $link = 'https';
        } else {
            $link = 'http';
        }
        
        $link .= '://';
        $link .= $http_host;
        $link .= $request_uri;
        
        if ( 'ft' === $args ) {
            $server_URL = $this->base_url . '/' . $this->base_path . '/fb';
        } else {
            $server_URL = $this->base_url . '/' . $this->base_path . '/ec';
        }
        
        $setup_link = $server_URL . '?extra_url=' . rawurlencode( base64_encode( $link ) ) . '&chk=' . base64_encode( 'refer' );
        //Need to remove static URL
        return $setup_link;
    }
    
    /**
     * Our pages URL.
     *
     * @param string $page
     *
     * @param int    $id
     *
     * @param string $action
     *
     * @param string $wp_nonce
     *
     * @param bool   $admin_url
     *
     * @return string $url
     *
     * @since 3.0
     */
    public function aet_pages_url(
        $page,
        $id,
        $action,
        $wp_nonce,
        $admin_url
    )
    {
        $args = array();
        if ( $page ) {
            $args['page'] = $page;
        }
        if ( $id ) {
            $args['id'] = $id;
        }
        if ( $action ) {
            $args['action'] = $action;
        }
        if ( $wp_nonce ) {
            $args['_wpnonce'] = $wp_nonce;
        }
        
        if ( true === $admin_url ) {
            $get_admin_url = admin_url( 'admin.php' );
        } else {
            $get_admin_url = admin_url( 'admin.php' );
            //You can change as per your request
        }
        
        add_query_arg( $args, $get_admin_url );
        $url = esc_url( add_query_arg( $args, $get_admin_url ) );
        return $url;
    }
    
    /**
     * Fetch old plugins data.
     *
     * @since 3.0
     */
    public function aet_fetch_data()
    {
        check_ajax_referer( 'aet_fetch_data_nonce', 'nonce' );
        do_action( 'aet_migrate_option' );
        echo  1 ;
        delete_transient( 'aet_updated' );
        wp_die();
    }

}