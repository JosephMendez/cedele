<?php

function cdls_form_home_setting()
{
	$section = $_GET['section'];

	switch ($section) {
		case 'highlight':
			cdls_form_highlight_categories();
			break;
		case 'placeholder':
			cdls_form_placeholder();
			break;
		default:
			cdls_form_highlight_categories();
			break;
	}
}
?>