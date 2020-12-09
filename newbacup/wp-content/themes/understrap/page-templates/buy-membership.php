<?php
/**
 * Template Name: Buy Membership
 *
 * @package UnderStrap
 */
get_header();
?>
<style>
    .heading__buy-membership {
        display: flex;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        align-items: center;
        justify-content: center;
    }
</style>
<h2 class="heading__buy-membership">Processing</h2>
<?php if(is_user_logged_in()): ?>
    <script type="text/javascript">
        (function ($) {
            "use strict";
            $(document).ready(function () {
                var data = {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_sku: '',
                };
                $.ajax({
                    type: 'post',
                    url: yith_wcwl_l10n.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                    },
                    complete: function (response) {
                    },
                    success: function (response) {
                        if (!response.error) {
                            window.location = response.url_redirect;
                            return;
                        } else if (response.error & response.product_url) {
                            window.location = response.product_url;
                            return;
                        }
                    },
                });
            });
        })(jQuery);
    </script>
<?php endif ?>
<?php
get_footer();
?>
