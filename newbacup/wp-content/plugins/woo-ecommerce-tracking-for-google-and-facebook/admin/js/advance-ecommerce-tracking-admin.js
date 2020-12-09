(
	function ($) {
		'use strict';
		
		/**
		 * All of the code for your admin-facing JavaScript source
		 * should reside in this file.
		 *
		 * Note: It has been assumed you will write jQuery code here, so the
		 * $ function reference has been prepared for usage within the scope
		 * of this function.
		 *
		 * This enables you to define handlers, for when the DOM is ready:
		 *
		 * $(function() {
		 *
		 * });
		 *
		 * When the window is loaded:
		 *
		 * $( window ).load(function() {
		 *
		 * });
		 *
		 * ...and/or other possibilities.
		 *
		 * Ideally, it is not considered best practise to attach more than a
		 * single DOM-ready or window-load handler for a particular page.
		 * Although scripts in the WordPress core, Plugins and Themes may be
		 * practising this, we should strive to set a better example in our own work.
		 */
		$(window).load(function () {
			function getUrlVars () {
				var vars = [], hash, get_current_url;
				get_current_url = aet_vars.current_url;
				var hashes = get_current_url.slice(get_current_url.indexOf('?') + 1).split('&');
				for (var i = 0; i < hashes.length; i++) {
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;
			}
			
			let getURLVARS = getUrlVars();
			let pluginspagesarray = [
				'aet-et-settings',
				'aet-ft-settings',
				'aet-gc-settings',
				'aet-get-started',
				'aet-information',
				'aet-cat-list',
				'aet-cft-list'
			];
			if (getURLVARS) {
				let getURLVARSPage = getURLVARS['page'];
				if ($.inArray(getURLVARSPage, pluginspagesarray) !== -1) {
					$('a[href="admin.php?page=aet-et-settings"]').parent().addClass('current');
					$('a[href="admin.php?page=aet-et-settings"]').addClass('current');
				}
			}
			
			function setAllAttributes (element, attributes) {
				Object.keys(attributes).forEach(function (key) {
					element.setAttribute(key, attributes[key]);
				});
				return element;
			}
			
			function reconnect_to_wizard () {
				$('#table_outer_wizard').show();
				$('#general_setting').hide();
				let table_outer_wizard_id = document.getElementById('table_outer_wizard').getElementsByClassName('sub_wizard_button')[0];
				if ($('#cancel_button').length == 0) {
					let new_a = document.createElement('a');
					new_a = setAllAttributes(new_a, {
							'href': 'javascript:void(0);',
							'id': 'cancel_button',
							'class': 'button button-primary button-large sub_wizard_button_a sub_wizard_button_third_a'
						}
					);
					new_a.textContent = 'Cancel';
					table_outer_wizard_id.appendChild(new_a);
				}
			}
			
			function cancel_button () {
				$('#general_setting').show();
				$('#table_outer_wizard').hide();
			}
			
			function update_manually_ID (get_val, get_attr, get_attr_two) {
				$.ajax({
					type: 'GET',
					url: aet_vars.ajaxurl,
					data: {
						'action': 'aet_update_manually_id',
						'get_val': get_val,
						'get_attr': get_attr,
						'get_attr_two': get_attr_two,
					},
					success: function (response) {
						location.reload(response);
					}
				});
			}
			
			$('body').on('click', '#reconnect_to_wizard', function () {
				reconnect_to_wizard();
			});
			$('body').on('click', '#cancel_button', function () {
				cancel_button();
			});
			/* description toggle */
			$('span.advance_ecommerce_tracking_tab_description').click(function (event) {
				event.preventDefault();
				$(this).next('p.description').toggle();
			});
			$('body').on('click', '#update_manually_ft_px, #update_manually_et_px', function () {
				let btn_attr = $(this).attr('data-attr');
				let get_attr = $('#manually_' + btn_attr + '_px').attr('data-attr');
				let get_val = $.trim($('#manually_' + btn_attr + '_px').val());
				let get_attr_two = $('#manually_' + btn_attr + '_px').attr('data-attr-two');
				console.log(get_val);
				
				if ('' === get_val) {
					console.log('blank');
					let sub_wizard_field = document.getElementById('sub_wizard_field').getElementsByClassName('field_div')[0];
					if ($('#main_error_div').length == 0) {
						let div = document.createElement('div');
						div = setAllAttributes(div, {
								'id': 'main_error_div',
								'class': 'error_div'
							}
						);
						let span = document.createElement('span');
						span = setAllAttributes(span, {
								'id': 'error_span',
								'class': 'error'
							}
						);
						span.textContent = 'Please enter ID here';
						div.appendChild(span);
						sub_wizard_field.appendChild(div);
					}
				}
				update_manually_ID(get_val, get_attr, get_attr_two);
			});
			
			/*Custom event section*/
			$('.all_ft_tr').hide();
			
			let selected = $('#event_type').find('option:selected');
			let select_val = $('#event_type').val();
			let extra_attr = selected.data('attr');
			getFieldBasedOnSelector(extra_attr, select_val);
			
			function getFieldBasedOnSelector (extra_attr, select_val) {
				$.each(extra_attr, function (key, value) {
					$('#tr_ft_' + key).show();
					if ('none' !== value) {
						//$('#tr_ft_' + key + ' input').attr('require', 'true');
					}
				});
				if ('Custom' === select_val) {
					$('#tr_ft_custom_event').show();
				} else {
					$('#tr_ft_custom_event').hide();
					$('#tr_ft_custom_event').find('input').val('');
				}
			}
			
			$('body').on('change', '#event_type', function () {
				$('.all_ft_tr').hide();
				let select_val = $(this).val();
				let selected = $(this).find('option:selected');
				let extra_attr = selected.data('attr');
				getFieldBasedOnSelector(extra_attr, select_val);
			});
			
			function adCustomParamField (count_cp) {
				let in_append_elem = document.getElementById('tr_ft_contents').getElementsByClassName('ft_contents_div')[0];
				let parent_div = document.createElement('div');
				parent_div = setAllAttributes(parent_div, {
					'id': 'total_custom_param_' + count_cp,
					'class': 'main_div_custom_param total_custom_param'
				});
				
				let input_parent_div1 = document.createElement('div');
				input_parent_div1 = setAllAttributes(input_parent_div1, {
					'id': 'ip_div_' + count_cp,
					'class': 'ip_div ip_div_' + count_cp,
				});
				
				let input_parent_div2 = document.createElement('div');
				input_parent_div2 = setAllAttributes(input_parent_div2, {
					'id': 'ip_div_' + count_cp,
					'class': 'ip_div ip_div_' + count_cp,
				});
				
				let input_parent_div3 = document.createElement('div');
				input_parent_div3 = setAllAttributes(input_parent_div3, {
					'id': 'param_delete',
					'class': 'param_delete',
					'data-id': count_cp
				});
				
				let child_input1 = document.createElement('input');
				child_input1 = setAllAttributes(child_input1, {
					'type': 'text',
					'id': 'key_' + count_cp,
					'name': 'key_param[]',
					'class': 'key_input key_' + count_cp,
					'placeholder': 'key',
					'value': '',
				});
				
				let child_input2 = document.createElement('input');
				child_input2 = setAllAttributes(child_input2, {
					'type': 'text',
					'id': 'value_' + count_cp,
					'name': 'value_param[]',
					'class': 'value_input value_' + count_cp,
					'placeholder': 'Value',
					'value': '',
				});
				
				let child_a = document.createElement('a');
				child_a = setAllAttributes(child_a, {
					'href': 'javascript:void(0);',
					'class': 'a_param_delete',
					'id': 'a_param_delete'
				});
				
				let child_a_img = document.createElement('img');
				child_a_img = setAllAttributes(child_a_img, {
					'src': aet_vars.trash_url,
				});
				
				child_a.appendChild(child_a_img);
				input_parent_div1.appendChild(child_input1);
				input_parent_div2.appendChild(child_input2);
				input_parent_div3.appendChild(child_a);
				parent_div.appendChild(input_parent_div1);
				parent_div.appendChild(input_parent_div2);
				parent_div.appendChild(input_parent_div3);
				in_append_elem.appendChild(parent_div);
			}
			
			$('body').on('click', '#custom_parameter_btn_div', function () {
				let count_cp = $('#tr_ft_contents .total_custom_param').length;
				if (count_cp == 0) {
					count_cp = 1;
				} else {
					count_cp = count_cp + 1;
				}
				adCustomParamField(count_cp);
			});
			
			$('body').on('click', '#a_param_delete', function () {
				let data_id = $(this).parent().attr('data-id');
				$('#total_custom_param_' + data_id).remove();
			});
			
			$('#custom_event_general_setting input#event_value').keypress(function (e) {
				var regex = new RegExp('^[0-9.]+$');
				var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
				if (regex.test(str)) {
					return true;
				}
				e.preventDefault();
				return false;
			});
			
			$('body').on('click', '.condition-check-all', function () {
				$('input.multiple_delete_chk:checkbox').not(this).prop('checked', this.checked);
			});
			
			$('#delete-custom-event').click(function () {
				let dynamic_string = $(this).attr('data-attr');
				if (0 == $('.multiple_delete_chk:checkbox:checked').length) {
					alert('Please select at least one ' + dynamic_string + ' event');
					return false;
				}
				if (confirm('Are You Sure You Want to Delete Selected ' + dynamic_string + ' Event?')) {
					var all_checked_val = [];
					$('.multiple_delete_chk:checked').each(function () {
						all_checked_val.push($(this).val());
					});
					$.ajax({
						type: 'GET',
						url: aet_vars.ajaxurl,
						data: {
							'action': 'aet_wc_multiple_delete_row__premium_only',
							'nonce': aet_vars.aet_chk_nonce_ajax,
							'all_checked_val': all_checked_val
						},
						success: function (response) {
							if (1 == response) {
								alert('Delete Successfully');
								$('.multiple_delete_chk').prop('checked', false);
								location.reload();
							}
						}
					});
				}
			});
			
			$('body').on('click', '#aet_fetch_data', function () {
				$.ajax({
					type: 'GET',
					url: aet_vars.ajaxurl,
					data: {
						'action': 'aet_fetch_data',
						'nonce': aet_vars.aet_fetch_data_nonce_ajax,
					},
					success: function (response) {
						if (1 == response) {
							location.reload();
						}
					}
				});
			});
			
		});
	}
)(jQuery);