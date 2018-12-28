$(document).ready(function () {

     $('.numericonly').on('keyup keypress blur change', function (e) {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        // Remove all non alpha-nums from str and store it back in the str variable
        var newVal = str = str.replace(/[^0-9]+/g, '');

        // Set the new value in the input
        $(this).val(newVal);
    });
    
     $('.decimalonly').on('keyup keypress blur change', function (e) {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        // Remove all non alpha-nums from str and store it back in the str variable
        var newVal = str = str.replace(/[^0-9.]+/g, '');

        // Set the new value in the input
        $(this).val(newVal);
    });
    
    $('.alphanumericonly').on('keyup keypress blur change', function (e) {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        // Remove all non alpha-nums from str and store it back in the str variable
        var newVal = str = str.replace(/[^a-zA-Z0-9]+/g, '');

        // Set the new value in the input
        $(this).val(newVal);
    });
    
    $('.webfront-url').on('keyup keypress blur change', function (e) {
        // Get the user input from "this" and put it in str variable
        var str = $(this).val();

        // Remove all non alpha-nums from str and store it back in the str variable
        var newVal = str = str.replace(/[^a-zA-Z0-9-]+/g, '');

        // Set the new value in the input
        $(this).val(newVal);
    });
    
    $('#webfrontTitleInput').on('keyup keypress blur change', function (e) {
        var url = $(this).val().toLowerCase().replace(/ /g, "-");
        $('#webfrontUrlInput').val(url);
    });

});

function toggleLateFeeFields(type) {
    if (type == 3) {
        $('#late_fee_type_3').show();
    } else {
        $('#late_fee_type_3').hide();
    }
    if (type == 2) {
        $('#recurring_period').show();
    } else {
        $('#recurring_period').hide();
    }
}