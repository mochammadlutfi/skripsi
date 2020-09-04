//This file contains all common functionality for the application

$(document).ready(function() {

    //Set global currency to be used in the application
    __currency_symbol = 'Rp';
    __currency_thousand_separator = '.';
    __currency_decimal_separator = ',';
    __currency_symbol_placement = 'before';
    __currency_precision = 0;
    __quantity_precision = 0;
    __p_currency_symbol = 'Rp';
    __p_currency_thousand_separator = '.';
    __p_currency_decimal_separator = ',';

    __currency_convert_recursively($(document), 2);

    //Input number
    $(document).on('click', '.input-number .quantity-up, .input-number .quantity-down', function() {
        var input = $(this)
            .closest('.input-number')
            .find('input');
        var qty = __read_number(input);
        var step = 1;
        if (input.data('step')) {
            step = input.data('step');
        }
        var min = parseInt(input.data('min'));
        var max = parseInt(input.data('max'));
        if ($(this).hasClass('quantity-up')) {
            //if max reached return false
            if (typeof max != 'undefined' && qty + step > max) {
                return false;
            }

            __write_number(input, qty + step);
            input.change();
        } else if ($(this).hasClass('quantity-down')) {
            //if max reached return false
            if (typeof min != 'undefined' && qty - step < min) {
                return false;
            }

            __write_number(input, qty - step);
            input.change();
        }
    });

    // $('input[type="number"]').on('keyup',function(){
    $(document).on('keyup', 'input[type="number"]', function() {
        // alert('asdjasbd')
        v = parseInt($(this).val());
        min = parseInt($(this).attr('min'));
        max = parseInt($(this).attr('max'));

        if (v < min){
            $(this).val(min);
        } else if (v > max){
            $(this).val(max);
        }
    });

    $('div.pos-tab-menu>div.list-group>a').click(function(e) {
        e.preventDefault();
        $(this)
            .siblings('a.active')
            .removeClass('active');
        $(this).addClass('active');
        var index = $(this).index();
        $('div.pos-tab>div.pos-tab-content').removeClass('active');
        $('div.pos-tab>div.pos-tab-content')
            .eq(index)
            .addClass('active');
    });
});


//Check for number string in input field, if data-decimal is 0 then don't allow decimal symbol
$(document).on('keypress', 'input.input_number', function(event) {
    var is_decimal = $(this).data('decimal');

    if (is_decimal == 0) {
        if (__currency_decimal_separator == '.') {
            var regex = new RegExp(/^[0-9,-]+$/);
        } else {
            var regex = new RegExp(/^[0-9.-]+$/);
        }
    } else {
        var regex = new RegExp(/^[0-9.,-]+$/);
    }

    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});

//Select all input values on click
$(document).on('click', 'input, textarea', function(event) {
    $(this).select();
});

