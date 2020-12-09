<?php
global $product;
$product_id = $product->get_id();
$post_additional = get_post_meta($product_id, '_custom_product_additional_linked');
$quantity_additional = get_post_meta($product_id, '_custom_product_quality_linked');
$additional_products = array();

if (count($post_additional) > 0){
  foreach ($post_additional[0] as $key => $product_id) {
    $_product = wc_get_product($product_id);
    array_push($additional_products, $_product);
  }
}
$list_bundle_product = get_post_meta($product_id, '_wc_bundle_products', true);
$gift_card_value = get_post_meta($product_id, 'gift_card_value', true);
$expiry_duration = get_post_meta($product_id, 'expiry_duration', true);
?>

  <?php
  if ( isset($gift_card_value) )  { ?>
    <input type="hidden" name="gift_card_value" value="<?php echo $gift_card_value;?>" />
    <input type="hidden" name="expiry_duration" value="<?php echo $expiry_duration;?>" />
  <?php }
  if (!empty($list_bundle_product)) {
    echo '<input type="hidden" name="bundle_data" id="bundle-data"/>';
    foreach ($list_bundle_product as $key => $bundle_section) { ?>
      <div class="bundle-section <?php echo $bundle_section['is_user_can_define'] == '0' ? 'single-selection' : '' ?>"
        data-price="<?php echo floatval($product->get_price()) ?>"
        option-thousand-sep="<?php echo get_option('woocommerce_price_thousand_sep', ','); ?>"
        option-decimal-sep="<?php echo get_option('woocommerce_price_decimal_sep', '.'); ?>"
        option-decimal-price-num="<?php echo get_option('woocommerce_price_num_decimals', 2); ?>">
        <h5 class="cdl-heading">
          <?php echo $bundle_section['title']; ?>
          <?php if ( $bundle_section['maximum'] && $bundle_section['is_user_can_define'] == '1' ){ ?>
            <span class="bundle-max-items">( max <?php echo $bundle_section['maximum']; ?> items )</span>
          <?php } ?>
        </h5>
        <?php if ($bundle_section['is_user_can_define'] == 1){ ?>
          <ul class="bundle-products mb-0">
            <?php foreach ($bundle_section['linked_products'] as $k => $pr) {
              $_product = wc_get_product($pr['product_id']); ?>
              <li>
                <h6 class="cdl-heading"><?php echo $_product->get_name(); ?></h6>
                <div class="bundle-product-qty text-right">
                  <div class="quantity_bundle" data-max-value="<?php echo $bundle_section['maximum']; ?>" data-group-index="<?php echo $key; ?>" data-product-index="<?php echo $k; ?>">
                    <button type="button" class="minus"></button>
                    <div class="quantity">
                      <input type="text"
                        id="quantity_bundle_<?php echo $key;?>_<?php echo $k;?>"
                        name="quantity_bundle_<?php echo $key;?>_<?php echo $k;?>"
                        class="input-text input-bundle-qtt"
                        step="1" min="0"
                        readonly=""
                        max="<?php echo $_product->get_stock_quantity();?>"
                        value="0"
                        size="4" />
                    </div>
                    <button type="button" class="plus"></button>
                  </div>
                </div>
              </li>
            <?php } ?>
          </ul>
          <span class="text-danger hint-text d-block mb-3"></span>
        <?php } else { ?>
          <table class="variations w-auto">
            <tbody>
              <tr>
                <td>
                  <select class="bundle-choose-option" <?php if($bundle_section['is_user_can_define'] == 0 && $bundle_section['is_required']==1) echo 'required'?>>
                    <option value="" data-additional-price="0">Choose an option</option>
                    <?php foreach ($bundle_section['linked_products'] as $k => $pr) {
                      $_product = wc_get_product($pr['product_id']); ?>
                      <option data-index="<?php echo $key;?>" data-additional-price="<?php echo floatval($pr['price']); ?>" data-quantity="<?php echo $pr['quantity'];?>" value="<?php echo $pr['product_id'];?>">
                        <?php echo $_product->get_name(); ?> (x<?php echo $pr['quantity']?>) <?php echo floatval($pr['price']) ? ('(+$' . floatval($pr['price']) . ')') : '' ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        <?php } ?>
      </div>
    <?php }
  }
  if (!empty($post_additional)) {
    echo '<input type="hidden" id="max-add-qty" value="'.$quantity_additional[0].'"/>';
    echo '<div class="d-flex w-100 mt-3 additional-products">';
    foreach ($additional_products as $key => $_product) {
    ?>
    <div class="additional-product" style="flex:1">
      <input type="hidden" name="product_addition_<?php echo $key;?>" value="<?php echo $_product->get_name(); ?>"/>
      <label class="title_label"><?php echo $_product->get_name(); ?></label>
      <div class="quantity_custom additional" data-product-index="<?php echo $key; ?>">
        <button type="button" class="minus"></button>
        <div class="quantity">
          <input type="text"
            id="quantity_addition_<?php echo $key;?>"
            name="quantity_addition_<?php echo $key;?>"
            class="input-text text"
            step="1" min="0"
            readonly=""
            max="<?php echo $_product->get_stock_quantity();?>"
            value="0"
            size="4" />
        </div>
        <button type="button" class="plus"></button>
      </div>
    </div>
  <?php }
    echo '</div>';
    if ($quantity_additional[0]){
      echo '<div class="mb-3"><p style="color: #AAAAAA">Maximum <span id="add-products-max">'.$quantity_additional[0].'</span> products.</p></div>';
    }
  } ?>
  <div>
    <?php echo do_shortcode('[alg_display_product_input_fields]'); ?>
  </div>

<?php
  if (!empty($list_bundle_product)) {
?>
  <script type="text/javascript">
    jQuery('document').ready(function(){

      var bundleData = <?php echo json_encode($list_bundle_product); ?>;
      bundleData.forEach(function(data){
        if (data.is_user_can_define == 1){
          if (data.linked_products.length){
            data.linked_products.forEach(function(pr){
              pr.quantity = 0;
            });
          }
        }
      });
      jQuery('#bundle-data').val(JSON.stringify(bundleData));

      function calBundleQty(container) {
        var total = 0;
        jQuery(container).closest('.bundle-section').find('.input-bundle-qtt').each(function(){
          total += parseInt(jQuery(this).val());
        });
        return total;
      };

      function updateBundleData(container, val) {
        var groupIndex = container.attr('data-group-index');
        var productIndex = container.attr('data-product-index');
        bundleData[groupIndex].linked_products[productIndex].quantity = val;
        jQuery('#bundle-data').val(JSON.stringify(bundleData));
      }

      jQuery('.quantity_bundle').each(function(){
        var container = jQuery(this);
        var subtractBtn = jQuery(this).find('.minus');
        var plusBtn = jQuery(this).find('.plus');
        var productIndex = jQuery(this).attr('data-product-index');
        var input = jQuery(this).find('input.input-text');
        var maxValue = parseInt(jQuery(this).attr('data-max-value'));

        subtractBtn.on('click', function(e){
          e.preventDefault();
          if (input.val() > 0){
            input.val(parseInt(input.val()) - 1);
          }
          updateBundleData(container, input.val());
        });

        plusBtn.on('click', function(e){
          e.preventDefault();
          var currentTotal = calBundleQty(container);
          if (!maxValue || maxValue && currentTotal < maxValue){
            input.val(parseInt(input.val()) + 1);
          }
          updateBundleData(container, input.val());
        });
      });

      jQuery('.bundle-choose-option').on('change', function(){
        // change price
        change_woocommerce_bundle_product()

        var selectedOption = jQuery(this).find('option:selected');
        var selected = selectedOption.attr('value');
        var optionIndex = selectedOption.attr('data-index');
        bundleData[optionIndex].linked_products.forEach(function(data, i){
          bundleData[optionIndex].linked_products[i].selected = false;
          if (data.product_id == selected){
            bundleData[optionIndex].linked_products[i].selected = true;
          }
        });
        jQuery('#bundle-data').val(JSON.stringify(bundleData));
      });

      var formValid = false;
      jQuery(document).on('submit', 'form.cart', function(event) {
        var isValid = true;
        jQuery('.bundle-products-option').find('.hint-text').text('');
        bundleData.forEach(function(data, i){
          if (data.is_user_can_define == '1'){
            var totalQty = 0;
            data.linked_products.forEach(function(product){
              totalQty += parseInt(product.quantity);
            });
            if (totalQty < parseInt(data.maximum)){
              isValid = false;
              jQuery('.bundle-products-option .bundle-section').eq(i).find('.hint-text').text('Please select more products');
            }
          }
        });
        if (!isValid){
          event.preventDefault();
        }
      });

      function formatNumber(num, thousand_sep = ',') {
        if (num) {
          return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousand_sep}`)
        }
        return 0
      }

      function change_woocommerce_bundle_product() {
        var origin_price = jQuery('.bundle-section').attr('data-price');
        var total_additional_price = get_bundle_product_additional();
        total_additional_price += parseFloat(origin_price);

        var floor_total_additional_price = Math.floor(total_additional_price);
        var decimal_total_additional_price = (total_additional_price - floor_total_additional_price).toFixed(2);

        // woocommerce_price_decimal_sep
        var thousand_sep = jQuery('.bundle-section').attr('option-thousand-sep');
        var decimal_sep = jQuery('.bundle-section').attr('option-decimal-sep');
        var decimal_price_num = jQuery('.bundle-section').attr('option-decimal-price-num');

        price_str = '$' + formatNumber(floor_total_additional_price, thousand_sep) + decimal_sep;
        price_decima_str = Math.floor(decimal_total_additional_price * (10 ** decimal_price_num));
        if (price_decima_str == 0) {
          price_decima_str = '0'.repeat(decimal_price_num);
        }
        var ele_price_title = jQuery('.bundle-products-option').closest('form').find('.product-add-cart .woocommerce-Price-amount');
        if (jQuery('.bundle-products-option').closest('form').find('.product-add-cart ins').length) {
          ele_price_title = jQuery('.bundle-products-option').closest('form').find('.product-add-cart ins .woocommerce-Price-amount');
        }

        jQuery(ele_price_title).find('span').first().html(price_str)
        jQuery(ele_price_title).find('span.price-decima').html(price_decima_str);
      }

      function get_bundle_product_additional() {
        var bundle_product_price = 0;
        jQuery('.bundle-choose-option').each(function(index, el) {
          var selectedOption = jQuery(this).find('option:selected');
          var additional_price = selectedOption.attr('data-additional-price');

          bundle_product_price += parseFloat(additional_price);
        });
        return bundle_product_price;
      }
    });
  </script>
<?php }
