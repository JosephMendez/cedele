<?php
/**
 * Template Name: Blog Page Template
 *
 * Template for displaying a blank page.
 *
 * @package UnderStrap
 */
?>
<?php

get_header();
?>
<?php
$banner_blog = wp_get_attachment_image_src(get_option('sdls_blog_image'), 'full');
$banner_ads = wp_get_attachment_image_src(get_option('sdls_banner_ads'), 'full');
$cate_ID = 0;
$list_post = get_posts([
    'posts_per_page' => 4,
    'category' => $cate_ID
]);
//print_r($list_post);
$category = wp_list_categories(array(
    'title_li' => '',
    'style' => 'list',
    'echo' => false
));
$list_categories = get_categories();
$content = the_content();
$container = get_theme_mod( 'understrap_container_type' );
?>
<div class="blog-template" id="page-wrapper">
    <img class="hero-banner" alt="<?php echo get_the_title(); ?>" src="<?php echo $banner_blog[0]; ?>"/>
    <img class="icon_blog_1 icon_left_blog" src="<?php echo get_template_directory_uri() .  '/images/Bread-1.png'?>" alt="<?php echo get_the_title(); ?>">
    <img class="icon_blog_2" src="<?php echo get_template_directory_uri() .  '/images/Long-Bread.png' ?>"alt="<?php echo get_the_title(); ?>">
    <img class="icon_blog_3" src="<?php echo get_template_directory_uri() .  '/images/French-Bread.png'?>" alt="<?php echo get_the_title(); ?>">
    <img class="icon_blog_4" src="<?php echo get_template_directory_uri() .  '/images/Banana.png'?>" alt="<?php echo get_the_title(); ?>">

    <div class="<?php echo esc_attr( $container ); ?>" id="content">
        <div class="blog-template-header">
            <h1 class="cdl-heading">Blog</h1>
            <div class="div-select-category-blog">
                <select class="select-category-blog form-control custom-select">
                    <option value="">Category</option>
                    <?php foreach ($list_categories as $key => $cate):?>
                    <option value="<?php echo get_category_link($cate->term_id); ?>"><?php echo $cate->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row row-xs mt-4 mt-sm-5 blog-template-section">
            <div class="col-lg-8 col-md-12 blog-template-list">
                <?php
                global $wp_query;
                $args = array_merge($wp_query->query_vars, ['posts_per_page' => 5]);
                query_posts($args);

                if (have_posts()) {
                    // Start the loop.
                    while (have_posts()) {
                        the_post();
                        ?>
                        <div class="row_blog">
                            <div class="left_blog">
                                <div class="left_blog_sub">
                                    <div class="border_top"></div>
                                    <a href="<?php echo get_permalink($post->id); ?>" class="title">
                                        <h3><?php echo the_title(); ?></h3>
                                    </a>
                                    <p class="date_create"><?php echo get_the_date('Y/m/d', $post->id) ?></p>
                                    <div class="blog-content-div">
                                        <?php ld_custom_excerpt_length(the_excerpt()) ?>
                                    </div>
                                    <div class="blog-read-button"><a href="<?php echo get_permalink($post->id); ?>">Read more...</a></div>
                                </div>
                            </div>
                            <div class="right_blog">
                                <?php echo get_the_post_thumbnail($post->ID, 'post-thumbnail'); ?>
                            </div>
                            <div class="layer_blog"></div>
                        </div>
                    <?php }
                } else {
                    ?>
                    <?php get_template_part('loop-templates/content', 'none'); ?>
                <?php } ?>
            </div>
            <div class="col-lg-4 blog-template-ads">
                <div class="blog-template-ads-sub">
                    <div class="row_category_blog">
                        <div class="category_list">
                            <ul class="list-group">
                                <li class="cat_item_title"><a>Category</a></li>
                                <?php echo $category ?>
                            </ul>
                        </div>
                    </div>
                    <div class="ads_blog">
                        <a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ) ?>">
                            <button class="btn_add_to_cart">Order now!</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .ads_blog
    {
        margin-top: 30px;
        width: 100%;
        height: 500px;
        background: url('<?php echo $banner_ads[0]; ?>');
        background-size: cover;
        text-align: center;
    }
</style>
