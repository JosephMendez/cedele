<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
$payment_type = $order->get_payment_method();
$order_status = $order->get_status();
?>

<div class="woocommerce-order">

  <?php
  if ( $order ) :
    do_action( 'woocommerce_before_thankyou', $order->get_id() );
    // Change status with user role 'Store Locator Manager'
      if(Store_Locator_Manager()) {
        $order->update_status( 'processing' );
    }
  ?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>

      <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

      <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
        <?php if ( is_user_logged_in() ) : ?>
          <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
        <?php endif; ?>
      </p>

    <?php else : ?>

      <?php if ($payment_type == 'omise_paynow' && $order_status == 'pending') : ?>
        <h4 class="text-center cdl-heading woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><i class="icon-check"></i><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h4>

        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

          <li class="woocommerce-order-overview__order order">
            <?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
            <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
          </li>

          <li class="woocommerce-order-overview__date date">
            <?php esc_html_e( 'Date:', 'woocommerce' ); ?>
            <strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
          </li>

          <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
            <li class="woocommerce-order-overview__email email">
              <?php esc_html_e( 'Email:', 'woocommerce' ); ?>
              <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
            </li>
          <?php endif; ?>

          <li class="woocommerce-order-overview__total total">
            <?php esc_html_e( 'Total:', 'woocommerce' ); ?>
            <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
          </li>

          <?php if ( $order->get_payment_method_title() ) : ?>
            <li class="woocommerce-order-overview__payment-method method">
              <?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
              <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
            </li>
          <?php endif; ?>

        </ul>
      <?php else : ?>
        <div class="thankyou-box">
          <h3 class="cdl-heading">
            <img alt="OK" src="<?php echo get_stylesheet_directory_uri().'/assets/symbol/Circle-check.svg'; ?>"/>
            <?php echo __('Thank you for your purchase', 'understrap');?></h3>
          <h4 class="order-id heading-font mb-2">Your Order ID: <?php echo $order->get_id(); ?></h4>
          <p class="mb-2">An order confirmation email has been sent to <a href = "mailto: <?php echo $order->get_billing_email(); ?>"><?php echo $order->get_billing_email(); ?></a></p>
			<!--          <p class="mb-2">Please check your email for your order detail</p>-->
          <a class="home-link mt-4 btn btn-lg btn-outline-primary heading-font text-uppercase btn-shadow" href="<?php echo home_url();?>" title="Home">Back to home</a>
        </div>
      <?php endif; ?>

    <?php endif; ?>

    <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>

  <?php else : ?>

    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

  <?php endif; ?>

</div>

<script type="text/javascript">
  var orderStatus = "<?php echo $order_status; ?>";
  function getOrderStatus(){
    jQuery.ajax({
      type: 'POST',
      url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
      dataType: 'json',
      data: {
        'action': 'fetch_order_status',
        'order_id': '<?php echo $order->get_id(); ?>'
      },
      success: function( response ) {
        if (response.data && response.data.order_status !== 'pending'){
          window.location.reload();
        }
      },
      error: function( response ) { console.log(response); },
    });
  }
  jQuery(document).ready(function(){
    if (orderStatus == 'pending'){
      setInterval(function(){
        getOrderStatus();
      }, 5000);
      jQuery('.omise-paynow-details').show();
    }
    localStorage.removeItem('customerAddress');
  });
</script>
