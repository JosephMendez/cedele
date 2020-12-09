<?php
/**
 * Shipping distance admin
 *
 * @package WooCommerce/Admin/Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2>
	<?php esc_html_e( 'Distance', 'woocommerce' ); ?>
	<?php echo wc_help_tip( __( 'Manage cost shipping cost according to Distance.', 'woocommerce' ) ); // @codingStandardsIgnoreLine ?>
</h2>

<style>
    .wc-shipping-distance input[type="number"] {
        width: 120px;
        padding-left: 5px;
        margin-left: 0px;
    }
    .wc-shipping-distance tbody tr .distance-label {
        display: block;
    }
    .wc-shipping-distance tbody tr .distance-input-edit {
        display: none;
    }
    .wc-shipping-distance tbody tr.editing .distance-label {
        display: none;
    }
    .wc-shipping-distance tbody tr.editing .distance-input-edit {
        display: block;
    }
    .wc-shipping-distance .button-cancel-changes {
        cursor: pointer;
    }
    .wc-shipping-distance .wc-shipping-class-delete {
        color: #b10000 !important;
    }
    .wc-shipping-distance .wc-shipping-class-delete:hover {
        color: red !important;
    }
    .wc-shipping-distance .button-cancel-changes {
        opacity: 0;
    }
    .wc-shipping-distance tr:hover .button-cancel-changes {
        opacity: 1;
    }
</style>

<table class="wc-shipping-classes wc-shipping-distance widefat">
    <thead>
        <tr>
            <td width="40%">Distance (km)</td>
            <td width="40%">Cost ($)</td>
            <td width="20%"></td>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <button type="submit" name="save" class="button button-primary wc-shipping-distance-save" value="<?php esc_attr_e( 'Save shipping classes', 'woocommerce' ); ?>" disabled><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
                <button class="button button-secondary wc-shipping-distance-add"><?php esc_html_e( 'Add more', 'woocommerce' ); ?></button>
            </td>
        </tr>
    </tfoot>
</table>
