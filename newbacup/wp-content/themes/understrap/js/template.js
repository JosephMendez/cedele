(function ($) {
	"use strict";




	$(document).ready(function () {


		// update wishlist counter
		$(document).on( 'added_to_wishlist removed_from_wishlist', function(){
			var counter = $('#wishlistCouter');

			$.ajax({
				url: yith_wcwl_l10n.ajax_url,
				data: {
					action: 'yith_wcwl_update_wishlist_count'
				},
				dataType: 'json',
				success: function( data ){
					counter.html( data.count );
				},
			})
		} );



		//change title page wishlist in my account page
		$('[data-id=user_birthday]').attr('placeholder', 'Birthday').addClass('input-text');

		$('.datetimepicker').datetimepicker({
			yearStart: 1940,
			timepicker: false,
			format: 'd F Y',
			formatDate: 'd F Y',
			// maxDate: new Date(),
			onSelectDate: function (ct) {
				var dt_to = $.datepicker.formatDate('dd MM yy', new Date(ct));
				$('.datetimepicker').val(dt_to);
			},
		});


		//
		$('.applyCareerContact').on('change', '#file-upload', function (e) {
			var fileName = e.target.files[0];
			if (fileName){
				$('label[for=file-upload]').text(fileName.name);
			} else {
				$('label[for=file-upload]').text('Upload Your CV');
			}
		});

		var colLeftHeight = $('.jobBlock .colLeft').outerHeight();
		$('.jobBlock .colRight').css('height', colLeftHeight+'px');

		$(document).click(function (event) {
			var click = $(event.target);
			var _open = $(".navbar-collapse").hasClass("show");
			if (_open === true && !click.hasClass("navbar-toggler")) {
				$(".navbar-toggler").click();
			}
		});

		var button_order_member = jQuery('.btn_buy_membership');

        button_order_member.click(function(e){
        	e.preventDefault();
        	var t = $(this);
        	var productId = t.attr('data-id');
            var data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: productId,
                product_sku: '',
            };
            $.ajax({
                type: 'post',
                url: yith_wcwl_l10n.ajax_url,
                data: data,
                beforeSend: function (response) {
                    t.text('Processing');
                },
                complete: function (response) {
                    t.text('Buy Membership');
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

	});


	$(window).on('scroll', function () {

	});

	/*window loaded */
	$(window).on('load', function () {

	});


	/*window resize*/
	$(window).on('resize', function () {

	});

	/*window load and resize*/
	$(window).on("load resize", function () {

	});

})(jQuery);
