jQuery(document).ready(function($) {
	init_popup_shipping_partner();
	function init_popup_shipping_partner(argument) {
		jQuery('body').append(`<div class="cdls-faded cdls-faded-partner">
			<div class="cdls-modal">
				<div class="cdls-modal-header">
					<h4>New Partner</h4>
					<span class="cdls-modal-close">
						<svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
							<path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
						</svg>
					</span>
				</div>
				<div class="cdls-modal-content">
					<table cellpadding="8">
						<tr>
							<td width="35%">Partner Name: *</td>
							<td><input type="text"  name="partner_name" placeholder="partner name" value=""/></td>
						</tr>
						<tr>
							<td>Short Name: *</td>
							<td><input type="text" name="short_name" placeholder="short name" value=""/></td>
						</tr>
						<tr>
							<td>Contact Number: *</td>
							<td><input type="text" name="contact_number" placeholder="contact number" value=""/></td>
						</tr>
						<tr>
							<td>Active/In-active:</td>
							<td><input type="checkbox" name="status" checked/> Active</td>
						</tr>
					</table>
				</div>
				<div class="cdls-modal-footer">
					<button type="button" class="button-primary button-submit-modal-partner">Save changes</button>
				</div>
			</div>
		</div>`)
	}
	function open_modal_partner() {
		jQuery('body .cdls-faded-partner').addClass('show');
		// jQuery('body').addClass('cdls-overflow-hidden')
	}
	function close_modal_partner() {
		jQuery('body .cdls-faded-partner').removeClass('show');
		// jQuery('body').removeClass('cdls-overflow-hidden')
	}
	function disabled_submit() {
		jQuery('.cdls-faded .button-submit-modal-partner').prop('disabled', true);
	}
	function enable_submit() {
		jQuery('.cdls-faded .button-submit-modal-partner').prop('disabled', false);
	}
	function render_list_partner() {
		let newContent = '';
		if (Array.isArray(cdls_list_partners)) {
			cdls_list_partners.forEach(item => {
				newContent += `<tr data-id="${item.id}">
	                <td>
	                    ${esc_html(item.partner_name)}
	                </td>
	                <td>
	                    ${esc_html(item.short_name)}
	                </td>
	                <td>
	                    ${esc_html(item.contact_number)}
	                </td>
	                <td>
	                    ${item.status == '1' ? 'Active' : 'In-active'}
	                </td>
	                <td>
	                    ${esc_html(item.rider_name_concat)}
	                </td>
	                <td>
	                    <span class="pointer cdls-noselect cdls-edit-partner">
	                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
	                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
	                        </svg>
	                    </span>
	                </td>
	            </tr>`
			})
		}

		jQuery('.cdls-table-partner tbody').html(newContent);
	}
	function esc_html(rawStr) {
		if (!rawStr) return '';
		return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
		   return '&#'+i.charCodeAt(0)+';';
		});
	}
	$(window).click(function() {
		close_modal_partner();
	});
	$('body').on('click', '.cdls-modal', function(event) {
	    event.stopPropagation();
	});
	$('body').on('click', '.cdls-modal-close', function(event) {
		close_modal_partner();
	});

	jQuery('body').on('click', '.button-new-partner', function(event) {
		event.preventDefault();
	    event.stopPropagation();

		enable_submit();
		clear_value_partner();
		open_modal_partner();
	});
	jQuery('body').on('click', '.button-submit-modal-partner', function(event) {
		send_ajax();
	});
	jQuery('body').on('input', '.cdls-modal input[type="text"]', function(event) {
		let name = jQuery(this).attr('name');
		let value = jQuery(this).val();
		object_partner[name] = value
	});
	jQuery('body').on('input', '.cdls-modal input[type="checkbox"]', function(event) {
		let name = jQuery(this).attr('name');
		let checked = jQuery(this).is(':checked');
		object_partner.status = checked ? '1' : 0
	});
	jQuery('body').on('click', '.cdls-edit-partner', function(event) {
		event.preventDefault();
	    event.stopPropagation();

		enable_submit()
		var id = jQuery(this).closest('tr').data('id');
		clear_value_partner();
		open_modal_partner();
		object_partner = _.find(cdls_list_partners, ['id', `${id}`])
		set_value_partner();
	});

	let object_partner = {
		partner_id: 0,
		partner_name: '',
		short_name: '',
		contact_number: '',
		status: '1',
	}
	let default_partner_object = {
		partner_id: 0,
		partner_name: '',
		short_name: '',
		contact_number: '',
		status: '1',
	}
	let cdls_list_partners = [];
	function set_value_partner() {
		jQuery('.cdls-modal input[name="partner_name"]').val(object_partner.partner_name)
		jQuery('.cdls-modal input[name="short_name"]').val(object_partner.short_name)
		jQuery('.cdls-modal input[name="contact_number"]').val(object_partner.contact_number)
		jQuery('.cdls-modal input[name="status"]').prop('checked', object_partner.status == '1' ? true : false)
	}
	function clear_value_partner() {
		jQuery('.cdls-modal input[name="partner_name"]').val('')
		jQuery('.cdls-modal input[name="short_name"]').val('')
		jQuery('.cdls-modal input[name="contact_number"]').val('')
		jQuery('.cdls-modal input[name="status"]').prop('checked', true)

		object_partner = Object.assign({}, default_partner_object)
	}

	function validate_value() {
		var messages = [];
		if (object_partner.partner_name == '') {
			messages.push('All fields are required!');
		}
		if (object_partner.short_name == '') {
			messages.push('All fields are required!');
		}
		if (object_partner.contact_number == '') {
			messages.push('All fields are required!');
		}
		var pattern = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]{6,}$/g
		if (!pattern.test(object_partner.contact_number)) {
			messages.push('Contact number is not valid!');
		}

		return messages;
	}

	let isSend = false
	function send_ajax() {
		let messages = validate_value();
		if (messages.length > 0) {
			alert(messages[0]);
			return
		}
		var dataPost = {
			action: 'cdls_shipping_rider',
			...object_partner
		};

		jQuery.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			dataType: 'json',
			data: dataPost,
			beforeSend: function() {
				disabled_submit();
			},
			success: function (result) {
				cdls_list_partners = result.data;
				render_list_partner()
			},
			complete: function () {
				close_modal_partner();
				enable_submit();
			}
		});
	}

	if (window.location.search.includes("page=manage-driver"))
		send_ajax_get_list();
	function send_ajax_get_list() {
		var dataPost = {
			action: 'cdls_shipping_rider',
			get_list: 1
		};

		jQuery.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			dataType: 'json',
			data: dataPost,
			success: function (result) {
				cdls_list_partners = result.data;
				render_list_partner()
			}
		});
	}
});