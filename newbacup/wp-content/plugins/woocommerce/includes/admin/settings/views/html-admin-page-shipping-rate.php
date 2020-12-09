<?php
/**
 * Shipping time admin
 *
 * @package WooCommerce/Admin/Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2>
	<?php esc_html_e( 'Shipping rate', 'woocommerce' ); ?>
	<?php echo wc_help_tip( __( 'Manage Shipping Rate Cost.', 'woocommerce' ) ); // @codingStandardsIgnoreLine ?>
</h2>

<style>
	.wc-shipping-rate input[type="number"] {
		width: 120px;
	}
</style>

<table class="wc-shipping-classes wc-shipping-rate widefat">
	<tbody>
        <tr>
            <td colspan="2"><b>Free Shipping Condition:</b></td>
        </tr>
        <tr>
            <td width="15%">Minimum order amount:</td>
            <td>
                <input type="number" id="wc_mini_amount" name="wc_mini_amount" min="0" step="0.01" placeholder="amount" required> $
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Flat Rate:</b></td>
        </tr>
        <tr>
            <td>Orders amount below:</td>
            <td>
                <input type="number" id="wc_order_amount_below" name="wc_order_amount_below" min="0" step="0.01" placeholder="amount" required> $
                <span>
                    apply the fixed shipping rate of
                    <input type="number" id="wc_apply_shipping_rate" name="wc_apply_shipping_rate" min="0" step="0.01" placeholder="amount" required> $
                    and additional fee
                </span>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
				<button type="submit" name="save" class="button button-primary wc-shipping-rate-save" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" disabled><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
            </td>
        </tr>
    </tfoot>
</table>