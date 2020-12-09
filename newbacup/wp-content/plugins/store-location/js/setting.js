jQuery(document).ready(function($) {
	var listArea = [];
	var listDistrict = [];
	var listOutlet = [];
	var pattern = "[\\p{L}0-9\\s.,()°-]+";

    //======================================================
    // add more input
	function more_input(that) {
		var formSetting = jQuery(that).parents('.form-setting');
		var listData = jQuery(formSetting).find('.list-data');
		var type = jQuery(formSetting).data('type');

		jQuery(listData).append(`
			<div class="form-row">
                <input class="input-row" name="data_name[]" type="text" pattern="${pattern}" value="" placeholder="${type}" required/>
                <input name="data_id[]" type="hidden" value="0">
                <span class="button-action button-remove">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </span>
            </div>
		`);

		var input = jQuery(formSetting).find('input[type="text"]');
		jQuery(input).focus();
	}

	// add more input
	function more_input_outlet(that) {
		var formSetting = jQuery(that).parents('.form-setting');
		var listData = jQuery(formSetting).find('.list-data');
		var type = jQuery(formSetting).data('type');

		jQuery(listData).append(`
			<div class="form-row">
				<div class="div-outlet">
                	<input class="input-row txt-data-name" name="data_name[]" type="text" pattern="${pattern}" value="" placeholder="${type}" required/>
                	<textarea class="data-description" placeholder="description" name="data_description[]"></textarea>
                </div>
                <input name="data_id[]" type="hidden" value="0">
                <span class="button-action button-remove">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </span>
            </div>
		`);

		var input = jQuery(formSetting).find('input[type="text"]');
		jQuery(input).focus();
	}

	function check_exist_val(that, inputVal) {
		var listVal = []
		jQuery(that).parents('.form-content').find('.input-row').each(function(index, el) {
			let val = jQuery(this).val()
			listVal.push(val)
		});
		listVal = listVal.filter(item => item.toLowerCase() === inputVal.toLowerCase())

		return listVal.length
	}

	function check_duplicate(that) {
		var listVal = []
		jQuery(that).parents('.form-setting').find('.input-row').each(function(index, el) {
			let val = jQuery(this).val()
			listVal.push(val.toLowerCase().trim())
		});

		return (new Set(listVal)).size !== listVal.length
	}

	async function check_exist_ajax(delete_id, type) {
		var flag = false
		await jQuery.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            dataType: 'json',
            data: {
            	id: delete_id,
            	type: type,
            	action: "check_data_exist"
            },
            success: function(result){
            	if (result.status == 1) {
            		flag = true
            	}
            }
        });

    	return flag
	}

	jQuery('body').on('click', '.button-remove', async function(event) {
		var delete_id = jQuery(this).siblings('.input-id').val();
		var type = jQuery(this).parents('.form-setting').data('type');

		if (delete_id) {
			var is_exist = await check_exist_ajax(delete_id, type);
			if (!is_exist) {
				var input_delete_ids =jQuery(this).parents('.wpsl-form-setting').find('.delete_ids');
				var oldVal = jQuery(input_delete_ids).val();

				jQuery(input_delete_ids).val(`${oldVal},${delete_id}`);
				jQuery(this).parents('.form-row').remove();
			} else {
				alert('This item can not delete!');
			}
		} else {
			jQuery(this).parents('.form-row').remove();
		}
	});

	// max length
	jQuery('body').on('input', '.input-row', function(event) {
		var oldVal = jQuery(this).val()
		var newVal = oldVal.substring(0, 100);
		jQuery(this).val(newVal)

		// hide message
		var formSetting = jQuery(this).parents('.form-setting');
		jQuery(formSetting).find('.warning-alert').hide();
	});

	jQuery('body').on('click', '.button-add-more', function(event) {
		event.preventDefault();
		more_input(this);
	});
	jQuery('body').on('click', '.button-add-more-outlet', function(event) {
		event.preventDefault();
		more_input_outlet(this);
	});

	jQuery('body').on('click', '.button-submit', function(e) {
		var is_duplicate = check_duplicate(this);

		if (is_duplicate) {
			// hide message
			var formSetting = jQuery(this).parents('.form-setting');
			jQuery(formSetting).find('.warning-alert').show();
			return 
		}
		jQuery(this).closest('form').submit();
	});
});