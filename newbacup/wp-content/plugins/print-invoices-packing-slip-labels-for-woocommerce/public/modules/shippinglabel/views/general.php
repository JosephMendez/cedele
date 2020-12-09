<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<h3 style="margin-bottom:0px; padding-bottom:5px; border-bottom:dashed 1px #ccc;"><?php _e('Shipping label', 'print-invoices-packing-slip-labels-for-woocommerce'); ?></h3>
<table class="form-table wf-form-table">
	<?php
	Wf_Woocommerce_Packing_List_Admin::generate_form_field(array(
		array(
			'type'=>"select",
			'label'=>__("Shipping label size",'print-invoices-packing-slip-labels-for-woocommerce'),
			'option_name'=>$this->module_id."[woocommerce_wf_packinglist_label_size]",
			'select_fields'=>array(
				2=>__('Full Page','print-invoices-packing-slip-labels-for-woocommerce'),
			)
		),
        array(
            'type'=>"radio",
            'label'=>__("Add footer",'print-invoices-packing-slip-labels-for-woocommerce'),
            'option_name'=>"woocommerce_wf_packinglist_footer_sl",
            'field_name'=>$this->module_id."[woocommerce_wf_packinglist_footer_sl]",
            'radio_fields'=>array(
                'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
                'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
            ),
            'help_text'=>__("Add footer in shipping label",'print-invoices-packing-slip-labels-for-woocommerce'),
        ),
	),$this->module_id);
	?>
</table>