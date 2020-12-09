<?php
add_filter('woocommerce_product_data_tabs', 'general_grouped_product_settings_tabs' );
function general_grouped_product_settings_tabs( $tabs ){
    $tabs['general_in_grouped'] = array(
        'label'    => 'General',
        'target'   => 'general_grouped_product_panels',
        'class'    => array('show_if_grouped'),
        'priority' => 1,
    );
    return $tabs;
 
}

add_action( 'woocommerce_product_data_panels', 'general_grouped_product_panels' );
function general_grouped_product_panels(){
    global $post;

    $post_id = $post->ID;
    $_regular_price_in_grouped = get_post_meta($post_id, '_regular_price_in_grouped', true);
    $_sale_price_in_grouped = get_post_meta($post_id, '_sale_price_in_grouped', true);

    echo '<div id="general_grouped_product_panels" class="panel woocommerce_options_panel show_if_grouped hidden">
        <div class="options_group pricing">';
 
        woocommerce_wp_text_input(
            array(
                'id'        => '_regular_price_in_grouped',
                'name'      => '_regular_price_in_grouped',
                'value'     => !empty($_regular_price_in_grouped) ? floatval($_regular_price_in_grouped) : '',
                'label'     => __( 'Regular price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'data_type' => 'price',
            )
        );

        woocommerce_wp_text_input(
            array(
                'id'          => '_sale_price_in_grouped',
                'name'        => '_sale_price_in_grouped',
                'value'       => !empty($_sale_price_in_grouped) ? floatval($_sale_price_in_grouped) : '',
                'data_type'   => 'price',
                'label'       => __( 'Sale price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'description' => '<a href="#" class="sale_schedule">' . __( 'Schedule', 'woocommerce' ) . '</a>',
            )
        );

        $_sale_price_in_grouped_dates_from = get_post_meta($post_id, '_sale_price_in_grouped_dates_from', true);
        $_sale_price_in_grouped_dates_to = get_post_meta($post_id, '_sale_price_in_grouped_dates_to', true);

        echo '<p class="form-field sale_price_dates_fields">
                <label for="_sale_price_dates_from">' . esc_html__( 'Sale price dates', 'woocommerce' ) . '</label>
                <input type="text" class="short" name="_sale_price_in_grouped_dates_from" id="_sale_price_in_grouped_dates_from" value="' . esc_attr( $_sale_price_in_grouped_dates_from ) . '" placeholder="' . esc_html( _x( 'From&hellip;', 'placeholder', 'woocommerce' ) ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
                <input type="text" class="short" name="_sale_price_in_grouped_dates_to" id="_sale_price_in_grouped_dates_to" value="' . esc_attr( $_sale_price_in_grouped_dates_to ) . '" placeholder="' . esc_html( _x( 'To&hellip;', 'placeholder', 'woocommerce' ) ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
                <a href="#" class="description cancel_sale_schedule">' . esc_html__( 'Cancel', 'woocommerce' ) . '</a>' . wc_help_tip( __( 'The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.', 'woocommerce' ) ) . '
            </p>';
    echo '</div>
        </div>';
}

add_action('woocommerce_process_product_meta', 'woocommerce_group_product_custom_general_fields_save');
function woocommerce_group_product_custom_general_fields_save($post_id)
{
    global $post;
    $post_id = $post->ID;

    $data = [
        '_regular_price_in_grouped'         => '',
        '_sale_price_in_grouped'            => '',
        '_sale_price_in_grouped_dates_from' => '',
        '_sale_price_in_grouped_dates_to'   => '',
    ];
    $data = shortcode_atts($data, $_REQUEST);

    update_post_meta($post_id, '_regular_price_in_grouped', $data['_regular_price_in_grouped']);
    update_post_meta($post_id, '_sale_price_in_grouped', $data['_sale_price_in_grouped']);
    update_post_meta($post_id, '_sale_price_in_grouped_dates_from', $data['_sale_price_in_grouped_dates_from']);
    update_post_meta($post_id, '_sale_price_in_grouped_dates_to', $data['_sale_price_in_grouped_dates_to']);
}
