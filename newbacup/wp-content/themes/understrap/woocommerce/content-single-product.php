<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
  <?php
  echo '<div class="d-block d-md-none">';
    wc_get_template('single-product/title.php');
  echo '</div>';
  wc_get_template('/loop/sale-flash.php');
  ?>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="product-info">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>


	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>


<script type="text/javascript">
  jQuery('document').ready(function(){

    jQuery('.additional-product .quantity_custom').each(function(){ 
      var subtractBtn = jQuery(this).find('.minus');
      var plusBtn = jQuery(this).find('.plus');
      var productIndex = jQuery(this).attr('data-product-index');
      var input = jQuery('#quantity_addition_'+productIndex);

      subtractBtn.on('click', function(e){
        e.preventDefault();
        var maxValue = parseInt(jQuery('#max-add-qty').attr('value'));
        maxValue = isNaN(maxValue) ? null : maxValue;
        if (input.val() > 0){
          input.val(parseInt(input.val()) - 1);
        }
      });

      plusBtn.on('click', function(e){
        e.preventDefault();
        var currentTotal = parseInt(jQuery('#quantity_addition_0').val()) + (jQuery('#quantity_addition_1').length ? parseInt(jQuery('#quantity_addition_1').val()) : 0);
        var maxValue = parseInt(jQuery('#max-add-qty').attr('value'));
        maxValue = isNaN(maxValue) ? null : maxValue;
        if (!maxValue || maxValue && currentTotal < maxValue){
          input.val(parseInt(input.val()) + 1);
        }
      });
    });

    jQuery('form.cart .product-add-cart').on('click', 'button.plus, button.minus', function () {
      var qty = jQuery(this).closest('form.cart .product-add-cart').find('.qty');
      var val = parseFloat(qty.val());
      var max = parseFloat(qty.attr('max'));
      var min = parseFloat(qty.attr('min'));
      var step = parseFloat(qty.attr('step'));
      if (jQuery(this).is('.plus')) {
        if (max && (max <= val)) {
          qty.val(max);
        } else {
          qty.val(val + step);
        }
      } else {
        if (min && (min >= val)) {
          qty.val(min);
        } else if (val > 1) {
          qty.val(val - step);
        }
      }
      onVariationQtyChanged(qty.val());
    });

    jQuery('form.cart .product-add-cart .qty').on('change', function(){
      onVariationQtyChanged(jQuery(this).val());
    })

    var initialMaxValue = jQuery('#max-add-qty').attr('value');
    function onVariationQtyChanged(qty) {
      if (jQuery('#max-add-qty').length){
        jQuery('#max-add-qty').attr('value', initialMaxValue * qty);
        jQuery('#add-products-max').text(initialMaxValue * qty);
        if (jQuery('#quantity_addition_0').length){
          jQuery('#quantity_addition_0').val(0);
        }
        if (jQuery('#quantity_addition_1').length){
          jQuery('#quantity_addition_1').val(0);
        }
      }
    };
  });

  jQuery('#toggle-tab-detail').on('click', function(){
    jQuery('table.variations').hide();
    jQuery('.single_variation_wrap').hide();
    jQuery('.bundle-products-option').hide();
  });

  jQuery('#toggle-tab-customize').on('click', function(){
    jQuery('table.variations').show();
    jQuery('.single_variation_wrap').show();
    jQuery('.bundle-products-option').show();
  });

  jQuery('.single_variation_wrap').on('show_variation', function ( event, variation ) {
    jQuery('.all_price').hide();
    jQuery('.variation_price').html(variation.price_html);
    jQuery('.variation_price').show();
  });
  jQuery('.single_variation_wrap').on('hide_variation', function ( event, variation ) {
    jQuery('.variation_price').html('');
    jQuery('.variation_price').hide();
    jQuery('.all_price').show();
  });
</script>
