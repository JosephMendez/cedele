<?php
    add_filter( 'woocommerce_get_sections_products', 'product_custom_section' );

    function product_custom_section( $sections ) {
        $sections['settings_product_custom'] = __( 'Rewards Product Settings', 'cedele' );
        return $sections;
    }

    function add_products_settings( $settings, $current_section ) {
        if ( 'settings_product_custom' === $current_section ) {
            $custom_field = array(
                array(
                    'name'     => __( 'Product ID:', 'cedele' ),
                    'id'       => 'rewards_product_id',
                    'type'     => 'text',
                ),
                array(
                    'type'  => 'sectionend',
                    'id'    => 'settings_product_custom',
                ),
            );
            return $custom_field;
        } else {
            return $settings;
        }
    }
    add_filter( 'woocommerce_get_settings_products', 'add_products_settings', 10, 2 );

?>