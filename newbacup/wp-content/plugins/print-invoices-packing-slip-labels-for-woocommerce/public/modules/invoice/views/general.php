<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wf_sub_tab_content" data-id="general" style="display:block;">
<p><?php _e('Configure the invoice settings from the options below.','print-invoices-packing-slip-labels-for-woocommerce');?></p>
<form method="post" class="wf_settings_form">
    <input type="hidden" value="invoice" class="wf_settings_base" />
    <input type="hidden" value="wf_save_settings" class="wf_settings_action" />
    <?php
    // Set nonce:
    if (function_exists('wp_nonce_field'))
    {
        wp_nonce_field(WF_PKLIST_PLUGIN_NAME);
    }
    ?>
	<table class="form-table wf-form-table">
	    <?php
	    Wf_Woocommerce_Packing_List_Admin::generate_form_field(array(
	        array(
	            'type'=>"radio",
	            'label'=>__("Enable invoice",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_enable_invoice",
	            'radio_fields'=>array(
	                'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
	                'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
	            ),
	        ),
	        array(
				'type'=>"radio",
				'label'=>__("Use order date as invoice date",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_orderdate_as_invoicedate",
				'help_text'=>__("If you choose 'No' then the invoice date will be the date on which it is generated.",'print-invoices-packing-slip-labels-for-woocommerce'),
				'radio_fields'=>array(
					'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
					'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
				),
			),
	        array(
	            'type'=>'order_st_multiselect',
	            'label'=>__("Generate invoice for order statuses",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_generate_for_orderstatus",
	            'help_text'=>__("Order statuses for which an invoice should be generated.",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'order_statuses'=>$order_statuses,
	            'field_vl'=>array_flip($order_statuses),
	            'attr'=>'',
	        ),
	        array(
	            'type'=>"radio",
	            'label'=>__("Attach invoice PDF in order email",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_add_invoice_in_mail",
	            'radio_fields'=>array(
	                'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
	                'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
	            ),
	            'help_text'=>__('PDF version of invoice will be attached with the order email based on the above statuses','print-invoices-packing-slip-labels-for-woocommerce'),
	        ),
	    ),$module_id);
	    ?>
	    <?php 
	    Wf_Woocommerce_Packing_List_Admin::generate_form_field(array(
	        array(
	            'type'=>"radio",
	            'label'=>__("Enable print invoice option for customers",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_packinglist_frontend_info",
	            'radio_fields'=>array(
	                'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
	                'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
	            ),
	            'help_text'=>__("Add print button to the order email/order summary",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'form_toggler'=>array(
					'type'=>'parent',
					'target'=>'wf_enable_print_button',
				)
	        ),
	        array(
	            'type'=>'order_st_multiselect',
	            'label'=>__("Show print button only for statuses",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_attach_invoice",
	            'order_statuses'=>$order_statuses,
	            'field_vl'=>$wf_generate_invoice_for,
	            'form_toggler'=>array(
					'type'=>'child',
					'id'=>'wf_enable_print_button',
					'val'=>'Yes',
				)
	        ),
	        array(
	            'type'=>"radio",
	            'label'=>__("Add customer note",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_add_customer_note_in_invoice",
	            'radio_fields'=>array(
	                'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
	                'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
	            ),
	            'help_text'=>__("Add customer note in invoice",'print-invoices-packing-slip-labels-for-woocommerce'),
	        ),
	        array(
	            'type'=>"uploader",
	            'label'=>__("Custom logo for invoice",'print-invoices-packing-slip-labels-for-woocommerce'),
	            'option_name'=>"woocommerce_wf_packinglist_logo",
	            'help_text'=>__('If left blank, defaulted to logo from General settings.','print-invoices-packing-slip-labels-for-woocommerce'),
	        ),
	    ),$module_id);
	    ?>
	</table>
	<?php
    include plugin_dir_path( WF_PKLIST_PLUGIN_FILENAME )."admin/views/admin-settings-save-button.php";
    ?>
</form> 
</div>    