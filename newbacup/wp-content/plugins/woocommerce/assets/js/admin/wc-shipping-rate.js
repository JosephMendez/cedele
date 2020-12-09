/* global shippingRateLocalizeScript, ajaxurl */
( function( $ ) {
    var old_datas;
    var changed_datas;
    var list_deletes = [];

    old_datas = {
        wc_mini_amount: shippingRateLocalizeScript.wc_mini_amount,
        wc_order_amount_below: shippingRateLocalizeScript.wc_order_amount_below,
        wc_apply_shipping_rate: shippingRateLocalizeScript.wc_apply_shipping_rate,
    }
    changed_datas = {
        wc_mini_amount: shippingRateLocalizeScript.wc_mini_amount,
        wc_order_amount_below: shippingRateLocalizeScript.wc_order_amount_below,
        wc_apply_shipping_rate: shippingRateLocalizeScript.wc_apply_shipping_rate,
    }

    load_input();
    function load_input() {
        var html = ''

        $('.wc-shipping-rate input[type="number"]').each(function(index, el) {
            var name = $(this).attr('name');

            $(this).val(changed_datas[name]);
        });
    }

    function check_changes() {
        var isChanged = false
        for (let key in changed_datas) {
            if (!changed_datas[key] && !old_datas[key]) {
                continue;
            }
            if (changed_datas[key] != old_datas[key]) {
                isChanged = true;
                break;
            }
        }

        if (isChanged) {
            enabled_button();
        } else {
            disabled_button();
        }
    }

    function check_valid() {
        if (changed_datas.wc_mini_amount === "" || 
            changed_datas.wc_order_amount_below === "" || 
            changed_datas.wc_apply_shipping_rate === "") {
            return false;
        }

        return true;
    }

    function enabled_button() {
        $('button.wc-shipping-rate-save').prop('disabled', false)
    }

    function disabled_button() {
        $('button.wc-shipping-rate-save').prop('disabled', true)
    }

    $('body').on('input', '.wc-shipping-rate input[type="number"]', function(event) {
        let value = $(this).val()
        let name = $(this).attr('name')
        value = value.replace('-', '');
        $(this).val(value);
        
        if (value == '')
            value = 0;

        let new_changed_datas = {...changed_datas};
        new_changed_datas[name] = value;
        changed_datas = new_changed_datas;
        check_changes();
    });

    $('body').on('click', 'button.wc-shipping-rate-save', function(event) {
        event.preventDefault();
        send_ajax()
    });

    function send_ajax() {
        if (!check_valid()) {
            alert('All fields are required!')
            return
        }
        jQuery.ajax({
            type: "POST",
            url: shippingRateLocalizeScript.url,
            dataType: 'json',
            data: {
                wc_mini_amount: changed_datas.wc_mini_amount,
                wc_order_amount_below: changed_datas.wc_order_amount_below,
                wc_apply_shipping_rate: changed_datas.wc_apply_shipping_rate,
                action: "woocommerce_shipping_rate_save_changes",
                wc_shipping_rate_nonce: shippingRateLocalizeScript.wc_shipping_rate_nonce
            },
            beforeSend: function() {
                $('.wc-shipping-rate').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(result) {
                if (result.success) {
                    old_datas = {
                        wc_mini_amount: result.data.wc_mini_amount,
                        wc_order_amount_below: result.data.wc_order_amount_below,
                        wc_apply_shipping_rate: result.data.wc_apply_shipping_rate,
                    }
                    changed_datas = {
                        wc_mini_amount: result.data.wc_mini_amount,
                        wc_order_amount_below: result.data.wc_order_amount_below,
                        wc_apply_shipping_rate: result.data.wc_apply_shipping_rate,
                    }
                    load_input()
                    disabled_button()
                } else {
                    alert(result.data)
                }
            },
            complete: function() {
                $('.wc-shipping-rate').unblock();
            }
        });
    }
})( jQuery, shippingRateLocalizeScript );
