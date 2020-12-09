<?php

function cdls_rider_management()
{
	$section = $_GET['section'];

	switch ($section) {
		case 'shipping-partner':
			cdls_form_shipping_partner();
			break;
		case 'manage-rider':
			cdls_form_manager_rider();
			break;
		default:
			cdls_form_manager_rider();
			break;
	}
}
?>