jQuery(document).ready(function($) {
    jQuery('.my-redemtions').find('.my-redemtions-item').each(function(index, el) {
        var redemption_code = jQuery(this).attr('data-redemption-code');
        var redemtions_detail_div = jQuery(this).find('.my-redemtions-detail');

        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: custom_my_redemptions_object.ajax_url,
            data: {
                action: "custom_get_list_redemptions",
                nonce: custom_my_redemptions_object.nonce,
                redemption_code: redemption_code
            },
            context: this,
            beforeSend: function() {
                jQuery(redemtions_detail_div).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(response) {
                jQuery(redemtions_detail_div).html(response);
            },
            error: function( jqXHR, textStatus, errorThrown ){

            },
            complete: function() {
                jQuery(redemtions_detail_div).unblock();
            }
        });
    });

	jQuery('body').on('click', '.myredemption-action', function(event) {
        var coupon_code = jQuery(this).closest('tr').attr('data-coupon-code');
        var redemption_type = jQuery(this).closest('tr').attr('data-redemption-type');
        var tableCloset = jQuery(this).closest('table');

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: custom_my_redemptions_object.ajax_url,
            data: {
                action: "custom_my_redemptions_action",
                nonce: custom_my_redemptions_object.nonce,
                coupon_code: coupon_code,
                redemption_type: redemption_type
            },
            context: this,
            beforeSend: function() {
                jQuery(tableCloset).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(response) {
                console.log('response', response)

                if (response.isSuccess == 1) {
                    jQuery.alert({
                        title: '',
                        content: 'Redeem succesfully!',
                        icon: 'fa fa-check-circle text-success',
                        autoClose: 5000,
                        backgroundDismiss: true,
                        bgOpacity: 0
                    });
                } else {
                    jQuery.alert({
                        title: '',
                        content: 'Redeem error!',
                        icon: 'fa fa-check-circle text-danger',
                        autoClose: 5000,
                        backgroundDismiss: true,
                        bgOpacity: 0
                    });
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){

            },
            complete: function() {
                jQuery(tableCloset).unblock();
            }
        });
    });
});