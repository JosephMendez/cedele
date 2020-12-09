jQuery(document).ready(function($) {
    // setup time picker
    init_time_picker()
    function init_time_picker(argument) {
        jQuery('.timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            formatTime: 'H:i',
            step: 30
        })
    }
    jQuery("body").on('click', '.handlediv', function(event) {
        jQuery(this).closest('.postbox').toggleClass('closed')
    });

    // working days
    var listWorkingDays = [];
    var defaultTime = '00:00';
    var endTime = '23:59';
    var errors = [];

    //======================================================
    // working time manager
    load_working_time_form_element();
    function load_working_time_form_element() {
        listWorkingDays = [];
        errors = [];
        jQuery(".day-checkbox").each(function(index) {
            let working_day_element = jQuery(this).parents(`.day-of-week`);

            let working_day = jQuery(working_day_element).data('day');
            let checked = jQuery(this).prop('checked');

            if (checked) {
                let opening_time = []
                jQuery(working_day_element).find('.sub-opening-time').each(function(index) {
                    let from = jQuery(this).find('.from input').val() || null
                    let to = jQuery(this).find('.to input').val() || null
                    opening_time.push({from, to})
                });
                listWorkingDays.push({working_day, opening_time})
            }
        });
        render_input_checkbox()
    }

    function render_input_checkbox() {
        // reset
        jQuery('.button-more-time').hide();
        jQuery('.sub-opening-time').hide();
        listWorkingDays.forEach((item, index) => {
            let working_day_element = jQuery(`.day-${item.working_day}`)
            jQuery(working_day_element).find('.button-more-time').show()
            jQuery(working_day_element).find('.sub-opening-time').show()
        })
    }

    function getOpeningElement() {
        return `<div class="sub-opening-time">
            <div class="from">
                From:
                <input class="timepicker input-start-time" type="time"
                    value="${defaultTime}">
            </div>
            <div class="to">
                To:
                <input class="timepicker input-end-time" type="time"
                    value="${defaultTime}">
            </div>
            <div class="clearfix"></div>
        </div>`
    }
    
    jQuery('body').on('click', '.day-checkbox', function(event) {
        let checked = jQuery(this).prop('checked');
        if (checked) {
            jQuery(this).parents('.day-of-week').find('.input-start-time').val(defaultTime);
            jQuery(this).parents('.day-of-week').find('.input-end-time').val(defaultTime);
        } else {
            jQuery(this).parents('.day-of-week').find('.input-start-time').val('');
            jQuery(this).parents('.day-of-week').find('.input-end-time').val('');
        }
        load_working_time_form_element()
    });
    
    jQuery('body').on('change', '.timepicker', function(event) {
        load_working_time_form_element()
    });
    
    // button more opening time
    jQuery('body').on('click', '.button-more-time', function(event) {
        let working_day_element = jQuery(this).parents(`.day-of-week`);

        jQuery(working_day_element).find('.opening-time').append(getOpeningElement())
        init_time_picker();
        load_working_time_form_element()
    });
    //======================================================
    
    //======================================================
    // File manager
    jQuery('body').on('click', '.button-remove-time', function(event) {
        let working_day_element = jQuery(this).parents(`.day-of-week`);

        jQuery(working_day_element).parents('.sub-opening-time').remove();
        load_working_time_form_element()
    });

    jQuery('body').on('click', '.icon-file', function(event) {
        var that = this
        event.preventDefault();

        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            jQuery(that).siblings('.file').val(uploaded_image.attributes.id);
            jQuery(that).siblings('.store-attachment-preview').addClass('show');
            jQuery(that).siblings('.store-attachment-preview').find('a').attr('href', uploaded_image.attributes.url);
            jQuery(that).siblings('.store-attachment-preview').find('a').html(uploaded_image.attributes.filename);
        });
    });
    jQuery('body').on('click', '.button-clear-attachment', function(event) {
        jQuery(this).parent().siblings('.file').val('');
        jQuery(this).parent().removeClass('show');
    });
    //======================================================
    
    //======================================================
    function check_validate_time(time) {
        let pattern = /^(?:(?:0?\d|1[0-2]):[0-5]\d)$/gi

        return pattern.test(time)
    }
    //======================================================
    // check central_kitchen
    async function check_central_kitchen_ajax(id) {
        var result = null
        await jQuery.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            dataType: 'json',
            data: {
                id: id,
                action: "sl_check_data_exist"
            },
            beforeSend: function() {
                jQuery('#form-location .button-save-location').prop('disabled', true);
            },
            success: function(res){
                if (res.status == 1) {
                    result = res
                }
            },
            complete: function() {
                jQuery('#form-location .button-save-location').prop('disabled', false);
            },
        });

        return result
    }

    check_central_kitchen_to_disable()
    function check_central_kitchen_to_disable() {
        var checked = jQuery('#form-location #central_kitchen').is(':checked');
        if (checked) {
            jQuery('#form-location #status').prop('readonly', true);
            jQuery('#form-location #status').addClass('disabled');
            jQuery('#form-location #status').attr('onclick', 'return false;');
            jQuery('#form-location #status').prop('checked', 'checked');
        } else {
            jQuery('#form-location #status').prop('readonly', false);
            jQuery('#form-location #status').removeClass('disabled');
            jQuery('#form-location #status').removeAttr('onclick');
        }
    }

    jQuery('body').on('click', '#form-location #central_kitchen', async function(event) {
        if (!jQuery(this).hasClass('disabled'))
            check_central_kitchen_to_disable()
    });

    jQuery('body').on('click', '#form-location .button-save-location', async function(event) {
        var id = jQuery('#form-location input[name="id"]').val();
        var checked = jQuery('#form-location #central_kitchen').is(':checked');
        console.log('click', id, checked);
        if (checked) {
            var store = await check_central_kitchen_ajax(id);
            if (store) {
                var result = confirm(`Currently, Store [${store.store_name}] is central kitchen. Are you sure to change central kitchen to this store?`);
                if (!result) {
                    return false;
                }
            }
        }
        jQuery('#working-time-data').val(JSON.stringify(listWorkingDays))
        jQuery(this).closest('form').submit()
    });
});