( function() {
  jQuery(document).ready(() => {

    if (jQuery('.product-quantity').length){
      jQuery('body').on('click', '.product-quantity .qtt-plus', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var productContainer = jQuery(this).parents('.product-quantity');
        var qttInput = productContainer.find('.qty');
        var qtt = parseInt(qttInput.val());
        var max = qttInput.attr('max');
        if ( max && qtt >= parseInt(max)){
          return;
        } else {
          qttInput.val(qtt+1);
          qttInput.attr('value', qtt+1);
          jQuery('button[name="update_cart"]').prop( 'disabled', false );
        }
      });
      jQuery('body').on('click', '.product-quantity .qtt-minus', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var productContainer = jQuery(this).parents('.product-quantity');
        var qttInput = productContainer.find('.qty');
        var qtt = parseInt(qttInput.val());
        if (qtt > 0){
          qttInput.val(qtt-1);
          qttInput.attr('value', qtt-1);
          jQuery('button[name="update_cart"]').prop( 'disabled', false );
        }
      });
    }

    jQuery('.product-modify .add_to_wishlist').on('click', function(){
      var parent = jQuery(this).closest('.product-modify');
      var removeButton = jQuery(parent).find('a.remove');
      removeButton[0].click();
    });

    function calculateShippingFee() {
      var customerAddress = JSON.parse(localStorage.getItem('customerAddress') || '{}');
      Cookies.set('customerAddress', localStorage.getItem('customerAddress') || '{}', { expires: 7, path: '/' });
      jQuery('#calc_shipping_token').val(Math.random());
      if (customerAddress.deliveryType == 'delivery'){
        if (customerAddress.deliveryAddress){
          var address = JSON.parse(customerAddress.deliveryAddress);
          jQuery('#calc_shipping_postcode').val(address.zipcode);
        }
        jQuery('.woocommerce-shipping-calculator').submit();
      } else if (customerAddress.deliveryType == 'self-collection'){
        jQuery('.woocommerce-shipping-calculator').submit();
      }
    }

    // update redemption point
    jQuery('body').on('change', '#checkbox_redemp_point', function(event) {
      var is_checked = jQuery(this).is(':checked');
      var is_using_redemption_point = 0

      if (is_checked) {
        is_using_redemption_point = 1
      }
      jQuery('.woocommerce-shipping-calculator').append(jQuery('input').attr('name', 'enable_redemp_point').val(is_using_redemption_point));
      calculateShippingFee()
    })

    function hideModal(e){
      e.preventDefault();
      jQuery('.address-modal').removeClass('show');
      jQuery('.address-modal').hide();
      jQuery('.modal-backdrop').remove();
    }

    jQuery('body').on('click', '.change-address', function(e){
      e.preventDefault();
		  showModalAddress();
      jQuery(document).trigger('onUserAddressModalShow');
    });

    jQuery('body').on('click', '.delivery .btn-cancel', function(e){
      hideModal(e);
      jQuery(document).trigger('onUserAddressModalClose');
    });

    jQuery('body').on('click', '.delivery .btn-delivery-apply', function(e){
      hideModal(e);
      jQuery(document).trigger('saveUserAddress');
      calculateShippingFee();
    });
    
    //if user enter cart page or refresh page witout apply coupon show address modal
    if (postDataCart && postDataCart.length === 0) {
      showModalAddress();
      jQuery(document).trigger('onUserAddressModalShow');
    }
    calculateShippingFee();

    jQuery(document.body).on('removed_from_cart updated_cart_totals', function () {
      jQuery.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.ajax_url,
        data: {
          'action': 'checking_cart_items',
          'added': 'yes'
        },
        success: function (response) {
          if (response) {
            jQuery('.cart-content-count').text(response);

            if (!jQuery('#main-nav .action-list .cart .badge').length){
              jQuery('#main-nav .action-list .cart a').append('<span class="badge badge-danger"></span>');
            }
            jQuery('#main-nav .action-list .cart .badge').text(response);
          }
        }
      });
    });

    jQuery(document.body).on('updated_cart_totals', function () {
      jQuery('.cart-title-subtotal .woocommerce-Price-amount').html(jQuery('.cart-subtotal .woocommerce-Price-amount').html());
    });

  });
	function showModalAddress() {
		jQuery('.address-modal').addClass('show');
		jQuery('.address-modal').show();
		jQuery('body').append('<div class="modal-backdrop fade show"></div>');
		if (!jQuery('.delivery-date-start input').length){
			jQuery(document).trigger('onUserAddressModalClose');
		}
	}

  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
})();
