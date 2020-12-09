var styles = [{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}];

( function() {

  jQuery.fn.inputDropdown = function() {

    var input = jQuery(this);

    input.bind('click', () => {
      input.addClass('opened');
    });

    jQuery(document).click(function(event) {
      var target = jQuery(event.target);
      if(!target.closest(input).length && input.hasClass('opened')) {
        input.removeClass('opened');
      }
    });
  };

  var isConsecutiveArr = function(arr) {
    var isCons = true;
    var sortedArr = _.sortBy(arr);
    for (var i=0; i < arr.length - 1; i++){
      if (arr[i] + 1 != arr[i+1]){
        isCons = false;
      }
    }
    return isCons;
  };

  jQuery(document).ready(() => {

    var queryParams = {};

    var map = new GMaps({
      el: '#map-container',
      lat: 1.2789,
      lng: 103.8536,
      zoom: 12,
      click: function(e) {
        map.hideInfoWindows();
      },
    });

    var mapLongToShortDay = {
      'monday': 'Mon',
      'tuesday': 'Tue',
      'wednesday': 'Wed',
      'thursday': 'Thu',
      'friday': 'Fri',
      'saturday': 'Sat',
      'sunday': 'Sun'
    };

    var generateDaysString = function(arr) {
      var listDayIndex = arr.map(item => item.dayIndex);
      if (arr.length == 1){
        return mapLongToShortDay[arr[0].working_day];
      } else if (arr.length == 2 || !isConsecutiveArr(listDayIndex)){
        return arr.map(item => mapLongToShortDay[item.working_day]).join(', ');
      } else if (isConsecutiveArr(listDayIndex)){
        return `${mapLongToShortDay[arr[0].working_day]} - ${mapLongToShortDay[arr[arr.length-1].working_day]}`;
      }
    };

    var _24hTo12h = function(timeString) {
      var timeArr = timeString.split(':');

      let h = `${parseInt(timeArr[0])%12}`;

      h = (h < 10 && h > 0) ? '0' + h : h;

      let m = `${timeArr[1]}`;

      m = (m < 10 && m >= 0 && m.length === 1) ? '0' + m : m;

      return `${h}:${m}${parseInt(timeArr[0]) > 12 ? ' PM' : ' AM'}`;
    };

    var generateWorkingTime = function(list) {
      var listWithIndex = list.map((item, index) => {
        return {
          ...item,
          dayIndex: index,
        }
      });
      var groupedTime = _.groupBy(listWithIndex, (item) => {
        return [item.start_working_time, item.end_working_time];
      });
      var listTimeArray = [];
      _.forEach(groupedTime, (item) => listTimeArray.push(item));
      _.orderBy(listTimeArray, (item) => {
        return _.minBy(item, i => i.dayIndex);
      });
      var listTimeRes = listTimeArray.map(item => ({
        working_day: generateDaysString(item),
        end_working_time: _24hTo12h(item[0].end_working_time),
        start_working_time: _24hTo12h(item[0].start_working_time)
      }));
      var listTimeText = listTimeRes.map(item => {
        return `<p class="mb-0"><span class="text-capitalize">${item.working_day}</span>: ${item.start_working_time} to ${item.end_working_time}</p>`;
      });
      return listTimeText.join('\n');
    };

    var prepareData = function(data) {
      var template = jQuery('#store-template').html();
      var locationArr = data.map( (item, index) => {
        var address = `${item.location.number_house} ${item.location.street_name}${ (item.location.floor_unit || item.location.building) ? `, ${item.location.floor_unit} ${item.location.building}` : ``}, Singapore, ${item.location.zipcode}`;
        let lastOrderWrong = _24hTo12h(item.last_order).startsWith('-');
        let lastOrder = lastOrderWrong ? null : '(Last Order: ' + _24hTo12h(item.last_order) + ')';
        return template.format(
          index == 0 ? 'expanded' : '',
          item.location.id,
          item.location.store_name,
          item.location.longitude,
          item.location.latitude,
          item.location.number_house,
          item.location.street_name,
          item.location.floor_unit,
          item.location.building,
          item.district,
          item.area,
          item.location.phone_number,
          item.img.length ? item.img[0] : '',
          item.file,
          item.location.store_name,
          item.file,
          address,
          item.location.phone_number,
          generateWorkingTime(item.working_time),
          item.location.zipcode,
          lastOrder,
        );
      });
      return locationArr.join('\n');
    };

    var getStores = function() {
      var params = { ...queryParams };
      var outlet = jQuery('#select-outlet').find(':selected').val();
      if (outlet != 'all') {
        params.outlet = outlet;
      }
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        url : custom_ajax_vars.ajaxurl,
        data : Object.assign({
          action: "list_store",
        }, params),
        context: this,
        beforeSend: function(){
        },
        success: function(response) {
          if(response.success) {
            jQuery('.store-list-wrapper').remove();
            jQuery('.store-list').append('<div class="store-list-wrapper cdl-scrollable"></div>');
            if (response.data.length > 2) {
              jQuery('.store-list-wrapper').html(prepareData(JSON.parse(response.data)));
            } else {
              jQuery('.store-list-wrapper').html('<div class="no-results">No store found.</div>');
            }
            initMapMarkers();
            jQuery('.cdl-scrollable').mCustomScrollbar({
              theme: 'dark',
            });
          }
          else {
          }
        },
        error: function( jqXHR, textStatus, errorThrown ){
          console.log( 'The following error occured: ' + textStatus, errorThrown );
        }
      });
    };

    var generateOverlay = function(id) {
      var template = jQuery('#store-overlay-template').html();
      var store = jQuery(`.store-card[data-id="${id}"]`);
      var address = `${store.attr('data-number_house')} ${store.attr('data-street_name')}${ (store.attr('data-floor_unit') || store.attr('data-building')) ? `, ${store.attr('data-floor_unit')} ${store.attr('data-building')}` : ``}, Singapore, ${store.attr('data-zipcode')}`;
      return template.format(store.attr('data-name'), address, store.attr('data-phone_number'), store.attr('data-img'), store.attr('data-file'));
    };

    var initMapMarkers = function() {
      if (map) {
        map.removeMarkers();
      }
      var markers = [];
      jQuery('.store-card').each(function() {
        var lat = jQuery(this).attr('data-lat');
        var lng = jQuery(this).attr('data-long');
        var id = jQuery(this).attr('data-id');
        var thisCard = jQuery(this);
        var markerIndex = markers.length;
        if (lat && lng) {
          lat = parseFloat(lat);
          lng = parseFloat(lng);
          var marker = {
            lat,
            lng,
            infoWindow: {
              content: generateOverlay(id),
                lat,
                lng,
            },
            click: function() {
              map.hideInfoWindows();
              map.setCenter({lat, lng});
              jQuery('.store-card').removeClass('expanded');
              jQuery(thisCard).addClass('expanded');
              jQuery(thisCard).parent().prepend(thisCard);
            }
          };
          thisCard.click(function() {
            marker.click();
            map.markers[markerIndex].infoWindow.open(map.map,map.markers[markerIndex]);
          });
          markers.push(marker);
        }
      });
      if (map && markers.length) {
        map.addMarkers(markers);
        map.setCenter(markers[0]);
        //map.fitLatLngBounds(markers);
      }
      jQuery('.store-card').first().click();
    };

    map.addStyle({
      styledMapName:"Styled Map",
      styles: styles,
      mapTypeId: "map_style"
    });

    map.setStyle("map_style");

    jQuery('#select-outlet').on('change', () => {
      getStores();
    });

    jQuery('.modal-toggle').click((e) => {
      e.preventDefault();
      jQuery('#location-modal').modal('show');
      var tab = jQuery(e.target).attr('data-selection');
      jQuery('.nav-tabs a[href="#' + tab + '"]').tab('show');
    });

    jQuery('.nav-tabs a.nav-link').on('shown.bs.tab', function () {
      setTimeout(() => {
        var isFiltered = jQuery('#suggestion-input').val();
        if (!isFiltered.length){
          if (jQuery(this).attr('href') == '#district') {
            jQuery('[name="select-all-areas"]').prop('checked', false);
            jQuery('[name="selected_area[]"]').prop('checked', false);
            jQuery('[name="select-all-districts"]').prop('checked', true);
            jQuery('[name="selected_district[]"]').prop('checked', true);
          } else {
            jQuery('[name="select-all-districts"]').prop('checked', false);
            jQuery('[name="selected_district[]"]').prop('checked', false);
            jQuery('[name="select-all-areas"]').prop('checked', true);
            jQuery('[name="selected_area[]"]').prop('checked', true);
          }
        }
      });
    });

    jQuery('#btn-clear').click((e) => {
      e.preventDefault();
      var activeTab = jQuery('.tab-pane.active');
      activeTab.find('input[type="checkbox"]').prop('checked', false);
    });

    jQuery('#btn-apply').click((e) => {
      e.preventDefault();
      var selectedValues;
      var activeTab = jQuery('.tab-pane.active');
      var deactiveTab = jQuery('.tab-pane:not(.active)');
      var checkboxes = activeTab.find('input[type="checkbox"]');
      var isSomethingChecked = activeTab.find('input[type="checkbox"]:checked');
      if (!isSomethingChecked.length){
        //if there isn't any checkbox checked, do nothing
        return;
      }
      if (checkboxes.length == isSomethingChecked.length){
        selectedValues = activeTab.attr('id') == 'district' ? 'All Districts' : 'All Areas';
        //get all store locations
        getStores();
      } else {
        var values = [];
        var ids = [];
        jQuery.each(isSomethingChecked, function() {
          if (jQuery(this).attr('data-type') != 'select-all'){
            values.push(jQuery(this).attr('data-name'));
            ids.push(jQuery(this).attr('value'));
          }
        });
        selectedValues = values.join(', ');
      }
      deactiveTab.find('input[type="checkbox"]').prop('checked', false);
      jQuery('#suggestion-input').val(selectedValues);
      jQuery('#reset-filter').removeClass('d-none');
      jQuery('#location-modal').modal('hide');

      if (activeTab.attr('id') == 'district'){
        queryParams.district = ids.join(',');
      } else if (activeTab.attr('id') == 'area'){
        queryParams.area = ids.join(',');
      }
      getStores();
    });

    jQuery('input[data-type="select-all"]').on('change', function() {
      var isChecked = jQuery(this).is(':checked');
      var activeTab = jQuery('.tab-pane.active');
      activeTab.find('input[type="checkbox"]').prop('checked', isChecked);
    });

    jQuery('input[data-type="select-option"]').on('change', function() {
      var isChecked = jQuery(this).is(':checked');
      var activeTab = jQuery('.tab-pane.active');
      var numOfCheckbox = activeTab.find('input[data-type="select-option"]');
      var numOfCheckboxChecked = activeTab.find('input[data-type="select-option"]:checked');
      activeTab.find('input[data-type="select-all"]').prop('checked', numOfCheckbox.length == numOfCheckboxChecked.length );
    });

    jQuery('#reset-filter').click(function() {
      jQuery(this).addClass('d-none');
      jQuery('#suggestion-input').val('');
      jQuery('input[data-type="select-all"]').prop('checked', true);
      jQuery('input[data-type="select-option"]').prop('checked', true);
      queryParams = {};
      getStores();
    });

    jQuery('.suggestion-input').inputDropdown();

    getStores();

  });
})();
