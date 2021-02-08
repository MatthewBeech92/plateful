$(function () {
    $('#log-in-form').on('submit', function (evt) {
        evt.preventDefault();
        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();
        var rememberMe;

        if ($('#log-in-form input[type="checkbox"]').prop('checked') === true) {
            rememberMe = $('#log-in-form input[type="checkbox"]').val();
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/login',
            type: 'POST',
            data: { email: email, password: password, remember: rememberMe },
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.input-error').removeClass('input-error');
                $('.error-feedback').remove();

                $.each(errors, function (field, error) {
                    $("input[name='" + field + "']").addClass('input-error');
                    $("input[name='" + field + "']").after("<span class='error-feedback'>" + error + '<br></span>');
                });
            },
            success: function () {
                $(location).attr('href', '/home');
            },
        });
    });
});
