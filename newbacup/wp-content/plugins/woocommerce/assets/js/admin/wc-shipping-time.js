/* global shippingTimeLocalizeScript, ajaxurl */
( function( $ ) {
    $('body').on('click', 'button.wc-shipping-time-save', function(event) {
        event.preventDefault();
        send_ajax()
    });

    $('body').on('input', 'input[id^="shipping_cost"]', function(event) {
        let name = $(this).attr('name')
        let value = $(this).val()
        value = value.replace('-', '');
        $(this).val(value);
        changes[name] = value;
        
        check_change();
    });

    var old_object = {...shippingTimeLocalizeScript.data};
    var changes = {...shippingTimeLocalizeScript.data};

    load_input();
    function load_input() {
        const {
            normal_hour_cost = 0,
            peak_hour_cost = 0,
            working_day_cost = 0,
            weekend_cost = 0,
            occasion_cost = 0
        } = changes;
        $('#shipping_cost_peak_hour').val(parseFloat(peak_hour_cost))
        $('#shipping_cost_weekend').val(parseFloat(weekend_cost))
        $('#shipping_cost_occasions').val(parseFloat(occasion_cost))
    }

    function check_change() {
        const array_keys = ['normal_hour_cost', 'peak_hour_cost', 'working_day_cost', 'weekend_cost', 'occasion_cost']
        let flag = false
        for (let i = 0; i < array_keys.length; i++) {
            if (!parseFloat(old_object[array_keys[i]]) && !parseFloat(changes[array_keys[i]]))
                continue; 
            if (parseFloat(old_object[array_keys[i]]) != parseFloat(changes[array_keys[i]]))
                flag = true;
        }

        if (flag) {
            enabled_button();
        } else {
            disabled_button(); 
        }
    }

    function enabled_button() {
        $('button.wc-shipping-time-save').prop('disabled', false)
    }

    function disabled_button() {
        $('button.wc-shipping-time-save').prop('disabled', true)
    }

    function send_ajax() {
        jQuery.ajax({
            type: "POST",
            url: shippingTimeLocalizeScript.url,
            dataType: 'json',
            data: {
                changes: changes,
                action: "woocommerce_shipping_times_save_changes",
                wc_shipping_time_nonce: shippingTimeLocalizeScript.wc_shipping_time_nonce
            },
            beforeSend: function() {
                $('.wc-shipping-time').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(result) {
                if (result.success) {
                    changes = {...result.data}
                    old_object = {...result.data}
                    check_change()
                } else {
                    alert(result.data)
                }
            },
            complete: function() {
                $('.wc-shipping-time').unblock();
            }
        });
    }
})( jQuery, shippingTimeLocalizeScript );
