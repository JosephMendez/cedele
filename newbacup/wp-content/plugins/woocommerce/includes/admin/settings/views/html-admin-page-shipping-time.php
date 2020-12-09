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
	<?php esc_html_e( 'Shipping time', 'woocommerce' ); ?>
	<?php echo wc_help_tip( __( 'Manage Shipping time Cost.', 'woocommerce' ) ); // @codingStandardsIgnoreLine ?>
</h2>

<style>
	.wc-shipping-time input[type="number"] {
		width: 120px;
	}
</style>

<table class="wc-shipping-classes wc-shipping-time widefat">
	<tbody>
        <tr>
            <td colspan="2"><b>Shipping Hour:</b></td>
        </tr>
        <tr style="display: none">
            <td width="15%">Normal hours:</td>
            <td>
                <input type="number" id="shipping_cost_normal_hour" name="normal_hour_cost" min="0" value="0" placeholder="Normal hour" required> $
            </td>
        </tr>
        <tr>
            <td>Peak hours:</td>
            <td>
                <input type="number" id="shipping_cost_peak_hour" name="peak_hour_cost" min="0" placeholder="Peak hour" required> $
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Shipping Date:</b></td>
        </tr>
        <tr style="display: none">
            <td>Working days:</td>
            <td>
                <input type="number" id="shipping_cost_working_day" name="working_day_cost" min="0" value="0" placeholder="Working day" required> $
            </td>
        </tr>
        <tr>
            <td>Weekends:</td>
            <td>
                <input type="number" id="shipping_cost_weekend" name="weekend_cost" min="0" placeholder="Weekends" required> $
            </td>
        </tr>
        <tr>
            <td>Occasions:</td>
            <td>
                <input type="number" id="shipping_cost_occasions" name="occasion_cost" min="0" placeholder="Occasions" required> $
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
				<button type="submit" name="save" class="button button-primary wc-shipping-time-save" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" disabled><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
            </td>
        </tr>
    </tfoot>
</table>