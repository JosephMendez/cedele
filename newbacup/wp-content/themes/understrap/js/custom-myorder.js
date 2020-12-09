jQuery(document).ready(function($) {
    jQuery('body').on('click', '.myorder-panel-list .myorder-panel-info', function(event) {
        event.preventDefault();
        $(this).closest('.myorder-panel-item').toggleClass('opening', 300);
    });

    jQuery('body').on('click', '.myorder-panel-list .myorder-panel-info-phone', function(event) {
        event.preventDefault();
        $(this).closest('.myorder-panel-item').toggleClass('opening', 300);
    });

    jQuery('body').on('click', '.myorder-panel-footer-cancel', function(event) {
        event.preventDefault();
        event.stopPropagation();

        var order_id = jQuery(this).closest('.myorder-panel-item').attr('data-order-id');
        jQuery('.cancelOrderModal').attr('data-order-id', order_id);
        show_modal_order();
    });

    jQuery('body').on('click', '.cancelOrderModal .btn-order-yes', function(event) {
        close_modal_order();
        var order_id = jQuery('.cancelOrderModal').attr('data-order-id');
        var current_page = jQuery('.myorder-panel-list').attr('data-current-page');
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: custom_my_order_object.ajax_url,
            data: {
                action: "custom_cancel_order",
                nonce: custom_my_order_object.nonce,
                current_page: current_page,
                order_id: order_id
            },
            context: this,
            beforeSend: function() {
                jQuery('.woocommerce-MyAccount-content').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(response) {
                jQuery('.woocommerce-MyAccount-content').html(response);
                jQuery.alert({
                    title: '',
                    content: 'Cancel to Cart succesfully',
                    icon: 'fa fa-check-circle text-success',
                    autoClose: 5000,
                    backgroundDismiss: true,
                    bgOpacity: 0
                });
            },
            error: function( jqXHR, textStatus, errorThrown ){

            },
            completed: function() {
                jQuery('.woocommerce-MyAccount-content').unblock();
            }
        });
    });

    jQuery('body').on('click', '.cancelOrderModal .btn-order-no', function(event) {
        close_modal_order();
    });

    function show_modal_order(argument) {
        jQuery('.cancelOrderModal').addClass('show');
        jQuery('.cancelOrderModal').show();
        if (!jQuery('.modal-backdrop').length) {
            jQuery('body').append('<div class="modal-backdrop fade show"></div>');
        }
    }

    function close_modal_order(argument) {
        jQuery('.cancelOrderModal').removeClass('show');
        jQuery('.cancelOrderModal').hide();
        jQuery('.modal-backdrop').remove();
    }
});