<?php
/**
 * Single post partial template
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
global $wp;
global $post;
$argv = [
		'category' => 1,
		'number_post_page' => 2
];
$post_category = wp_get_post_categories($post->ID);

$cats = array();
$postcat = get_the_category( $post->ID );
$postcat_id = $postcat[0]->cat_ID;

$related_post = new WP_Query([
	'cat' => $postcat_id,
	'posts_per_page' => 2,
    'post__not_in' => array( $post->ID ),
]);
 function func_custom_breadcrumb() {
    global $post;
    echo '<ul class="custom-breadcrumbs">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li><li class="separator"> &gt; </li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li class="separator"> &gt; </li><li> ');
            if (is_single()) {
                echo '</li><li class="separator"> &gt; </li><li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">&gt;</li>';
                }
                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    // elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    // elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ul>';
}
?>
<?php func_custom_breadcrumb(); ?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <div class="wp_border_line line-top-detail-blog"></div>
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title text-center">', '</h1>'); ?>
		<p class="date_create"><?php echo get_the_date('F d, Y') ?></p>
	</header>
    <div class="blog-detail-banner">
        <?php echo get_the_post_thumbnail($post->ID, 'full', 'width: 100%;'); ?>
    </div>
	<div class="blog-content">
        <div class="blog-content-sub">
            <?php the_content(); ?>
            <div class="clearfix"></div>
            <div class="share-wrap">
                <h2 class="text_share">SHARE THIS ARTICLE</h2>
                <div class="share_btn">
                    <a target="_blank" style="margin-left: 0"
                       href="https://twitter.com/intent/tweet?url=<?php echo home_url(add_query_arg(array(), $wp->request));
                       ?>">
                        <svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 1.01001C20 1.50001 19.02 1.69901 18 2.00001C16.879 0.735013 15.217 0.665013 13.62 1.26301C12.023 1.86101 10.977 3.32301 11 5.00001V6.00001C7.755 6.08301 4.865 4.60501 3 2.00001C3 2.00001 -1.182 9.43301 7 13C5.128 14.247 3.261 15.088 1 15C4.308 16.803 7.913 17.423 11.034 16.517C14.614 15.477 17.556 12.794 18.685 8.77501C19.0218 7.55268 19.189 6.28987 19.182 5.02201C19.18 4.77301 20.692 2.25001 21 1.00901V1.01001Z"
                                  stroke="#2D9CDB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a target="_blank" data-href="<?php echo home_url(add_query_arg(array(), $wp->request)); ?>"
                       href="https://www.facebook.com/sharer/sharer.php?u=<?php echo home_url(add_query_arg(array(), $wp->request)); ?>&amp;src=sdkpreparse"
                       class="fb-xfbml-parse-ignore">
                        <svg width="13" height="20" viewBox="0 0 13 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 8V12H4V19H8V12H11L12 8H8V6C8 5.73478 8.10536 5.48043 8.29289 5.29289C8.48043 5.10536 8.73478 5 9 5H12V1H9C7.67392 1 6.40215 1.52678 5.46447 2.46447C4.52678 3.40215 4 4.67392 4 6V8H1Z"
                                  stroke="#2F80ED" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <a target="_blank"
                       href="https://pinterest.com/pin/create/button/?url=<?php echo home_url(add_query_arg(array(), $wp->request)); ?>">
                        <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.66954 13.0972C6.16354 15.8232 5.54754 18.4372 3.71954 19.8022C3.15654 15.6822 4.54854 12.5872 5.19454 9.30219C4.09254 7.39219 5.32754 3.54719 7.65154 4.49419C10.5115 5.66019 5.17454 11.5962 8.75754 12.3382C12.4985 13.1122 14.0265 5.65519 11.7065 3.22919C8.35454 -0.271813 1.94954 3.15019 2.73754 8.16319C2.92954 9.38919 4.15854 9.76119 3.22854 11.4532C1.08354 10.9622 0.443536 9.22019 0.526536 6.89719C0.658536 3.09719 3.84354 0.435187 7.03754 0.0671871C11.0775 -0.398813 14.8685 1.59419 15.3915 5.50719C15.9815 9.92319 13.5685 14.7072 9.24954 14.3622C8.07854 14.2692 7.58654 13.6722 6.66954 13.0972Z"
                                  fill="#F44336"/>
                        </svg>
                    </a>
                    <a target="_blank"
                       href="mailto:info@example.com?&subject=&body=<?php echo home_url(add_query_arg(array(), $wp->request)); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5Z"
                                  stroke="#F44336" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 7L12 13L21 7" stroke="#F44336" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

<div class="row related_border" style="margin-top: 50px">

    <?php if (count($related_post->posts) > 0) { ?>
        <div class="col-12" style="margin-bottom: 24px">
            <div class="wp_border_line"></div>
        </div>
    	<div class="col-12" style="margin-bottom: 16px">
            <h2 class="title text-left">Related Articles</h2>
    	</div>
        <div class="col-12 blog-template-list">
            <?php foreach ($related_post->posts as $value): ?>
            <div class="row_blog">
                <div class="left_blog">
                    <div class="left_blog_sub">
                        <div class="border_top"></div>
                        <a href="<?php echo get_permalink($value->ID); ?>" class="title">
                            <h3><?php echo $value->post_title; ?></h3>
                        </a>
                        <p class="date_create"><?php echo get_the_date('Y/m/d', $value->ID) ?></p>
                        <div class="blog-content-div">
                            <?php echo wp_trim_words($value->post_content, 30); ?>
                            <a class="read_more" href="<?php echo get_permalink($value->ID); ?>">Read more...</a>
                        </div>
                        <p class="blog-read-button"><a href="<?php echo get_permalink($value->ID); ?>">Read more...</a></p>
                    </div>
                </div>
                <div class="right_blog">
                    <?php echo get_the_post_thumbnail($value->ID, 'post-thumbnail'); ?>
                </div>
                <div class="layer_blog"></div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php } ?>
	<div class="col-12 text-center">
		<a href="/blog">
			<button class="btn_add_to_cart mb-4">View all Articles</button>
		</a>
	</div>

</div>
<style>
	p {
		font-style: normal;
		font-weight: normal;
		font-size: 16px;
		line-height: 24px;
		letter-spacing: 0.44px;
		font-family: 'Gotham Regular', -apple-system, san-serif;
		color: #000000;
	}

	.post-navigation {
		display: none
	}

</style>
