jQuery(document).ready(function($) {
    $('select.wc-enhanced-select').on('change', function(event) {
        var value = $(this).val();
        var is_can_not_update_order = $('#is_can_not_update_order').val();
        var disabled = false;
        if ((value == 'wc-completed' || value == 'wc-failed' || value == 'wc-cancelled') && is_can_not_update_order) {
          disabled = true;
        }
        $('.wp_custom_order_rider').prop('disabled', disabled);
        $('.wp_custom_order_shipping_cost').prop('disabled', disabled);
    });

    // Ajax customer search boxes
    $( ':input.wp_custom_order_rider' ).filter( ':not(.enhanced)' ).each( function() {
        var select2_args = {
            allowClear:  $( this ).data( 'allow_clear' ) ? true : false,
            placeholder: $( this ).data( 'placeholder' ),
            minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '1',
            escapeMarkup: function( m ) {
                return m;
            },
            ajax: {
                url:         custom_object.ajax_url,
                dataType:    'json',
                delay:       1000,
                data:        function( params ) {
                    return {
                    term:     params.term,
                    search_all_rider: $( this ).data( 'search_all' ) ? 1 : '',
                        action:   'json_custom_search_rider',
                        exclude:  $( this ).data( 'exclude' )
                    };
                },
                processResults: function( data ) {
                    var terms = [];
                    if ( data ) {
                        $.each( data, function( id, text ) {
                            terms.push({
                                id: id,
                                text: text
                            });
                        });
                    }
                    return {
                        results: terms
                    };
                },
                cache: true
            }
        };

        $( this ).selectWoo( select2_args ).addClass( 'enhanced' );

        if ( $( this ).data( 'sortable' ) ) {
            var $select = $(this);
            var $list   = $( this ).next( '.select2-container' ).find( 'ul.select2-selection__rendered' );

            $list.sortable({
                placeholder : 'ui-state-highlight select2-selection__choice',
                forcePlaceholderSize: true,
                items       : 'li:not(.select2-search__field)',
                tolerance   : 'pointer',
                stop: function() {
                    $( $list.find( '.select2-selection__choice' ).get().reverse() ).each( function() {
                        var id     = $( this ).data( 'data' ).id;
                        var option = $select.find( 'option[value="' + id + '"]' )[0];
                        $select.prepend( option );
                    } );
                }
            });
        }
    });

    // list order woocommerce
    function open_modal_rider() {
        jQuery('body .assign-to-rider-modal').addClass('show');
        jQuery('.assigned_riders_notice').hide()
    }
    function close_modal_rider() {
        jQuery('body .assign-to-rider-modal').removeClass('show');
        jQuery('.wp_custom_order_rider').val('').trigger('change');
        jQuery('.order_shipping_cost').val('');
        jQuery('#bulk-action-selector-top').val(-1);
    }
    $('body').on('click', '.cdls-modal', function(event) {
        event.stopPropagation();
    });
    $('body').on('click', '.cdls-modal-close', function(event) {
        close_modal_rider();
    });
    $('body').on('click', '.button-cancel-modal-assign-to-rider', function(event) {
        close_modal_rider();
    });
    $('body').on('click', '.button-submit-modal-assign-to-rider', function(event) {
        action_submit_form();
    });
    $('body').on('change', '.wp_custom_order_rider', function(event) {
        var select_val = jQuery(this).val()

        if (select_val) {
            jQuery('.assigned_riders_notice').hide()
        }
    });
    jQuery('body').on('change', '#bulk-action-selector-top', function(event) {
        var select_val = jQuery(this).val();

        if (select_val == 'assign_to_rider') {
            open_modal_rider();
        }
    });

    function action_submit_form() {
        jQuery('.assigned_riders_notice').hide()
        var select_val = jQuery('.wp_custom_order_rider').val()
        var order_shipping_cost = jQuery('.order_shipping_cost').val()
        if (select_val) {
            jQuery("input#doaction").closest('form').append(
                jQuery("<input />").attr("type", "hidden")
                    .attr("name", 'assigned_riders')
                    .val(select_val)
            ).append(
                jQuery("<input />").attr("type", "hidden")
                    .attr("name", 'order_shipping_cost')
                    .val(order_shipping_cost)
            );
            jQuery('input#doaction').click()
        } else {
            jQuery('.assigned_riders_notice').show()
        }
    }

    function clear_value(argument) {
        // body...
    }

    var stc = jQuery('#occasions_wc_mini_amount');
    if(stc.length > 0){
        var regular_mini_amout = jQuery('#wc_mini_amount');
        var regular_amount_below = jQuery('#wc_order_amount_below');
        regular_mini_amout.change(function(){
            var v = $(this).val();
            regular_amount_below.attr('max', v);
        });

        var occasions_mini_amout = jQuery('#occasions_wc_mini_amount');
        var occasions_amount_below = jQuery('#occasions_wc_order_amount_below');
        occasions_mini_amout.change(function(){
            var v = $(this).val();
            occasions_amount_below.attr('max', v);
        });

        $('input[type="number"]').each(function(index, el) {
            $(this).attr('step', '0.01');
        });
    }

    // Customize update for order

    jQuery('.date-picker__custom').datepicker({
        minDate: 0,
    });

});