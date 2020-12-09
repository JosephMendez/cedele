var admin = {
    changeStatus: function () {
        jQuery('#order_status').change(function () {
            if (jQuery(this).val() == 'wc-failed') {
                jQuery('#p-failed-reason').show();
                jQuery('#failed_reason').attr('required', true);
            } else {
                jQuery('#p-failed-reason').hide();
                jQuery('#failed_reason').removeAttr('required');
            }
        })
    },
    setMaxForOrderMount: function () {
        var max = parseFloat(jQuery('#wc_mini_amount').val());
        jQuery('#wc_order_amount_below').prop('max', max);
    },
    shipping: function () {
        jQuery('.wc-shipping-rate #wc_order_amount_below').off('change').on('change', function () {
            var max = parseFloat(jQuery('#wc_mini_amount').val());
            var value = parseFloat(jQuery(this).val())
            if (value > max) {
                jQuery(this).val(max);
            }
        });
        jQuery('.wc-shipping-rate #wc_mini_amount').off('change').on('change', function () {
            admin.setMaxForOrderMount();
        });
    },
    filter_order: function () {
        if ( typeof jQuery.fn.datepicker === "function" ) {
            jQuery("#delivery_date_from").datepicker();
            jQuery("#delivery_date_to").datepicker();
        }
    }
}

jQuery(document).ready(function () {
    admin.changeStatus();
    admin.shipping();
    admin.setMaxForOrderMount();
    admin.filter_order();
})