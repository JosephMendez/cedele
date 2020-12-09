<?php
/*
 * rule gender: 1=male,2=female,0=unknown
 * rule date: Complete date plus hours and minutes: YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:33+05:30)
 * */
const EDENRED_USER = "admin";
const EDENRED_PASSWORD = "abcd1234$";
const EDENRED_COMPANY = "Cedele20";

const EDENRED_DOMAIN ="https://www.fidescloud.com";
const EDENRED_VERSION ="v1";
const STORE_CODE = "ST665579320";

//@action create member when signup successfully.
add_action( 'user_register', function ( $user_login ) {
//	Get info of user register.
	$info = get_user_by('id', $user_login);
	$user_info = $info->data;
	$form_data = json_decode(stripslashes($_POST['form_data']), true);

	if (!json_last_error()) {
		foreach ($form_data as $key => $data) {
			switch ($data['field_name']) {
				case 'first_name': {
					$user_info->first_name = $data['value'];
					break;
				}
				case 'last_name': {
					$user_info->last_name = $data['value'];
					break;
				}
				case 'phone_number': {
					$user_info->phone_number = $data['value'];
					break;
				}
				case 'user_gender': {
					$user_info->user_gender = $data['value'];
					break;
				}
				case 'user_birthday': {
					$user_info->user_birthday = $data['value'];
					break;
				}
			}

		}
	}

	$token = get_token();
	$body = format_data($user_info);
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/member/createMemberAndAccount';
	$respond = cal_api('POST', $url, json_encode($body), $token);

	$status = json_decode($respond);
}, 10, 3 );

// @action: update number. If member doesn't exist in Edenred-system, create new
add_action( 'profile_update', function ( $user_id ) {
	$form_user = $_POST;

	if($form_user['woocommerce-process-checkout-nonce'] || $form_user['payment_method']) {
		return;
	}
	$birthday = new DateTime($form_user['user_registration_user_birthday']);
	$gender = strtolower($form_user["user_registration_user_gender"]);

	if ($gender == 'male') {
		$gender = '1';
	} elseif ($gender == 'female') {
		$gender = '2';
	} else {
		$gender = '0';
	}

	$date_tmp = new DateTime();
	$info = get_user_by('id', $user_id);
	if ($info->data->user_registered) {
		$date_tmp = new DateTime($info->data->user_registered);
	}

	$edenred_info = get_edenred_id($user_id);

	$bu_content = array(
		"member_code" => $edenred_info[0]->user_id,
		"first_name" => $form_user["account_first_name"],
		"last_name" => $form_user["account_last_name"],
		"email" => $form_user["account_email"],
		"gender" => $gender,
		"account_type_code" => "default",
		"birthday_day" => $birthday->format('d')+0,
		"birthday_month" => $birthday->format('m')+0,
		"birthday_year" => $birthday->format('Y')+0,
		"mobile" => $form_user["user_registration_phone_number"],
		"full_name" => $form_user["account_first_name"].' '.$form_user["account_last_name"],
		"register_date" => $date_tmp->format(DATE_RFC3339_EXTENDED)
	);

	$body = array(
		"bu_content" => $bu_content
	);

	$token = get_token();
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/member/updateMemberInfo';
	$response = cal_api('POST', $url, json_encode($body), $token);
	$status = json_decode($response);

//	If user not exist, create new.
	if ($response && $status->code === "11008") {
//		Call api
		$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/member/createMemberAndAccount';
		$response = cal_api('POST', $url, json_encode($body), $token);
	}
}, 10, 3);

//@action: Will catch event after checkout successful.
add_action('woocommerce_before_thankyou', function ( $order_id ) {
//	$channel_code = online || pos || wechat || ecommerce
//	transaction_type_code = purchase ||

	// update redemption points
	$redemp_point = WC()->session->get( 'enable_redemp_point');
  if ($redemp_point) {
		$used_redemp_point = WC()->session->get( 'used_redemp_point', 0);
		createGiftCodeForMember($used_redemp_point);
		update_post_meta($order_id, 'used_redemp_point', $used_redemp_point);
  }

	$transaction_type_code = "purchase";
	$channel_code = "EC";
	$items_qty = 0;

	$obj_order = wc_get_order( $order_id );
	$order_data = $obj_order->get_data();
	foreach ($obj_order->get_items() as $item_id => $item_data) {
		$items_qty += $item_data->get_quantity();
	}

	$purchase_date = new DateTime($order_data['date_completed']);
	$transaction_date = new DateTime($order_data['date_created']);

	$arr_items = [];
	$base_price = 0;

	foreach ($obj_order->get_items() as $item_key => $item ) {
		$_product = $item->get_product();
		if ($_product) {
			$quantity = $item->get_quantity();
			$real_price = wc_format_decimal($item['total'] / $item['quantity'], 2);
			$real_amount = wc_format_decimal($item['total'], 2);
			$unit_price = $_product->get_price();

			if ($_product->is_type('variation')) {
				$product_code = $item->get_variation_id();
			} else {
				$product_code = $item->get_product_id();
			}

			$product = array(
				"product_code" => $product_code,
				"quantity"     => $quantity,
				"real_price"   => $real_price,
				"real_amount"  => $real_amount,
				"unit_price"   => $unit_price
			);

			$base_price += $real_amount;
			$arr_items[] = $product;
		}
	}

	$transaction_tenders = [
		array(
			"real_amount" =>  wc_format_decimal($base_price, 2),
			"tender_type_code" => "BC"
		)
	];
	$edenred_info = get_edenred_id($obj_order->get_user_id());

	if ($order_id) {
		$bu_content = array(
			"member_code"=> $edenred_info[0]->user_id,
			"transaction_type_code" => $transaction_type_code,
			"purchase_date"=> $purchase_date->format(DATE_RFC3339_EXTENDED),
			"transaction_date"=> $transaction_date->format(DATE_RFC3339_EXTENDED),
			"invoice_no"=> $order_id,
			"original_invoice_no"=> $order_id, // needed for void transaction api.
			"store_code"=> "",
			"channel_code"=> $channel_code,
			"employee_code"=> "",
			"total_amount"=> wc_format_decimal($base_price, 2),
			"total_quantity"=> $items_qty,
			"total_discount"=> $order_data['discount_total'],
			"transaction_details" => $arr_items,
			"transaction_tenders" => $transaction_tenders
		);
	}

	$body = array(
		"bu_content" => $bu_content
	);

	$token = get_token();
	$url = EDENRED_DOMAIN.'/openapi/fides/customization/'.EDENRED_VERSION.'/cedele/createTransaction';
//	https://uat.fidescloud.com/openapi/fides/customization/v1/cedele/createTransaction
	$respone = cal_api('POST', $url, json_encode($body), $token);
	$status = json_decode($respone);

	if (!$respone || $status->code !== "2000") {
		$user_info = $obj_order->get_user();
		email_transaction_edenred_service($user_info->user_email, "Transaction Failed !", $body, $status);
	}
}, 10, 1);

function get_edenred_id($userId) {
	global $wpdb;
	return $wpdb->get_results( "SELECT COALESCE(edenred_id, id) AS user_id FROM {$wpdb->prefix}users WHERE id =$userId LIMIT 1");
}

function format_data($user_info) {

	$date_tmp = new DateTime($user_info->user_registered);
	$gender = strtolower($user_info->user_gender);
	if ($gender == 'male') {
		$gender = '1';
	} elseif ($gender == 'female') {
		$gender = '2';
	} else {
		$gender = '0';
	}

	$birthday = new DateTime();
	if ($user_info->user_birthday) $birthday = new DateTime($user_info->user_birthday);

	$bu_content = array(
		"member_code" => $user_info->ID,
		"first_name" => $user_info->first_name,
		"last_name" => $user_info->last_name,
		"full_name" => $user_info->first_name.' '.$user_info->last_name,
		"email" => $user_info->user_email,
		"mobile" => $user_info->phone_number,
		"segmentations" => [],
		"options" => [],
		"account_type_code" => "default",
		"register_date" => $date_tmp->format(DATE_RFC3339_EXTENDED),
		"birthday_day" => $birthday->format('d')+0,
		"birthday_month" => $birthday->format('m')+0,
		"birthday_year" => $birthday->format('Y')+0,
		"gender" => $gender
	);

	$body = array(
		"bu_content" => $bu_content
	);

	return $body;
}

function get_token() {
//	$result_api = cal_api('GET', EDENRED_DOMAIN.'/security/oauth/token?grant_type=custom&login_name='.EDENRED_USER.'&password='.sha1(EDENRED_PASSWORD).'&company='.EDENRED_COMPANY, false, '');
//	$result = json_decode($result_api);
//
//	return $result->access_token;

    return '';
}

function cal_api($method, $url, $data, $token) {
	$curl = curl_init();
	switch ($method){
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
	}
	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: Basic ZmlkZXM6YWJjMTIzJA==',
		'Content-Type: application/json',
		'access_token: '.$token
	));

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// EXECUTE:
	$result = curl_exec($curl);
	curl_close($curl);
	return $result;
}

function getMemberInfo($id) {
	$token = get_token();
	$edenred_info = get_edenred_id($id);
	$member_code = array(
		"member_code" => $edenred_info[0]->user_id
	);
	$body = array(
		"bu_content" => $member_code
	);
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/member/getMemberInfo';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

	if ($result->code == 2000) {
		return $result->result;
	} return null;
}

function getPointMember($id) {
	$token = get_token();
	$edenred_info = get_edenred_id($id);
	$point_member = array(
		"member_code" => $edenred_info[0]->user_id
	);
	$body = array(
		"bu_content" => $point_member
	);
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/point/queryMemberPoint';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

	if ($result->code == 2000) {
		return $result->result;
	} return null;
}

function queryMemberCouponList($from_date, $to_date, $page_size = 20, $page_num = 1) {
	$user = wp_get_current_user();
	$edenred_info = get_edenred_id($user->ID);
	if ($to_date) {
		try {
			$to_date = new DateTime($to_date);
		} catch (Exception $e) {}
	} else {
		$to_date = new DateTime();
	}

	$from_date = $from_date ? $from_date : $user->user_registered;
	try {
		$from_date = new DateTime($from_date);
	} catch (Exception $e) {}

	$query_member = array(
		"member_code"=> $edenred_info[0]->user_id,
		"page_size"=> $page_size,
		"page_no"=> $page_num,
		"create_date_from"=> $from_date->format(DATE_RFC3339_EXTENDED),
		"create_date_to"=> $to_date->format(DATE_RFC3339_EXTENDED),
		"order_by"=> "null",
		"asc"=> "null"
	);

	$body = array(
		"bu_content" => $query_member
	);

	$token = get_token();
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/loyalty/queryMemberCouponList ';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

	if ($result->code == 2000) {
		return $result->result;
	} return null;
}

add_action( 'woocommerce_order_refunded', 'voidTransaction', 10, 2 );
function voidTransaction( $order_id, $refund_id )
{
	$token = get_token();
	$refund_id;
	$point_member = array(
		"original_invoice_no" => $order_id
	);
	$body = array(
		"bu_content" => $point_member
	);

	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/transaction/voidTransaction';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

//	temporary
	send_email_customer_cancel_order($order_id);
}

function createCouponForRefund($order_id) {

	$order = wc_get_order($order_id);
	$order_data = $order->get_data();
	$customer = $order->get_user();

	$coupon_code = sha1($order_id+$order_data['customer_id']); // Code - perhaps generate this from the user ID + the order ID
	$coupon_code = 'CDL'.substr($coupon_code, 0, 6);

	$expiry_date = date('Y-m-d', strtotime("now + 1 years"));

	if (wc_get_coupon_id_by_code( $coupon_code ) == false) {
		$amount = $order_data['total']; // Amount
		$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product

		$coupon = array(
			'post_title' => $coupon_code,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'     => 'shop_coupon'
		);

		$new_coupon_id = wp_insert_post( $coupon );
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', wc_format_decimal($amount, 2) );
		update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
		update_post_meta( $new_coupon_id, 'product_ids', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' );
		update_post_meta( $new_coupon_id, 'expiry_date', $expiry_date );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
		update_post_meta( $new_coupon_id, 'customer_email', $customer->user_email );
		update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
//		update_post_meta( $new_coupon_id, 'minimum_amount', wc_format_decimal($amount, 2) ); array_filter( array_map( 'trim', explode( ',', wc_clean( $args['customer_email'] ) ) ) )

	}
	return $coupon_code;
}

function getListCoupon($page_size = 30, $page_num = 0, $member_code, $date_from = "2010-08-04T10:04:46.709+00:00", $date_to = "2050-29-12T10:04:46.709+00:00", $token ) {
	// get list coupon.

	$bu_content = array(
		"member_code" => $member_code,
		"page_size" => $page_size,
		"page_no" => $page_num,
		"create_date_from" => $date_from,
		"create_date_to" => $date_to,
		"order_by" => "",
		"asc" => ""
	);
	$body = array(
		"bu_content" => $bu_content
	);

	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/loyalty/queryMemberCouponList';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

	if ($result->code == 2000) {
		return $result->result;
	} return null;
}

function getCouponFromEdenred($coupon, $token, $user_id) {
	$edenred_info = get_edenred_id($user_id);
	$list_coupons = getListCoupon(999999, 1, $edenred_info[0]->user_id, "2010-08-04T10:04:46.709+00:00", "2050-09-11T10:04:46.709+00:00", $token);
	$result = null;
	$list_coupons_avaiable = [];
	if ($list_coupons->results !== null) {
		for ($i = 0; $i < count($list_coupons->results); $i++) {
			$exp_date = $list_coupons->results[$i]->expired_date;
			$effective_date = $list_coupons->results[$i]->effective_date;
			$now = new DateTime();
			$now = $now->format(DATE_RFC3339_EXTENDED);

			if ( strtolower($list_coupons->results[$i]->coupon_code) === $coupon
				&& $list_coupons->results[$i]->status == 1
				&& $now <= $exp_date
				&& $now >= $effective_date
			) {
				$list_coupons_avaiable[] = $list_coupons->results[$i];
			}
		}
	}
	if (count($list_coupons_avaiable) > 0) {
		usort($list_coupons_avaiable,function($first, $second){
				return (new DateTime($first->expired_date)) > (new DateTime($second->expired_date));
		});
		$result = $list_coupons_avaiable[0];
	}
	return $result;
}

// define the woocommerce_applied_coupon callback
function action_woocommerce_applied_coupon($value) {
	if ($value !== null && !empty($value)) {
		$user = wp_get_current_user();
		if ( 0 == $user->ID ) return $value;

		// Nếu coupon k có trong Cedele.
		$token = get_token();
		$coupon = getCouponFromEdenred($value, $token, $user->ID);

		if ($value && wc_get_coupon_id_by_code($value) == false) {
			// Check xem nó có phải từ phía Edenred. Nếu có thì validate nếu không thì remove.
			if ($coupon) {
				$discount_type = array(
					'cash' => 'fixed_cart',
					'discount' => 'percent'
				); // Type: fixed_cart, percent, fixed_product, percent_product

				$_coupon = array(
					'post_title' => $coupon->coupon_code,
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'     => 'shop_coupon'
				);

				$_value = $coupon->coupon_value;
				if ($coupon->coupon_type_code == 'discount' ) $_value = wc_format_decimal(100 * $_value, 2);

				$new_coupon_id = wp_insert_post( $_coupon );

				// Add meta
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type[$coupon->coupon_type_code] );
				update_post_meta( $new_coupon_id, 'coupon_amount', $_value );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				update_post_meta( $new_coupon_id, 'usage_limit_per_user', '' );
				update_post_meta( $new_coupon_id, 'product_ids', '' );
				update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
				update_post_meta( $new_coupon_id, 'usage_limit', '1' );
				update_post_meta( $new_coupon_id, 'expiry_date', $coupon->expired_date );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
				update_post_meta( $new_coupon_id, 'minimum_amount', wc_format_decimal($coupon->coupon_info->minimum_purchase_condition, 2) );

			}
		} else { // Nếu có trong Cedele.
			// check phai tu E denred hay k?.
			if ($coupon) {
//			check tren edenred no con hieu luc khong? neu co thi tra ve neu khong thi false.
				return $value;
			}
		}
	}
	 return $value;
};

// add the action
add_filter( 'woocommerce_coupon_code', 'action_woocommerce_applied_coupon', 10, 1 );


//@function send API-issueMemberCoupon to Edenred.
function issue_member_coupon($coupon, $user_id, $token) {
	$date_tmp = new DateTime();
	$bu_content =array(
		"member_code" => $user_id,
		"coupon_serial_no" => $coupon->coupon_serial_no,
		"channel_code" => "EC",
		"issue_time" => $date_tmp->format(DATE_RFC3339_EXTENDED)
	);
	$body = array(
		"bu_content" => $bu_content
	);
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/loyalty/issueMemberCoupon';
	$respond = cal_api('POST', $url, json_encode($body), $token);

	$status = json_decode($respond);
	if (!$respond || $status->code) {
		email_transaction_edenred_service('c9team@vmodev.com', "Applied Coupon", $body, $status);
	}


}

// define the woocommerce_checkout_process callback
function action_woocommerce_checkout_process() {
	$token = get_token();
	$user = wp_get_current_user();
	$edenred_info = get_edenred_id($user->ID);
	$coupons_applied = WC()->cart->get_applied_coupons();
  	$list_coupons = getListCoupon(999999, 1, $edenred_info[0]->user_id, "2010-08-04T10:04:46.709+00:00", "2050-09-11T10:04:46.709+00:00", $token);
	$list_coupons = $list_coupons->results;

	for ($i = 0; $i < count($coupons_applied); $i++) {
		for ($y = 0; $y <count($list_coupons); $y++) {
			if (strtolower($coupons_applied[$i]) == strtolower($list_coupons[$y]->coupon_code)) {

				$exp_date = $list_coupons[$y]->expired_date;
				$effective_date = $list_coupons[$y]->effective_date;
				$now = new DateTime();
				$now = $now->format(DATE_RFC3339_EXTENDED);

				if ($list_coupons[$y]->status !== 1
				|| $now > $exp_date || $now < $effective_date) {
					throw new Exception( "The coupon code invalid, Please remove from cart !");
				} else {
					issue_member_coupon($list_coupons[$y], $edenred_info[0]->user_id, $token);
					break;
				}
			}
		}
	}
}

function all_list_coupons($page_size, $page_num, $user_id, $from, $to, $token, $result) {
	$respond = getListCoupon($page_size, $page_num, $user_id, $from, $to, $token);
	$result = array_merge($result, $respond->results);

	 if ($respond->total_count == $page_size) {
		 $page_num++;
		 all_list_coupons($page_size, $page_num, $user_id, $from, $to, $token, $result);
	 } else {
		return $result;
	 }
}

// add the action
add_action( 'woocommerce_checkout_process', 'action_woocommerce_checkout_process', 10, 1 );

// sync product to Edenred
function createOrUpdateProduct($product_data)
{
  $token = get_token();
  $body = array(
    "bu_content" => $product_data
  );

  $url = EDENRED_DOMAIN . '/openapi/' . EDENRED_VERSION . '/masterdata/createOrUpdateProduct';

  $respond = cal_api('POST', $url, json_encode($body), $token);
  $result = json_decode($respond);

  if ($result->code == 2000) {
    return $result->result;
  } return null;
}

function batchCreateOrUpdateProducts($products_data)
{
  $token = get_token();
  $body = array(
    "bu_content" => $products_data
  );
  $url = EDENRED_DOMAIN . '/openapi/' . EDENRED_VERSION . '/masterdata/batchCreateOrUpdateProducts';
  $respond = cal_api('POST', $url, json_encode($body), $token);

  if ($respond->code == 2000) {
    return $respond->result;
  } return null;
}

// get redemtion event
function getRedemptionEventDetail($data) {
  $token = get_token();
  $body = array(
    "bu_content" => $data
  );
  $url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/redemption/getRedemptionEventDetail';
  $respond = cal_api('POST', $url, json_encode($body), $token);
  $result = json_decode($respond);

  return $result;
}

function createRedemptionOrderForMember($data) {
  $token = get_token();
  $edenred_info = get_edenred_id($data['member_code']);
  $data['member_code'] = $edenred_info[0]->user_id;
  $body = array(
    "bu_content" => $data
  );
  $url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/redemption/createRedemptionOrderForMember';
  $respond = cal_api('POST', $url, json_encode($body), $token);
  $result = json_decode($respond);

  return $result;
}

function queryRedemptionEvent()
{
  $token = get_token();
  $data = array(
    "page_size" => "10",
    "page_no" => "1",
    "from" => "2020-09-04T10:04:46.709+00:00",
    "to" => "2050-08-04T10:04:46.709+00:00",
  );
  $body = array(
    "bu_content" => $data
  );
  $url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/redemption/queryRedemptionEvent';
  $respond = cal_api('POST', $url, json_encode($body), $token);
  $result = json_decode($respond);

  if ($result->code == 2000) {
    return $result->result;
  } return null;
}

function updateMemberInfo($member_code, $data)
{
	$datetime = DateTime::createFromFormat('m-d-Y', $data[4]);
	$birthday_day = $birthday_month = $birthday_year = null;
	if ($datetime) {
		$birthday_day = $datetime->format('d');
		$birthday_month = $datetime->format('m');
		$birthday_year = $datetime->format('Y');
	}
	$now = new DateTime();
	$now = $now->format(DATE_RFC3339_EXTENDED);

//	$phoneNumber = strlen($data[3]) >= 10 ? $data[3] : ('65' . $data[3]);
	$edenred_info = get_edenred_id($member_code);
	$data = array(
		"member_code" => $edenred_info[0]->user_id,
		"first_name" => $data[0],
		"last_name" => $data[1],
		"full_name" => $data[0].' '.$data[1],
		"email" => $data[2],
		// "mobile" => $phoneNumber,
		"account_type_code" => "default",
		"zip_code" => $data[12],
		"register_date" => $now
	);
	if ($birthday_day) {
		$data['birthday_day'] = intval($birthday_day);
	}
	if ($birthday_month) {
		$data['birthday_month'] = intval($birthday_month);
	}
	if ($birthday_year) {
		$data['birthday_year'] = intval($birthday_year);
	}
	$body = array(
		"bu_content" => $data
	);

	$token = get_token();
	$url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/member/updateMemberInfo';
	$respond = cal_api('POST', $url, json_encode($body), $token);
	$result = json_decode($respond);

	if ($result->code == 2000) {
		return $result->result;
	} return null;
}

function createGiftCodeForMember($amount = 0)
{
    $now = new DateTime();
    $now = $now->format(DATE_RFC3339_EXTENDED);

    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $billing_address_1 = get_user_meta( $user_id, 'billing_address_1', true );
    $billing_address_2 = get_user_meta( $user_id, 'billing_address_2', true );
    $billing_phone = get_user_meta( $user_id, 'billing_phone', true );
    $user_email = $user_info->user_email;
	$edenred_info = get_edenred_id($user_id);

	$data = [
      'redemption_event_code' => 'redeem_default_catalog',
      'member_code' => $edenred_info[0]->user_id,
      'channel_code' => 'EC',
      'order_time' => $now,
      'order_details' => [
        ['gift_code' => '1SGD', 'redemption_type' => 1, 'quantity' => $amount]
      ],
      'address_info' => [
        'address' => $billing_address_1 ? $billing_address_1 : $billing_address_2,
        'contact_person' => $user_email,
        'contact' => $billing_phone
      ]
    ];

    $token = get_token();
    $body = array(
        "bu_content" => $data
    );
    $url = EDENRED_DOMAIN.'/openapi/'.EDENRED_VERSION.'/redemption/createRedemptionOrderForMember';
    $respond = cal_api('POST', $url, json_encode($body), $token);
    $result = json_decode($respond);

    return $result;
}
