jQuery(document).ready(function($) {
    // FAQ
    jQuery('body').on('click', '.section-right--faq-item-question', function(event) {
        jQuery(this).closest('.section-right--faq-item').toggleClass('opening', 500);
    });
    jQuery(window).resize(function() {
        get_scroll_top();
    });

    var scrollList = [];
    var currentNav = jQuery('.front-faq-page__section-right--faq-category').attr('id');
    function get_scroll_top() {
        if (jQuery('#main-nav').length > 0) {
            var mainNavigationHeight = jQuery('#main-nav').innerHeight();

            scrollList = [];
            jQuery('.front-faq-page__section-right--faq-category').each(function(index, el) {
                var divId = jQuery(this).attr('id');
                scrollList.push({
                    idFor: divId,
                    offsetToTop: (jQuery(this).offset().top - mainNavigationHeight)
                });
            });
        }
    }

    set_active_li();
    function set_active_li(argument) {
        jQuery('.front-faq-page__section-right--list-categories li,.front-faq-page__section-ul li').removeClass();
        jQuery('body').find('.front-faq-page__section-right--list-categories li,.front-faq-page__section-ul li').each(function(index, el) {
            var dataFor = jQuery(this).attr('data-for');

            if (dataFor === currentNav) {
                jQuery(this).addClass('active');
            }
        });
    }

    jQuery('body').on('click', '.front-faq-page__section-right--list-categories li,.front-faq-page__section-ul li', function(event) {
			jQuery(".front-faq-page__section-right--list-categories li,.front-faq-page__section-ul li").removeClass('active');
			jQuery(this).addClass('active');

        var divId = jQuery(this).attr('data-for');
        var mainNavigationHeight = jQuery('#main-nav').innerHeight();
        mainNavigationHeight = mainNavigationHeight ? mainNavigationHeight : 0;
        /* Act on the event */
        jQuery('html').animate({
            scrollTop: (jQuery("#" + divId).offset().top - mainNavigationHeight + 1)
        }, 700);
    });

    var bodyScollTop = null;
    var oldCurrentNav = null;
    get_scroll_top();
    jQuery(window).scroll(function (e){
        if (jQuery('.front-faq-page__section-right').length > 0) {
            if (scrollList.length > 0) {
                oldCurrentNav = currentNav;
                bodyScollTop = jQuery('html').scrollTop();
                for (var i = scrollList.length - 1; i >= 0; i--) {
                    if (bodyScollTop > scrollList[i].offsetToTop) {
                        currentNav = scrollList[i].idFor;
                        break;
                    }
                }
                if (currentNav != oldCurrentNav)
                    set_active_li();
            }
        }
    });
});
