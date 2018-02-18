"use strict";


$(function () {
    var $form = $('form.calc');
    $form.submit(function (ev) {
        ev.preventDefault();
        ev.stopPropagation();

        $.post("/construction/calcPrices", $form.serialize(), function (res) {
            $('#calc-result span').html(res);
            $('#calc-result').show();
        });
    });
})


