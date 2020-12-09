jQuery(document).ready(function($) {
	$('body').on('change', '.cdls-form-highlight select', function(event) {
		var value = jQuery(this).val();
		var tr = jQuery(this).closest('tr');

		jQuery(tr).siblings().each(function(index, el) {
			if (value == jQuery(this).find('select').val())
				jQuery(this).find('select').val('')
		});
	});

	function check_exist_feature(feature) {
		var existed = false
		jQuery('.cdls-form-highlight select').each(function(index, el) {
			var value = jQuery(this).val();
			var id = jQuery(this).closest('tr').data('id');

			if (value == '1' || value == '2') {
				if (!(_.find(ajax_object_highlight.features, { 'meta_value': value, 'term_id': `${id}` }))) {
					existed = true;
				}
			} else {
				let term = _.find(ajax_object_highlight.features, {'term_id': `${id}`})
				if (!term)
					term = {}
				if (term.meta_value != value && !(!term.meta_value && !value)) {
					existed = true;
				}
			}
		});
		return existed
	}

	function check_changes() {
		var isChanged = false
		jQuery('.cdls-form-highlight select').each(function(index, el) {
			var value = jQuery(this).val();
			var id = jQuery(this).closest('tr').data('id');

			let term = _.find(ajax_object_highlight.features, {'term_id': `${id}`})
			if (!term)
				term = {}
			if (term.meta_value != value && !(!term.meta_value && !value)) {
				isChanged = true;
			}
		});

		jQuery('.cdls-form-highlight input[type="checkbox"]').each(function(index, el) {
			var checked = jQuery(this).is(':checked');
			var id = jQuery(this).closest('tr').data('id');

			let term = _.find(ajax_object_highlight.highlights, {'term_id': `${id}`})
			if (!term)
				term = {}
			if (!!term.meta_value !== !!checked) {
				isChanged = true;
			}
		});

		return isChanged;
	}

	jQuery('body').on('click', '.cdls-form-highlight .cdls-button-submit-highlight', function(event) {
		if (check_exist_feature()) {
			var isCanChange = confirm("You are going to hide 'Old Category' and display 'New Category' on Home screen. Do you still want to make change?")

			if (!isCanChange) {
				return false
			}
		}

		jQuery(this).closest('form').submit();
	});

	jQuery('body').on('click', '.cdls-nagination a', function(event) {
		event.preventDefault();
		var href = jQuery(this).attr('href')
		if (check_exist_feature()) {
			var result = confirm("You are going to hide 'Old Category' and display 'New Category' on Home screen. Do you still want to make change?");

			if (result) {
				jQuery(this).closest('form').append(`<input type="hidden" name="cdls_save_and_redirect" value="${href}">`)
				jQuery(this).closest('form').submit();
				return;
			} else {
				return false;
			}
		}
			
		if (check_changes()) {
			var result = confirm("Save your changed?");

			if (result) {
				jQuery(this).closest('form').append(`<input type="hidden" name="cdls_save_and_redirect" value="${href}">`)
				jQuery(this).closest('form').submit();
			} else {
				return false;
			}
		} else {
			window.location.href = href;
		}
	});
});