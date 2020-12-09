jQuery(document).ready(function($) {
	jQuery(".datepicker").datetimepicker({
		timepicker: false,
		format: 'm/d/Y',
		formatDate: 'Y/m/d'
	});

	function check_checkbox() {
		var flag = true;
		jQuery('.holiday-checkbox').each(function(index, el) {
			let checked = jQuery(this).is(':checked');

			if (!checked) {
				flag = false
			}
		});

		return flag;
	}

	if (check_checkbox()) {
		jQuery('.holiday-checkbox-all').prop('checked', true)
	}

	jQuery('body').on('click', '.holiday-checkbox-all', function(e) {
		let checked = jQuery(this).is(':checked');
		jQuery('.holiday-checkbox').prop('checked', checked)
	})

	jQuery('body').on('click', '.holiday-checkbox', function(e) {
		jQuery('.holiday-checkbox-all').prop('checked', false)
	})

	// holiday
	jQuery('#holidays-table').on('click', 'input[type="submit"]', function(event) {
		event.preventDefault();
		var value = jQuery('#bulk-action-selector-top').val()

		if (value === 'delete') {
			var result = confirm('Do you really want to delete the holiday?');
			
			if (result) {
				jQuery(this).parents('#holidays-table').submit();
			}
		}
	});
});