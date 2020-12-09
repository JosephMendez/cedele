<?php
/**
 * Wishlist page template - Standard Layout
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $wishlist                      \YITH_WCWL_Wishlist Current wishlist
 * @var $wishlist_items                array Array of items to show for current page
 * @var $wishlist_token                string Current wishlist token
 * @var $wishlist_id                   int Current wishlist id
 * @var $users_wishlists               array Array of current user wishlists
 * @var $current_page                  int Current page
 * @var $page_links                    array Array of page links
 * @var $is_user_owner                 bool Whether current user is wishlist owner
 * @var $show_price                    bool Whether to show price column
 * @var $show_dateadded                bool Whether to show item date of addition
 * @var $show_stock_status             bool Whether to show product stock status
 * @var $show_add_to_cart              bool Whether to show Add to Cart button
 * @var $show_remove_product           bool Whether to show Remove button
 * @var $show_price_variations         bool Whether to show price variation over time
 * @var $show_variation                bool Whether to show variation attributes when possible
 * @var $show_cb                       bool Whether to show checkbox column
 * @var $show_quantity                 bool Whether to show input quantity or not
 * @var $show_ask_estimate_button      bool Whether to show Ask an Estimate form
 * @var $show_last_column              bool Whether to show last column (calculated basing on previous flags)
 * @var $move_to_another_wishlist      bool Whether to show Move to another wishlist select
 * @var $move_to_another_wishlist_type string Whether to show a select or a popup for wishlist change
 * @var $additional_info               bool Whether to show Additional info textarea in Ask an estimate form
 * @var $price_excl_tax                bool Whether to show price excluding taxes
 * @var $enable_drag_n_drop            bool Whether to enable drag n drop feature
 * @var $repeat_remove_button          bool Whether to repeat remove button in last column
 * @var $available_multi_wishlist      bool Whether multi wishlist is enabled and available
 * @var $no_interactions               bool
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<!-- WISHLIST TABLE -->
<div
	class="wishlist_table <?php echo $no_interactions ? 'no-interactions' : ''; ?>"
	data-pagination="<?php echo esc_attr( $pagination ); ?>" data-per-page="<?php echo esc_attr( $per_page ); ?>"
	data-page="<?php echo esc_attr( $current_page ); ?>" data-id="<?php echo esc_attr( $wishlist_id ); ?>"
	data-token="<?php echo esc_attr( $wishlist_token ); ?>">

	<?php $column_count = 2; ?>

	<div class="wishlist-items-wrapper">
	<?php
	if ( $wishlist && $wishlist->has_items() ) :
		foreach ( $wishlist_items as $item ) :
			// phpcs:ignore Generic.Commenting.DocComment
			/**
			 * @var $item \YITH_WCWL_Wishlist_Item
			 */
			global $product;

			$product      = $item->get_product();
			$availability = $product->get_availability();
			$stock_status = isset( $availability['class'] ) ? $availability['class'] : false;
			$is_in_stock = $product->is_in_stock();
			$product_id = $product->get_id();
			$product_type = get_post_meta($product_id, '_type', true);
			$is_seasonal_product = $product_type == 'season-product-date-range' || $product_type == 'season-product-one-day-only';
			$is_seasonal_available = $is_in_stock && $is_seasonal_product && checkSeasonalProductAvailability($product_type, $product_id);

			if ( $product && $product->exists() ) :
				?>
				<div id="yith-wcwl-row-<?php echo esc_attr( $item->get_product_id() ); ?>"
					 class="itemWrap"
					 data-row-id="<?php echo esc_attr( $item->get_product_id() ); ?>">
					<?php if ( $show_cb ) : ?>
						<div class="product-checkbox">
							<input type="checkbox" value="yes" name="items[<?php echo esc_attr( $item->get_product_id() ); ?>][cb]"/>
						</div>
					<?php endif ?>


					<div class="item">
						<?php wc_get_template('/loop/sale-flash.php');?>
						<?php if ( $show_remove_product ) : ?>
							<div class="product-remove">
									<a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item->get_product_id() ) ); ?>" class="remove_from_wishlist" title="<?php echo esc_html( apply_filters( 'yith_wcwl_remove_product_wishlist_message_title', __( 'Remove this product', 'yith-woocommerce-wishlist' ) ) ); ?>">
										<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M1 1L13 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</a>
							</div>
						<?php endif; ?>


						<div class="itemThumb">
							<?php do_action( 'yith_wcwl_table_before_product_thumbnail', $item, $wishlist ); ?>
							<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item->get_product_id() ) ) ); ?>">
								<?php echo $product->get_image(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<?php do_action( 'yith_wcwl_table_after_product_thumbnail', $item, $wishlist ); ?>
						</div>
						<div class="itemDetail">
							<h3 class="itemName">
								<?php do_action( 'yith_wcwl_table_before_product_name', $item, $wishlist ); ?>

								<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item->get_product_id() ) ) ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ); ?></a>

								<?php do_action( 'yith_wcwl_table_after_product_name', $item, $wishlist ); ?>
							</h3>

							<?php
							if ( $show_variation && $product->is_type( 'variation' ) ) {
								echo '<div  class="itemVariation">';
								echo wc_get_formatted_variation( $product );
								echo '</div>';
							}

							?>

							<div class="itemAddToCart">
								<div class="w-100 invalid-message">
									<?php
										if ( !$is_in_stock ){
											echo '<p>Out of stock</p>';
										}
										if ( $is_seasonal_product && !$is_seasonal_available ){
											echo '<p>Product is not available anymore</p>';
										}
									?>
								</div>
								<?php do_action( 'yith_wcwl_table_product_before_add_to_cart', $item, $wishlist ); ?>

								<!-- Add to cart button -->
								<?php $show_add_to_cart = apply_filters( 'yith_wcwl_table_product_show_add_to_cart', $show_add_to_cart, $item, $wishlist ); ?>
								<?php if ( $show_add_to_cart && ( ( isset( $stock_status ) && 'out-of-stock' !== $stock_status && !$is_seasonal_product ) || ( $is_seasonal_product && $is_seasonal_available ) ) ) : ?>
									<?php woocommerce_template_loop_add_to_cart( array( 'quantity' => $show_quantity ? $item->get_quantity() : 1 ) ); ?>
								<?php endif ?>

								<?php do_action( 'yith_wcwl_table_product_after_add_to_cart', $item, $wishlist ); ?>
							</div>

						</div>

						<?php if ( $show_price || $show_price_variations ) : ?>
							<div class="itemPrice">
								<?php do_action( 'yith_wcwl_table_before_product_price', $item, $wishlist ); ?>

								<?php
								if ( $show_price ) {
									echo $item->get_formatted_product_price(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}

								if ( $show_price_variations ) {
									echo $item->get_price_variation(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>

								<?php do_action( 'yith_wcwl_table_after_product_price', $item, $wishlist ); ?>
							</div>
						<?php endif ?>

					</div>

				</div>
			<?php
			endif;
		endforeach;
	else :
	?>
		<div class="wishlist-empty"><?php echo esc_html( apply_filters( 'yith_wcwl_no_product_to_remove_message', __( 'No products added to the wishlist', 'yith-woocommerce-wishlist' ), $wishlist ) ); ?></div>
	<?php
	endif;

	if ( ! empty( $page_links ) ) :
	?>
		<div class="pagination-row wishlist-pagination">
			<?php echo $page_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif ?>
	</div>

</div>
