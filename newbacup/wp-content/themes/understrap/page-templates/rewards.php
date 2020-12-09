<?php
/**
 * Template Name: Rewards Template
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$sdls_banner_rewards_page = wp_get_attachment_image_src(get_option('sdls_banner_rewards_page'), 'full');

get_header();

while ( have_posts() ) {

	?>

	<div class="rewardsPage">
		<?php
		if ($sdls_banner_rewards_page){  ?>
			<div class="rewardsImageBanner">
				<img src="<?php echo $sdls_banner_rewards_page[0]; ?>" />
			</div>
		<?php } ?>
		<div class="container">
			<div class="rewardsPageInner">
				<div class="rewardsPageContent">
					<div class="contentPage">
						<?php
						the_post();
						the_content();
						?>
					</div>
				</div>
			</div>
		</div>

	</div>
	<?php
}?>

<?php
get_footer();
