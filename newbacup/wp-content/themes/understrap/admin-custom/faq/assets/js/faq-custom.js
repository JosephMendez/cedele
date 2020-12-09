jQuery(document).ready(function($) {
    var is_submiting = false
    jQuery('body').on('click', '.btn-add-faq-category', function(event) {
        if (!is_submiting) {
            is_submiting = true
            var title = jQuery('.faq-category-title').val();

            if (!title) {
                alert('The Title is required!');
                is_submiting = false;
                return false;
            }

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: faq_custom_ajax_object.ajax_url,
                data: {
                    action: "faq_custom_add_categories",
                    nonce: faq_custom_ajax_object.nonce,
                    title: title
                },
                context: this,
                beforeSend: function() {
                    jQuery('.faq_custom_categories-form .spinner').addClass('is-active');
                },
                success: function(response) {
                    load_faq_custom_table();
                },
                complete: function() {
                    is_submiting = false;
                    jQuery('.faq_custom_categories-form .spinner').removeClass('is-active');
                }
            });
        }
    });

    function load_faq_custom_table(argument) {
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: faq_custom_ajax_object.ajax_url,
            data: {
                action: "faq_custom_get_categories",
                nonce: faq_custom_ajax_object.nonce,
            },
            context: this,
            beforeSend: function() {
                jQuery('.wp-list-table').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(response) {
                jQuery('#wpbody-content .wrap').html(response)
            },
            complete: function() {
                jQuery('.wp-list-table').unblock();
            }
        });
    }
});