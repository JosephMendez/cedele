<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
global $wpdb;
$ck = $wpdb->get_row("SELECT * FROM wp_store_location WHERE central_kitchen = 1");

?>
<?php if (is_front_page()) {
	dynamic_sidebar( 'footerfull' );
} ?>
<div class="wrapper" id="wrapper-footer">
		<div class="<?php echo esc_attr( $container ); ?>">
				<div class="cedele-footer">
						<div class="row cedele-footer-content">
								<div class="col-md-3 cedele-footer-central-kitchen">
										<img src="<?php echo get_template_directory_uri(); ?>/images/cedele-footer.svg" width="200px" />
										<?php if ($ck) { ?>
												<p>
														<?php echo $ck->number_house . ' ' . $ck->street_name . ', ' . $ck->floor_unit . ' ' . $ck->building . ', Singapore, ' . $ck->zipcode ?>
														<br />
														Tel: <?php echo $ck->phone_number ?>
												</p>
										<?php } ?>
								</div>
								<div class="col-md-4">
										<?php dynamic_sidebar( 'footercol2' ); ?>
								</div>
								<div class="col-md-5">
										<?php dynamic_sidebar( 'footercol3' ); ?>
								</div>
						</div>
				</div>
		</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var msg = '<p style="color: #ffffff; margin-top: 5px;">Exclusive deals and vouchers are waiting for you!</p>';
		if (jQuery('input.tnp-email').length) {
			jQuery('input.tnp-email').attr('placeholder', 'Enter your email address');
			jQuery('input.tnp-email').after(msg);
		}
	});
</script>


<?php
if ( (is_page( 'cart' ) || is_cart()) && !is_user_logged_in() ) {
	get_template_part( 'inc/cartLoginRegister/ajax_login_html');
}
?>

<?php wp_footer(); ?>

</body>

</html>
