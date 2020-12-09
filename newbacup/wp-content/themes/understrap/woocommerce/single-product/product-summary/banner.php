<?php
global $post;
global $product;
$check_product_lead = get_post_meta($product->get_id(),'product-lead-time-checkbox');
$product_lead_day = get_post_meta($product->get_id(), 'product-lead-time-days');

$deliveryMethod = get_post_meta($post->ID, 'delivery_method');
$checkedDate = get_post_custom($post->ID);
$typeChoosen = get_post_meta($post->ID, '_type', true);
$deliveryText = understrap_generate_delivery_method_text($deliveryMethod, $checkedDate, $typeChoosen);
$showOverview = strlen($deliveryText) > 0 && $deliveryText!='&nbsp;' || $check_product_lead[0] === 'advance';
?>

<?php echo do_shortcode('[finale_countdown_timer skip_rules="no"]'); ?>

<div class="product-overview">
  <?php if ($showOverview) { ?>
    <div class="overview-content">
      <?php if (strlen($deliveryText) > 0 && $deliveryText!='&nbsp;') { ?>
        <p>
          <i>
          	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    		  <path d="M3 21H21" stroke="#F44336" stroke-width="1.5" stroke-linecap="round"
    			  stroke-linejoin="round"/>
    		  <path d="M15 8C15 8.79565 15.3161 9.55871 15.8787 10.1213C16.4413 10.6839 17.2044 11 18 11C18.7956 11 19.5587 10.6839 20.1213 10.1213C20.6839 9.55871 21 8.79565 21 8V7H3L5 3H19L21 7M3 7V8C3 8.79565 3.31607 9.55871 3.87868 10.1213C4.44129 10.6839 5.20435 11 6 11C6.79565 11 7.55871 10.6839 8.12132 10.1213C8.68393 9.55871 9 8.79565 9 8V7H3ZM9 8C9 8.79565 9.31607 9.55871 9.87868 10.1213C10.4413 10.6839 11.2044 11 12 11C12.7956 11 13.5587 10.6839 14.1213 10.1213C14.6839 9.55871 15 8.79565 15 8V7L9 8Z"
    			  stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    		  <path d="M5 20.9996V10.8496" stroke="#F44336" stroke-width="1.5" stroke-linecap="round"
    			  stroke-linejoin="round"/>
    		  <path d="M19 20.9996V10.8496" stroke="#F44336" stroke-width="1.5" stroke-linecap="round"
    			  stroke-linejoin="round"/>
    		  <path d="M9 21V17C9 16.4696 9.21071 15.9609 9.58579 15.5858C9.96086 15.2107 10.4696 15 11 15H13C13.5304 15 14.0391 15.2107 14.4142 15.5858C14.7893 15.9609 15 16.4696 15 17V21"
    			  stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    		</svg>
          </i>
          <span><?php echo $deliveryText; ?></span>
        </p>
      <?php }?>
      <?php if($check_product_lead[0] === 'advance') { ?>
        <p>
          <i>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 7V12L15 15" stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </i>
          <span><?php echo $product_lead_day[0] ?> days advance notice</span>
        </p>
      <?php }?>
    </div>
  <?php }?>
  <div class="product-discount">
    <button type="button" class="btn btn-outline-primary btn-shadow heading-font btn-discount">Discount for this product</button>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="discountModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function(){
    
    var discountInterval;
    discountInterval = setInterval(function(){
      if (jQuery('.wdr_table_container').length){
        jQuery('.product-discount').show();
        jQuery('#discountModal .modal-body').html(jQuery('.wdr_table_container').html());
        if (discountInterval){
          clearInterval(discountInterval);
        }
      }
    }, 500);

    if (jQuery('.wdr_table_container').length){
      jQuery('.product-discount').show();
      jQuery('#discountModal .modal-body').html(jQuery('.wdr_table_container').html());
    }
    jQuery('body').on('click', '.btn-discount', function(e){
      e.preventDefault();
      jQuery('#discountModal').addClass('in show');
      jQuery('#discountModal').show();
      jQuery('body').append('<div class="modal-backdrop fade show"></div>');
    });
    jQuery('body').on('click', '#discountModal .close', function(e){
      jQuery('#discountModal').removeClass('in show');
      jQuery('#discountModal').hide();
      jQuery('.modal-backdrop').remove();
    });
  });
</script>