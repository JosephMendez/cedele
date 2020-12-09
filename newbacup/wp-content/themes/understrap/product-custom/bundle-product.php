<?php
// Display Fields
add_action('woocommerce_product_options_related', 'woocommerce_product_custom_fields1');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_bundle_product_custom_fields_save');
function woocommerce_product_custom_fields1()
{
	global $post, $wpdb;

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	$all_products = wc_get_products($args);
	$product_options = [];
	foreach ($all_products as $key => $product) {
		if ($product->get_type() == "simple") {
			$product_options[$product->get_id()] = $product->get_title();
		}
	}

	$post_id = $post->ID;
	$list_bundle_product = get_post_meta($post_id, '_wc_bundle_products', true);
	?>
	<style>
		.wc-custom-bp {
			cursor: pointer;
		}

		.wc-custom-bp .wc-custom-input-wrap {
			display: flex;
		}

		.wc-custom-bp .wc-custom-input-wrap label {
			font-weight: bold;
		}

		.wc-custom-bp .red {
			color: red;
		}

		.wc-custom-bp span.select2 {
			width: 100% !important;
		}

		.wc-custom-bp .wc-custom-bp-number {
			width: 100px !important;
		}

		/* fieldset */
		.wc-custom-fieldset-bundle-product {
			width: calc(100% - 20px);
			box-sizing: border-box;
			border: 1px solid #9c9c9c;
			margin: 10px !important;
		}

		.wc-custom-fieldset-bundle-product input,
		.wc-custom-fieldset-bundle-product select {
			float: unset !important;
		}

		.wc-custom-fieldset-bundle-product .wc-custom-input-3,
		.wc-custom-fieldset-bundle-product .wc-custom-input-1 {
			width: 40%;
		}

		.wc-custom-fieldset-bundle-product .wc-custom-input-1 input {
			width: 140px !important;
		}

		.wc-custom-fieldset-bundle-product .wc-custom-input-2 {
			width: 145px !important;
			opacity: 1;
		}

		.wc-custom-fieldset-bundle-product .wc-custom-input-2.wc-hide {
			opacity: 0;
		}

		.wc-custom-fieldset-bundle-product .wc-custom-input-2,
		.wc-custom-fieldset-bundle-product .wc-custom-input-3 {
			text-align: center;
		}

		.wc-custom-fieldset-bundle-product label {
			font-weight: bold;
			margin: 0px;
		}

		/* table */
		.wc-custom-table-bundle-product {
			margin-top: 10px;
		}

		.wc-custom-table-bundle-product td {
			vertical-align: middle;
		}

		.wc-custom-table-bundle-product input[type="number"] {
			opacity: 1;
		}

		.wc-custom-table-bundle-product input[type="number"].wc-hide {
			opacity: 0;
		}
	</style>
	<div class="options_group wc-custom-bp">
		<p class="form-field _custom_product_quality_linked_field ">
			<label for="_custom_product_quality_linked">Bundle products</label>
			<button type="button" class="button wc-custom-bp-add-more-option">Add new option</button>
		</p>
		<?php
		if (is_array($list_bundle_product) && count($list_bundle_product) > 0):
			foreach ($list_bundle_product as $key => $bundle_product):
				$prefix_name = "_wc_bundle_products[$key]";
				?>
				<fieldset class="wc-custom-fieldset-bundle-product">
					<div class="wc-custom-input-wrap">
						<div class="wc-custom-input-1">
							<label>Option title: <span class="red">*</span></label>
							<input type="text" placeholder="Title" name="<?php echo $prefix_name . "[title]" ?>"
								   value="<?php echo $bundle_product['title'] ?>"
								   data-name="_wc_bundle_products[{key}][title]">
						</div>
						<div class="wc-custom-input-2">
							<div class="div-maximum"
								 style="<?php echo ($bundle_product['is_user_can_define'] == 1) ? '' : 'display: none;' ?>">
								<label>Maximum:</label>
								<input type="number" placeholder="max" name="<?php echo $prefix_name . "[maximum]" ?>"
									   value="<?php echo !empty($bundle_product['maximum']) ? $bundle_product['maximum'] : 0 ?>"
									   data-name="_wc_bundle_products[{key}][maximum]">
							</div>
							<div class="div-required"
								 style="<?php echo !($bundle_product['is_user_can_define'] == 1) ? '' : 'display: none;' ?>">
								<input
									type="checkbox" <?php echo ($bundle_product['is_required'] == 1) ? 'checked' : '' ?>
									name="<?php echo $prefix_name . "[is_required]" ?>"
									value="1"
									data-name="_wc_bundle_products[{key}][is_required]">
								<label>Option is required</label>
							</div>
						</div>
						<div class="wc-custom-input-3">

							<label>User define quantity:</label>
							<select name="<?php echo $prefix_name . "[is_user_can_define]" ?>"
									data-name="_wc_bundle_products[{key}][is_user_can_define]">
								<option value="0">No</option>
								<option
									value="1" <?php echo !empty($bundle_product['is_user_can_define']) ? 'selected' : '' ?>>
									Yes
								</option>
							</select>
						</div>
						<div>
							<button type="button" class="button button-primary wc-custom-delete-bundle-product">Delete
							</button>
						</div>
					</div>
					<hr>
					<label for="">Product items:</label>
					<button type="button" class="button wc-custom-bp-add-linked-product" style="float: right;">Add
						product
					</button>
					<table class="wc-custom-table-bundle-product wp-list-table widefat striped" cellpadding="8"
						   border="0" cellspacing="0">
						<thead>
						<tr>
							<th width="35%">Name</th>
							<th width="35%">Quantity</th>
							<th width="20%">Price</th>
							<th width="10%"></th>
						</tr>
						</thead>
						<tbody>
						<?php
						if (is_array($bundle_product['linked_products']) && count($bundle_product['linked_products']) > 0):
							foreach ($bundle_product['linked_products'] as $k => $linked):
								?>
								<tr>
									<td>
										<?php
										woocommerce_wp_select_bundle_product(array(
											'name' => $prefix_name . "[linked_products][$k][product_id]",
											'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][product_id]',
											'class' => 'wc-custom-bp-linked-product',
											'options' => $product_options,
											'value' => $linked['product_id']
										));
										?>
									</td>
									<td>
										<?php
										$is_hide = !empty($bundle_product['is_user_can_define']) ? 'wc-hide' : '';
										custom_bp_input_number(
											array(
												'name' => $prefix_name . "[linked_products][$k][quantity]",
												'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][quantity]',
												'placeholder' => 'Quantity',
												'type' => 'number',
												'class' => 'wc-custom-bp-number' . ' ' . $is_hide,
												'custom_attributes' => array(
													'step' => 1,
													'min' => 0
												),
												'value' => (isset($linked['quantity']) ? $linked['quantity'] : 0),
											)
										);
										?>
									</td>
									<td>
										<?php
										$is_hide = !empty($bundle_product['is_user_can_define']) ? 'wc-hide' : '';
										custom_bp_input_number(
											array(
												'name' => $prefix_name . "[linked_products][$k][price]",
												'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][price]',
												'placeholder' => 'Quantity',
												'type' => 'number',
												'class' => 'wc-custom-bp-number wc-custom-bp-number-float' . ' ' . $is_hide,
												'custom_attributes' => array(
													'step' => 0.01,
													'min' => 0
												),
												'value' => (isset($linked['price']) ? $linked['price'] : 0),
											)
										);
										?>
									</td>
									<td>
                            <span class="wc-custom-bp-remove-button">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000"
									 xmlns="http://www.w3.org/2000/svg">
                                  <path
									  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                  <path fill-rule="evenodd"
										d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </span>
									</td>
								</tr>
							<?php endforeach;endif; ?>
						</tbody>
					</table>
				</fieldset>
			<?php endforeach;endif; ?>
	</div>
	<script type="text/template" id="wc-custom-template-bundle-product">
		<fieldset class="wc-custom-fieldset-bundle-product">
			<div class="wc-custom-input-wrap">
				<div class="wc-custom-input-1">
					<label>Option title: <span class="red">*</span></label>
					<input type="text" placeholder="Title" name="_wc_bundle_products[{key}][title]"
						   data-name="_wc_bundle_products[{key}][title]">
				</div>
				<div class="wc-custom-input-2">
					<div class="div-maximum" style="display: none">
						<label>Maximum:</label>
						<input type="number" placeholder="max" name="_wc_bundle_products[{key}][maximum]"
							   data-name="_wc_bundle_products[{key}][maximum]">
					</div>
					<div class="div-required">
						<input type="checkbox" checked name="_wc_bundle_products[{key}][is_required]"
							   value="1"
							   data-name="_wc_bundle_products[{key}][is_required]">
						<label>Option is required</label>
					</div>
				</div>
				<div class="wc-custom-input-3">
					<label>User define quantity:</label>
					<select name="_wc_bundle_products[{key}][is_user_can_define]"
							data-name="_wc_bundle_products[{key}][is_user_can_define]">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div>
					<button type="button" class="button button-primary wc-custom-delete-bundle-product">Delete</button>
				</div>
			</div>
			<hr>
			<label for="">Product items:</label>
			<button type="button" class="button wc-custom-bp-add-linked-product" style="float: right;">Add product
			</button>
			<table class="wc-custom-table-bundle-product wp-list-table widefat striped" cellpadding="8" border="0"
				   cellspacing="0">
				<thead>
				<tr>
					<th width="35%">Name</th>
					<th width="35%">Quantity</th>
					<th width="20%">Price</th>
					<th width="10%"></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						<?php
						woocommerce_wp_select_bundle_product(array(
							'name' => '_wc_bundle_products[{key}][linked_products][{k}][product_id]',
							'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][product_id]',
							'class' => 'wc-custom-bp-linked-product',
							'options' => $product_options,
							'value' => 0
						));
						?>
					</td>
					<td>
						<?php
						custom_bp_input_number(
							array(
								'name' => '_wc_bundle_products[{key}][linked_products][{k}][quantity]',
								'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][quantity]',
								'placeholder' => 'Quantity',
								'type' => 'number',
								'class' => 'wc-custom-bp-number',
								'custom_attributes' => array(
									'step' => 1,
									'min' => 0
								),
								'value' => 0,
							)
						);
						?>
					</td>
					<td>
						<?php
						custom_bp_input_number(
							array(
								'name' => '_wc_bundle_products[{key}][linked_products][{k}][price]',
								'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][price]',
								'placeholder' => 'Quantity',
								'type' => 'number',
								'class' => 'wc-custom-bp-number wc-custom-bp-number-float',
								'custom_attributes' => array(
									'step' => 0.01,
									'min' => 0
								),
								'value' => 0,
							)
						);
						?>
					</td>
					<td>
                            <span class="wc-custom-bp-remove-button">
                                <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000"
									 xmlns="http://www.w3.org/2000/svg">
                                  <path
									  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                  <path fill-rule="evenodd"
										d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </span>
					</td>
				</tr>
				</tbody>
			</table>
		</fieldset>
	</script>
	<script type="text/template" id="wc-custom-template-linked-product">
		<tr>
			<td>
				<?php
				woocommerce_wp_select_bundle_product(array(
					'name' => '_wc_bundle_products[{key}][linked_products][{k}][product_id]',
					'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][product_id]',
					'class' => 'wc-custom-bp-linked-product',
					'options' => $product_options,
					'value' => 0
				));
				?>
			</td>
			<td>
				<?php
				custom_bp_input_number(
					array(
						'name' => '_wc_bundle_products[{key}][linked_products][{k}][quantity]',
						'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][quantity]',
						'placeholder' => 'Quantity',
						'type' => 'number',
						'class' => 'wc-custom-bp-number',
						'custom_attributes' => array(
							'step' => 1,
							'min' => 0
						),
						'value' => 0,
					)
				);
				?>
			</td>
			<td>
				<?php
				custom_bp_input_number(
					array(
						'name' => '_wc_bundle_products[{key}][linked_products][{k}][price]',
						'data-name' => '_wc_bundle_products[{key}][linked_products][{k}][price]',
						'placeholder' => 'Quantity',
						'type' => 'number',
						'class' => 'wc-custom-bp-number wc-custom-bp-number-float',
						'custom_attributes' => array(
							'step' => 0.01,
							'min' => 0
						),
						'value' => 0,
					)
				);
				?>
			</td>
			<td>
                <span class="wc-custom-bp-remove-button">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000"
						 xmlns="http://www.w3.org/2000/svg">
                      <path
						  d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd"
							d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </span>
			</td>
		</tr>
	</script>
	<script type="text/javascript" defer>
		// jquery anchor
		jQuery(document).ready(function ($) {
			function wc_custom_bp_init_select2(argument) {
				jQuery('.wc-custom-bp .wc-enhanced-select').select2();
			}

			function wc_custom_disable_select2(table) {
				var choose_ids = []
				setTimeout(() => {
					jQuery(table).find('option').prop('disabled', false);
					jQuery(table).find('option:selected').each(function (index, el) {
						if (!!jQuery(this).val()) {
							choose_ids.push(jQuery(this).val())
						}
					});

					choose_ids.forEach(item => {
						jQuery(table).find('select').each(function (index, el) {
							if (!jQuery(this).find(`option[value="${item}"]`).is(':selected'))
								jQuery(this).find(`option[value="${item}"]`).prop('disabled', true);
						});
					});
				}, 100)
				wc_custom_bp_init_select2()
			}

			jQuery('body .wc-custom-bp table').each(function (index, el) {
				wc_custom_disable_select2(jQuery(this))
			});
			jQuery('body').on('click', '.wc-custom-bp p', function (event) {
				jQuery(this).siblings().toggle()
			});

			jQuery('body').on('click', '.wc-custom-bp .wc-custom-bp-remove-button', function (event) {
				event.preventDefault();
				jQuery(this).closest('tr').remove();
				update_name_input()
			});

			jQuery('body').on('click', '.wc-custom-bp .wc-custom-delete-bundle-product', function (event) {
				event.preventDefault();
				jQuery(this).closest('fieldset').remove();
				update_name_input()
			});

			// add more bundle product
			jQuery('body').on('click', '.wc-custom-bp .wc-custom-bp-add-more-option', function (event) {
				event.preventDefault();
				event.stopPropagation();
				jQuery('.wc-custom-bp fieldset').show()
				var html = jQuery('#wc-custom-template-bundle-product').html();

				jQuery(this).closest('.wc-custom-bp').append(html)
				update_name_input()
				wc_custom_bp_init_select2();
			});

			// add more linked product
			jQuery('body').on('click', '.wc-custom-bp .wc-custom-bp-add-linked-product', function (event) {
				var html = jQuery('#wc-custom-template-linked-product').html();
				jQuery(this).closest('fieldset').find('tbody').append(html)
				update_name_input()
				wc_custom_select_user_can_define()
				wc_custom_bp_init_select2();
				wc_custom_disable_select2(jQuery(this).closest('fieldset').find('table'));
			});

			jQuery('body').on('change', '.wc-custom-bp table select', function (event) {
				wc_custom_disable_select2(jQuery(this).closest('table'))
			});

			function update_name_input() {
				jQuery('.wc-custom-bp').find('input[type="text"], input[type="number"], input[type="checkbox"]').each(function (index, el) {
					let newName = jQuery(this).attr('data-name');
					let key = jQuery(this).closest('fieldset').index();
					let k = 0;
					if (jQuery(this).closest('tr').length) {
						k = jQuery(this).closest('tr').index();
					}
					newName = newName.replace(/{key}/g, key).replace(/{k}/g, k)
					jQuery(this).attr('name', newName)
				});
				jQuery('.wc-custom-bp').find('select').each(function (index, el) {
					let newName = jQuery(this).attr('data-name');
					let key = jQuery(this).closest('fieldset').index();
					let k = 0;
					if (jQuery(this).closest('tr').length) {
						k = jQuery(this).closest('tr').index();
					}
					newName = newName.replace(/{key}/g, key).replace(/{k}/g, k)
					jQuery(this).attr('name', newName)
				});
			}

			// add more linked product
			jQuery('body').on('blur input', '.wc-custom-bp input[type="number"]:not(.wc-custom-bp-number-float)', function (event) {
				let val = jQuery(this).val()
				let newVal = parseInt(val) ? parseInt(val) : 0;
				newVal = Math.abs(parseInt(newVal));
				jQuery(this).val(newVal);
			});
			jQuery('body').on('blur input', '.wc-custom-bp input.wc-custom-bp-number-float', function (event) {
				let val = jQuery(this).val()
				let newVal = parseFloat(val) ? parseFloat(val) : 0;
				newVal = Math.abs(parseFloat(newVal));
				jQuery(this).val(newVal);
			});

			jQuery('body').on('change', '.wc-custom-input-3 select', function (event) {
				let val = jQuery(this).val()
				if (val == 1) {
					jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-maximum').show();
					jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-required').hide();
					jQuery(this).closest('fieldset').find('table input[type="number"]').addClass('wc-hide');
				} else {
					jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-maximum').hide();
					jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-required').show();
					jQuery(this).closest('fieldset').find('table input[type="number"]').removeClass('wc-hide');
				}
			});

			function wc_custom_select_user_can_define(argument) {
				jQuery('.wc-custom-bp .wc-custom-input-3 select').each(function (index, el) {
					let val = jQuery(this).val()
					if (val == 1) {
						jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-maximum').show();
						jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-required').hide();
						jQuery(this).closest('fieldset').find('table input[type="number"]').addClass('wc-hide')
					} else {
						jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-maximum').hide();
						jQuery(this).closest('fieldset').find('.wc-custom-input-2 .div-required').show();
						jQuery(this).closest('fieldset').find('table input[type="number"]').removeClass('wc-hide')
					}
				});
			}
		});
	</script>
	<?php
}

function woocommerce_wp_select_bundle_product($field)
{
	global $post, $wpdb;

	$post_id = $post->ID;
	$field['class'] = isset($field['class']) ? $field['class'] : 'select short';
	$field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
	$field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
	$field['data-name'] = isset($field['data-name']) ? $field['data-name'] : '';
	$field['value'] = isset($field['value']) ? $field['value'] : '';

	echo '<select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '"
        data-name="' . esc_attr($field['data-name']) . '" class="wc-enhanced-select">';
	echo '<option value="0">-- Choose product --</option>';
	foreach ($field['options'] as $key => $value) {

		echo '<option value="' . esc_attr($key) . '" ' . ($field['value'] == $key ? 'selected="selected"' : '') . '>' . esc_html($value) . '</option>';
	}
	echo '</select>';
}

function custom_bp_input_number($field)
{
	$custom_attributes = isset($field['custom_attributes']) ? $field['custom_attributes'] : [];
	$custom_attributes['step'] = isset($custom_attributes['step']) ? $custom_attributes['step'] : 1;
	$custom_attributes['min'] = isset($custom_attributes['min']) ? $custom_attributes['min'] : 0;

	echo '<input
        type="' . $field['type'] . '"
        name="' . $field['name'] . '"
        data-name="' . $field['data-name'] . '"
        class="' . $field['class'] . '"
        placeholder="' . $field['placeholder'] . '"
        step="' . $custom_attributes['step'] . '"
        min="' . $custom_attributes['min'] . '"
        value="' . $field['value'] . '"
    />';
}

function woocommerce_bundle_product_custom_fields_save($post_id)
{
	// Custom Product Text Field
	global $post;
	$post_id = $post->ID;
	$_wc_bundle_products = $_POST['_wc_bundle_products'];
	$new_wc_bundle_products = [];
	if (is_array($_wc_bundle_products)) {
		foreach ($_wc_bundle_products as $key => $bundle_product) {
			$new_linked_products = [];
			if (!empty($bundle_product['linked_products'])) {
				foreach ($bundle_product['linked_products'] as $k => $linked) {
					if (!empty($linked['product_id'])) {
						$new_linked_products[] = $linked;
					}
				}
			}
			$bundle_product['linked_products'] = $new_linked_products;
			$new_wc_bundle_products[] = $bundle_product;
		}
	}

	if (!empty($_wc_bundle_products))
		update_post_meta($post_id, '_wc_bundle_products', $new_wc_bundle_products);
}

?>
