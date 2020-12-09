( function() {
  jQuery(document).ready(() => {

    function print_message_wl( response_message ) {
      if (!jQuery('#yith-wcwl-popup-message').length){
        jQuery('body').prepend('<div id="yith-wcwl-popup-message" style="display: none;"><div id="yith-wcwl-message"></div></div>');
      }
      var msgPopup = jQuery( '#yith-wcwl-popup-message' ),
          msg = jQuery( '#yith-wcwl-message' ),
          timeout = typeof yith_wcwl_l10n.popup_timeout != 'undefined' ? yith_wcwl_l10n.popup_timeout : 3000;

      if( typeof yith_wcwl_l10n.enable_notices != 'undefined' && ! yith_wcwl_l10n.enable_notices ){
          return;
      }

      msg.html( response_message );
      msgPopup.css( 'margin-left', '-' + jQuery( msgPopup ).width()/2 + 'px' ).fadeIn();
      window.setTimeout( function() {
          msgPopup.fadeOut();
      }, timeout );
    }

    jQuery(document).on( 'added_to_wishlist removed_from_wishlist', function(){
      print_message_wl('Product is removed from wishlist');

      jQuery.ajax({
        url: yith_wcwl_l10n.ajax_url,
        data: {
          action: 'yith_wcwl_update_wishlist_count'
        },
        dataType: 'json',
        success: function( data ){
          jQuery('.wishlistNumber span').text(data.count);
        },
      });

    });

  });  
})();