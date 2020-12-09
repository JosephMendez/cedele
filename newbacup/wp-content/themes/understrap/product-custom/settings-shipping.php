<?php
    function shipping_custom_section( $sections ) {
        unset($sections['shipping_time']);
        unset($sections['shipping_rate']);
        $sections['shipping_term_conditions'] = __( 'Shipping terms & conditions', 'cedele' );
        return $sections;
    }
    add_filter( 'woocommerce_get_sections_shipping', 'shipping_custom_section' );

    function add_my_products_settings( $settings, $current_section ) {
        if ( 'shipping_term_conditions' === $current_section ) {
            $custom_field = array(
                // Regular Shipping
                array(
                    'name' => __( 'Regular:', 'cedele' ),
                    'id'       => 'minimum_label',
                    'type'       => 'title',
                ),
                array(
                    'name' => __( 'Free shipping condition:', 'cedele' ),
                    'id'       => 'minimum_label',
                    'type'       => 'title',
                ),
                array(
                    'name'     => __( 'Minimum order amount for FREE shipping:', 'cedele' ),
                    'id'       => 'wc_mini_amount',
                    'type'     => 'number',
                ),
                array(
                    'type'  => 'sectionend',
                    'id'    => 'shipping_term_conditions',
                ),
                array(
                    'name' => __( 'Shipping Condition:', 'cedele' ),
                    'id'       => 'minimum_label',
                    'type'       => 'title',
                ),
                array(
                    'name'     => __( 'Minimum order amount for shipping:', 'cedele' ),
                    'id'       => 'wc_order_amount_below',
                    'type'     => 'number',
                ),
                array(
                    'name'     => __( 'Shipping fee:', 'cedele' ),
                    'id'       => 'wc_apply_shipping_rate',
                    'type'     => 'number',
                ),
                array(
                    'name'     => __( 'Extra fee:', 'cedele' ),
                    'id'       => 'wc_order_peak_hour',
                    'type'     => 'number',
                    'desc' => 'peak hour'
                ),
                array(
                    'type'  => 'sectionend',
                    'id'    => 'shipping_term_conditions',
                ),

                // Occasions Shipping
                array(
                    'name' => __( 'Occasions:', 'cedele' ),
                    'id'       => 'occasions_minimum_label',
                    'type'       => 'title',
                    'class' => 'heading_occasions'
                ),
                array(
                    'name' => __( 'Free shipping condition:', 'cedele' ),
                    'id'       => 'minimum_label_occasions',
                    'type'       => 'title',
                ),
                array(
                    'name'     => __( 'Minimum order amount for FREE shipping:', 'cedele' ),
                    'id'       => 'occasions_wc_mini_amount',
                    'type'     => 'number',
                ),
                array(
                    'type'  => 'sectionend',
                    'id'    => 'shipping_term_conditions',
                ),
                array(
                    'name' => __( 'Shipping Condition:', 'cedele' ),
                    'id'       => 'occasions_minimum_label',
                    'type'       => 'title',
                ),
                array(
                    'name'     => __( 'Minimum order amount for shipping:', 'cedele' ),
                    'id'       => 'occasions_wc_order_amount_below',
                    'type'     => 'number',
                ),
                array(
                    'name'     => __( 'Shipping fee:', 'cedele' ),
                    'id'       => 'occasions_wc_apply_shipping_rate',
                    'type'     => 'number',
                ),
                array(
                    'name'     => __( 'Extra fee', 'cedele' ),
                    'id'       => 'occasions_wc_order_peak_hour',
                    'type'     => 'number',
                    'desc' => 'peak hour'
                ),
                array(
                    'type'  => 'sectionend',
                    'id'    => 'shipping_term_conditions',
                ),
            );
            return $custom_field;
        } else {
            return $settings;
        }
    }
    add_filter( 'woocommerce_get_settings_shipping', 'add_my_products_settings', 10, 2 );


?>