<?php
/**
 * Template Name: FAQ
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );


global $wp;
$faq_custom_table = $wpdb->prefix . 'faq_custom';
$faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

$query_string = "SELECT * FROM $faq_categories_custom_table";
$query_string_faq = "SELECT * FROM $faq_custom_table";
$query_last_update = "SELECT updated_at FROM $faq_custom_table ORDER BY updated_at DESC";

$list_categories = $wpdb->get_results($query_string);
$list_faq = $wpdb->get_results($query_string_faq);
$last_updated_at = $wpdb->get_row($query_last_update);
if ($last_updated_at) {
    $last_updated_at = $last_updated_at->updated_at;
}
?>
<div class="wrapper" id="front-faq-page">
    <div class="<?php echo esc_attr( $container ); ?>" id="content">
        <div class="row">
            <div class="col-md-12 content-area" id="primary">
                <main class="site-main" id="main" role="main">
                    <h3 class="front-faq-page__h3-title d-none d-sm-block">Frequently Asked Questions</h3>
                    <h3 class="front-faq-page__h3-title d-block d-sm-none">FAQ</h3>
                    <div class="row row-xs">
                        <div class="col-lg-4 front-faq-page__section-left">
                            <div class="front-faq-page__section-left-sub">
                                <ul class="front-faq-page__section-ul">
                                    <?php
                                    if ($list_categories):
                                        foreach ($list_categories as $category_key => $category):
                                        ?>
                                            <li data-for="<?php esc_html_e("section-category-" . $category->id) ?>"><?php esc_html_e($category->title); ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8 front-faq-page__section-right">
                            <ul class="front-faq-page__section-right--list-categories">
                                <?php
                                if ($list_categories):
                                    foreach ($list_categories as $category_key => $category):
                                    ?><li data-for="<?php esc_html_e("section-category-" . $category->id) ?>" class="front-faq-page__section-right--item-category"><?php esc_html_e($category->title); ?></li><?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <?php
                            if ($list_categories):
                                foreach ($list_categories as $category_key => $category):
                                ?>
                                <div id="<?php esc_html_e("section-category-" . $category->id) ?>" class="front-faq-page__section-right--faq-category">
                                    <div class="section-right--border-top"></div>
                                    <h4 class="section-right--category-title" data-category-id="<?php esc_html_e($category->id) ?>">
                                        <?php esc_html_e($category->title) ?>
                                    </h4>
                                    <div class="section-right--list-faqs">
                                <?php
                                    if ($list_faq):
                                        $faq_no = 1;
                                        foreach ($list_faq as $faq_key => $faq):
                                            if ($faq->faq_category_id === $category->id):
                                ?>
                                            <div class="section-right--faq-item">
                                                <div class="section-right--faq-item-question">
                                                    <span class="section-right--faq-item-question-content"><?php echo $faq_no . '. ' . $faq->question ?></span>
                                                    <span class="section-right--faq-item-question-icon">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6 9L12 15L18 9" stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="section-right--faq-item-answer">
                                                    <?php echo $faq->answer ?>
                                                </div>
                                            </div>
                                            <?php $faq_no++;endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="front-faq-page__section-right-note-last-update">
                                <p>This FAQ will be constantly updated depending on the number of commonly asked questions we receive. Thank you for your support!</p>
                                <p>Last updated on <?php echo date('d F Y', strtotime($last_updated_at)); ?></p>
                            </div>
                        </div>
                    </div>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .row end -->
    </div><!-- #content -->
</div><!-- #full-width-page-wrapper -->

<?php
get_footer();
