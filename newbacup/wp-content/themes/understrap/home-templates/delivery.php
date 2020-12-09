<?php
    global $wpdb;
    $table_peak_hour = $wpdb->prefix . 'cedele_setting_peak_hour';
    $table_occasion = $wpdb->prefix . 'cedele_setting_occasion';

    $stores = get_store_locations();
    $placeholder = get_option('cdls_placeholder', 'Free deliveries for order above 80$');
    $central_kitchen = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}store_location WHERE status = 1 AND central_kitchen = 1");
    $holidays = $wpdb->get_results("SELECT start_date, end_date FROM `{$wpdb->prefix}store_holiday` WHERE id in (SELECT holiday_id FROM wp_store_holiday_related WHERE store_id = $central_kitchen->id)");
    $date_working = $wpdb->get_results("SELECT * from {$wpdb->prefix}store_working_time WHERE store_id = $central_kitchen->id");

    $deliveryStartTime = get_option('delivery_start_time');
    $deliverySlotDuration = get_option('delivery_slot_duration');
    $deliveryGapTime = get_option('delivery_gap_time');
    $deliveryCutOffTime = get_option('cot_delivery');

    $pickupStartTime = get_option('pickup_start_time');
    $pickupSlotDuration = get_option('pickup_slot_duration');
    $pickupCutOffTime = get_option('cot_pickup');

    $list_peak_hours = $wpdb->get_results("SELECT * FROM $table_peak_hour", ARRAY_A);
    $list_occasions = $wpdb->get_results("SELECT * FROM $table_occasion", ARRAY_A);
    $extraCost = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}woocommerce_shipping_time_cost`", ARRAY_A);
    $wc_order_peak_hour = get_option('wc_order_peak_hour', 0);
    $occasions_wc_order_peak_hour = get_option('occasions_wc_order_peak_hour', 0);

    //get user address
    $customer_id = get_current_user_id();
    $customer_address_1 = addslashes(get_user_meta( $customer_id, 'shipping_address_1', true ));
    $customer_address_2 = addslashes(get_user_meta( $customer_id, 'shipping_address_2', true ));
    $customer_state = addslashes(get_user_meta( $customer_id, 'shipping_state', true ));
    $customer_postcode = get_user_meta( $customer_id, 'shipping_postcode', true );
    if ($customer_state){
        $country = get_user_meta( $customer_id, 'shipping_country', true ); ;
        $customer_state_name = WC()->countries->get_states( $country )[$customer_state];
    }

    $productLeadTime = 0;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_productLeadTime = get_post_meta($cart_item['product_id'], 'product-lead-time-minutes', true);
        if ($_productLeadTime > $productLeadTime) {
            $productLeadTime = $_productLeadTime;
        }
    }

    $deliverable = WC()->session->get('deliverable');
    $wc_order_amount_below = get_option('wc_order_amount_below', 0);
    $occasions_wc_order_amount_below = get_option('occasions_wc_order_amount_below', 0);
?>
<div class="delivery-wrapper">
    <div class="delivery">
        <div class="delivery-form d-flex">
            <svg class="icon icon-Map"><use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Map"></use></svg>
            <label class="ml-3">How would you like to receive your order?</label>
        </div>
        <div class="select-delivery d-flex mt-3">
            <div class="d-flex align-items-center delivery-method">
                <input name="select-delivery" type="radio" value="delivery" checked/>
                <label class="ml-2">Delivery</label>
            </div>
            <div class="d-flex align-items-center self-collection-method">
                <input name="select-delivery" type="radio" value="self-collection"/>
                <label class="ml-2">Self Collection</label>
            </div>
        </div>
        <div id="delivery-options">
            <div class="typeahead__container mt-2">
                <div class="typeahead__field">
                    <div class="typeahead__query">
                        <input class="js-typeahead-user_v1" name="user_v1[query]" placeholder="<?php echo $placeholder ?>" autocomplete="off" id="delivery-input-search">
                    </div>
                </div>
            </div>

            <!-- <input type="text" placeholder="<?php echo $placeholder ?>" class="mt-2 delivery-input-search" id="delivery-input-search" readonly/> -->
            <input type="text" placeholder="Building Name, Unit Number. For example: Frasers Tower, #01-03" class="mt-2 delivery-input-search" id="delivery-address2"/>
        </div>
        <div id="store-selector" class="mt-2">
            <select class="store-selector" id="store-selector-input"></select>
        </div>
        <div class="delivery-error text-left d-none">
            <p class="text-danger my-3">
                Minimum order for delivery: $<?php echo $wc_order_amount_below; ?>.<br/> 
                Minimum order for delivery on special occasions: $<?php echo $occasions_wc_order_amount_below; ?>.
            </p>
        </div>
        <div class="delivery-choose-time mt-3">
            <div class="d-flex">
                <svg class="icon icon-Clock"><use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Clock"></use></svg>
                <label class="ml-2 delivery-label">Choose a preferred date and time</label>
                <label class="ml-2 pickup-label">Choose a pickup time</label>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="delivery-date-start cedele-datepicker">
                        <input type="text" placeholder="Select Date" name="delivery-time-select-start" id="delivery-time-select-start" class="delivery-time-select-start" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="delivery-time">
                        <select name="select-time" id="select-time-picker">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions text-right mt-3">
            <button class="btn btn-light heading-font btn-cancel text-uppercase">Cancel</button>
            <button class="btn btn-primary heading-font btn-delivery-apply text-uppercase ml-2">Apply</button>
        </div>

        <input type="hidden" class="totalCartPrice" value="<?= WC()->cart->get_cart_contents_total() ?>">
    </div>
</div>

<script type="text/javascript">
    //template for reuse later
    var template = jQuery('.delivery-wrapper').html();
    var savedValue = getUserAddress();

    var SERVER_DATE_FORMAT = 'DD MM YYYY';
    var SERVER_DATETIME_FORMAT = 'DD MM YYYY HH:mm:ss'
    var SERVER_TIME_FORMAT = 'HH:mm:ss';
    var CLIENT_TIME_FORMAT = 'HH:mm';
    var CLIENT_DATE_FORMAT = 'DD MMM YYYY';

    var stores = <?php echo $stores ? json_encode($stores) : '[]' ?>;
    var dateWorkingEncode = <?php echo $date_working ? json_encode($date_working) : '[]' ?>;
    var holidaysEncode = <?php echo $holidays ? json_encode($holidays) : '[]' ?>;
    var deliveryStartTime = <?php echo $deliveryStartTime ? $deliveryStartTime : 0 ?>;
    var deliverySlotDuration = <?php echo $deliverySlotDuration ? $deliverySlotDuration : 0 ?>;
    var deliveryGapTime = <?php echo $deliveryGapTime ? $deliveryGapTime : 0 ?>;
    var deliveryCutOffTime = <?php echo $deliveryCutOffTime ? $deliveryCutOffTime : 0 ?>;
    var pickupStartTime = <?php echo $pickupStartTime ? $pickupStartTime : 0 ?>;
    var pickupSlotDuration = <?php echo $pickupSlotDuration ? $pickupSlotDuration : 0 ?>;
    var pickupCutOffTime = <?php echo $pickupCutOffTime ? $pickupCutOffTime : 0 ?>;
    var listPeakHours = <?php echo $list_peak_hours ? json_encode($list_peak_hours) : '[]' ?>;
    var listOccasions = <?php echo $list_occasions ? json_encode($list_occasions) : '[]' ?>;
    var extraCost = <?php echo $extraCost ? json_encode($extraCost) : '{}' ?>;
    var extraCostPeakHour = <?php echo $wc_order_peak_hour ? json_encode($wc_order_peak_hour) : 0 ?>;
    var extraCostPeakHourOccasion = <?php echo $occasions_wc_order_peak_hour ? json_encode($occasions_wc_order_peak_hour) : 0 ?>;
    var productLeadTime = <?php echo $productLeadTime; ?>;
    var postDataCart = <?php echo json_encode($_POST); ?>;
    var deliverable = <?php echo $deliverable ? 'true' : 'false'; ?>;
    var minOrderAmount = <?php echo $wc_order_amount_below ? $wc_order_amount_below : 0; ?>;
    var minOrderAmountOccasion = <?php echo $occasions_wc_order_amount_below ? $occasions_wc_order_amount_below : 0; ?>;

    //get user address
    var customer_id = '<?php echo $customer_id;?>';
    var customer_address_1 = '<?php echo $customer_address_1;?>';
    var customer_address_2 = '<?php echo $customer_address_2;?>';
    var customer_state_name = '<?php echo $customer_state_name;?>';
    var customer_postcode = '<?php echo $customer_postcode;?>';

    var parseDate = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    ];
    var parseDateByKey = {
        'monday': 1,
        'tuesday': 2,
        'wednesday': 3,
        'thursday': 4,
        'friday': 5,
        'saturday': 6,
        'sunday': 0,
    };

    var apikey = '<?php echo $GLOBALS['gmapKey'];?>';
    // var autocomplete;
    // function extractFromAdress(components, type){
    //     for (var i=0; i<components.length; i++)
    //         for (var j=0; j<components[i].types.length; j++)
    //             if (components[i].types[j]==type) return components[i].long_name;
    //     return "";
    // }
    // function autocompleteHandler(){
    //    autocomplete = new google.maps.places.Autocomplete(
    //       (document.getElementById('delivery-input-search')),
    //       { types: ['geocode'], componentRestrictions: {country: 'sg'}, language: ['en'] });
    //     google.maps.event.addListener(autocomplete, 'place_changed', function() {
    //         var place = autocomplete.getPlace();
    //         var postCode = extractFromAdress(place.address_components, 'postal_code');
    //         var streetNumber = extractFromAdress(place.address_components, 'street_number');
    //         var route = extractFromAdress(place.address_components, 'route');
    //         var state = extractFromAdress(place.address_components, 'neighborhood');
    //         var address = {
    //             formatted_address: place.formatted_address,
    //             lat: place.geometry.location.lat(),
    //             long: place.geometry.location.lng(),
    //             zipcode: postCode,
    //             streetNumber: streetNumber,
    //             route: route,
    //             state: state
    //         }
    //         saveUserAddress('deliveryAddress', JSON.stringify(address));
    //     });
    // }
    // function initAutocomplete() {
    //     autocompleteHandler();
    // }
    function getUserAddress(){
        return JSON.parse(localStorage.getItem('customerAddress') || '{}');
    }
    function saveUserAddress(key, value, type){
        savedValue[key] = value;
    }
    function saveUserAddressToLocalStorage(){
        localStorage.setItem('customerAddress', JSON.stringify(savedValue));
    }

    // get holiday days to disable them in calendar
    function getHolidays(holidays) {
        if (!holidays.length){
            return [];
        }
        var dateHolidays = [];
        holidays.forEach(function(h, index) {
            var dates = []
            var endDate = new Date(h.end_date);
            //to avoid modifying the original date
            var theDate = new Date(h.start_date);
            while (theDate < endDate) {
                dates = [...dates, new Date(theDate)]
                theDate.setDate(theDate.getDate() + 1)
            }
            dates = [...dates, endDate];
            dateHolidays = dateHolidays.concat(dates);
        });
        return dateHolidays;
    }

    // get not working days to disable them in calendar
    function getOffDays(dateWorking){
        if (!dateWorking.length){
            return [];
        }
        var dateOfWeekTodayString = new Date().toLocaleString('en-us', {  weekday: 'long' }).toLowerCase();
        var dateTodayWorking = dateWorking.find(d => d.working_day == dateOfWeekTodayString);
        var dateWorkingArr = dateWorking.map(item => item['working_day']);
        var different = parseDate.filter(x => !dateWorkingArr.includes(x));
        var keyDateDisable = [];
        if (different.length) {
            different.forEach(function(item, index) {
                keyDateDisable.push(parseDateByKey[item]);
            });
        };
        return keyDateDisable;
    }

    var datePickerInit = false;
    function registerDatePicker(dateWorking, holidays, defaultDate, defaultTime) {
        datePickerInit = true;
        var dateData = jQuery('#delivery-date-picker').val() || new Date();
        jQuery('#delivery-time-select-start').datetimepicker({
            format: 'ddd DD MMM',
            daysOfWeekDisabled: getOffDays(dateWorking),
            disabledDates: getHolidays(holidays),
            minDate: moment().startOf('d'),
            defaultDate: defaultDate ? defaultDate : false,
        });
        if (jQuery('#delivery-time-select-start').val()){
            saveUserAddress('date', moment(jQuery('#delivery-time-select-start').val(), 'ddd DD MMM').format(CLIENT_DATE_FORMAT));
            generateTimeOptions(moment(jQuery('#delivery-time-select-start').val(), 'ddd DD MMM'), dateWorking, defaultTime);
        }
        jQuery('#delivery-time-select-start').on('dp.change', function(e){
            generateTimeOptions(e.date, dateWorking);
            saveUserAddress('date', moment(e.date).format(CLIENT_DATE_FORMAT));
        });
    }

    function unregisterDatePicker(){
        if (datePickerInit){
            jQuery('#delivery-time-select-start').datetimepicker('destroy');
            jQuery('#delivery-time-select-start').off();
        }
        datePickerInit = false;
    }

    function generateStores(stores){
        var storeOptions = [];
        stores.forEach(function(store) {
            var address = store.store_name;
            var fullAddress = store.number_house + ' ' + store.street_name + ', ' + store.floor_unit + ' ' + store.building + ', Singapore, ' + store.zipcode;
            if(store.central_kitchen == 0 ){
				storeOptions.push({
					id: store.id,
					text: address,
					fullAddress: fullAddress,
					storeName: store.store_name,
				});
			}
        })
        return storeOptions;
    }

    function removePastTimeSlots(slots){
        var _slots = [];
        slots.forEach(function(slot, index){
            if ( slot.start.isSameOrAfter(moment().add(parseInt(productLeadTime), 'minutes')) ) {
                _slots.push(slot);
            }
        })
        return _slots;
    }

    var slots = [];
    function generateTimeOptions(date, dateWorking, defaultTime){
        if (!date){
            return;
        }
        var selectedDay = moment(date).format('dddd').toLowerCase();
        var selectedDayConfig = dateWorking.find( function(d) {
            return d.working_day == selectedDay;
        });
        var type = jQuery('input[name="select-delivery"]:checked').val();
        var startWorkingTime = moment(date).format(SERVER_DATE_FORMAT) + ' ' + (selectedDayConfig.start_working_time || '00:00:00');
        var endWorkingTime = moment(date).format(SERVER_DATE_FORMAT) + ' ' + (selectedDayConfig.end_working_time || '24:00:00');
        slots = [];
        if (type == 'delivery'){
            //add delivery start time to opening time
            var startDeliveryTime = moment(startWorkingTime, SERVER_DATETIME_FORMAT).add(parseInt(deliveryStartTime), 'm');
            //add delivery cutoff time to closing time
            var endDeliveryTime = moment(endWorkingTime, SERVER_DATETIME_FORMAT).subtract(parseInt(deliveryCutOffTime), 'm');

            var slotStartTime = startDeliveryTime.clone();
            var slotEndTime = moment(slotStartTime.clone()).add(parseInt(deliverySlotDuration), 'm');
            while (!moment(slotEndTime).isAfter(endDeliveryTime)){
                var slot = addExtraCost({
                    start: slotStartTime,
                    end: slotEndTime,
                    text: slotStartTime.format(CLIENT_TIME_FORMAT) + ' - ' + slotEndTime.format(CLIENT_TIME_FORMAT),
                    selectedDate: moment(date),
                    cost: 0,
                    baseCost: parseFloat(extraCost.normal_hour_cost),
                    id: slots.length
                });
                slots.push(slot);
                slotStartTime = slotStartTime.clone().add(parseInt(deliveryGapTime), 'm');
                slotEndTime = slotStartTime.clone().add(parseInt(deliverySlotDuration), 'm');
            }
            slots = removePastTimeSlots(slots);
        } else {
            //add pickup start time to opening time
            var startPickupTime = moment(startWorkingTime, SERVER_DATETIME_FORMAT).add(parseInt(pickupStartTime), 'm');
            //add pickup cutoff time to closing time
            var endPickupTime = moment(endWorkingTime, SERVER_DATETIME_FORMAT).subtract(parseInt(pickupCutOffTime), 'm');

            var slotStartTime = startPickupTime.clone();
            var slotEndTime = moment(slotStartTime.clone()).add(parseInt(pickupSlotDuration), 'm');
            while (!moment(slotEndTime).isAfter(endPickupTime)){
                var slot = {
                    start: slotStartTime,
                    end: slotEndTime,
                    text: slotStartTime.format(CLIENT_TIME_FORMAT) + ' - ' + slotEndTime.format(CLIENT_TIME_FORMAT),
                    selectedDate: moment(date),
                    cost: 0,
                    baseCost: parseFloat(extraCost.normal_hour_cost),
                    id: slots.length
                };
                slots.push(slot);
                slotStartTime = slotEndTime.clone();
                slotEndTime = slotStartTime.clone().add(parseInt(pickupSlotDuration), 'm');
            }
            slots = removePastTimeSlots(slots);
        }
        if (jQuery('#select-time-picker').hasClass('select2-hidden-accessible')){
            jQuery('#select-time-picker').select2('destroy');
        }
        jQuery('#select-time-picker').val(null);
        jQuery('#select-time-picker').html('');
        jQuery('#select-time-picker').select2({
            theme: 'bootstrap4',
            data: slots,
            minimumResultsForSearch: Infinity,
            templateResult: formatOption,
            escapeMarkup: function(markup) {
                return markup;
            }
        });
        if (slots.length){
            var defaultValue;
            if (defaultTime){
                var selectedTime = _.findIndex(slots, {text: defaultTime});
                if (selectedTime >= 0){
                    defaultValue = slots[selectedTime];
                    jQuery('#select-time-picker').val(defaultValue.id);
                    jQuery('#select-time-picker').trigger('change');
                } else {
                    defaultValue = slots[0];
                }
            } else {
                defaultValue = slots[0];
            }
            jQuery('#select-time-picker').trigger({
                type: 'select2:select',
                params: {
                    data: defaultValue
                }
            });
        } else {
            saveUserAddress('deliveryFee', 0);
            saveUserAddress('time', null);
        }
    };

    function addExtraCost(slot){
        if (isOccasion(slot)){
            slot.isOccasion = true;
            // slot.cost += parseFloat(extraCost.occasion_cost);
        } 
        // else if (isWeekend(slot)){
        //     slot.isWeekend = true;
        //     slot.cost += parseFloat(extraCost.weekend_cost);
        // }
        if (isPeakHour(slot)){
            slot.isPeakHour = true;
            if (isOccasion(slot)){
                slot.isOccasion = true;
                slot.cost += parseFloat(extraCostPeakHourOccasion);
            } else {
                slot.cost += parseFloat(extraCostPeakHour); 
           }
        }
        return slot;
    }

    function isWeekend(slot){
        var day = moment(slot.start).day();
        return day == parseDateByKey.sunday || day == parseDateByKey.saturday;
    };

    function isPeakHour(slot){
        var isPeak = false;
        listPeakHours.forEach(function(range){
            var peakHourStart = moment(moment(slot.selectedDate).format(SERVER_DATE_FORMAT) + ' ' + range.start_time, SERVER_DATETIME_FORMAT);
            var peakHourEnd = moment(moment(slot.selectedDate).format(SERVER_DATE_FORMAT) + ' ' + range.end_time, SERVER_DATETIME_FORMAT);
            if (
                (moment(slot.start).isSameOrBefore(peakHourStart) && moment(slot.end).isAfter(peakHourStart)) ||
                (moment(slot.start).isBefore(peakHourEnd) && moment(slot.end).isSameOrAfter(peakHourEnd)) ||
                (moment(slot.start).isSameOrAfter(peakHourStart) && moment(slot.end).isSameOrBefore(peakHourEnd))
            ){
                isPeak = true;
            }
        });
        return isPeak;
    };

    function isOccasion(slot){
        var isOccasionDay = false;
        listOccasions.forEach(function(range){
            var startDay = moment(range.start_date).startOf('day');
            var endDay = moment(range.end_date).endOf('day');
            if (moment(slot.start).isBetween(startDay, endDay)){
                isOccasionDay = true;
            }
        });
        return isOccasionDay;
    };

    function formatOption(state){
        var $state = jQuery(
            '<span><span class="text"></span></span>'
        );
        $state.find('.text').text(state.text);
        if (state.cost){
            $state.append('<span class="extra-fee"></span>');
            $state.find('.extra-fee').text('+$' + state.cost + ' delivery surcharge');
            $state.addClass('has-extra');
        }
        return $state;
    };

    jQuery(document).on('saveUserAddress', function(){
        saveUserAddressToLocalStorage();
        jQuery('.delivery-error').addClass('d-none');
        jQuery('.btn-delivery-apply').attr('disabled', false);
    });

    jQuery(document).on('onUserAddressModalClose', function(){
        jQuery('.delivery-wrapper').html(template);
        //autocompleteHandler();
        initAddressSelector();
    });

    function validateDelivery(slot) {
        var cartTotal = jQuery('.totalCartPrice').val();
        var isDeliverable = slot.isOccasion ? parseFloat(cartTotal) >= parseFloat(minOrderAmountOccasion) : parseFloat(cartTotal) >= parseFloat(minOrderAmount);
        var tab = jQuery('input[name="select-delivery"]:checked').val();
        if (!isDeliverable && tab == 'delivery') {
            jQuery('.delivery-error').removeClass('d-none');
            jQuery('.btn-delivery-apply').attr('disabled', true);
        } else {
            jQuery('.delivery-error').addClass('d-none');
            jQuery('.btn-delivery-apply').attr('disabled', false);
        }
    }

    jQuery(document).ready(function($) {
        $( document.body ).on( 'updated_cart_totals', function(){
            $.post(home_ajax_vars.ajaxurl, {'action': 'get_total_cart_ajax'}, function (e) {
                var obj = JSON.parse(e);
                if(obj.code == 1) {
                    var cartTotal = obj.value;
                    jQuery('.totalCartPrice').val(cartTotal);
                    var isDeliverable = slots.isOccasion ? parseFloat(cartTotal) >= parseFloat(minOrderAmountOccasion) : parseFloat(cartTotal) >= parseFloat(minOrderAmount);
                    if (!isDeliverable) {
                        jQuery('.delivery-error').removeClass('d-none');
                        jQuery('.btn-delivery-apply').attr('disabled', true);
                    } else {
                        jQuery('.delivery-error').addClass('d-none');
                        jQuery('.btn-delivery-apply').attr('disabled', false);
                    }
                }
            });
        });
    });


    jQuery(document).on('onUserAddressModalShow', function(){
        //validateDelivery();
        setTimeout(function(){
            var selectedTime = jQuery('#select-time-picker').val();
            if (selectedTime) {
                var selectedSlot = _.findIndex(slots, {id: parseInt(jQuery('#select-time-picker').val())});
                if (selectedSlot >= 0) {
                    validateDelivery(slots[selectedSlot]);
                }
            }
        });
    });

    function initAddressSelector(){
        jQuery('input[name="select-delivery"]').on('change', function(e, dateTime){
            var tab = jQuery('input[name="select-delivery"]:checked').val();
            jQuery('#select-time-picker').val('');
            saveUserAddress('deliveryType', tab);
            jQuery('.delivery-error').addClass('d-none');
            jQuery('.btn-delivery-apply').attr('disabled', false);
            if (tab == 'delivery'){
                saveUserAddress('pickupStoreId', null);
                jQuery('#delivery-options').show();
                jQuery('#store-selector').hide();
                jQuery('.delivery-label').show();
                jQuery('.pickup-label').hide();
                unregisterDatePicker();
                registerDatePicker(dateWorkingEncode, holidaysEncode);
            } else {
                jQuery('#delivery-options').hide();
                jQuery('#store-selector').show();
                jQuery('.delivery-label').hide();
                jQuery('.pickup-label').show();
                jQuery('#store-selector-input').trigger('change', dateTime);
            }
        });

        var storeOptions = generateStores(stores);
        jQuery('#store-selector-input').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: Infinity,
            data: storeOptions,
        });

        jQuery('#store-selector-input').on('change', function(e, dateTime) {
            var storeId = jQuery(this).val();
            saveUserAddress('pickupStoreId', storeId);
            checkStoreItemStatus(storeId);
            var selectedStore = _.find(storeOptions, {id: storeId});
            if (selectedStore){
                saveUserAddress('pickupStoreAddress', selectedStore.storeName + ' - ' + selectedStore.fullAddress);
                saveUserAddress('pickupStoreOnlyAddress', selectedStore.fullAddress);
                saveUserAddress('pickupStoreName', selectedStore.storeName);
            }
            jQuery.ajax({
                type : "POST",
                dataType : "json",
                url : home_ajax_vars.ajaxurl,
                data : Object.assign({
                  action: "get_store_working_days",
                }, {id: storeId}),
                context: this,
                beforeSend: function(){
                },
                success: function(response) {
                  if(response.success) {
                    var data = JSON.parse(response.data);
                    if (!dateTime){
                        unregisterDatePicker();
                    }
                    registerDatePicker(
                        data.date_working,
                        data.holidays,
                        dateTime ? dateTime.defaultDate : null,
                        dateTime ? dateTime.defaultTime : null,
                    );
                  }
                },
                error: function( jqXHR, textStatus, errorThrown ){
                  console.log( 'The following error occured: ' + textStatus, errorThrown );
                }
            });
        });


        jQuery('#select-time-picker').on('select2:select', function (e) {
            var data = e.params.data;
            saveUserAddress('deliveryFee', data.baseCost + data.cost);
            saveUserAddress('time', data.text);
            validateDelivery(data);
        });

        jQuery('#delivery-address2').on('change', function(){
            saveUserAddress('deliveryAddress2', jQuery(this).val());
        });

        jQuery('#delivery-input-search').on('change', function() {
            if (!jQuery(this).val()){
                saveUserAddress('deliveryAddress', '');
            }
        });

        var capitalize = function (str, lower = false) {
           return (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, function(match) {
              return match.toUpperCase()
           });
        }

        var previousSearchResults = [];
        var selectedFromDropDown = false;
        jQuery('.js-typeahead-user_v1').typeahead({
            minLength: 2,
            dynamic: true,
            delay: 500,
            template: function (query, item) {
                return '<span class="address-result">' +
                    '<span class="address">{{ADDRESS}}</span>' +
                "</span>"
            },
            emptyTemplate: "no result for {{query}}",
            group: false,
            mustSelectItem: true,
            filter: false,
            source: {
                addresses: {
                    display: "ADDRESS",
                    href: "",
                    ajax: function (query) {
                        return {
                            type: "GET",
                            url: "//developers.onemap.sg/commonapi/search?returnGeom=Y&getAddrDetails=Y",
                            data: {
                                searchVal: "{{query}}"
                            },
                            callback: {
                                done: function (data) {
                                    if (data.results && data.results.length) {
                                        data.results.forEach(function(result) {
                                            result.ADDRESS = capitalize(result.ADDRESS, true);
                                        });
                                        previousSearchResults = data.results;
                                        return data.results;
                                    } else {
                                        return previousSearchResults;
                                    }
                                }
                            }
                        }
                    }

                },
            },
            callback: {
                onClick: function (node, a, place, event) {
                    selectedFromDropDown = true;
                    //jQuery('#delivery-input-search').val(capitalize(place.ADDRESS, true));
                    selectAddress(place);
                }
            },
            debug: false
        });
        var selectAddress = function(place) {
            var address = {
                formatted_address: capitalize(place.ADDRESS, true),
                lat: place.LATITUDE,
                long: place.LONGITUDE,
                zipcode: place.POSTAL,
                streetNumber: capitalize(place.BLK_NO),
                route: capitalize(place.ROAD_NAME),
                state: ''
            }
            saveUserAddress('deliveryAddress', JSON.stringify(address));
        }
        jQuery('#delivery-input-search').on('blur', function() {
            setTimeout(function() {
                if (!selectedFromDropDown && previousSearchResults.length) {
                    selectAddress(previousSearchResults[0]);
                    jQuery('#delivery-input-search').val(previousSearchResults[0].ADDRESS);
                }
            }, 100);
        });
        jQuery('#delivery-input-search').on('change', function() {
            selectedFromDropDown = false;
        });

        var customerAddress = getUserAddress();

        if (!customerAddress || customerAddress && Object.keys(customerAddress).length === 0 || customerAddress && !customerAddress.deliveryAddress){
            saveUserAddress('deliveryType', 'delivery');
            if (customer_id && customer_address_1) {
                geocoder = new google.maps.Geocoder();
                var lat = '', lng = '';
                geocoder.geocode( { 'address': customer_address_1}, function(results, status) {
                    if (status == 'OK' && results.length) {
                        lat = results[0].geometry.location.lat();
                        lng = results[0].geometry.location.lng();
                    } else {
                        //alert('Geocode was not successful for the following reason: ' + status);
                    }
                    var deliveryAddress = {
                        formatted_address: customer_address_1,
                        state: customer_state_name,
                        zipcode: customer_postcode,
                        lat: lat,
                        long: lng,
                    };
                    saveUserAddress('deliveryAddress', JSON.stringify(deliveryAddress));
                    jQuery('#delivery-input-search').val(customer_address_1);
                    if (customer_address_2){
                        saveUserAddress('deliveryAddress2', customer_address_2);
                        jQuery('#delivery-address2').val(customer_address_2);
                    }
                    jQuery('.delivery .btn-apply').click();
                });
            } else {
                saveUserAddress('deliveryAddress', '');
                saveUserAddressToLocalStorage();
            }
        }
        if (customerAddress && !customerAddress.deliveryType){
            saveUserAddress('deliveryType', 'delivery');
            saveUserAddress('deliveryAddress', '');
        }
        var isSavedDateInThePast = customerAddress && moment(customerAddress.date).isBefore(moment(), 'd');

        if (customerAddress.deliveryType && customerAddress.deliveryType == 'self-collection'){
            jQuery('input[value="self-collection"]').prop('checked', true);
            if (customerAddress.pickupStoreId){
                jQuery('.store-selector').val(customerAddress.pickupStoreId);
            }
            jQuery('input[name="select-delivery"]').trigger('change', {
                init: true,
                defaultDate: customerAddress.date && !isSavedDateInThePast ? moment(customerAddress.date, CLIENT_DATE_FORMAT) : null,
                defaultTime: customerAddress.time && !isSavedDateInThePast ? customerAddress.time : null
            });
        } else {
            registerDatePicker(
                dateWorkingEncode,
                holidaysEncode,
                customerAddress.date && !isSavedDateInThePast ? moment(customerAddress.date, CLIENT_DATE_FORMAT) : null,
                customerAddress.time && !isSavedDateInThePast ? customerAddress.time : null
            );
        }
        if(customerAddress.deliveryAddress){
            jQuery('#delivery-input-search').val(JSON.parse(customerAddress.deliveryAddress).formatted_address);
        };
        jQuery('#delivery-address2').val(customerAddress.deliveryAddress2);
    }

    jQuery(document).ready(function() {
        initAddressSelector();
    })
</script>
