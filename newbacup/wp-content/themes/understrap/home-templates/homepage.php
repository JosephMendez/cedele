<?php
/**
 * Template Name: Home Page
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
get_template_part( 'global-templates/hero' );

$shopUrl = get_permalink( woocommerce_get_page_id( 'shop' ) );
$featuredCats = cedele_get_featured_categories();
$highlightCats = cedele_get_highlight_categories();
$getTermId = function($value) {
    return $value->term_id;
};
if (count($highlightCats) > 0){
    $highlightCatsId = array_map($getTermId, $highlightCats);
    $args = array(
        'hide_empty'=> 0,
        'orderby' => 'id',
        'order' => 'ASC',
        'include' => $highlightCatsId
    );
    $highlightCategories = get_terms( 'product_cat', $args);
}
usort($featuredCats, function($a, $b) {
    return strcmp($a->meta_value, $b->meta_value);
});
?>
<?php if (isset($highlightCategories)) { ?>
    <div class="hightlight-categories">
        <div class="<?php echo esc_attr( $container ); ?>">
            <div class="hightlight-categories-carousel cedele-carousel owl-carousel">
                <?php
                    foreach ($highlightCategories as $cat) {
                        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
                        $image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
                ?>
                    <a href="<?php echo $shopUrl . '?swoof=1&product_cat='. $cat->slug; ?>" class="highlight-cat">
                        <img src="<?php echo $image[0]?>"/>
                    </a>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php
    foreach ($featuredCats as $key=>$cat) {
        echo '<div class="woocommerce woocommerce-page featured-products section-'.$key.'">';
        echo '<div class="'.esc_attr( $container ).'">';
        $currentCat = get_terms( 'product_cat', array('include' => array($cat->term_id)));
        $exclude_ids = getSesonalProducts();
		$args = array(
			'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__not_in' => $exclude_ids,
            'tax_query' => array( array(
                'taxonomy'         => 'product_cat',
                'field'            => 'slug',
                'terms'            => $currentCat[0]->slug,
            )),
		);
        $loop = new WP_Query( $args );
        echo '<h3 class="cdl-heading mb-5">'.$currentCat[0]->name.'</h3>';
		if ( $loop->have_posts() ) {
            echo '<ul class="products columns-4 cedele-carousel owl-carousel">';
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
            endwhile;
            echo '</ul>';
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
        echo '</div></div>';
    }
?>

<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">

					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'loop-templates/content', 'page' );
					}
					?>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- #content -->

</div><!-- #full-width-page-wrapper -->

<?php
get_footer();
