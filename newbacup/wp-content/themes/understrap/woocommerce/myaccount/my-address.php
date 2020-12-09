<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$customer_id = get_current_user_id();
$userMeta = get_user_meta($customer_id);
$isSameAddress = get_user_meta($customer_id, 'shipping_same_address', true);
$countries = new WC_Countries();
if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'shipping' => __( 'Shipping address', 'understrap' ),
			'billing'  => __( 'Billing address', 'understrap' ),
		),
		$customer_id
	);
	$billing_fields = $countries->get_address_fields( '', 'billing_' );
	$shipping_fields = $countries->get_address_fields( '', 'shipping_' );
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'understrap' ),
		),
		$customer_id
	);
	$billing_fields = $countries->get_address_fields( '', 'billing_' );
}
$billing_fields["billing_state"]["country"] = 'SG';
$shipping_fields["shipping_state"]["country"] = 'SG';

$oldcol = 1;
$col    = 1;
$get_addresses = array_reverse($get_addresses);
?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="u-columns woocommerce-Addresses col2-set addresses">
<?php endif; ?>

<?php foreach ( $get_addresses as $name => $address_title ) : ?>

	<div class="u-column woocommerce-Address form-<?php echo $name; ?>">
		<header class="woocommerce-Address-title">
			<h4 class="cdl-heading"><?php echo esc_html( $address_title ); ?></h4>
		</header>

		<?php 
			$load_address = $name; 
			$fields = $load_address == 'billing' ? $billing_fields : $shipping_fields;
		?>
		<form action="/my-account/edit-address/<?php echo $load_address; ?>/" class="edit-account" method="post">

			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<?php foreach ( $fields as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $userMeta[$key][0] ); ?>

			<?php endforeach; ?>

			<?php
				if ( $name == 'shipping' ) {
			?>
				<div class=" clearfix custom-control custom-checkbox">
	        <input type="checkbox" id="same-address" class="custom-control-input" checked>
	        <label class="custom-control-label" for="same-address">Billing address is the same as Delivery address</label>
	      </div>
			<?php		
				} else if ( $name == 'billing' ) {
			?>
	      <input type="hidden" id="same-address-billing" name="shipping_same_address" class="custom-control-input" checked>
			<?php		
				}
			?>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<div class="text-right clearfix pt-3">
					<button type="button" class="btn btn-light heading-font btn-lg btn-cancel text-uppercase reset-address" data-type="<?php echo $load_address;?>">Reset</button>
					<input type="submit" class="btn btn-primary heading-font btn-lg text-uppercase ml-3" name="save_address" value="<?php esc_attr_e( 'SAVE CHANGES', 'woocommerce' ); ?>" />
					<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
					<input type="hidden" name="action" value="edit_address" />
			</div>

		</form>

	</div>

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
	<?php
endif;
?>

<script type="text/javascript">
		var apikey = '<?php echo $GLOBALS['gmapKey'];?>';
		var autocomplete;
		function extractFromAdress(components, type){
				for (var i=0; i<components.length; i++)
						for (var j=0; j<components[i].types.length; j++)
								if (components[i].types[j]==type) return components[i].long_name;
				return "";
		}
		function initAutocomplete() {
				var autocomplete1 = new google.maps.places.Autocomplete(
					(document.getElementById('billing_address_1')),
					{ types: ['geocode'], componentRestrictions: {country: 'sg'}, language: ['en'] });
				var autocomplete2 = new google.maps.places.Autocomplete(
					(document.getElementById('shipping_address_1')),
					{ types: ['geocode'], componentRestrictions: {country: 'sg'}, language: ['en'] });
				function addGoogleEvent(instance, type){
					google.maps.event.addListener(instance, 'place_changed', function() {
							var place = instance.getPlace();
							var postCode = extractFromAdress(place.address_components, 'postal_code');
							var streetNumber = extractFromAdress(place.address_components, 'street_number');
							var route = extractFromAdress(place.address_components, 'route');
							var state = extractFromAdress(place.address_components, 'neighborhood');
							var address = {
									formatted_address: place.formatted_address,
									lat: place.geometry.location.lat(),
									long: place.geometry.location.lng(),
									zipcode: postCode,
									streetNumber: streetNumber,
									route: route,
									state: state
							}
							var inputState = jQuery('#'+type+'_state');
							var inputPostCode = jQuery('#'+type+'_postcode');
							inputPostCode.val(postCode);
							inputState.find('option').each(function() {
								jQuery(this).prop('selected', false);
								if (jQuery(this).text().toLowerCase() == state.toLowerCase()){
									jQuery(this).prop('selected', true);
								}
							});
							inputState.trigger('change');
					});
				};
				addGoogleEvent(autocomplete1, 'billing');
				addGoogleEvent(autocomplete2, 'shipping');
		}
		jQuery(document).ready(function(){
			var defaultAddress = {
				'billing': {
					address_1: jQuery('#billing_address_1').val(),
					address_2: jQuery('#billing_address_2').val(),
					state: jQuery('#billing_state').val(),
					postcode: jQuery('#billing_postcode').val(),
				},
				'shipping': {
					address_1: jQuery('#shipping_address_1').val(),
					address_2: jQuery('#shipping_address_2').val(),
					state: jQuery('#shipping_state').val(),
					postcode: jQuery('#shipping_postcode').val(),
				}
			};

			var isSameAddress = <?php echo $isSameAddress ? $isSameAddress : '0';?>;
			jQuery('#same-address').prop('checked', isSameAddress == '1');
			jQuery('#same-address').on('change', function(){
				var isChecked = jQuery(this).is(':checked');
				if (isChecked) {
					jQuery('.form-billing').hide();
					jQuery('#shipping_same_address').prop('checked', isChecked);
					jQuery('#shipping_same_address').prop('value', '1');
					jQuery('#same-address-billing').prop('value', '1');
				} else {
					jQuery('.form-billing').show();
					jQuery('#shipping_same_address').prop('checked', isChecked);
					jQuery('#shipping_same_address').prop('value', '0');
					jQuery('#same-address-billing').prop('value', '0');
				}
			});
			setTimeout(function(){
				jQuery('#same-address').trigger('change');
			});

			jQuery('.reset-address').on('click', function(){
				var type = jQuery(this).attr('data-type');
				jQuery('#'+type+'_address_1').val(defaultAddress[type].address_1);
				jQuery('#'+type+'_address_2').val(defaultAddress[type].address_2);
				jQuery('#'+type+'_state').val(defaultAddress[type].state);
				jQuery('#'+type+'_postcode').val(defaultAddress[type].postcode);
				jQuery('#'+type+'_state').trigger('change');
			});
			jQuery('#billing_address_1').prop('required', true);
			jQuery('#billing_state').prop('required', true);
			jQuery('#billing_postcode').prop('required', true);
			jQuery('#shipping_address_1').prop('required', true);
			jQuery('#shipping_state').prop('required', true);
			jQuery('#shipping_postcode').prop('required', true);
			jQuery('.edit-account').each(function() {
				jQuery(this).validate();
			});
		});
</script>