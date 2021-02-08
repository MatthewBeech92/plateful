$(document).ready(function () {
    $('.nav-header .hamburger-icon').click(function () {
        if ($('.hamburger-icon').attr('aria-expanded') === 'false') {
            $('.page-container').addClass('add-nav-dropdown');
            $('.hamburger-icon').attr('aria-expanded', 'true');
        } else {
            $('.page-container').removeClass('add-nav-dropdown');
            $('.hamburger-icon').attr('aria-expanded', 'false');
        }
    });

    $('.nav-header .sidenav-icon').click(function () {
        var sidebar;

        if ($('.page-container').hasClass('no-sidebar')) {
            sidebar = true;
        } else {
            sidebar = false;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/sidebar',
            type: 'POST',
            data: { sidebarExpanded: sidebar },
            error: function (jqXhr) {
                // var errors = jqXhr.responseJSON.errors;
                console.log(jqXhr);
            },
            success: function (data) {
                var sidebar = JSON.parse(data);

                if (sidebar.sidebarExpanded) {
                    $('.page-container').removeClass('no-sidebar');
                    $('.sidenav-icon').attr('aria-expanded', 'true');
                } else {
                    $('.page-container').addClass('no-sidebar');
                    $('.sidenav-icon').attr('aria-expanded', 'false');
                }
            },
        });
    });

    if ($(window).width() < 768) {
        $('.page-container').removeClass('no-sidebar');
    }

    $(window).resize(function () {
        if ($(window).width() >= 768) {
            $('.page-container').removeClass('add-nav-dropdown');
            $('.hamburger-icon').attr('aria-expanded', 'false');
        } else if ($(window).width() < 768) {
            $('.page-container').removeClass('no-sidebar');
            $('.sidenav-icon').attr('aria-expanded', 'true');
        }
    });
});
