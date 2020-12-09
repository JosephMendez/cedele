<?php
/**
 * Single Product boxandtab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */
?>
<?php
global $product;
global $woocommerce;
global $wpdb;
$product_id = $product->get_id();
$result_location = $wpdb->get_results("select * from wp_store_location where id IN(select store_id from wp_store_location_post where post_id = $product_id)");
$result_post = $wpdb->get_results("select post_content from wp_posts where id = $product_id limit 1");
$attributes = $product->get_attributes();
$attribute_keys = array_keys($attributes);

$available_variations = $product->get_available_variations();
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
?>

<div class="product-info">
	<div class="product-detail">
      <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#customize" id="toggle-tab-customize">CUSTOMIZE</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product_detail" id="toggle-tab-detail">PRODUCT DETAILS</a></li>

      </ul>
      <div class="tab-content">
        <div id="customize" class="tab-pane fade show active">

        </div>
        <div id="product_detail" class="tab-pane fade">
          <br>
          <div id="div-description">
            <?php foreach ($result_post as $value) { ?>
              <p class="text_description"><?php echo $value->post_content; ?></p>
            <?php } ?>
          </div>

        </div>
      </div>
    </div>
</div>

<?php
// if($product->get_type() === 'variable') {
//   do_action('woocommerce_after_add_to_cart_form', $post_additional[0][0], 1, $post_additional[0][1], 2);
// }
do_action('woocommerce_after_variations_form');

?>
