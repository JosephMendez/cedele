jQuery(document).ready(function($) {
    init_time_picker()
    function init_time_picker(argument) {
        if (jQuery('.product-availability-timedatepicker').length){
            jQuery('.product-availability-timedatepicker').xddatetimepicker({
                datepicker: false,
                format: 'H:i',
                formatTime: 'H:i',
                step: 30
            })
        }
    }
    // radio
    toggle_product_store()
    function toggle_product_store() {
        // let checked = jQuery('.wp-product-delivery-method input[value="delivery"]').is(':checked');

        // if (checked) {
        //     jQuery('.wp-product-store-location').hide();
        // } else {
        //     jQuery('.wp-product-store-location').show();
        // }
    }
    if (check_checkbox()) {
        jQuery('.wp-product-checkbox-all').prop('checked', true)
    }
    jQuery('body').on('click', '.wp-product-delivery-method input[type="radio"]', function(e) {
        toggle_product_store();
    })

    // checkbox
    jQuery('body').on('click', '.wp-product-checkbox-all', function(e) {
        let checked = jQuery(this).is(':checked');
        jQuery('.wp-product-checkbox').prop('checked', checked)
    })

    jQuery('body').on('click', '.wp-product-checkbox', function(e) {
        let checked = jQuery(this).is(':checked');

        if (checked && check_checkbox()) {
            jQuery('.wp-product-checkbox-all').prop('checked', true)
        } else if (!checked) {
            jQuery('.wp-product-checkbox-all').prop('checked', false)
        }
    })

    function check_checkbox() {
        var flag = true;
        jQuery('.wp-product-checkbox').each(function(index, el) {
            let checked = jQuery(this).is(':checked');

            if (!checked) {
                flag = false
            }
        });

        return flag;
    }

    // product lead time
    get_product_lead_time()
    function get_product_lead_time() {
        jQuery('.wp-product-lead-time input[type="radio"]').each(function(index, el) {
            var checked = jQuery(this).is(':checked');
            if (checked) {
                jQuery(this).siblings().show();
                jQuery(this).siblings().find('input[type="number"]').prop('required', true);
            }
        });
    }

    jQuery('body').on('click', '.wp-product-lead-time input[type="radio"]', function(event) {
        jQuery('.wp-product-lead-time span').hide()
        jQuery('.wp-product-lead-time input[type="number"]').prop('required', false);
        jQuery(this).siblings().show()
        jQuery(this).siblings().find('input[type="number"]').prop('required', true);
    });

    jQuery('#variable_product_options .save-variation-changes').click(function(event) {
        var emptyValue = false
        jQuery('#variable_product_options div.woocommerce_variation').find('.wc_input_price[name^=variable_regular_price]').each(function(e) {
            let val = jQuery(this).val()
            if (!val)
                emptyValue = true
        })

        if (emptyValue) {
            alert('Variation\'s regular price is required!')
            return false
        }
    });

    jQuery('form#post').submit(function(event) {
        let productDataDiv = jQuery('form#post #woocommerce-product-data');
        let value = jQuery('form#post #_regular_price').val();
        let price_in_grouped = jQuery('form#post #_regular_price_in_grouped').val();
        let productType = jQuery('form#post #product-type').val();

        if (!!productDataDiv) {
            if (!check_bundle_product()) {
                alert('Option title in Bundle product tab is required!');
                return false
            }
            if (productType === 'simple' && !value) {
                alert('Regular price is required!')
                return false
            } else if (productType === 'variable') {
                var total = jQuery('#variable_product_options').find('.woocommerce_variations').attr('data-total');
                if (!total || parseInt(total) === 0) {
                    alert('Variation is required!');
                    return false
                }
                emptyValue = false
                jQuery('form#post div.woocommerce_variation').find('.wc_input_price[name^=variable_regular_price]').each(function(e) {
                    let val = jQuery(this).val()
                    if (!val)
                        emptyValue = true
                })

                if (emptyValue) {
                    alert('Variation\'s regular price is required!')
                    return false
                }
            } else if (productType === 'grouped' && !price_in_grouped) {
                alert('Regular price is required!')
                return false
            }
        }
    });

    function check_bundle_product () {
        is_valid = true
        jQuery('.wc-custom-bp').find('.wc-custom-input-1 input[type="text"]').each(function(index, el) {
            if (!jQuery(this).val()) {
                is_valid = false;
            }
        });
        return is_valid
    }

    // add event listener in general grouped product
    jQuery(document.body)
    .on('keyup', '#_sale_price_in_grouped', function() {
        var sale_price_field = jQuery( this ), regular_price_field;

        regular_price_field = jQuery( '#_regular_price_in_grouped' );

        var sale_price    = parseFloat(
            window.accounting.unformat( sale_price_field.val(), woocommerce_admin.mon_decimal_point )
        );
        var regular_price = parseFloat(
            window.accounting.unformat( regular_price_field.val(), woocommerce_admin.mon_decimal_point )
        );

        if ( sale_price >= regular_price ) {
            $( document.body ).triggerHandler( 'wc_add_error_tip', [ jQuery(this), 'i18n_sale_less_than_regular_error' ] );
        } else {
            $( document.body ).triggerHandler( 'wc_remove_error_tip', [ jQuery(this), 'i18n_sale_less_than_regular_error' ] );
        }
    })

    var date1 = jQuery('#general_grouped_product_panels #_sale_price_in_grouped_dates_from').val()
    var date2 = jQuery('#general_grouped_product_panels #_sale_price_in_grouped_dates_to').val()

    if (date1 || date2)
        jQuery('#general_grouped_product_panels .sale_schedule').click();

    // hide group product
    jQuery('.show_if_grouped #grouped_products').parents('.options_group').addClass('hidden_grouped_product');


    // Variation Product
    jQuery('body').on('click', '.woocommerce_variation', function(event) {
        jQuery('body').find('.custom-product-availability').each(function(index, el) {
            var type_variant = jQuery(this).find('input[id^="type-choosen-variation"]').val();
            type_variant = type_variant ? type_variant : "daily-product-variation";
            if (type_variant === "daily-product-variation") {
                jQuery(this).find('input[id^="daily-product-variations"]').prop('checked', true);

                jQuery(this).find('div[id^="daily-product-info-variations"]').show();
                jQuery(this).find('div[id^="season-product-info-variations"]').hide();
                jQuery(this).find('input[id^="type-choosen-variation"]').val('daily-product-variation');
            } else if (type_variant === "season-product-one-day-only-variation") {
                jQuery(this).find('input[id^="season-product-variations"]').prop('checked', true);
                jQuery(this).find('select[id^="season-product-list-date-available-select-variations"]').val("one-day-only-variations");

                jQuery(this).find('div[id^="daily-product-info-variations"]').hide();
                jQuery(this).find('div[id^="season-product-info-variations"]').show();
                jQuery(this).find('div[class^="date-range-from-variations"]').hide();
                jQuery(this).find('div[class^="date-range-to-variations"]').hide();
                jQuery(this).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');

                jQuery(this).find('div[class^="season-product-available-one-day-variations"]').show();
                jQuery(this).find('div[class^="season-product-available-time-range-variations"]').hide();
                jQuery(this).find('input[id^="one-day-date-picker-variations"]').show();
                jQuery(this).find('div[class^="date-range-from-variations"]').hide();
                jQuery(this).find('div[class^="date-range-to-variations"]').hide();
                jQuery(this).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');
            } else {
                jQuery(this).find('input[id^="season-product-variations"]').prop('checked', true);
                jQuery(this).find('select[id^="season-product-list-date-available-select-variations"]').val("time-range-variations");

                jQuery(this).find('div[id^="daily-product-info-variations"]').hide();
                jQuery(this).find('div[id^="season-product-info-variations"]').show();
                jQuery(this).find('div[class^="date-range-from-variations"]').hide();
                jQuery(this).find('div[class^="date-range-to-variations"]').hide();
                jQuery(this).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');
                const value = jQuery(this).find('select[id^="season-product-list-date-available-select-variations"]').val();

                jQuery(this).find('div[class^="season-product-available-one-day-variations"]').hide();
                jQuery(this).find('div[class^="season-product-available-time-range-variations"]').show();
                jQuery(this).find('input[id^="one-day-date-picker-variations"]').hide();
                jQuery(this).find('div[class^="date-range-from-variations"]').show();
                jQuery(this).find('div[class^="date-range-to-variations"]').show();
                jQuery(this).find('input[id^="type-choosen-variation"]').val('season-product-date-range-variation');
            }
        });
    });

    jQuery('body').on('click', 'input[id^="daily-product-variations"]', function(event) {
        var divParent = jQuery(this).closest('.custom-product-availability');

        jQuery(divParent).find('div[id^="daily-product-info-variations"]').show();
        jQuery(divParent).find('div[id^="season-product-info-variations"]').hide();
        jQuery(divParent).find('input[id^="type-choosen-variation"]').val('daily-product-variation');
    });

    jQuery('body').on('click', 'input[id^="season-product-variations"]', function(event) {
        var divParent = jQuery(this).closest('.custom-product-availability');

        jQuery(divParent).find('div[id^="daily-product-info-variations"]').hide();
        jQuery(divParent).find('div[id^="season-product-info-variations"]').show();
        jQuery(divParent).find('div[class^="date-range-from-variations"]').hide();
        jQuery(divParent).find('div[class^="date-range-to-variations"]').hide();
        jQuery(divParent).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');
        const value = jQuery(divParent).find('select[id^="season-product-list-date-available-select-variations"]').val();

        if (value === 'one-day-only-variations') {
            jQuery(divParent).find('div[class^="season-product-available-time-range-variations"]').hide();
            jQuery(divParent).find('input[id^="one-day-date-picker-variations"]').show();
            jQuery(divParent).find('div[class^="date-range-from-variations"]').hide();
            jQuery(divParent).find('div[class^="date-range-to-variations"]').hide();
            jQuery(divParent).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');
        } else {
            jQuery(divParent).find('div[class^="season-product-available-time-range-variations"]').show();
            jQuery(divParent).find('input[id^="one-day-date-picker-variations"]').hide();
            jQuery(divParent).find('div[class^="date-range-from-variations"]').show();
            jQuery(divParent).find('div[class^="date-range-to-variations"]').show();
            jQuery(divParent).find('input[id^="type-choosen-variation"]').val('season-product-date-range-variation');
        }
    });

    jQuery('body').on('change', 'select[id^="season-product-list-date-available-select-variations"]', function(event) {
        var value = jQuery(this).val();
        var divParent = jQuery(this).closest('.custom-product-availability');

        if (value == 'one-day-only-variations') {
            jQuery(divParent).find('input[id^="one-day-date-picker-variations"]').show();
            jQuery(divParent).find('div[class^="date-range-from-variations"]').hide();
            jQuery(divParent).find('div[class^="date-range-to-variations"]').hide();
            jQuery(divParent).find('div[class^="season-product-available-one-day-variations"]').show();
            jQuery(divParent).find('div[class^="season-product-available-time-range-variations"]').hide();
            jQuery(divParent).find('input[id^="type-choosen-variation"]').val('season-product-one-day-only-variation');
        } else {
            jQuery(divParent).find('input[id^="one-day-date-picker-variations"]').hide();
            jQuery(divParent).find('div[class^="date-range-from-variations"]').show();
            jQuery(divParent).find('div[class^="date-range-to-variations"]').show();
            jQuery(divParent).find('div[class^="season-product-available-one-day-variations"]').hide();
            jQuery(divParent).find('div[class^="season-product-available-time-range-variations"]').show();
            jQuery(divParent).find('input[id^="type-choosen-variation"]').val('season-product-date-range-variation');
        }
    });
});