<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.thedotstore.com
 * @since      3.0
 *
 * @package    Advance_Ecommerce_Tracking
 * @subpackage Advance_Ecommerce_Tracking/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Advance_Ecommerce_Tracking
 * @subpackage Advance_Ecommerce_Tracking/public
 * @author     Thedotstore <wordpress@multidots.in>
 */
class Advance_Ecommerce_Tracking_Public
{
    /**
     * Add inline js.
     *
     * @since    3.0
     * @access   private
     * @var      array $aet_js
     */
    private  $admin_obj = '' ;
    /**
     * Add inline js.
     *
     * @since    3.0
     * @access   private
     * @var      array $aet_js
     */
    private  $aet_js = array() ;
    /**
     * Stepping array.
     *
     * @since    3.0
     * @access   private
     * @var      array $aet_steps
     */
    private  $aet_int_aas_array = array() ;
    /**
     * Detail page variable.
     *
     * @since    3.0
     * @access   private
     * @var      array $aet_steps
     */
    private  $single_page = false ;
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
     * Store analytics data.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $aet_data = array() ;
    /**
     * Store facebook data.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $aft_data = array() ;
    /**
     * Store google conversion data.
     *
     * @since    3.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $agc_data = array() ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    3.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->aet_int_aas_array = $this->aet_interation_action_and_steps();
        $this->admin_obj = new Advance_Ecommerce_Tracking_Admin( '', '' );
        $this->aet_data = aet_get_all_aet_tracking_data( 'et' );
        $this->aft_data = aet_get_all_aft_tracking_data( 'ft' );
        $this->agc_data = aet_get_all_gc_tracking_data( 'gc' );
    }
    
    /**
     * User interaction steps.
     *
     * @since 3.0
     */
    private function aet_interation_action_and_steps()
    {
        return array(
            'clicked_product'    => array(
            'action' => 'click',
            'step'   => 1,
        ),
            'viewed_product'     => array(
            'action' => 'detail',
            'step'   => 2,
        ),
            'added_to_cart'      => array(
            'action' => 'add',
            'step'   => 3,
        ),
            'started_checkout'   => array(
            'action' => 'checkout',
            'step'   => 4,
        ),
            'completed_purchase' => array(
            'action' => 'purchase',
            'step'   => 5,
        ),
        );
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    3.0
     */
    public function enqueue_styles()
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
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/advance-ecommerce-tracking-public.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Load E-commerce tracking plugin.
     *
     * @param array  $aet_options
     *
     * @param string $enhance_ecommerce_tracking
     *
     * @return array $aet_options
     */
    public function aet_require_ec( $aet_options, $enhance_ecommerce_tracking )
    {
        if ( 'on' === $enhance_ecommerce_tracking ) {
            if ( empty($aet_options['ec']) ) {
                $aet_options['ec'] = "'require', 'ec'";
            }
        }
        return $aet_options;
    }
    
    /**
     * Add tracking code here.
     *
     * @since 3.0
     */
    public function aet_add_tracking_code()
    {
        $track_user = aet_tracking_user( 'et' );
        $src = $this->aet_tracking_url( 'ec' );
        $aet_options = $this->aet_tracking_option();
        $ua = aet_get_tracking_id( 'et' );
        
        if ( $track_user ) {
            $google_analytics_opt_code = '';
            $script_js = "(function (i, s, o, g, r, a, m) {i['GoogleAnalyticsObject'] = r;i[r] = i[r] || function () {\n\t\t\t\t\t\t   (i[r].q = i[r].q || []).push(arguments);}, i[r].l = 1 * new Date();a = s.createElement(o),\n\t\t\t\t\t\t    m = s.getElementsByTagName(o)[0];a.async = 1;a.src = g;m.parentNode.insertBefore(a, m);})\n\t\t\t\t\t        (window, document, 'script', '" . $src . "', '" . self::aet_analytics_var() . "');";
            ?>
			<script type="text/javascript">
				<?php 
            echo  $google_analytics_opt_code . "\n" ;
            echo  wp_kses_post( $script_js ) . "\n" ;
            if ( count( $aet_options ) >= 1 ) {
                foreach ( $aet_options as $value ) {
                    
                    if ( !is_array( $value ) ) {
                        echo  wp_kses_post( self::aet_analytics_var() ) . '(' . wp_kses_post( $value ) . ");\n" ;
                    } else {
                        if ( !empty($value['value']) ) {
                            echo  '' . wp_kses_post( $value['value'] ) . "\n" ;
                        }
                    }
                
                }
            }
            ?>
								window['<?php 
            echo  wp_kses_post( self::aet_analytics_var() ) ;
            ?>'] = <?php 
            echo  wp_kses_post( self::aet_analytics_var() ) ;
            ?>;
			</script>
			<?php 
        }
    
    }
    
    /**
     * URl for the tracking.
     *
     * @param string $args
     *
     * @return string $src
     *
     * @since 3.0
     */
    public function aet_tracking_url( $args )
    {
        $debug_mode = DEBUG_OPTION;
        if ( 'ec' === $args ) {
            
            if ( true === $debug_mode ) {
                $src = apply_filters( 'aet_analytics_src', '//www.google-analytics.com/analytics_debug.js' );
            } else {
                $src = apply_filters( 'aet_analytics_src', '//www.google-analytics.com/analytics.js' );
            }
        
        }
        if ( 'ft' === $args ) {
            
            if ( true === $debug_mode ) {
                $src = apply_filters( 'aet_facebook_src', 'https://connect.facebook.net/en_US/fbevents.js' );
            } else {
                $src = apply_filters( 'aet_facebook_src', 'https://connect.facebook.net/en_US/fbevents.js' );
            }
        
        }
        if ( 'gc' === $args ) {
            
            if ( true === $debug_mode ) {
                $src = apply_filters( 'aet_facebook_src', '//www.googleadservices.com/pagead/conversion.js' );
            } else {
                $src = apply_filters( 'aet_facebook_src', '//www.googleadservices.com/pagead/conversion.js' );
            }
        
        }
        return $src;
    }
    
    /**
     * Get tracking option.
     *
     * @return array $aet_options
     *
     * @since 3.0
     */
    public function aet_tracking_option()
    {
        global  $wp_query ;
        $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
        $ua = aet_get_tracking_id( 'et' );
        $aet_options = array();
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $aet_options['create'] = "'create', '" . esc_js( $ua ) . "', '" . esc_js( 'auto' ) . "'";
            $aet_options = apply_filters( 'aet_tracking_require_filter', $aet_options, $enhance_ecommerce_tracking );
            $aet_options['send'] = "'send','pageview'";
            $aet_options = apply_filters( 'aet_tracking_options_end', $aet_options );
            return $aet_options;
        }
    
    }
    
    /**
     * Create unique tracker variable for analytics.
     *
     * @since 3.0
     */
    public static function aet_analytics_var()
    {
        return apply_filters( 'aet_ga_tracker_variable', '__gatd' );
    }
    
    /**
     *
     * Get taxonomy list
     *
     * @param string $taxonomy .
     *
     * @param int    $post_id
     *
     * @return array $results
     *
     * @since 3.0
     */
    public function aet_get_taxonomy_list( $taxonomy, $post_id )
    {
        $terms = get_the_terms( $post_id, $taxonomy );
        $results = array();
        if ( is_wp_error( $terms ) || empty($terms) ) {
            return array();
        }
        foreach ( $terms as $term ) {
            $results[] = html_entity_decode( $term->name );
        }
        return $results;
    }
    
    /**
     * Get action for user interaction.
     *
     * @param string $event_key
     *
     * @return array $action
     *
     * @since 3.0
     */
    private function aet_get_interation_action( $event_key )
    {
        $action = '';
        if ( isset( $this->aet_int_aas_array[$event_key], $this->aet_int_aas_array[$event_key]['action'] ) ) {
            $action = $this->aet_int_aas_array[$event_key]['action'];
        }
        return $action;
    }
    
    /**
     * Get step for user interaction.
     *
     * @param string $event_key
     *
     * @return array $step
     *
     * @since 3.0
     */
    private function aet_get_interation_step( $event_key )
    {
        $step = '';
        if ( isset( $this->aet_int_aas_array[$event_key], $this->aet_int_aas_array[$event_key]['step'] ) ) {
            $step = $this->aet_int_aas_array[$event_key]['step'];
        }
        return $step;
    }
    
    /**
     * Track checkout page.
     *
     * @since 3.0
     */
    public function aet_checkout_process()
    {
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
            
            if ( 'on' === $enhance_ecommerce_tracking ) {
                if ( aet_is_page_reload() ) {
                    return;
                }
                $get_cart = WC()->cart->get_cart();
                if ( empty($get_cart) ) {
                    return;
                }
                $aet_api_attr = array(
                    't'              => 'event',
                    'ec'             => 'Checkout',
                    'ea'             => 'Initial Checkout',
                    'el'             => 'Checkout Section',
                    'ev'             => '',
                    'cos'            => 1,
                    'pa'             => $this->aet_get_interation_action( 'started_checkout' ),
                    'pal'            => '',
                    'nonInteraction' => true,
                );
                $items = array();
                $i = 0;
                foreach ( $get_cart as $item ) {
                    $i++;
                    $product_id = $item['product_id'];
                    $product = null;
                    $attribute_value_implode = '';
                    
                    if ( !empty($item['variation_id']) ) {
                        $product = wc_get_product( $item['variation_id'] );
                        $product_title = $product->get_name();
                        
                        if ( $product->is_type( 'variable' ) ) {
                            $variation_attributes = $product->get_variation_attributes();
                            $variation_attributes_array = array();
                            foreach ( $variation_attributes as $term_slug ) {
                                $variation_attributes_array[] = ucwords( $term_slug );
                            }
                            $total_attribute_value = count( $variation_attributes_array );
                            
                            if ( $total_attribute_value > 1 ) {
                                $attribute_value_implode = implode( ', ', $variation_attributes_array );
                            } else {
                                $attribute_value_implode = $variation_attributes_array['0'];
                            }
                        
                        }
                    
                    } else {
                        $product = wc_get_product( $product_id );
                        $product_title = $product->get_name();
                    }
                    
                    $categories = implode( ', ', $this->aet_get_taxonomy_list( 'product_cat', $product_id ) );
                    $prd_key = 'pr' . $i . 'id';
                    $prd_name = 'pr' . $i . 'nm';
                    $prd_cat = 'pr' . $i . 'ca';
                    $prd_va = 'pr' . $i . 'va';
                    $prd_pr = 'pr' . $i . 'pr';
                    $prd_qt = 'pr' . $i . 'qt';
                    $prd_ps = 'pr' . $i . 'ps';
                    $items[$prd_key] = $product_id;
                    // Product ID
                    $items[$prd_name] = $product_title;
                    // Product Name
                    $items[$prd_cat] = $categories;
                    // Product Category
                    $items[$prd_va] = $attribute_value_implode;
                    // Product Variation Title
                    $items[$prd_qt] = $item['quantity'];
                    // Product Quantity
                    $items[$prd_pr] = $product->get_price();
                    // Product Price
                    $items[$prd_ps] = $i;
                    // Product Order
                }
                $aet_api_attr = array_merge( $aet_api_attr, $items );
                aet_measurement_protocol_api_call( $aet_api_attr );
            }
        
        }
    
    }
    
    /**
     * Store order id after order complete
     *
     * @since 3.0
     */
    public function aet_store_order_id( $order_id )
    {
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
            
            if ( 'on' === $enhance_ecommerce_tracking ) {
                $get_order_id = get_post_meta( $order_id, 'order_id_wth_uuid', true );
                if ( !empty($get_order_id) ) {
                    return;
                }
                $ga_uuid = aet_measurement_protocol_get_client_id();
                if ( $ga_uuid ) {
                    update_post_meta( $order_id, 'order_id_wth_uuid', $ga_uuid );
                }
            }
        
        }
    
    }
    
    /**
     * Add order to analytics
     *
     * @since 3.0
     */
    public function aet_order_pro_comp( $order_id )
    {
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
            
            if ( 'on' === $enhance_ecommerce_tracking ) {
                $aet_placed_order_success = get_post_meta( $order_id, 'aet_placed_order_success', true );
                if ( 'true' === $aet_placed_order_success ) {
                    return;
                }
                $order = wc_get_order( $order_id );
                $discount = '';
                if ( count( $order->get_coupon_codes() ) > 0 ) {
                    foreach ( $order->get_coupon_codes() as $coupon_code ) {
                        
                        if ( !$coupon_code ) {
                            continue;
                        } else {
                            $discount = $coupon_code;
                            break;
                        }
                    
                    }
                }
                $ga_uuid = aet_measurement_protocol_get_client_id( $order_id );
                $aet_api_attr = array(
                    't'   => 'event',
                    'ec'  => 'Checkout',
                    'ea'  => 'Completed Checkout',
                    'el'  => $order_id,
                    'ev'  => round( $order->get_total() ),
                    'cos' => 2,
                    'pa'  => $this->aet_get_interation_action( 'completed_purchase' ),
                    'cid' => $ga_uuid,
                    'ti'  => $order_id,
                    'ta'  => null,
                    'tr'  => $order->get_total(),
                    'tt'  => $order->get_total_tax(),
                    'ts'  => $order->get_shipping_total(),
                    'tcc' => $discount,
                );
                
                if ( is_user_logged_in() ) {
                    $aet_api_attr['uid'] = $order->get_user_id();
                    // UserID tracking
                }
                
                // Declare items in cart
                $cart_contents = $order->get_items();
                $items = array();
                $i = 0;
                foreach ( $cart_contents as $item ) {
                    $i++;
                    $variation_id = ( $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id() );
                    $product_id = ( $variation_id > 0 ? wp_get_post_parent_id( $variation_id ) : 0 );
                    $product = null;
                    $attribute_value_implode = '';
                    
                    if ( false === $product_id ) {
                        $product_id = $variation_id;
                        $product = wc_get_product( $product_id );
                        $product_title = $product->get_name();
                    } else {
                        $product = wc_get_product( $variation_id );
                        $product_title = $product->get_name();
                        
                        if ( $product->is_type( 'variable' ) ) {
                            $variation_attributes = $product->get_variation_attributes();
                            $variation_attributes_array = array();
                            foreach ( $variation_attributes as $term_slug ) {
                                $variation_attributes_array[] = ucwords( $term_slug );
                            }
                            $total_attribute_value = count( $variation_attributes_array );
                            
                            if ( $total_attribute_value > 1 ) {
                                $attribute_value_implode = implode( ', ', $variation_attributes_array );
                            } else {
                                $attribute_value_implode = $variation_attributes_array['0'];
                            }
                        
                        }
                    
                    }
                    
                    $categories = implode( ', ', $this->aet_get_taxonomy_list( 'product_cat', $product_id ) );
                    $prd_key = 'pr' . $i . 'id';
                    $prd_name = 'pr' . $i . 'nm';
                    $prd_cat = 'pr' . $i . 'ca';
                    $prd_va = 'pr' . $i . 'va';
                    $prd_pr = 'pr' . $i . 'pr';
                    $prd_qt = 'pr' . $i . 'qt';
                    $prd_ps = 'pr' . $i . 'ps';
                    $items[$prd_key] = $product_id;
                    // Product ID
                    $items[$prd_name] = $product_title;
                    // Product Name
                    $items[$prd_cat] = $categories;
                    // Product Category
                    $items[$prd_va] = $attribute_value_implode;
                    // Product Variation Title
                    $items[$prd_pr] = $order->get_item_total( $item );
                    // Product Price
                    $items[$prd_qt] = $item->get_quantity();
                    // Product Quantity
                    $items[$prd_ps] = $i;
                    // Product Order
                }
                $aet_api_attr = array_merge( $aet_api_attr, $items );
                aet_measurement_protocol_api_call( $aet_api_attr );
                update_post_meta( $order_id, 'aet_placed_order_success', 'true' );
            }
        
        }
    
    }
    
    /**
     * Change return URL for Paypal. Using default URL it override transaction's data. So need to change URL.
     *
     * @param string $paypal_url
     *
     * @return string $paypal_url
     *
     * @since 3.0
     */
    public function aet_change_return_url( $paypal_url )
    {
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
            
            if ( 'on' === $enhance_ecommerce_tracking ) {
                $paypal_url = remove_query_arg( 'utm_nooverride', $paypal_url );
                $paypal_url = add_query_arg( 'utm_nooverride', '1', $paypal_url );
                return $paypal_url;
            }
        
        }
    
    }
    
    /**
     * Add js code for tracking in one variable
     *
     * @since 3.0
     */
    public function aet_et_tracking_imp_js_code_in_footer()
    {
        $track_user = aet_tracking_user( 'et' );
        
        if ( $track_user ) {
            $enhance_ecommerce_tracking = $this->aet_data['enhance_ecommerce_tracking'];
            
            if ( 'on' === $enhance_ecommerce_tracking ) {
                
                if ( !empty($this->aet_js['impression']) ) {
                    foreach ( $this->aet_js['impression'] as $imporession_code ) {
                        wc_enqueue_js( $imporession_code );
                    }
                    wc_enqueue_js( $this->aet_send_event_hit__premium_only(
                        'event',
                        'Products',
                        'Impression',
                        'Impression',
                        '',
                        'true'
                    ) );
                }
                
                if ( !empty($this->aet_js['event']) ) {
                    foreach ( $this->aet_js['event'] as $event_code ) {
                        wc_enqueue_js( $event_code );
                    }
                }
            }
        
        }
    
    }
    
    /**
     * Call the event object
     *
     * @param string $event_name
     * @param mixed  $params
     * @param string $method
     *
     * @return string
     */
    public function call_event( $event_name, $params, $method )
    {
        return sprintf(
            "fbq('%s', '%s', %s);",
            $method,
            $event_name,
            wp_json_encode( $params, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT )
        );
    }
    
    /**
     * Add facebook Tracking code.
     *
     * @since 3.0
     */
    public function aet_add_ft_tracking_code()
    {
        $track_user = aet_ft_tracking_user( 'ft' );
        
        if ( $track_user ) {
            $src = $this->aet_tracking_url( 'ft' );
            $ua = aet_get_tracking_id( 'ft' );
            $advanced_matching_event_code = '';
            $init_call_event = '';
            $init_call_event = $this->init_call_event( $ua, 'init' ) . "\n";
            $fb_js_code = "!function (f, b, e, v, n, t, s) {if (f.fbq) return; n = f.fbq = function () {n.callMethod ?\n\t\t\t\t\t\tn.callMethod.apply(n, arguments) : n.queue.push(arguments)}; if (!f._fbq) f._fbq = n;\n\t\t\t\t\t\tn.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;\n\t\t\t\t\t\tt.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)\n\t\t\t\t\t\t}(window,document, 'script', '" . $src . "');";
            ?>
			<script type="text/javascript">
				<?php 
            echo  wp_kses_post( $fb_js_code ) . "\n" ;
            echo  wp_kses_post( $init_call_event ) ;
            echo  wp_kses_post( $advanced_matching_event_code ) ;
            echo  wp_kses_post( $this->init_call_event( 'PageView', 'track' ) ) . "\n" ;
            ?>
			</script>
			<?php 
        }
    
    }
    
    /**
     * Init Call the event object
     *
     * @param string $event_name
     * @param mixed  $params
     * @param string $method
     *
     * @return string
     */
    public function init_call_event( $event_name, $method )
    {
        return sprintf( "fbq('%s', '%s');", $method, $event_name );
    }
    
    /**
     * Print js with script.
     *
     * @param string $code
     *
     * @since 3.0
     */
    public function aet_print_js_for_ft( $code )
    {
        ?>
		<script type="text/javascript">
			<?php 
        echo  wp_kses_post( $code ) ;
        ?>
		</script>
		<?php 
    }
    
    /**
     * Track initial Checkout Process.
     *
     * @since 3.0
     */
    public function aet_ft_checkout_process()
    {
        $track_user = aet_ft_tracking_user( 'ft' );
        
        if ( $track_user ) {
            $fb_ecommerce_tracking = $this->aft_data['fb_ecommerce_tracking'];
            
            if ( 'on' === $fb_ecommerce_tracking ) {
                if ( aet_is_page_reload() ) {
                    return;
                }
                $get_cart = WC()->cart->get_cart();
                if ( empty($get_cart) ) {
                    return;
                }
                $product_id_array = array();
                $product_title_array = array();
                $price_total = 0;
                $total_item_qty = 0;
                $all_content = array();
                foreach ( $get_cart as $item ) {
                    $product_id = $item['product_id'];
                    $product = null;
                    
                    if ( !empty($item['variation_id']) ) {
                        $product = wc_get_product( $item['variation_id'] );
                    } else {
                        $product = wc_get_product( $product_id );
                    }
                    
                    $price_total_with_qty = (double) $product->get_price() * (int) $item['quantity'];
                    $product_id_array[] = $product_id;
                    $product_title_array[] = get_the_title( $product_id );
                    $price_total += (double) $price_total_with_qty;
                    $total_item_qty += $item['quantity'];
                    $all_content[$product_id]['product_id'] = $product_id;
                    $all_content[$product_id]['product_name'] = get_the_title( $product_id );
                    $all_content[$product_id]['product_qty'] = $item['quantity'];
                    $all_content[$product_id]['product_price'] = $product->get_price();
                }
                $data_array = array(
                    'content_ids'  => wp_json_encode( $product_id_array ),
                    'item_price'   => $price_total,
                    'content_name' => wp_json_encode( $product_title_array ),
                    'currency'     => get_woocommerce_currency(),
                    'num_items'    => $total_item_qty,
                    'contents'     => wp_json_encode( $all_content ),
                    'value'        => $price_total,
                );
                $code = $this->call_event( 'InitiateCheckout', $data_array, 'track' );
                echo  wp_kses_post( $this->aet_print_js_for_ft( $code ) ) ;
            }
        
        }
    
    }
    
    /**
     * Track order in fb.
     *
     * @param int $order_id
     *
     * @since 3.0
     */
    public function aet_ft_order_pro_comp( $order_id )
    {
        $track_user = aet_ft_tracking_user( 'ft' );
        
        if ( $track_user ) {
            $fb_ecommerce_tracking = $this->aft_data['fb_ecommerce_tracking'];
            
            if ( 'on' === $fb_ecommerce_tracking ) {
                $aet_ft_placed_order_success = get_post_meta( $order_id, 'aet_ft_placed_order_success', true );
                if ( 'true' === $aet_ft_placed_order_success ) {
                    return;
                }
                $order = wc_get_order( $order_id );
                $discount = '';
                if ( count( $order->get_coupon_codes() ) > 0 ) {
                    foreach ( $order->get_coupon_codes() as $coupon_code ) {
                        
                        if ( !$coupon_code ) {
                            continue;
                        } else {
                            $discount = $coupon_code;
                            break;
                        }
                    
                    }
                }
                $cart_contents = $order->get_items();
                $main_array = array();
                $sub_array = array();
                $item_array = array();
                $orders_detail = array();
                $product_id_array = array();
                $product_title_array = array();
                $total_item_qty = 0;
                foreach ( $cart_contents as $item ) {
                    $variation_id = ( $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id() );
                    $product_id = ( $variation_id > 0 ? wp_get_post_parent_id( $variation_id ) : 0 );
                    $product = null;
                    $attribute_value_implode = '';
                    
                    if ( false === $product_id ) {
                        $product_id = $variation_id;
                        $product = wc_get_product( $product_id );
                        $product_title = $product->get_name();
                    } else {
                        $product = wc_get_product( $variation_id );
                        $product_title = $product->get_name();
                        
                        if ( $product->is_type( 'variable' ) ) {
                            $variation_attributes = $product->get_variation_attributes();
                            $variation_attributes_array = array();
                            foreach ( $variation_attributes as $term_slug ) {
                                $variation_attributes_array[] = ucwords( $term_slug );
                            }
                            $total_attribute_value = count( $variation_attributes_array );
                            
                            if ( $total_attribute_value > 1 ) {
                                $attribute_value_implode = implode( ', ', $variation_attributes_array );
                            } else {
                                $attribute_value_implode = $variation_attributes_array['0'];
                            }
                        
                        }
                    
                    }
                    
                    $product_id_array[] = $product_id;
                    $product_title_array[] = $product_title;
                    $item_array[$product_id]['product_id'] = $product_id;
                    $item_array[$product_id]['product_name'] = $product_title;
                    $item_array[$product_id]['variant'] = $attribute_value_implode;
                    $item_array[$product_id]['product_qty'] = $item->get_quantity();
                    $item_array[$product_id]['product_price'] = $order->get_item_total( $item );
                    $total_item_qty += $item->get_quantity();
                }
                $orders_detail['order_sub_total'] = $order->get_subtotal();
                $orders_detail['coupon'] = $discount;
                $orders_detail['coupon_discount'] = $order->get_discount_total();
                $orders_detail['shipping_total'] = $order->get_shipping_total();
                $orders_detail['tax'] = $order->get_total_tax();
                $orders_detail['order_total'] = $order->get_total();
                $sub_array['items'] = $item_array;
                $sub_array['orders_detail'] = $orders_detail;
                $main_array['orders'] = $sub_array;
                $data_array = array(
                    'content_ids'  => wp_json_encode( $product_id_array ),
                    'item_price'   => $order->get_total(),
                    'content_name' => wp_json_encode( $product_title_array ),
                    'currency'     => get_woocommerce_currency(),
                    'num_items'    => $total_item_qty,
                    'contents'     => wp_json_encode( $main_array ),
                    'value'        => $order->get_total(),
                );
                update_post_meta( $order_id, 'aet_ft_placed_order_success', 'true' );
                $code = $this->call_event( 'Purchase', $data_array, 'track' );
                echo  wp_kses_post( $this->aet_print_js_for_ft( $code ) ) ;
            }
        
        }
    
    }
    
    /**
     * Post timout exceed.
     *
     * @param int $time
     *
     * @return int $time
     *
     * @since 3.0
     */
    public function aet_post_timeout( $time )
    {
        return 5;
    }
    
    /**
     * Google conversion code.
     *
     * @param int $order_id
     *
     * @since 3.0.0
     */
    public function aet_gc_tracking_code( $order_id )
    {
        $gc_enable = $this->agc_data['gc_enable'];
        
        if ( 'on' === $gc_enable ) {
            $gc_id = $this->agc_data['gc_id'];
            $gc_label = $this->agc_data['gc_label'];
            
            if ( !empty($gc_id) || !empty($gc_label) ) {
                $order = wc_get_order( $order_id );
                $currency = $order->get_currency();
                $total = $order->get_total();
                $gc_url = $this->aet_tracking_url( 'gc' );
                $js_variable_code = '';
                $js_variable_code .= "const google_conversion_id = '" . esc_attr( $gc_id ) . "';";
                $js_variable_code .= "const google_conversion_language = 'en';";
                $js_variable_code .= "const google_conversion_format = '3';";
                $js_variable_code .= "const google_conversion_color = 'ffffff';";
                $js_variable_code .= "const google_conversion_label = '" . esc_attr( $gc_label ) . "';";
                $js_variable_code .= "const google_conversion_value = '" . esc_attr( $total ) . "';";
                $js_variable_code .= "const google_conversion_currency = '" . esc_attr( $currency ) . "';";
                $js_variable_code .= "const google_remarketing_only = 'false';";
                ?>
				<script type="text/javascript">
					<?php 
                echo  wp_kses_post( $js_variable_code ) ;
                ?>
				</script>
				<script type="text/javascript" src="<?php 
                echo  esc_url( $gc_url ) ;
                ?>"></script>
				<noscript>
					<div style="display:inline;">
						<?php 
                echo  '<img height="1" width="1" style="border-style:none;" alt="" src="' . $gc_url . '/' . esc_attr( $gc_id ) . '/?value=' . esc_attr( $total ) . '&amp;currency_code=' . esc_attr( $currency ) . '&amp;label=' . esc_attr( $gc_label ) . '&amp;guid=ON&amp;script=0"/>' ;
                ?>
					</div>
				</noscript>
				<?php 
            }
        
        }
    
    }

}