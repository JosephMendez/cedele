<?php
/**
 * Template Name: Contact Page
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$sdls_banner_contact_page = wp_get_attachment_image_src(get_option('sdls_banner_contact_page'), 'full');

get_header();

while ( have_posts() ) {

	?>

	<div class="contactPage">
		<?php
		if ($sdls_banner_contact_page){  ?>
			<div class="contactImageBanner">
				<img src="<?php echo $sdls_banner_contact_page[0]; ?>" />
			</div>
		<?php } ?>
		<div class="container">
			<div class="contactPageInner">
				<div class="contactPageContent">
					<div class="titleBLock">
						<h3 class="title">Contact us</h3>
					</div>
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
