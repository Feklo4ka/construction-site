'use strict';

$(function () {
    var $price = $('input.price');
    $price.on('click', function () {
        var $input = $(this);
        $input.prop('readonly', false);
    });
    
    $price.on('keypress', function (ev) {
        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
        var $input = $(this);
        
        if (keycode == 13) {
            $input.prop('readonly', true);
            
            $.post("/construction/calc_edit", {
                price: $input.val(),
                id: $input.data('id')
            });
        }
        

    });
});