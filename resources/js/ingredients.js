$(function () {
    /* Add Ingredient */
    $('#add-ingredient-form .submit').on('click', function (evt) {
        var ingredientData = $('#add-ingredient-form').serialize();

        evt.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/ingredients',
            type: 'POST',
            data: ingredientData,
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                console.log(jqXhr.responseText);
                $('.error-feedback').remove();
                $('.input-box, .styled-select').removeClass('input-error');

                $.each(errors, function (field, error) {
                    var formattedError = error.toString().replace(',', '<br>');

                    $('#' + field.replace('_', '-') + '').addClass('input-error');
                    $('#' + field.replace('_', '-') + '').after("<span class='error-feedback'>" + formattedError + '</span>');
                });

                showFieldsetErrors();
            },
            success: function (data) {
                console.log(data);
                $(location).attr('href', '/ingredients/' + data);
            },
        });
    });

    $('#edit-ingredient-form .submit').on('click', function (evt) {
        var ingredientData = $('#edit-ingredient-form').serialize();

        evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: $('#edit-ingredient-form').attr('action'),
            type: 'POST',
            data: ingredientData,
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.error-feedback').remove();
                $('.input-box, .styled-select').removeClass('input-error');

                $.each(errors, function (field, error) {
                    var formattedError = error.toString().replace(',', '<br>');

                    $('#' + field.replace('_', '-') + '').addClass('input-error');
                    $('#' + field.replace('_', '-') + '').after("<span class='error-feedback'>" + formattedError + '</span>');
                });

                showFieldsetErrors();
            },
            success: function (data) {
                $(location).attr('href', '/ingredients/' + data);
            },
        });
    });

    /** **** Functions ** ****/

    function showFieldsetErrors() {
        if ($('fieldset').find('.input-error')) {
            $('fieldset').hide();
            $('.input-error').first().parents('fieldset').show();
        }
    }
});
