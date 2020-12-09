jQuery(document).ready(function($) {
    
    var listPeakHour = [];
    var listNewPeakHour = [];

    init_time_picker();
    init_date_picker();
    function init_time_picker() {
        jQuery('.cdls-form-shipping-time .timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            formatTime: 'H:i',
            step: 30
        })
    }

    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // add more peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time-peak-hour .button-more', function(event) {
        var startTimeInput = jQuery(this).closest('tr').find('.input-start-time');
        var endTimeInput = jQuery(this).closest('tr').find('.input-end-time');
        var startTime = jQuery(startTimeInput).val();
        var endTime = jQuery(endTimeInput).val();

        if (startTime && endTime) {
            add_more_peak_hour(startTime, endTime);
            jQuery(startTimeInput).val('');
            jQuery(endTimeInput).val('');
        }
    });

    // edit peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time .button-edit', function(event) {
        jQuery(this).closest('tr').addClass('editing')
    });

    // edit peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time .button-time-save', function(event) {
        jQuery(this).closest('tr').removeClass('editing')
        jQuery(this).closest('tr').find('.editing-data-span input').each(function(index, el) {
            let newValue = jQuery(this).val()
            jQuery(this).data('old', newValue);
        });
    });

    // edit peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time .button-time-cancel', function(event) {
        jQuery(this).closest('tr').removeClass('editing')
        jQuery(this).closest('tr').find('.editing-data-span input').each(function(index, el) {
            let oldValue = jQuery(this).data('old')
            jQuery(this).val(oldValue);
        });
    });

    // delete peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time-peak-hour .button-delete', function(event) {
        var tr = jQuery(this).closest('tr')
        var id = jQuery(tr).data('id')

        if (id) delete_peak_hour(id);
        jQuery(tr).remove()
    });

    jQuery('body').on('blur', '.input-description', function(event) {
        jQuery(this).val(jQuery(this).val().trim())
    });

    function add_more_peak_hour(startTime, endTime) {
        const name = `new-${uuidv4()}`;
        const html = `<tr>
            <td>
                <span class="edited-data-span">
                    ${startTime} - ${endTime}
                </span>
                <span class="editing-data-span">
                    <input type="text" class="timepicker" name="changes[${name}][start_time]" value="${startTime}" data-name="start_time" data-old="${startTime}"/>
                    -
                    <input type="text" class="timepicker" name="changes[${name}][end_time]" value="${endTime}" data-name="end_time" data-old="${endTime}"/>
                </span>
            </td>
            <td>
                <span class="pointer cdls-noselect button-edit edited-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </span>
                <span class="pointer cdls-noselect button-delete edited-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </span>
                <span class="pointer cdls-noselect button-time-cancel editing-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                        <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                    </svg>
                </span>
            </td>
        </tr>`

        jQuery('.cdls-table-shipping-time-peak-hour tbody').append(html)
        init_time_picker()
    }

    function delete_peak_hour(id) {
        if (id) {
            let html = `
                <input type="hidden" name="deletes[]" value="${id}"/>`

            jQuery('.cdls-form-shipping-time').append(html)
        }
    }

    // ----------------------
    // Occasions
    function init_date_picker() {
        jQuery('.cdls-form-shipping-time .timedatepicker').datetimepicker({
            timepicker: false,
            format: 'm/d/Y',
            formatDate: 'Y/m/d'
        })
    }
    // add more peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time-occasion .button-more', function(event) {
        var startDateInput = jQuery(this).closest('tr').find('.input-start-date');
        var endDateInput = jQuery(this).closest('tr').find('.input-end-date');
        var descriptionInput = jQuery(this).closest('tr').find('.input-description');

        var startDate = jQuery(startDateInput).val();
        var endDate = jQuery(endDateInput).val();
        var description = jQuery(descriptionInput).val();

        if (startDate && endDate && description) {
            add_more_occasion(startDate, endDate, description);
            jQuery(startDateInput).val('');
            jQuery(endDateInput).val('');
            jQuery(descriptionInput).val('');
        }
    });

    // delete peak hour
    jQuery('body').on('click', '.cdls-table-shipping-time-occasion .button-delete', function(event) {
        var tr = jQuery(this).closest('tr')
        var id = jQuery(tr).data('id')

        if (id) delete_occasion(id);
        jQuery(tr).remove()
    });

    function add_more_occasion(startDate, endDate, description) {
        const name = `new-${uuidv4()}`;
        const html = `<tr>
            <td>
                <span class="edited-data-span">
                    ${startDate}
                </span>
                <span class="editing-data-span">
                    <input type="text" class="timedatepicker" name="occasion_changes[${name}][start_date]" value="${startDate}" data-name="start_date" data-old="${startDate}"/>
                </span>
            </td>
            <td>
                <span class="edited-data-span">
                    ${endDate}
                </span>
                <span class="editing-data-span">
                    <input type="text" class="timedatepicker" name="occasion_changes[${name}][end_date]" value="${endDate}" data-name="end_date" data-old="${endDate}"/>
                </span>
            </td>
            <td>
                <span class="edited-data-span">
                    ${description}
                </span>
                <span class="editing-data-span">
                    <input type="text" class="timedatepicker" name="occasion_changes[${name}][description]" value="${description}" data-name="description" data-old="${description}"/>
                </span>
            </td>
            <td>
                <span class="pointer cdls-noselect button-edit edited-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </span>
                <span class="pointer cdls-noselect button-delete edited-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </span>
                <span class="pointer cdls-noselect button-time-cancel editing-data-span">
                    <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-x" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                        <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                    </svg>
                </span>
            </td>
        </tr>`

        jQuery('.cdls-table-shipping-time-occasion tbody').append(html)
        init_date_picker()
    }

    function delete_occasion(id) {
        if (id) {
            let html = `<input type="hidden" name="occasion_deletes[]" value="${id}"/>`

            jQuery('.cdls-form-shipping-time').append(html)
        }
    }

    function valid_time(startTime, endTime) {
        return endTime >= startTime;
    }

    function valid_date(startDate, endDate) {
        return (new Date(endDate) >= new Date(startDate));
    }

    function check_peak_hour_valid() {
        var list_peak = [];
        var requireMessage = 'All fields is required!',
            validMessage = 'Start time should be ealier than end time!',
            includeMessage = 'Peak Hour is included in another!';
        jQuery('.cdls-table-shipping-time-peak-hour').find('tbody tr').each(function(index, el) {
            let startTime = jQuery(this).find('input[data-name="start_time"]').val()
            let endTime = jQuery(this).find('input[data-name="end_time"]').val()

            list_peak.push({startTime, endTime})
        });

        let length = list_peak.length;

        if (length == 0) {
            return true;
        }

        for (let i = 0; i < length; i++) {
            if (!list_peak[i]) {
                alert(requireMessage)
                return false
            }
            if (!list_peak[i].startTime || !list_peak[i].endTime) {
                alert(requireMessage)
                return false
            }
        }

        for (let i = 0; i < length; i++) {
            item = {...list_peak[i]}
            if (!valid_time(item.startTime, item.endTime)) {
                alert(validMessage)
                return false
            }
        }

        for (let i = 0; i < length; i++) {
            for (let j = 0; j < length; j++) {
                if (i === j) continue
                item = {...list_peak[i]}
                item2 = {...list_peak[j]}

                if (item.startTime == item2.startTime && item.endTime == item2.endTime) {
                    alert(includeMessage)
                    return false
                }
                if (item.startTime > item2.startTime && item.startTime < item2.endTime) {
                    alert(includeMessage)
                    return false
                }
                if (item.endTime > item2.startTime && item.endTime < item2.endTime) {
                    alert(includeMessage)
                    return false
                }
                if (item2.startTime > item.startTime && item2.startTime < item.endTime) {
                    alert(includeMessage)
                    return false
                }
                if (item2.endTime > item.startTime && item2.endTime < item.endTime) {
                    alert(includeMessage)
                    return false
                }
            }
        }

        return true
    }

    function check_occasion_valid() {
        var list_date = [];
        var requireMessage = 'All fields is required!',
            validMessage = 'Start date should be ealier than end date!',
            includeMessage = 'Occasions is included in another!!';
        jQuery('.cdls-table-shipping-time-occasion').find('tbody tr').each(function(index, el) {
            let startDate = jQuery(this).find('input[data-name="start_date"]').val()
            let endDate = jQuery(this).find('input[data-name="end_date"]').val()
            let description = jQuery(this).find('input[data-name="description"]').val()

            list_date.push({startDate, endDate, description})
        });
        let length = list_date.length;

        if (length == 0) {
            return true;
        }

        for (let i = 0; i < length; i++) {
            if (!list_date[i]) {
                alert(requireMessage)
                return false
            }
            if (!list_date[i].startDate || !list_date[i].endDate || !list_date[i].description) {
                alert(requireMessage)
                return false
            }
        }

        for (let i = 0; i < length; i++) {
            item = {...list_date[i]}
            if (!valid_date(item.startDate, item.endDate)) {
                alert(validMessage)
                return false
            }
        }

        for (let i = 0; i < length; i++) {
            for (let j = 0; j < length; j++) {
                if (i === j) continue
                startDate = new Date(list_date[i].startDate)
                endDate = new Date(list_date[i].endDate)
                startDate2 = new Date(list_date[j].startDate)
                endDate2 = new Date(list_date[j].endDate)

                if (startDate == startDate2 && endDate == endDate2) {
                    alert(includeMessage)
                    return false
                }
                if (startDate > startDate2 && startDate < endDate2) {
                    alert(includeMessage)
                    return false
                }
                if (endDate > startDate2 && endDate < endDate2) {
                    alert(includeMessage)
                    return false
                }
                if (startDate2 > startDate && startDate2 < endDate) {
                    alert(includeMessage)
                    return false
                }
                if (endDate2 > startDate && endDate2 < endDate) {
                    alert(includeMessage)
                    return false
                }
            }
        }

        return true
    }

    // submit
    jQuery('body').on('click', '.cdls-button-submit-form', function(event) {
        event.preventDefault();
        if (!check_peak_hour_valid()) {
            return
        }
        if (!check_occasion_valid()) {
            return
        }
        jQuery(this).closest('form').submit();
    });
});