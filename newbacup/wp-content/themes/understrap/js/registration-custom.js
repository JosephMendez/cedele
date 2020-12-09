(function ($) {
    "use strict";
    $(document).ready(function () {
        var redirect = jQuery('input[name="ur-redirect-url"]');
        var redirectValue = jQuery('.buy-membership').val();
        if(redirect.length > 0) {
            redirect.val(redirectValue);
        }
    });
})(jQuery);
