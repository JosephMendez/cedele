jQuery(document).ready(function($) {
	init_popup_shipping_rider();
	function init_popup_shipping_rider(argument) {
		jQuery('body').append(`<div class="cdls-faded cdls-faded-rider">
			<div class="cdls-modal">
				<div class="cdls-modal-header">
					<h4>New Rider</h4>
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
							<td width="35%">Rider name: *</td>
							<td><input type="text"  name="rider_name" placeholder="rider name" value=""/></td>
						</tr>
						<tr>
							<td>Contact Number: *</td>
							<td><input type="text" name="contact_number" placeholder="contact number" value=""/></td>
						</tr>
						<tr>
							<td>Shipping partner:</td>
							<td>
								<select name="partner_id" class="cdls-modal-shipping-partner"></select>
							</td>
						</tr>
						<tr>
							<td>Active/In-active:</td>
							<td><input type="checkbox" name="status" checked/> Active</td>
						</tr>
					</table>
				</div>
				<div class="cdls-modal-footer">
					<button type="button" class="button-primary button-submit-modal-rider">Save changes</button>
				</div>
			</div>
		</div>`)
	}
	function open_modal_rider() {
		jQuery('body .cdls-faded-rider').addClass('show');
		// jQuery('body').addClass('cdls-overflow-hidden')
	}
	function close_modal_rider() {
		jQuery('body .cdls-faded-rider').removeClass('show');
		// jQuery('body').removeClass('cdls-overflow-hidden')
	}
	function disabled_submit() {
		jQuery('.cdls-faded .button-submit-modal-rider').prop('disabled', true);
	}
	function enable_submit() {
		jQuery('.cdls-faded .button-submit-modal-rider').prop('disabled', false);
	}
	function render_list_rider() {
		let newContent = '';
		if (Array.isArray(cdls_list_riders)) {
			cdls_list_riders.forEach(item => {
				let partner = _.find(cdls_list_partners, ['id', `${item.partner_id}`]);
				let partner_name = partner ? partner.partner_name : '';
				newContent += `<tr data-id="${item.id}">
	                <td>
	                    ${esc_html(item.rider_name)}
	                </td>
	                <td>
	                    ${esc_html(item.contact_number)}
	                </td>
	                <td>
	                    ${esc_html(partner_name)}
	                </td>
	                <td>
	                   	${item.total_order ? item.total_order : 0}
	                </td>
	                <td>
	                    ${item.status == '1' ? 'Active' : 'In-active'}
	                </td>
	                <td>
	                    <span class="pointer cdls-noselect cdls-edit-rider">
	                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
	                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
	                        </svg>
	                    </span>
	                </td>
	            </tr>`
			})
		}

		jQuery('.cdls-table-rider tbody').html(newContent);
	}
	function esc_html(rawStr) {
		if (!rawStr) return '';
		return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
		   return '&#'+i.charCodeAt(0)+';';
		});
	}
	$(window).click(function() {
		close_modal_rider();
	});
	$('body').on('click', '.cdls-modal', function(event) {
	    event.stopPropagation();
	});
	$('body').on('click', '.cdls-modal-close', function(event) {
		close_modal_rider();
	});

	jQuery('body').on('click', '.button-new-rider', function(event) {
		event.preventDefault();
	    event.stopPropagation();

		enable_submit();
		clear_value_rider();
		open_modal_rider();
	});
	jQuery('body').on('click', '.button-submit-modal-rider', function(event) {
		send_ajax();
	});
	jQuery('body').on('input', '.cdls-modal input[type="text"]', function(event) {
		let name = jQuery(this).attr('name');
		let value = jQuery(this).val();
		object_rider[name] = value
	});
	jQuery('body').on('input', '.cdls-modal input[type="checkbox"]', function(event) {
		let name = jQuery(this).attr('name');
		let checked = jQuery(this).is(':checked');
		object_rider.status = checked ? '1' : 0
	});
	jQuery('body').on('change', '.cdls-modal select', function(event) {
		let name = jQuery(this).attr('name');
		let value = jQuery(this).val();
		object_rider[name] = value
	});
	jQuery('body').on('click', '.cdls-edit-rider', function(event) {
		event.preventDefault();
	    event.stopPropagation();

		enable_submit()
		var id = jQuery(this).closest('tr').data('id');
		clear_value_rider();
		open_modal_rider();
		object_rider = _.find(cdls_list_riders, ['id', `${id}`])
		set_value_rider();
	});

	let object_rider = {
		rider_id: 0,
		rider_name: '',
		partner_id: '',
		contact_number: '',
		status: '1',
	}
	let default_rider_object = {
		rider_id: 0,
		rider_name: '',
		partner_id: '',
		contact_number: '',
		status: '1',
	}
	let cdls_list_riders = [];
	let cdls_list_partners = ajax_object_rider.cdls_list_partners;
	function set_value_rider() {
		jQuery('.cdls-modal input[name="rider_name"]').val(object_rider.rider_name)
		jQuery('.cdls-modal input[name="contact_number"]').val(object_rider.contact_number)
		jQuery('.cdls-modal input[name="status"]').prop('checked', object_rider.status == '1' ? true : false)

		if (!!(_.find(cdls_list_partners, {id: object_rider.partner_id, status: '1'}))) {
			jQuery('.cdls-modal select[name="partner_id"]').val(object_rider.partner_id);
		}
	}
	function clear_value_rider() {
		jQuery('.cdls-modal input[name="rider_name"]').val('')
		jQuery('.cdls-modal input[name="contact_number"]').val('')
		jQuery('.cdls-modal select[name="partner_id"]').val('')
		jQuery('.cdls-modal input[name="status"]').prop('checked', true)

		object_rider = Object.assign({}, default_rider_object)
	}
	render_select_shipping_partner();
	function render_select_shipping_partner() {
		let html = '<option value="" selected>--Choose shipping partner--</option>'
		if (Array.isArray(cdls_list_partners)) {
			cdls_list_partners.forEach(item => {
				html += `<option value="${item.id}">${item.partner_name}</option>`
			})
		}
		jQuery('.cdls-modal-shipping-partner').html(html)
	}

	function validate_value() {
		var messages = [];
		if (object_rider.rider_name == '') {
			messages.push('All fields are required!');
		}
		if (object_rider.contact_number == '') {
			messages.push('All fields are required!');
		}
		var pattern = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]{6,}$/g
		if (!pattern.test(object_rider.contact_number)) {
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
			action: 'cdls_rider_management',
			...object_rider
		};

		jQuery.ajax({
			type: 'POST',
			url: ajax_object_rider.ajax_url,
			dataType: 'json',
			data: dataPost,
			beforeSend: function() {
				disabled_submit();
			},
			success: function (result) {
				cdls_list_riders = result.data;
				render_list_rider()
			},
			complete: function () {
				close_modal_rider();
				enable_submit();
			}
		});
	}
	
	if (window.location.search.includes("page=manage-driver"))
		send_ajax_get_list()
	function send_ajax_get_list() {
		var dataPost = {
			action: 'cdls_rider_management',
			get_list: 1
		};

		jQuery.ajax({
			type: 'POST',
			url: ajax_object_rider.ajax_url,
			dataType: 'json',
			data: dataPost,
			success: function (result) {
				cdls_list_riders = result.data;
				render_list_rider()
			}
		});
	}
});