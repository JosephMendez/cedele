/* global shippingDistanceLocalizeScript, ajaxurl */
( function( $ ) {
    function fresh_arr(input) {
        if (Array.isArray(input) && input.length > 0) {
            return newInput = input.map(item => {
                return {...item}
            })
        }
        return [];
    }
    var old_datas;
    var changed_datas;
    var list_deletes = [];

    old_datas = fresh_arr(shippingDistanceLocalizeScript.data);
    changed_datas = fresh_arr(shippingDistanceLocalizeScript.data);

    load_input();
    function load_input() {
        var html = ''

        changed_datas.forEach(item => {
            html += `<tr>
                <td>
                    <span class="distance-label">
                        ${parseFloat(item.distance_from)} - ${parseFloat(item.distance_to)} km
                        <div class="row-actions">
                            <a class="wc-shipping-class-edit" href="#">${shippingDistanceLocalizeScript.editLabel}</a> | <a href="#" class="wc-shipping-class-delete">${shippingDistanceLocalizeScript.removeLabel}</a>
                        </div>
                    </span>
                    <span class="distance-input-edit">
                        <input type="number" step="0.01" name="distance_from" class="input_distance_from" placeholder="from" value="${parseFloat(item.distance_from)}"> -
                        <input type="number" step="0.01" class="input_distance_to" name="distance_to" placeholder="to" value="${parseFloat(item.distance_to)}">
                        <p><a class="button-cancel-changes">Cancel changes</a></p>
                    </span>
                </td>
                <td>
                    <span class="distance-label">
                        ${parseFloat(item.distance_cost)}
                    </span>
                    <span class="distance-input-edit">
                        <input type="number" step="0.01" class="input_cost" name="distance_cost" placeholder="cost" value="${parseFloat(item.distance_cost)}">
                    </span>
                </td>
                <td></td>
            </tr>`
        });
        $('.wc-shipping-distance tbody').html(html)
    }

    function add_more(distance_to) {
        changed_datas.push({
            distance_from: parseFloat(distance_to),
            distance_to: parseFloat(distance_to),
            distance_cost: 0,
        });

        var html = `<tr>
            <td>
                <input type="number" step="0.01" name="distance_from" class="input_distance_from" placeholder="from" value="${parseFloat(distance_to)}"> - <input type="number" step="0.01" class="input_distance_to" name="distance_to" placeholder="to" value="${parseFloat(distance_to)}">
                <p><a class="button-cancel-changes">Cancel changes</a></p>
            </td>
            <td>
                <input type="number" step="0.01" class="input_cost" name="distance_cost" placeholder="cost" value="0">
            </td>
            <td></td>
        </tr>`
        $('.wc-shipping-distance tbody').append(html)
    }

    function remove_data(i) {
        $('.wc-shipping-distance tbody tr').eq(i).remove();
        changed_datas.splice(i, 1);
    }

    $('body').on('click', '.wc-shipping-distance .wc-shipping-distance-add', function(event) {
        event.preventDefault();
        const [lastItem] = changed_datas.slice(-1);

        let distance_to = lastItem ? lastItem.distance_to : 0;
        add_more(distance_to)
        enabled_button();
    });

    function check_valid() {
        for (var i = 0; i < changed_datas.length; i++) {
            let item = changed_datas[i];

            if (item.distance_from === "" || item.distance_to === "" || item.distance_cost === "") {
                return false;
            }
        }

        return true;
    }

    function check_valid_distance() {
        for (var i = 0; i < changed_datas.length; i++) {
            let item = changed_datas[i];

            if (parseFloat(item.distance_from) > parseFloat(item.distance_to)) {
                return false;
            }
        }

        return true;
    }

    function check_includes() {
        for (let i = 0; i < changed_datas.length; i++) {
            for (let j = i; j < changed_datas.length; j++) {
                if (i == j)
                    continue;
                let item = changed_datas[i];
                let item2 = changed_datas[j];

                if (parseFloat(item.distance_from) == parseFloat(item2.distance_from) && parseFloat(item.distance_to) == parseFloat(item2.distance_to)) {
                    return true;
                }
                if (parseFloat(item.distance_from) > parseFloat(item2.distance_from) && parseFloat(item.distance_from) < parseFloat(item2.distance_to)) {
                    return true;
                }
                if (parseFloat(item.distance_to) > parseFloat(item2.distance_from) && parseFloat(item.distance_to) < parseFloat(item2.distance_to)) {
                    return true;
                }
                if (parseFloat(item2.distance_from) > parseFloat(item.distance_from) && parseFloat(item2.distance_from) < parseFloat(item.distance_to)) {
                    return true;
                }
                if (parseFloat(item2.distance_to) > parseFloat(item.distance_from) && parseFloat(item2.distance_to) < parseFloat(item.distance_to)) {
                    return true;
                }
            }
        }

        return false;
    }

    function enabled_button() {
        $('button.wc-shipping-distance-save').prop('disabled', false)
    }

    function disabled_button() {
        $('button.wc-shipping-distance-save').prop('disabled', true)
    }

    $('body').on('input', '.wc-shipping-distance input[type="number"]', function(event) {
        let value = $(this).val()
        let name = $(this).attr('name')
        let index = $(this).closest('tr').index()

        if (value == '') value = 0;

        let new_changed_datas = changed_datas.slice();
        new_changed_datas[index][name] = value
        changed_datas = new_changed_datas
        enabled_button();
    });

    $('body').on('click', '.button-cancel-changes', function(event) {
        event.preventDefault();
        let index = $(this).closest('tr').index()

        if (changed_datas[index].id) {
            changed_datas[index] = {...old_datas[index]}
            $(this).closest('tr').removeClass('editing');
        } else {
            remove_data(index)
            enabled_button();
        }
    });

    $('body').on('click', '.wc-shipping-distance .wc-shipping-class-edit', function(event) {
        event.preventDefault();
        let index = $(this).closest('tr').index();
        $(this).closest('tr').addClass('editing');
    });

    $('body').on('click', '.wc-shipping-distance .wc-shipping-class-delete', function(event) {
        event.preventDefault();
        let index = $(this).closest('tr').index();

        if (changed_datas[index].id) {
            list_deletes.push(changed_datas[index].id)
        }
        remove_data(index);
        enabled_button();
    });

    $('body').on('click', 'button.wc-shipping-distance-save', function(event) {
        event.preventDefault();
        send_ajax()
    });

    function send_ajax() {
        if (!check_valid()) {
            alert('All fields are required!')
            return
        }
        if (!check_valid_distance()) {
            alert('Distance from is not valid!')
            return
        }
        if (check_includes()) {
            alert('The distance is existing in other range!')
            return
        }
        jQuery.ajax({
            type: "POST",
            url: shippingDistanceLocalizeScript.url,
            dataType: 'json',
            data: {
                list_deletes: list_deletes,
                changes: changed_datas,
                action: "woocommerce_shipping_distance_save_changes",
                wc_shipping_distance_nonce: shippingDistanceLocalizeScript.wc_shipping_distance_nonce
            },
            beforeSend: function() {
                $('.wc-shipping-distance').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(result) {
                if (result.success) {
                    changed_datas = fresh_arr(result.data)
                    old_datas = fresh_arr(result.data)
                    load_input()
                    $('.wc-shipping-distance tr').removeClass('editing');
                    disabled_button()
                } else {
                    alert(result.data)
                }
            },
            complete: function() {
                $('.wc-shipping-distance').unblock();
            }
        });
    }
})( jQuery, shippingDistanceLocalizeScript );
