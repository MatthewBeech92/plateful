$(function () {
    $('#register-btn').on('click', function (e) {
        var formData = $('#register-form').serialize();

        e.preventDefault();
        register(formData);
    });

    function register(formData) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/register',
            type: 'POST',
            data: formData,
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.input-error').removeClass('input-error');
                $('.error-feedback').remove();

                $.each(errors, function (field, error) {
                    $("input[name='" + field + "']").addClass('input-error');
                    $("input[name='" + field + "']").after("<span class='error-feedback'>" + error + '<br></span>');
                });
            },
            success: function (data) {
                $('#register-success').html(data).show();
                setTimeout(function () {
                    $(location).attr('href', '/home');
                }, 2000);
            },
        });
    }
});
