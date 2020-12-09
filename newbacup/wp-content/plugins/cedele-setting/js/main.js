jQuery(document).ready(function($) {
	// setup time picker
	init_time_picker()
	function init_time_picker(argument) {
		jQuery('.timepicker').datetimepicker({
			datepicker: false,
			format: 'H:i',
			formatTime: 'H:i',
			step: 30
		})
	}

	jQuery('#_stores').on('change', function() {
		const val = jQuery(this).val();

		jQuery('#_self-collection-content-search-input').attr('value', '');

		// val = id store
		jQuery.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			dataType: 'json',
			data: {
				action: 'get_list',
				store_id: val
			},
			success: function (result) {
				const products = result.data;
				let newContent = '';

				if (products && products.length) {
					for (let i = 0; i < products.length; i++) {
						newContent += '<tr id="product-' + products[i].id + '">';
						newContent += '<td>' + products[i].id + '</td>';
						newContent += '<td>' + products[i].post_title + '</td>';
						newContent += '<td><input data-id="'+products[i].id+'" id="check_box-'+ products[i].id +'" type="checkbox"/></td>'
						newContent += '</tr>';
					}
				}

				jQuery('#self-collection-content-list-product tbody').html(newContent);

				if (products && products.length) {
					for (let i = 0; i < products.length; i++) {
						if (products[i].is_in_stock == 1) {
							jQuery('#check_box-'+ products[i].id +'').attr('checked', true);
						}
					}
				}
			}
		});
	})

	jQuery('#_self-collection-content-search-input').on('keyup', function () {
		if (jQuery('#_stores').val() !== '') {
			jQuery.ajax({
				type: 'GET',
				url: ajax_object.ajax_url,
				data: {
					action: 'search_product',
					q: jQuery(this).val().trim(),
					store_id: jQuery('#_stores').val()
				},
				dataType: 'json',
				success: function (result) {
					const products = result.data;
					let newContent = '';
	
					if (products && products.length) {
						for (let i = 0; i < products.length; i++) {
							newContent += '<tr id="product-' + products[i].id + '">';
							newContent += '<td>' + products[i].id + '</td>';
							newContent += '<td>' + products[i].post_title + '</td>';
							newContent += '<td><input data-id="'+products[i].id+'" id="check_box-'+ products[i].id +'" type="checkbox"/></td>'
							newContent += '</tr>';
						}
					}
	
					jQuery('#self-collection-content-list-product tbody').html(newContent);

					for (let i = 0; i < products.length; i++) {
						if (products[i].is_in_stock == 1) {
							jQuery('#check_box-'+ products[i].id +'').attr('checked', true);
						}
					}
				}
			})
		}
	})


	jQuery('body').on('change', '[id^=check_box-]', function(event) {
		console.log(event.target.checked, event.target.attributes['data-id'].value)
		jQuery.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			dataType: 'json',
			data: {
				is_in_stock: event.target.checked ? 1 : 0,
				product_id: event.target.attributes['data-id'].value,
				store_id: jQuery('#_stores').val(),
				action: 'toggle_stock',
			},
			success: function(result) {
				
			}
		})
	})
});