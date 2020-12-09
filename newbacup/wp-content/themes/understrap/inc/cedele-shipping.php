<?php
/**
 * Add Cedel Shipping method
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !function_exists('get_list_stores')){
	function get_list_stores($post_id)
	{
		if (empty($post_id))
			return [];
		global $wpdb;
		$table_name = $wpdb->prefix . 'store_location_post';
		$result = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id", ARRAY_A);
		return $result;
	}
}
if ( !function_exists('get_list_option_stores')){
	function get_list_option_stores()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'store_location';
		$result = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1 ORDER BY store_name ASC", ARRAY_A);
		return $result;
	}
}
if ( !function_exists('get_list_distance_fees')){
	function get_list_distance_fees()
	{
		global $wpdb;
		$table_distance_cost = $wpdb->prefix . 'woocommerce_shipping_distance_cost';
		$result = $wpdb->get_results("SELECT * FROM $table_distance_cost");
		return $result;
	}
}
function getDistance($lat1, $lat2, $long1, $long2)
{
  $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&key=".$GLOBALS['gmapKey'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);
  $response_a = json_decode($response, true);
  $dist = $response_a['rows'][0]['elements'][0]['distance']['value'];

  return array('distance' => $dist);
}

function cedele_shipping_method(){
	if (!class_exists('Cedele_Shipping_Method'))
	{
		class Cedele_Shipping_Method extends WC_Shipping_Method
		{
			public function __construct()
			{
				$this->id = 'cedele';
				$this->method_title = __('Cedele Shipping', 'understrap');
				$this->method_description = __('Custom Shipping Method for Cedele', 'understrap');
				// Contreis availability
				$this->availability = 'including';
				$this->countries = array('SG');
				$this->init();
				$this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';

				$this->zone_zipcode_map = array(
					'01' => array('ME','MS','MP'),
					'02' => array('ME','MS','MP'),
					'03' => array('ME','MS','MP'),
					'04' => array('ME','MS','MP'),
					'05' => array('ME','MS','MP'),
					'06' => array('ME','MS','MP'),
					'07' => array(),
					'08' => array(),
					'09' => array('SESA'),
					'10' => array(),
					'11' => array('CL'),
					'12' => array('CL'),
					'13' => array('CL'),
					'14' => array('BM','QS'),
					'15' => array('BM','QS'),
					'16' => array('BM','QS'),
					'17' => array(),
					'18' => array(),
					'19' => array(),
					'20' => array(),
					'21' => array(),
					'22' => array('OC','RV'),
					'23' => array('OC','RV'),
					'24' => array('BT','TLN'),
					'25' => array('BT','TLN'),
					'26' => array('BT','TLN'),
					'27' => array('BT','TLN'),
					'28' => array('NV'),
					'29' => array('NV'),
					'30' => array('NV'),
					'31' => array('TPH','SRN'),
					'32' => array('TPH','SRN'),
					'33' => array('TPH','SRN'),
					'34' => array(),
					'35' => array(),
					'36' => array(),
					'37' => array(),
					'38' => array('GL'),
					'39' => array('GL'),
					'40' => array('GL'),
					'41' => array('GL'),
					'42' => array(),
					'43' => array(),
					'44' => array(),
					'45' => array(),
					'46' => array('BD'),
					'47' => array('BD'),
					'48' => array('BD'),
					'49' => array('C', 'CB'),
					'50' => array('C'),
					'51' => array('TP','PR'),
					'52' => array('TP','PR'),
					'53' => array('HG','PGG'),
					'54' => array('HG','PGG'),
					'55' => array('HG','PGG'),
					'56' => array('BS','AMK'),
					'57' => array('BS','AMK'),
					'58' => array(),
					'59' => array(),
					'60' => array('JE','JW','PIN'),
					'61' => array('JE','JW','PIN','JURT'),
					'62' => array('JE','JW','PIN','JUND','JURD','WIS'),
					'63' => array('JE','JW','PIN','TUAS','WWC'),
					'64' => array('JE','JW','PIN'),
					'65' => array('BP','CCK'),
					'66' => array('BP','CCK'),
					'67' => array('BP','CCK'),
					'68' => array('BP','CCK'),
					'69' => array('LCK','TGH','WWC'),
					'70' => array('LCK','TGH'),
					'71' => array('LCK','TGH'),
					'72' => array('WLS'),
					'73' => array('WLS'),
					'74' => array(),
					'75' => array('YSN','SBW'),
					'76' => array('YSN','SBW'),
					'77' => array(),
					'78' => array(),
					'79' => array('SLT'),
					'80' => array('SLT'),
					'81' => array('C'),
					'81' => array('HG','PGG')
				);
			}

			/**
			 Load the settings API
			 */
			function init()
			{
					$this->init_form_fields();
					$this->init_settings();
					add_action('woocommerce_update_options_shipping_' . $this->id, array(
							$this,
							'process_admin_options'
					));
			}

			function init_form_fields()
			{
					$this->form_fields = array(
							'enabled' => array(
									'title' => __('Enable', 'cedele') ,
									'type' => 'checkbox',
									'default' => 'yes'
							) ,
					);
			}

			private function isPeakHour($time, $date)
			{
				global $wpdb;
    		$table_peak_hour = $wpdb->prefix . 'cedele_setting_peak_hour';
				$list_peak_hours = $wpdb->get_results("SELECT * FROM $table_peak_hour", ARRAY_A);
				$times_splitted = explode(" - ", $time);
				$slot_start = strtotime($date.' '.$times_splitted[0]);
				$slot_end = strtotime($date.' '.$times_splitted[1]);
				$isPeak = false;

				foreach ($list_peak_hours as $key => $range) {
					$range_start = strtotime($date.' '.$range['start_time']);
					$range_end = strtotime($date.' '.$range['end_time']);
					if (
            ($slot_start <= $range_start && $range_start <= $slot_end) ||
            ($slot_start <= $range_end && $range_end <= $slot_end) ||
            ($range_start <= $slot_start && $slot_end <= $range_end)
	        ){
	          $isPeak = true;
	        }
				}
				return $isPeak;
			}
			private function isPeakDay($date)
			{
				global $wpdb;
    		$table_occasion = $wpdb->prefix . 'cedele_setting_occasion';
    		$list_occasions = $wpdb->get_results("SELECT * FROM $table_occasion", ARRAY_A);
				$date_timestamp = strtotime($date);
				$isPeak = false;
				foreach ($list_occasions as $key => $range) {
					$range_start = strtotime($range['start_date']);
					$range_end = strtotime($range['end_date']);
					if ($range_start <= $date_timestamp && $date_timestamp <= $range_end) {
						$isPeak = true;
					}
				}
				return $isPeak;
			}

			public function calculate_shipping($package = array())
			{
				if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

				if( !isset($_COOKIE ['customerAddress']) ) {
					$address_session = WC()->session->get('customerAddress');
					if ($address_session) $customerAddress = $address_session;
				}
				else {
					$customerAddress = json_decode(stripslashes($_COOKIE['customerAddress']));
					WC()->session->set('customerAddress', $customerAddress);
				}
				if (!property_exists($customerAddress, 'deliveryAddress')){
					return $package;
				}
				$deliveryAddress = json_decode(stripslashes($customerAddress->deliveryAddress));
				$deliveryType = $customerAddress->deliveryType;
				$shippingTimeFee = $customerAddress->deliveryFee;

				/******** NORMAL DAY *********/
				//free shipping above
				$wc_mini_amount = get_option('wc_mini_amount', 0);
				//minimum order amount for delivery
				$wc_order_amount_below = get_option('wc_order_amount_below', 0);
				//flat rate for shipping
				$wc_apply_shipping_rate = get_option('wc_apply_shipping_rate', 0);
				//flat rate for shipping extra hour
				$wc_order_peak_hour = get_option('wc_order_peak_hour', 0);

				/******** OCCASIONS *********/
				//free shipping above
				$occasions_wc_mini_amount = get_option('occasions_wc_mini_amount', 0);
				//minimum order amount for delivery
				$occasions_wc_order_amount_below = get_option('occasions_wc_order_amount_below', 0);
				//flat rate for shipping
				$occasions_wc_apply_shipping_rate = get_option('occasions_wc_apply_shipping_rate', 0);
				//flat rate for shipping extra hour
				$occasions_wc_order_peak_hour = get_option('occasions_wc_order_peak_hour', 0);
				
				$cart_total = WC()->cart->cart_contents_total;
				WC()->session->set( 'shipping_cost', 0 );
				WC()->session->set( 'surcharge', 0 );
				WC()->session->set('deliverable', true);
				$items = WC()->cart->get_cart();

				if( $deliveryType == 'self-collection' ) {
					//$wc_cart->add_fee( "Self-pickup", 0 , false);
					WC()->session->set( 'assigned_store', $customerAddress->pickupStoreId );
					$rate = array(
						'id' => $this->id,
						'label' => 'Self-pickup',
						'cost' => 0
					);
				} else if ( $deliveryType == 'delivery' && $deliveryAddress) {
					$shipping_cost = 0;
					$willCalDistanceFee = true;

					$deliveryDate = $customerAddress->date;
					$deliveryTime = $customerAddress->time;
					$isPeakDay = $this->isPeakDay($deliveryDate);
					//$isPeakHour = $this->isPeakHour($deliveryTime, $deliveryDate);

					//calculate distance fee here
					//$distance_fee = 0;
					$flat_shipping_fee = 0;
					//check if there's advance product
					$hasAdvanceProduct = false;
					$hasTimeAdvanceProduct = false;
					foreach($items as $key => $item) {
						$_product = wc_get_product( $item['data']->get_id()); 
						$_product_id = $_product->get_parent_id() ? $_product->get_parent_id() : $_product->get_id();
						$productLeadTime = get_post_meta($_product_id, 'product-lead-time-checkbox', true);
						$isAdvancedProduct = $productLeadTime == 'advance';
						if ($isAdvancedProduct){
							$hasAdvanceProduct = true;

              date_default_timezone_set("Asia/Singapore");
              $cot_order = get_option('cot_order');
              $extraLeadDay = 0;
              if ($cot_order){
                  $cot = strtotime($cot_order.':00');
                  $curr_time = time();
                  if ($curr_time >= $cot){
                      $extraLeadDay += 1;
                  }
              }

              $productLeadTimeDays = get_post_meta($_product_id, 'product-lead-time-days', true);
              $address_session = WC()->session->get('customerAddress');
              $totalLeadTimeDays = $productLeadTimeDays + $extraLeadDay;
              if ($address_session->date) {
                  $today = date('d M Y');
                  $availableDate = date_create_from_format('d M Y', $today)->modify('+'.$totalLeadTimeDays.' day');
                  $customerChoosenDate = date_create_from_format('d M Y', $address_session->date);
              }

              if ($availableDate > $customerChoosenDate){
                  $hasTimeAdvanceProduct = true;
              }
						}
					}
					if ($deliveryAddress && $willCalDistanceFee){
						$list_stores_avail_ids_array = array();
						foreach($items as $key => $item) {
							$list_stores = get_list_stores($item['data']->get_id());
							$list_stores_avail = array_filter($list_stores, function($store) {
								return $store['is_in_stock'] == 1;
							});
							$list_stores_avail_ids = array_map(function($store){
								return $store['store_id'];
							}, $list_stores_avail);
							array_push($list_stores_avail_ids_array, $list_stores_avail_ids);
						}
						$intersect_store_ids = call_user_func_array('array_intersect', $list_stores_avail_ids_array);
						//if there is at least one stores that all products are available
						$list_all_stores = get_list_option_stores();			
						$nearest_distance = 0;
						//$distance_fee_configs = get_list_distance_fees();

						//calculate distance from user to central kitchen
						$matched_ck_key = array_search(1, array_column($list_all_stores, 'central_kitchen'));
						$ck = $list_all_stores[$matched_ck_key];
						$distance_ck_to_customer = getDistance($ck['latitude'], $deliveryAddress->lat, $ck['longitude'], $deliveryAddress->long);

						if(!$intersect_store_ids){
							$intersect_store_ids = $list_stores_avail_ids_array[0];
						}

						if ($hasAdvanceProduct){
							$nearest_distance = $distance_ck_to_customer['distance'];
							WC()->session->set( 'assigned_store', $ck['id'] );
						} elseif ($intersect_store_ids && count($intersect_store_ids) > 0){

							//compare cache & new address, if equal does not calculate distance again
							$cached_distance = WC()->session->get( 'distance_cached' );
							if ( $cached_distance &&
								   $cached_distance['address']->lat == $deliveryAddress->lat &&
							 		 $cached_distance['address']->long == $deliveryAddress->long && 
							 		 $cached_distance['deliveryType'] == $deliveryType) {
								$distances_array = $cached_distance['distances_array'];
							} else {
								//calculate distance to find nearest store
								$distances_array = array();
								foreach ($intersect_store_ids as $key => $store_id) {
									$matched_store_key = array_search($store_id, array_column($list_all_stores, 'id'));
									$matched_store = $list_all_stores[$matched_store_key];
									$distance_to_customer = getDistance($matched_store['latitude'], $deliveryAddress->lat, $matched_store['longitude'], $deliveryAddress->long);
									if ($distance_to_customer['distance']){
										array_push($distances_array, array('id' => $matched_store['id'], 'distance' => $distance_to_customer['distance']));
									}
								}
								WC()->session->set( 'distance_cached', array(
									'distances_array' => $distances_array,
									'address' => $deliveryAddress,
									'deliveryType' => $deliveryType
								));
							}
							$nearest_distance = min(array_column($distances_array, 'distance'));

							//if nearest store is nearer than central kitchen, assign to the nearest store
							if ($nearest_distance <= $distance_ck_to_customer['distance']){
								$nearest_store_key = array_search($nearest_distance, array_column($distances_array, 'distance'));
								$nearest_store = $distances_array[$nearest_store_key];
								WC()->session->set( 'assigned_store', $nearest_store['id'] );
							} else { //else assign it to central kitchen
								$nearest_distance = $distance_ck_to_customer['distance'];
								WC()->session->set( 'assigned_store', $ck['id'] );
							}
						} else { //else assign it to central kitchen
							$nearest_distance = $distance_ck_to_customer['distance'];
							WC()->session->set( 'assigned_store', $ck['id'] );
						}
						// foreach ($distance_fee_configs as $key => $config) {
						// 	if ($config->distance_from * 1000 <= $nearest_distance && $nearest_distance < $config->distance_to * 1000 ) {
						// 		$distance_fee = $config->distance_cost*1;
						// 	}
						// }
						$flat_shipping_fee += $isPeakDay ? $occasions_wc_apply_shipping_rate : $wc_apply_shipping_rate;
						// if ($isPeakHour) {
						// 	$flat_shipping_fee += $isPeakDay ? $occasions_wc_order_peak_hour : $wc_order_peak_hour;
						// }
					}

					$additional_fee = 0;
					//add shipping time fee by user selection of time slot
					$additional_fee += $shippingTimeFee;
					//add shipping fee by shipping class
					$shipping_classes = WC()->shipping->get_shipping_classes();
					$shipping_classes_in_cart = array();
					foreach($items as $key => $item) {
						$_product = wc_get_product( $item['data']->get_id()); 
						$shipping_class_id = $_product->get_shipping_class_id(); 
						if ($shipping_class_id){
							array_push($shipping_classes_in_cart, $shipping_class_id);
						}
					}
					$shipping_classes_cost = array();
					foreach (array_unique($shipping_classes_in_cart) as $key => $class) {
						$shipping_class_key = array_search($class, array_column($shipping_classes, 'term_id'));
						array_push($shipping_classes_cost, $shipping_classes[$shipping_class_key]->fee ? $shipping_classes[$shipping_class_key]->fee : 0);
					};
					$additional_fee += max($shipping_classes_cost);
					//get toll fee
					$zone_toll_fee = 0;
					if ($deliveryAddress->zipcode){
						$all_zones = WC_Shipping_Zones::get_zones(); 
						$states = WC()->countries->get_states( 'SG' );

						$zone_configs = array_map(function($zone){
							return array(
								'id' => $zone['id'],
								'zone_name' => $zone['zone_name'],
								'zone_toll' => $zone['zone_toll'],
								'zone_extra_fee' => $zone['zone_extra_fee'],
								'zone_locations' => array_map(function($location){ return str_replace('SG:', '', $location->code); }, $zone['zone_locations'])
							);
						}, $all_zones);
						$zipcode_sector = substr($deliveryAddress->zipcode, 0, -4);
						$matched_states = $this->zone_zipcode_map[$zipcode_sector];
						foreach ($zone_configs as $key => $zone_config) {
							$matched_zone = array_intersect($zone_config['zone_locations'], $matched_states);
							if (count($matched_zone) > 0){
								$zone_toll_fee = $zone_config['zone_toll'];
								$zone_extra_fee = $zone_config['zone_extra_fee'];
							}
						}
						$additional_fee += $zone_toll_fee;
						if ( $isPeakDay ) {
						 $additional_fee += $zone_extra_fee;
						}
					}

					$surcharge = 0;
					//calculate total shipping cost
					$min_amount_for_shipping = $isPeakDay ? $occasions_wc_order_amount_below : $wc_order_amount_below;
					$min_amount_for_free_shipping = $isPeakDay ? $occasions_wc_mini_amount : $wc_mini_amount;
					if ($cart_total >= $min_amount_for_free_shipping){
						//only additional fee
						$surcharge += $additional_fee;
						WC()->session->set('cart_valid', true);
					} else {
						//distance fee + additional fee
						//$shipping_cost += $distance_fee;
						$shipping_cost += $flat_shipping_fee;
						$surcharge += $additional_fee;
						WC()->session->set('cart_valid', true);
					}

					if($hasTimeAdvanceProduct) {
              WC()->session->set('cart_valid', false);
          }

          if ($cart_total < $min_amount_for_shipping){
						// //flat shipping rate + additional fee
						// $shipping_cost += $wc_apply_shipping_rate;
						// $surcharge += $additional_fee;
						// not allow delivery when amount < config in admin
						WC()->session->set('cart_valid', false);
						WC()->session->set('deliverable', false);
					} else {
						WC()->session->set('deliverable', true);
					}

					$rate = array(
						'id' => $this->id.'_shipping',
						//'label' => 'Delivery'.' (fee: '. $flat_shipping_fee .')',
						'label' => 'Delivery',
						'cost' => $shipping_cost 
					);
					WC()->session->set('surcharge', $surcharge);
				} else if ( $deliveryType == 'delivery' && !$deliveryAddress ) {
					$rate = array(
						'id' => $this->id,
						'label' => 'Delivery',
						'cost' => 0
					);
				}
				$this->add_rate($rate);

				return $package;
			}
		}
	}
}
add_action('woocommerce_shipping_init', 'cedele_shipping_method');

function add_cedele_shipping_method($methods)
{
  $methods[] = 'Cedele_Shipping_Method';
  return $methods;
}
add_filter('woocommerce_shipping_methods', 'add_cedele_shipping_method');

add_action( 'woocommerce_cart_totals_after_shipping', 'custom_checkout_jquery_script', 10 );
function custom_checkout_jquery_script() {
	?>
	<script type="text/javascript">
		(function($){
			var customerAddress = localStorage.getItem('customerAddress') || '{}';
			Cookies.set('customerAddress', customerAddress, { expires: 7, path: '/' });
			$('body').trigger('update_order_review');
		})(jQuery);
	</script>
	<?php
}