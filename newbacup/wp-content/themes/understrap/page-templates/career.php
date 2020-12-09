<?php
/**
 * Template Name: Career
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$sdls_banner_career_page = wp_get_attachment_image_src(get_option('sdls_banner_career_page'), 'full');

get_header();

//while ( have_posts() ) {


$argsCat = array(
	"taxonomy" => 'career_category',
	'parent' => 0
);
$_categories = get_categories($argsCat);

$argsTag = array(
	"taxonomy" => 'career_tag',
	'parent' => 0
);
$_tags = get_categories($argsTag);

$args = array();
$args['posts_per_page'] = -1;
$args['post_type'] = 'career';
$args['post_status'] = 'publish';


$query = new WP_Query($args);
$class = ' ' . $style;

?>

	<div class="careerPage">
		<?php
		if ($sdls_banner_career_page) { ?>
			<div class="careerImageBanner">
				<img class="hero-banner" src="<?php echo $sdls_banner_career_page[0]; ?>"/>
			</div>
		<?php } ?>

		<div class="container">
			<div class="careerPageInner">
				<div class="careerPageContent">
					<div class="contentPage">
						<?php
						the_post();
						the_content();
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="jobBlock">
			<div class="container">
				<div class="jobBlockContent">
					<div class="row">
						<div class="col-12 col-lg-5">
							<div class="colLeft">
								<div class="blockTitle">
									<h3 class="title">Apply Now</h3>
<!--									<div class="desc">Nec pharetra integer ultrices tempus rutrum. Ullamcorper pharetra habitant montes, scelerisque amet montes, at. Enim natoque aliquet massa congue non aliquam.</div>-->
								</div>
								<div class="applyForm">
									<?php echo do_shortcode('[contact-form-7 id="1452" title="Apply Career"]'); ?>
								</div>

							</div>
						</div>

						<div class="col-12 col-lg-7">
							<div class="colRight">
								<form id="filerCareer" class="filerCareer" method="post" action="#">
									<div class="formFiler">
										<div class="blockTitle">
											<h3 class="title">Opening Jobs</h3>
										</div>
										<div class="desc">Showing <span id="numberCareer"><?php echo $query->post_count ?></span> Jobs</div>
										<div class="fieldFilter">
											<select id="tagCareerFilter" class="txt_select" name="career-filter-tag">
												<option value="">Type</option>
												<?php
												foreach ($_tags as $item) {
													echo '<option value="' . $item->term_id . '">' . $item->name . '</option>';
												} ?>
											</select>
											<select id="catCareerFilter" class="txt_select" name="career-filter-cat">
												<option value="">Department</option>
												<?php
												foreach ($_categories as $item) {
													echo '<option value="' . $item->term_id . '">' . $item->name . '</option>';
												} ?>
											</select>
											<input type="hidden" name="action" value="filder_career">
											<?php wp_nonce_field('ajax-filter-career-nonce', 'career-security'); ?>
										</div>

									</div>

									<div id="listCareer">
										<?php
										while ($query->have_posts()) : $query->the_post();
											get_template_part('content-career');
										endwhile;
										wp_reset_postdata(); ?>
									</div>

								</form>
							</div>
						</div>


					</div>
				</div>
			</div>
		</div>

	</div>
<?php
//}?>

<?php
get_footer();
