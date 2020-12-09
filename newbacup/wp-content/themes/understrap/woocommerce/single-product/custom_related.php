<?php

global $product; // If not set…

if( ! is_a( $product, 'WC_Product' ) ){
    $product = wc_get_product(get_the_id());
}
$exclude_ids = getSesonalProducts();
$args = array(
    'posts_per_page' => 12,
    'columns'        => 4,
    'orderby'        => 'rand',
    'order'          => 'desc',
    'post__not_in'   => $exclude_ids
);
$upsell_products_ids = $product->get_upsell_ids();
$upsell_products = wc_get_products( array(
  'include' => array_diff($upsell_products_ids, $exclude_ids), 
));
$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], array_merge($exclude_ids, $upsell_products_ids) ) ), 'wc_products_array_filter_visible' );
$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
$args['related_products'] = array_merge($upsell_products, $args['related_products']);

// Set global loop values.
wc_set_loop_prop( 'name', 'related' );
wc_set_loop_prop( 'columns', $args['columns'] );
?>

</main>
</div>
</div>
</div>
<div class="container-fuild bg_related">
	<div class="woocommerce woocommerce-page">
		<div class="container">

			<?php wc_get_template( 'single-product/related.php', $args ); ?>
		</div>
	</div>

</div>

<style>
  .bg_related
  {
    background-image: url('<?php echo get_site_url() .  '/wp-content/themes/understrap/images/BG-2.png'?>');
  }
</style>