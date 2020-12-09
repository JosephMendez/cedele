<?php
function update_product_info_on_edenred_func1( $post_id, $post ) { 
	if ( $post->post_type != 'product') return; // Only products
  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return;
  }
  if (isset($_POST['product-type']) && $_POST['product-type'] == 'simple') {
    $is_modify = false;
  	$old_price = get_post_meta( $post_id, '_regular_price', true);
  	$new_price = isset($_POST['_regular_price']) ? $_POST['_regular_price'] : 0;
    $old_product_title = isset($_POST['_old_product_title']) ? $_POST['_old_product_title'] : '';

    if ($old_product_title != $post->post_title) {
      $is_modify = true;
    }
  	if (floatval($old_price) != floatval($new_price)) {
      $is_modify = true;
  	}

    $products_data = [
      'product_code' => $post_id,
      'product_name' => $post->post_title,
      'unit_price' => $new_price,
    ];
    if ($is_modify) {
      createOrUpdateProduct($products_data);
    }
  } else if (isset($_POST['product-type']) && $_POST['product-type'] == 'grouped') {

  } else if (isset($_POST['product-type']) && $_POST['product-type'] == 'external') {

  } else if (isset($_POST['product-type']) && $_POST['product-type'] == 'variable') {
    $is_modify = false;
    $old_product_title = isset($_POST['_old_product_title']) ? $_POST['_old_product_title'] : '';
    $_old_variant_product_price = isset($_POST['_old_variant_product_price']) ? $_POST['_old_variant_product_price'] : [];
    $variable_post_id = isset($_POST['variable_post_id']) ? $_POST['variable_post_id'] : [];
    $variable_regular_price = isset($_POST['variable_regular_price']) ? $_POST['variable_regular_price'] : [];

    $list_n_variations = [];
    if (is_array($variable_post_id) && count($variable_post_id) > 0) {
      foreach ($variable_post_id as $key => $variable_id) {
        $new_price = isset($variable_regular_price[$key]) ? $variable_regular_price[$key] : 0;
        $old_price = isset($_old_variant_product_price[$variable_id]) ? $_old_variant_product_price[$variable_id] : 0;
        $v_data = [
          'product_code' => $variable_id,
          'product_name' => $post->post_title,
          'unit_price' => $new_price,
        ];
        $list_n_variations[] = $v_data;
        if (floatval($old_price) != floatval($new_price)) {
          $is_modify = true;
        }
      }
    } else {
      $product = wc_get_product($post_id);
      $variant_ids = $product->get_children();
      foreach ($variant_ids as $key => $v_id) {
        $v_data = [
          'product_code' => $v_id,
          'product_name' => $post->post_title
        ];
        $list_n_variations[] = $v_data;
      }
    }

    if ($old_product_title != $post->post_title) {
      $is_modify = true;
    }

    if ($is_modify) {
      batchCreateOrUpdateProducts(['products' => $list_n_variations]);
    }
  } else if (isset($_POST['product-type']) && $_POST['product-type'] == 'bundle') {
    $is_modify = false;
    $old_price = get_post_meta( $post_id, '_regular_price', true);
    $new_price = isset($_POST['_regular_price']) ? $_POST['_regular_price'] : 0;
    $old_product_title = isset($_POST['_old_product_title']) ? $_POST['_old_product_title'] : '';

    if ($old_product_title != $post->post_title) {
      $is_modify = true;
    }
    if (floatval($old_price) != floatval($new_price)) {
      $is_modify = true;
    }

    $products_data = [
      'product_code' => $post_id,
      'product_name' => $post->post_title,
      'unit_price' => $new_price,
    ];
    if ($is_modify) {
      createOrUpdateProduct($products_data);
    }
  }
}; 
         
// add the action 
add_action( 'woocommerce_process_product_meta', 'update_product_info_on_edenred_func1', 10, 3 );
