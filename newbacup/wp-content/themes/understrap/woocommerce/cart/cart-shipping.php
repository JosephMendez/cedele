<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
$customerAddress = json_decode(stripslashes($_COOKIE['customerAddress']));
if ($customerAddress && property_exists($customerAddress, 'deliveryAddress')){
  $deliveryAddress = json_decode(stripslashes($customerAddress->deliveryAddress));
}
if ($customerAddress->deliveryType == 'delivery' && isset($deliveryAddress)){
  $destination_html = $deliveryAddress->formatted_address.'<br/>'.$customerAddress->deliveryAddress2.'<br/>('.$customerAddress->date.' - '.$customerAddress->time.')';
} else if ($customerAddress->deliveryType == 'self-collection'){
  $destination_html = $customerAddress->pickupStoreAddress.'<br/>('.$customerAddress->date.' - '.$customerAddress->time.')';
} else {
  $destination_html = 'Select shipping address to continue';
}
$noZipCode = $customerAddress->deliveryType == 'delivery' && !$deliveryAddress->zipcode;
$wc_mini_amount = get_option('wc_mini_amount', 0);
$subtotal = WC()->cart->cart_contents_total;
$amount_add_to_free = $wc_mini_amount - $subtotal;
$deliverable = WC()->session->get('deliverable');
$wc_order_amount_below = get_option('wc_order_amount_below', 0);
$occasions_wc_order_amount_below = get_option('occasions_wc_order_amount_below', 0);
?>
<?php if ( is_cart() ) { ?>
  <tr class="woocommerce-shipping-totals shipping">
    <th colspan="2" style="padding-right: 28px; padding-bottom: 5px;">
		<span>Shipping & Handling</span>
		<span class="btn btn-header change-address btn-shadow btn-primary heading-font" style="float: right;">Edit</span>
	</th>
  </tr>
  <tr>
    <td colspan="2" data-title="<?php echo esc_attr( $package_name ); ?>">
      <?php if ( $available_methods ) : ?>
        <ul id="shipping_method" class="woocommerce-shipping-methods">
          <?php foreach ( $available_methods as $method ) : ?>
            <li>
              <?php
              if ( 1 < count( $available_methods ) ) {
                printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
              } else {
                printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
              }

			  printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) ); // WPCS: XSS ok.

              do_action( 'woocommerce_after_shipping_rate', $method, $index );
              if ($customerAddress->deliveryType == 'delivery' && $method->cost == 0){
                echo '<p class="woocommerce-Price-amount amount woocommerce-Price-amount-front">FREE</p>';
              }
              if ($customerAddress->deliveryType == 'self-collection'){
                echo '<p class="free-fee">FREE</p>';
              }
              ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php if ( is_cart() ) : ?>
          <p class="woocommerce-shipping-destination">
            <?php
            /*
            if ( $formatted_destination ) {
              // Translators: $s shipping destination.
              printf( esc_html__( 'Shipping to %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
              $calculator_text = esc_html__( 'Change address', 'woocommerce' );
            } else {
              echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
            }
            */?>
            <?php echo $destination_html; ?>
<!--            <a href="#" class="change-address">--><?php //echo __('Change', 'understrap');?><!--</a>-->
          </p>
          <?php
            if ($amount_add_to_free > 0 && $customerAddress->deliveryType == 'delivery' ) {
              echo '<p class="promotion-text mb-1 pt-1">Spend $'.$amount_add_to_free .' more for free delivery</p>';
            }
            if ( $noZipCode ) {
              echo '<p class="text-danger mb-3">Please provide more detail of delivery address!</p>';
            }
            if ($customerAddress->deliveryType == 'delivery' && !$deliverable) {
              echo '<p class="text-danger mb-3 cart-undeliverable">Minimum order for delivery: $'.$wc_order_amount_below.'.<br/> Minimum order for delivery on special occasions: $'.$occasions_wc_order_amount_below.'.</p>';
            }
          ?>
        <?php endif; ?>
        <?php
      elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
        if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
          echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
        } else {
          echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
        }
      elseif ( ! is_cart() ) :
        echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
      else :
        // Translators: $s shipping destination.
        echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
        $calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
      endif;
      ?>

      <?php if ( $show_package_details ) : ?>
        <?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
      <?php endif; ?>

      <?php if ( $show_shipping_calculator ) : ?>
        <?php woocommerce_shipping_calculator( $calculator_text ); ?>
      <?php endif; ?>
    </td>
  </tr>
<?php } else { ?>
  <?php if ( $available_methods ) { ?>

    <tr id="shipping_method" class="woocommerce-shipping-methods">
      <?php foreach ( $available_methods as $method ) : ?>
        <td colspan="2" data-title="<?php echo esc_attr( $package_name ); ?>">
          <?php
          if ( 1 < count( $available_methods ) ) {
            printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
          } else {
            printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
          }
          printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), 'Delivery Fee' ); // WPCS: XSS ok.
          do_action( 'woocommerce_after_shipping_rate', $method, $index );
          ?>
        </td>
        <td>
          <?php
            echo '$'.$method->get_cost();
          ?>
        </td>
      <?php endforeach; ?>
    </tr>

  <?php } else { ?>
    <tr>
      <td>
      <?php if ( ! $has_calculated_shipping || ! $formatted_destination ) :
        if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
          echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
        } else {
          echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
        }
      elseif ( ! is_cart() ) :
        echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
      else :
        // Translators: $s shipping destination.
        echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
        $calculator_text = esc_html__( 'Enter a different address', 'woocommerce' ); ?>
      </td>
    </tr>
  <?php endif; } ?>
<?php } ?>

