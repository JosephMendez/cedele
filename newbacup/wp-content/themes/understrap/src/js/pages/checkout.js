( function() {
  function initAutocomplete() {
    var autocomplete1 = new google.maps.places.Autocomplete(
      (document.getElementById('billing_address_1')),
      { types: ['geocode'], componentRestrictions: {country: 'sg'}, language: ['en'] });
    function addGoogleEvent(instance, type){
      google.maps.event.addListener(instance, 'place_changed', function() {
          var place = instance.getPlace();
          var postCode = extractFromAdress(place.address_components, 'postal_code');
          var streetNumber = extractFromAdress(place.address_components, 'street_number');
          var route = extractFromAdress(place.address_components, 'route');
          var state = extractFromAdress(place.address_components, 'neighborhood');
          var address = {
              formatted_address: place.formatted_address,
              lat: place.geometry.location.lat(),
              long: place.geometry.location.lng(),
              zipcode: postCode,
              streetNumber: streetNumber,
              route: route,
              state: state
          }
          var inputState = jQuery('#'+type+'_state');
          var inputPostCode = jQuery('#'+type+'_postcode');
          inputPostCode.val(postCode);
          inputState.find('option').each(function() {
            jQuery(this).prop('selected', false);
            if (jQuery(this).text().toLowerCase() == state.toLowerCase()){
              jQuery(this).prop('selected', true);
            }
          });
          inputState.trigger('change');
      });
    };
    addGoogleEvent(autocomplete1, 'billing');
}

  jQuery(document).ready(() => {
    var customerAddress = JSON.parse(localStorage.getItem('customerAddress') || '{}');
    initAutocomplete();

    function setBillingAddress(){
      if (customerAddress.deliveryType == 'delivery'){
        if (customerAddress.deliveryAddress){
          var address = JSON.parse(customerAddress.deliveryAddress);
          jQuery('#billing_address_1').val(address.formatted_address);
          jQuery('#billing_address_2').val(customerAddress.deliveryAddress2 || '');
          jQuery('#billing_postcode').val(address.zipcode);
          jQuery('#billing_state option').each(function(){
            if (jQuery(this).text() == address.state){
              jQuery(this).attr('selected','selected');
              jQuery('#billing_state').trigger('change');
            }
          });
          jQuery('#shipping_address_1').val(address.formatted_address);
          jQuery('#shipping_address_2').val(customerAddress.deliveryAddress2 || '');
          jQuery('#shipping_postcode').val(address.zipcode);
          jQuery('#shipping_state option').each(function(){
            if (jQuery(this).text() == address.state){
              jQuery(this).attr('selected','selected');
            }
          });
        }
      }
    }

    setBillingAddress();

    var billingAddressFields = ['#billing_address_1_field', '#billing_address_2_field', '#billing_state_field', '#billing_postcode_field'];
    billingAddressFields.forEach(function(field){
      jQuery(field).appendTo('.billing-address-fields').show();
    });

    jQuery('#same-address').on('change', function(){
      var isChecked = jQuery(this).is(':checked');
      if (isChecked){
        jQuery('.billing-address-fields').hide();
        setBillingAddress();
      } else {
        jQuery('.billing-address-fields').show();
      }
    });

    function setFieldState(name, checked){
      if (checked && !jQuery(name).val()){
        return;
      }
      jQuery(name).prop('readonly', checked);
    } 

    jQuery('#user-account-info').on('change', function(){
      var isChecked = jQuery(this).is(':checked');
      if (isChecked){
        jQuery('#billing_first_name').val(billing_first_name);
        jQuery('#billing_last_name').val(billing_last_name);
        jQuery('#billing_phone').val(billing_phone);
        jQuery('#billing_email').val(billing_email);
        jQuery('#billing_first_name').attr('value', billing_first_name);
        jQuery('#billing_last_name').attr('value', billing_last_name);
        jQuery('#billing_phone').attr('value', billing_phone);
        jQuery('#billing_email').attr('value', billing_email);
      }
      setFieldState('#billing_first_name', isChecked);
      setFieldState('#billing_last_name', isChecked);
      setFieldState('#billing_phone', isChecked);
      setFieldState('#billing_email', isChecked);
    });
    jQuery('#user-account-info').trigger('change');


    jQuery('body').on('change', 'input[name="payment_method"]', function(){
      var method = jQuery('input[name="payment_method"]:checked').attr('value');
      if (method == 'omise_paynow'){
        jQuery('.payment_box.payment_method_omise').hide();
      }
    });
  });
})();