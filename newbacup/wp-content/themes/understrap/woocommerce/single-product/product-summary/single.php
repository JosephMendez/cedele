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
global $wpdb;

function check_product_availability($store_id, $product_id) {
	global $wpdb;
  $result = $wpdb->get_results("
      SELECT wp_store_location_post.is_in_stock
      FROM wp_posts, wp_store_location_post
      WHERE wp_store_location_post.post_id = wp_posts.id
      AND wp_store_location_post.store_id = ${store_id} AND wp_store_location_post.post_id
      IN (SELECT post_id FROM wp_postmeta WHERE meta_key = 'delivery_method'
      AND (meta_value = 'both' OR meta_value = 'self'))
      AND wp_posts.id = $product_id
      ORDER BY wp_posts.id
  "
  );
  if (!empty($result) && $result[0]->is_in_stock == 1) {
    return true;
  } else {
    return false;
  }
}

$product_id = $product->get_id();
$result_location = $wpdb->get_results("select * from wp_store_location where id IN(select store_id from wp_store_location_post where post_id = $product_id)");
$result_post = $wpdb->get_results("select post_content from wp_posts where id = $product_id limit 1");
foreach ($result_location as $key => $location) {
	$availability = check_product_availability($location->id, $product_id);
	$result_location[$key]->is_in_stock = $availability;
}
?>


<div class="product-info">
	<div class="product-detail">
	  <ul class="nav nav-tabs">
		<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">PRODUCT DETAILS</a></li>
		<?php if ($result_location != null) { ?>
		  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">STORE AVAILABLE</a></li>
		<?php } ?>
	  </ul>
	  <div class="tab-content">
		<div id="home" class="tab-pane fade show active">
		  <br>
		  <?php wc_get_template('/single-product/product-summary/ingredients.php');?>
		  <div id="div-description">
			<?php foreach ($result_post as $value) { ?>
			  <p class="text_description"><?php echo $value->post_content; ?></p>
			<?php } ?>
		  </div>
		</div>
		<div id="menu1" class="tab-pane fade">
		  <table class="table table-striped store-table">
			<?php foreach ($result_location as $value) { ?>
				<?php if ($value->is_in_stock) { ?>
				  <tr>
						<td class="text-left">
						  <strong class="text_address"><?php echo $value->store_name ?></strong>
						</td>
						<td class="text-left">
						  <span><?php echo $value->number_house . ' ' . $value->street_name . ', ' . $value->floor_unit . ' ' . $value->building . ', Singapore' . ' ' . $value->zipcode ?></span>
						</td>
				  </tr>
				<?php } ?>
			<?php } ?>
		  </table>
		</div>
	  </div>
	</div>
</div>
<?php
if ($product->get_type() == 'giftcard') {
	wc_get_template('single-product/add-to-cart/bundle.php');
}
