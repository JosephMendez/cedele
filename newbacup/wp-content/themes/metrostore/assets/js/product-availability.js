jQuery(document).ready(function($) {
	init_time_picker()
	function init_time_picker(argument) {
		jQuery('.product-availability-timedatepicker').datetimepicker({
			datepicker: false,
			format: 'H:i',
			formatTime: 'H:i',
			step: 30
		})
	}
	// radio
	toggle_product_store()
	function toggle_product_store() {
		let checked = jQuery('.wp-product-delivery-method input[value="delivery"]').is(':checked');

		if (checked) {
			jQuery('.wp-product-store-location').hide();
		} else {
			jQuery('.wp-product-store-location').show();
		}
	}
	if (check_checkbox()) {
		jQuery('.wp-product-checkbox-all').prop('checked', true)
	}
	jQuery('body').on('click', '.wp-product-delivery-method input[type="radio"]', function(e) {
		toggle_product_store();
	})

	// checkbox
	jQuery('body').on('click', '.wp-product-checkbox-all', function(e) {
		let checked = jQuery(this).is(':checked');
		jQuery('.wp-product-checkbox').prop('checked', checked)
	})

	jQuery('body').on('click', '.wp-product-checkbox', function(e) {
		let checked = jQuery(this).is(':checked');

		if (checked && check_checkbox()) {
			jQuery('.wp-product-checkbox-all').prop('checked', true)
		} else if (!checked) {
			jQuery('.wp-product-checkbox-all').prop('checked', false)
		}
	})

	function check_checkbox() {
		var flag = true;
		jQuery('.wp-product-checkbox').each(function(index, el) {
			let checked = jQuery(this).is(':checked');

			if (!checked) {
				flag = false
			}
		});

		return flag;
	}

	// product lead time
	get_product_lead_time()
	function get_product_lead_time() {
		jQuery('.wp-product-lead-time input[type="radio"]').each(function(index, el) {
			var checked = jQuery(this).is(':checked');
			if (checked) {
				jQuery(this).siblings().show();
				jQuery(this).siblings().find('input[type="number"]').prop('required', true);
			}
		});
	}

	jQuery('body').on('click', '.wp-product-lead-time input[type="radio"]', function(event) {
		jQuery('.wp-product-lead-time span').hide()
		jQuery('.wp-product-lead-time input[type="number"]').prop('required', false);
		jQuery(this).siblings().show()
		jQuery(this).siblings().find('input[type="number"]').prop('required', true);
	});
});