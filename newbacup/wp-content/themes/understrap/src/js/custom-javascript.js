( function() {
  String.prototype.format = function () {
    var args = arguments;
    return this.replace(/\{\{|\}\}|\{(\d+)\}/g, function (m, n) {
      if (m == "{{") { return "{"; }
      if (m == "}}") { return "}"; }
      return args[n] ? args[n] : "";
    });
  }

	var url = window.location.href;
	if(url.includes('my-account')) {
		jQuery('.woocommerce-form-register').parents('.u-column2').remove();
		if (jQuery('.woocommerce-form-login').length){
			jQuery('body').addClass('woocommerce-login');
		}
	}
	if(url.includes('register')) {
		jQuery('.woocommerce-form-login').parents('.u-column1').remove();
	}

	jQuery(document.body).on('added_to_cart', function() {
		jQuery.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				'action': 'checking_cart_items',
				'added' : 'yes'
			},
			success: function (response) {
				if (response) {
          if (!jQuery('#main-nav .action-list .cart .badge').length){
            jQuery('#main-nav .action-list .cart a').append('<span class="badge badge-danger"></span>');
          }
					jQuery('#main-nav .action-list .cart .badge').text(response);
					var ja = jQuery.alert({
						title: '',
						content: 'Added to Cart succesfully',
						icon: 'fa fa-check-circle text-success',
						backgroundDismiss: true,
						bgOpacity: 0
					});
          setTimeout(function(){
            ja.close();
          }, 5000);
				}
			}
		});
	});

	jQuery(document).ready(function () {
		jQuery('.cdl-scrollable').mCustomScrollbar({
			theme: 'dark'
		});
		jQuery('select.custom-select').select2({
			theme: 'bootstrap4',
			minimumResultsForSearch: Infinity,
		});

		if (jQuery('.woo-quantity-edit').length){
			jQuery('body').on('click', '.woo-quantity-edit .qtt-plus', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var productContainer = jQuery(this).parents('li.product');
				var qttInput = productContainer.find('.qtt-input');
				var cartButton = productContainer.find('.add-to-cart-container a');
				var qtt = parseInt(qttInput.val());
				qttInput.val(qtt+1);
				cartButton.attr('data-quantity', qtt+1);
			});
			jQuery('body').on('click', '.woo-quantity-edit .qtt-minus', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var productContainer = jQuery(this).parents('li.product');
				var qttInput = productContainer.find('.qtt-input');
				var cartButton = productContainer.find('.add-to-cart-container a');
				var qtt = parseInt(qttInput.val());
				if (qtt > 1){
					qttInput.val(qtt-1);
					cartButton.attr('data-quantity', qtt-1);
				}
			});
		}

		jQuery('#user_registration_user_birthday').datetimepicker({
			maxDate: 0,
			format: 'DD MM YYYY',
      defaultDate: jQuery(this).attr('value')
		});

		jQuery('.qtt-input').on('keypress', function (e) {
			e = (e) ? e : window.event;
			let charCode = (e.which) ? e.which : e.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}

			if (e.target.value == '' && (charCode == 48 || charCode == 96) ) {
				return false;
			}

			return true;
		});

    jQuery('body').on('click', '.logout-link', function(){
      localStorage.setItem('customerAddress', '{}');
      Cookies.set('customerAddress', '{}', { expires: 7, path: '/' });
    });

    var userAddress = JSON.parse(localStorage.getItem('customerAddress'));
    if (!userAddress) {
      var customerAddress = {
        deliveryType: 'self-collection',
        deliveryAddress: ''
      };
      localStorage.setItem('customerAddress', JSON.stringify(customerAddress));
    }

	});

	var customerAddress = JSON.parse(localStorage.getItem('customerAddress') || '{}');
	var storeId = customerAddress.pickupStoreId;
	if (storeId && !jQuery('#store-selector').length){
		checkStoreItemStatus(storeId);
	}

	jQuery(document).on('click', '.dropdown-menu', function (e) {
		e.stopPropagation();
	});

	// hold onto the drop down menu
	var dropdownMenu;

	// and when you show it, move it to the body
	jQuery('.dropdown-search').on('show.bs.dropdown', function (e) {
		// grab the menu
		dropdownMenu = jQuery(e.target).find('.dropdown-menu');

		// detach it and append it to the body
		jQuery('body').append(dropdownMenu.detach());
	});

	// and when you hide it, reattach the drop down, and hide it normally
	jQuery('.dropdown-search').on('hide.bs.dropdown', function (e) {
		jQuery(e.target).append(dropdownMenu.detach());
	});
})();

function checkStoreItemStatus(storeId){
  var storeProducts = [];

  var updateStatusProduct = function() {
    storeProducts.forEach(function(product){
      var productId = product.id;
      if (product.is_in_stock == '0'){
        //find product in single product page
        var singleProduct = jQuery('.single-product #product-'+productId);
        if (singleProduct.length){
          jQuery('.footer-wishlist').removeClass('d-none');
          var addToCartButton = singleProduct.find('.single_add_to_cart_button');
          var statusText = singleProduct.find('.status-text p');
          addToCartButton.attr('class', 'single_add_to_cart_button btn btn-header btn-secondary heading-font outofstock');
          addToCartButton.text('Sold Out');
          statusText.html('The choosen store is out of stock.</br> Please choose another store to continue.');
        }

        //find product in loop
        var loopProduct = jQuery('li.product .add_to_cart_button[data-product_id="'+productId+'"]');
        if (!loopProduct.hasClass('outofstock')){
          loopProduct.attr('data-old-text', loopProduct.text());
          loopProduct.attr('data-old-class', loopProduct.attr('class'));
          loopProduct.text('Sold Out');
          loopProduct.addClass('outofstock');
        }
      }
    });
  };

  if (storeId) {
    var loopProducts = jQuery('li.product .add_to_cart_button');
    jQuery.each(loopProducts, function(product){
      if (jQuery(this).attr('data-old-text')){
        jQuery(this).text(jQuery(this).attr('data-old-text'));
      }
      if (jQuery(this).attr('data-old-class')){
        jQuery(this).attr('class', jQuery(this).attr('data-old-class'));
      }
    });

    jQuery.ajax({
      type: 'POST',
      url: global_ajax_vars.ajax_url,
      data: {
        'action': 'get_store_products',
        'store_id' : storeId
      },
      success: function (response) {
        var res = JSON.parse(response);
        storeProducts = res.data;
        updateStatusProduct();
      }
    });
  }

  jQuery(document).on( 'yith_infs_added_elem', function(){
    updateStatusProduct();
  });
}
