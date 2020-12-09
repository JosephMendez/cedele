jQuery(document).ready(function($) {
    var list_coupons = custom_my_rewards_object.coupons ? custom_my_rewards_object.coupons.results : [];
    var list_can_show = [];
    var link_template = jQuery('.frmyCoupon__section_coupon').attr('data-link-template');
    var variant_coupon = [
        {variant_class: '', variant_img: `${link_template}/images/rewards/fruit1.png`},
        {variant_class: 'coupon2', variant_img: `${link_template}/images/rewards/fruit2.png`},
        {variant_class: 'coupon3', variant_img: `${link_template}/images/rewards/fruit3.png`},
    ];
    var coupon_per_page = 9;

    list_coupons = filter_list_coupon(list_coupons);
    load_page(1)

    function load_page(c_page) {
        jQuery('.frmyCoupon__section_coupon').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        get_list_show_page_coupon(c_page);
        show_page_coupon();
        reset_pagination(c_page);
        setTimeout(() => {
            jQuery('.frmyCoupon__section_coupon').unblock();
        }, 300)
    }

    function filter_list_coupon(list_coupons) {
        new_list_coupons = [];
        if (Array.isArray(list_coupons) && list_coupons.length > 0) {
            new_list_coupons = list_coupons.filter(function(item, index) {
                return item.status == 1 && (new Date(item.expired_date)) > (new Date()) && (new Date(item.effective_date)) <= (new Date());
            });
            
            new_list_coupons.sort(function(a, b){
                return (new Date(b.expired_date) > new Date(a.expired_date)) ? -1 : 1;
            });
        }
        return new_list_coupons.slice();
    }

    function get_list_show_page_coupon(current_page) {
        let from = (parseInt(current_page) - 1) * coupon_per_page;
        let to = (from + coupon_per_page);
        list_can_show = list_coupons.slice(from, to);
    }

    function show_page_coupon() {
        var html = '';

        list_can_show.forEach((item, coupon_key) => {
            var variant_key = coupon_key % 3;
            var expired_date = new Date(item.expired_date);
            var month = expired_date.getDate() < 10 ? '0' : '';
            var expired_date_str = [
                expired_date.getFullYear(),
                (expired_date.getMonth() < 9 ? '0' : '') + (expired_date.getMonth() + 1),
                (expired_date.getDate() < 10 ? '0' : '') + expired_date.getDate()
            ].join('-');

            html += `<div class="frmyCoupon__section--coupon-item ${variant_coupon[variant_key].variant_class}">
                <p class="frmyCoupon__section--coupon-name">${item.coupon_name}</p>
                <div class="frmyCoupon__section--coupon-ticket">
                    <div class="frmyCoupon__section--coupon-ticketsub">
                        <div class="frmyCoupon__section--coupon-icon">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0)">
                                <path d="M14.1716 9.92893L15.5858 11.3431" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.4142 14.1716L19.8284 15.5858" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22.6569 18.4142L24.0711 19.8284" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.1005 17L17 7.10051C17.3751 6.72543 17.8838 6.51472 18.4142 6.51472C18.9446 6.51472 19.4533 6.72543 19.8284 7.10051L21.9497 9.22183C21.5747 9.5969 21.364 10.1056 21.364 10.636C21.364 11.1665 21.5747 11.6752 21.9497 12.0503C22.3248 12.4253 22.8335 12.636 23.364 12.636C23.8944 12.636 24.4031 12.4253 24.7782 12.0503L26.8995 14.1716C27.2746 14.5466 27.4853 15.0554 27.4853 15.5858C27.4853 16.1162 27.2746 16.6249 26.8995 17L17 26.8995C16.6249 27.2746 16.1162 27.4853 15.5858 27.4853C15.0553 27.4853 14.5466 27.2746 14.1716 26.8995L12.0502 24.7782C12.4253 24.4031 12.636 23.8944 12.636 23.364C12.636 22.8335 12.4253 22.3248 12.0502 21.9497C11.6752 21.5747 11.1665 21.364 10.636 21.364C10.1056 21.364 9.59689 21.5747 9.22182 21.9497L7.1005 19.8284C6.72543 19.4534 6.51471 18.9446 6.51471 18.4142C6.51471 17.8838 6.72543 17.3751 7.1005 17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0">
                                <rect width="24" height="24" fill="white" transform="translate(0.0294342 17) rotate(-45)"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div class="frmyCoupon__section--coupon-text">${item.coupon_code}</div>
                    </div>
                    <p class="frmyCoupon__section--coupon-item-expired-date">Valid until ${expired_date_str}</p>
                </div>
                <div class="frmyCoupon__section--layer-decorate">
                    <img src="${variant_coupon[variant_key].variant_img}" alt="coupon">
                </div>
            </div>`;
        })

        jQuery('.frmyCoupon__section--list-coupons').html(html);
    }

    function reset_pagination(current_page) {
        var html = '<ul class="front-custom-pagination">';
        var from = (parseInt(current_page) - 1) * coupon_per_page;
        var to = (from + coupon_per_page);
        var total_count = list_coupons.length;
        var max_pages = Math.ceil(total_count / coupon_per_page);

        if (total_count > coupon_per_page) {
            if (current_page > 1)
                html += `<li><a class="page-numbers" href="http://page${current_page - 1}">Pre</a></li>`;
            for (let i = 1; i <= max_pages; i++) {
                if (current_page == i) {
                    html += `<li><span aria-current="page" class="page-numbers current">${i}</span></li>`;
                } else {
                    html += `<li><a class="page-numbers" href="http://page${i}">${i}</a></li>`;
                }
            }
            if (current_page < max_pages)
                html += `<li><a class="page-numbers" href="http://page${current_page + 1}">Next</a></li>`;
        }

        html += '</ul>';

        jQuery('.frmyCoupon__section--pagination').html(html)
    }

    jQuery('body').on('click', '.frmyCoupon__section--pagination a.page-numbers', function(event) {
        event.preventDefault();
        var current_page = jQuery(this).attr('href');
        current_page = current_page.split("page").pop();
        current_page = parseInt(current_page) ? parseInt(current_page) : 1;
        
        load_page(current_page)                   
    });

    // 
    // ajax function
    // jQuery('body').on('click', 'a.page-numbers', function(event) {
    //     event.preventDefault();
    //     var current_page = jQuery(this).attr('href');
    //     current_page = current_page.split("page").pop();
    //     console.log('current_page', current_page)
    //     current_page = parseInt(current_page) ? parseInt(current_page) : 1;

    //     jQuery.ajax({
    //         type: "POST",
    //         dataType: "html",
    //         url: custom_my_order_object.ajax_url,
    //         data: {
    //             action: "custom_my_rewards_vouchers",
    //             nonce: custom_my_rewards_object.nonce,
    //             current_page: current_page,
    //         },
    //         context: this,
    //         beforeSend: function() {
    //             jQuery('.frmyCoupon__section_coupon').block({
    //                 message: null,
    //                 overlayCSS: {
    //                     background: '#fff',
    //                     opacity: 0.6
    //                 }
    //             });
    //         },
    //         success: function(response) {
    //             jQuery('.frmyCoupon__section_coupon').html(response);
    //         },
    //         error: function( jqXHR, textStatus, errorThrown ){

    //         },
    //         completed: function() {
    //             jQuery('.frmyCoupon__section_coupon').unblock();
    //         }
    //     });                                 
    // });
});                                                     