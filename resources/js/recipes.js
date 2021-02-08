var calorieInformationTemplate = require('./templates/recipes/calorieInformation.handlebars');
var fullIngredientsTableTemplate = require('./templates/recipes/fullIngredientsTable.handlebars');
var partialIngredientsTableTemplate = require('./templates/recipes/partialIngredientsTable.handlebars');
var recipeCardTimeTemplate = require('./templates/recipes/recipeCardTime.handlebars');

$(function () {
    var pathArray = window.location.pathname.split('/');
    var recipeId = pathArray[pathArray.length - 1];

    /*
     *
     * Recipe List Page
     *
     */

    $(document).on('keyup', '#recipe-search-all', function (evt) {
        var query = $(this).val();

        if (query.length > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });

            $.ajax({
                url: '/recipes/filter-recipes/' + query,
                type: 'POST',
                error: function (jqXhr) {
                    var error = jqXhr.responseJSON.error;

                    $('.error-feedback').remove();
                    $('<div class="error-feedback">' + error + '</div>').appendTo('.search-form');
                    $('.recipe-cards').hide();
                },
                success: function (data) {
                    var recipes = JSON.parse(data);

                    $('.error-feedback').remove();
                    $('.recipe-cards.search-results').remove();
                    $('.recipe-cards').hide();

                    $('<div />', {
                        class: 'recipe-cards search-results',
                    }).insertAfter('.recipe-cards');

                    $.each(recipes, function (index, item) {
                        $('.recipe-cards.search-results').append(
                            recipeCardTimeTemplate({
                                id: item.id,
                                image: item.image,
                                name: item.name,
                                time: item.time,
                            })
                        );
                    });
                },
            });
        } else {
            $('.error-feedback').remove();
            $('.recipe-cards.search-results').remove();
            $('.recipe-cards').show();
        }
    });

    /*
     *
     * Create Recipe Page
     *
     */

    $(document).on('keyup', '#ingredient-searchbox', function (evt) {
        var optionInFocus = $(this).next('.listbox').children('.option-focused');

        if (evt.key === 'Down' || evt.key === 'ArrowDown' || evt.key === 'Up' || evt.key === 'ArrowUp') {
            return true;
        } else if (evt.key === 'Enter') {
            if (optionInFocus.length !== 0) {
                addIngredient(optionInFocus);
            }
        } else {
            findIngredient();
        }
    });

    // Add ingredient to the ingredient list
    $(document).on('click', '.ingredient-item', function () {
        addIngredient($(this));
    });

    $(document).on('click', '.recipe-amount', function () {
        $(this).children('input').trigger('focus');
    });

    // Remove ingredient from ingredient list
    $(document).on('click', '.td-cross .cross-icon', function () {
        if ($('#ingredient-list-table tbody tr').length === 1) {
            $('#calorie-info').remove();
            $('#ingredient-list').remove();
            $('.recipe-info').children('button').addClass('d-none');
        } else {
            $(this).parentsUntil('tbody').remove();

            if ($('#calorie-info').length !== 0) {
                showCalorieInfo();
            }
        }
    });

    // Show calorie information
    $(document).on('keyup', '.ingredient-amount-input', function (evt) {
        var ingredientValue = $(this).serializeArray();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/ingredients/validate-ingredient',
            type: 'POST',
            data: ingredientValue,
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $.each(errors, function (field, error) {
                    var errorField = 'ingredient[' + field.split('.')[1] + ']';

                    $('span[data-error-input="' + errorField + '"]').remove();
                    $('input[name="' + errorField + '"]').addClass('input-error');
                    $('#ingredient-list').append("<span class='error-feedback' data-error-input='" + errorField + "'>" + error + '</span>');
                    $('#calorie-info').remove();
                    $('.recipe-info').children('button').remove();
                });
            },
            success: function (successField) {
                $('span[data-error-input="' + successField + '"]').remove();
                $('input[name="' + successField + '"]').removeClass('input-error');

                if ($('#ingredient-list .error-feedback').length === 0) {
                    showCalorieInfo();
                }
            },
        });
    });

    // Save the Recipe
    $(document).on('click', '#add-recipe-form .fieldset-submit', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/recipes',
            type: 'POST',
            data: gatherRecipeInfo($('#add-recipe-form')),
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.error-feedback').remove();
                $('.input-box, .styled-select').removeClass('input-error');

                $.each(errors, function (field, error) {
                    if (field.substring(0, 11) === 'ingredient.') {
                        var ingredientName = 'ingredient[' + field.split('.')[1] + ']';

                        $('.ingredient-amount-input[name="' + ingredientName + '"]').addClass('input-error');
                        $('#ingredient-list').append("<span class='error-feedback' data-error-input='" + ingredientName + "'>" + error + '</span>');
                    }

                    $('#' + field.replace('_', '-') + '').addClass('input-error');
                    $('#' + field.replace('_', '-') + '').after("<span class='error-feedback'>" + error + '</span>');
                });

                showFieldsetErrors();
            },
            success: function () {
                $(location).attr('href', '/recipes');
            },
        });
    });

    /*
     *
     * Edit Recipe Page
     *
     */

    // edit the Recipe
    $('#edit-recipe-form .fieldset-submit').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: $('#edit-recipe-form').attr('action'),
            type: 'PUT',
            data: gatherRecipeInfo($('#edit-recipe-form')),
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.error-feedback').remove();
                $('.input-box, .styled-select').removeClass('input-error');

                $.each(errors, function (field, error) {
                    if (field.substring(0, 11) === 'ingredient.') {
                        var ingredientName = 'ingredient[' + field.split('.')[1] + ']';

                        $('.ingredient-amount-input[name="' + ingredientName + '"]').addClass('input-error');
                        $('#ingredient-list').append("<span class='error-feedback' data-error-input='" + ingredientName + "'>" + error + '</span>');
                    }

                    $('#' + field.replace('_', '-') + '').addClass('input-error');
                    $('#' + field.replace('_', '-') + '').after("<span class='error-feedback'>" + error + '</span>');
                });
                showFieldsetErrors();
            },
            success: function () {
                $(location).attr('href', '/recipes');
            },
        });
    });

    /*
     *
     * Recipe Page
     *
     */

    // Upload Recipe Image
    $('.upload-file').on('change', function () {
        var file = new FormData();
        var files = this.files[0];

        file.append('recipeImage', files);
        file.append('recipeId', recipeId);

        // Start loading Spinner
        showLoadingSpinner();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: 'upload-recipe-image',
            type: 'POST',
            data: file,
            contentType: false,
            processData: false,
            error: function (jqXhr) {
                var errorObj = JSON.parse(jqXhr.responseText);

                $('.upload-errors').remove();
                $('<ul class="upload-errors">').appendTo('.recipe-image-container');

                for (var i = 0; i < errorObj.errors.recipeImage.length; i++) {
                    $('<li />', {
                        html: errorObj.errors.recipeImage[i],
                    }).appendTo('.upload-errors');
                }
            },
            success: function (data) {
                // hide loading spinner
                hideLoadingSpinner();
                $('.upload-errors').remove();

                $('<img />', {
                    src: data.url,
                    alt: 'Recipe Image',
                }).appendTo($('.recipe-image'));

                $('<div class="delete-recipe-image"></div>').appendTo($('.recipe-image'));

                $('.upload-recipe-img-text').text('Change Recipe Image');
            },
        });
    });

    // Delete Recipe Image
    $(document).on('click', '.delete-recipe-image', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: 'delete-recipe-image',
            type: 'POST',
            data: { recipeId: recipeId },
            success: function (data) {
                // reset file upload
                $('#recipe-image-input').val('');

                $('.recipe-image > img').remove();
                $('.recipe-image .delete-recipe-image').remove();
                $('.upload-errors').remove();

                $('.upload-recipe-img-text').text('Upload Recipe Image');
            },
        });
    });

    /*
     *
     * Functions
     *
     */

    function gatherRecipeInfo(form) {
        var recipeData = form.serialize();
        var macroInfo = {};

        $('#calorie-info table tbody tr').each(function () {
            var macroName = $(this).children(':first-child').data('macroName');
            var macroValue = $(this).children(':last-child').data('macroValue');

            macroInfo[macroName] = macroValue;
        });

        return (recipeData += '&' + $.param(macroInfo));
    }

    function findIngredient() {
        var query = $('#ingredient-searchbox').val();
        var currentIngredients = [];

        $.each($('.ingredient-amount-input'), function () {
            currentIngredients.push($(this).attr('name'));
        });

        if (query.length >= 2 && query.trim() !== '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });
            $.ajax({
                url: '/ingredients/find/' + query,
                type: 'POST',
                data: { currentIngredients },
                error: function (jqXhr) {
                    var errors = jqXhr.responseJSON.errors;

                    $('#find-ingredient').children('.error-feedback').remove();

                    $('#find-ingredient').append("<span class='error-feedback'>" + errors + '</span>');
                    $('#ingredient-listbox').remove();
                },
                success: function (data) {
                    var ingredients = JSON.parse(data);
                    var listbox = $('<ul />', {
                        id: 'ingredient-listbox',
                        class: 'listbox',
                        role: 'listbox',
                        'aria-labelledby': 'ingredient-search',
                    });

                    if ($('#ingredient-listbox').length === 0) {
                        listbox.insertAfter($('#ingredient-searchbox'));
                    } else {
                        $('#ingredient-listbox').empty();
                    }

                    $.each(ingredients, function (index, item) {
                        $('<li />', {
                            id: 'option-' + index,
                            class: 'ingredient-item',
                            'data-ingredient-id': item.id,
                            role: 'option',
                            html: item.name + ' - ' + item.brand_name,
                        }).appendTo('#ingredient-listbox');
                    });

                    $('#find-ingredient').attr('aria-expanded', 'true');
                    $('#find-ingredient').addClass('listbox-active');
                    $('#find-ingredient').children('.error-feedback').remove();
                },
            });
        }
    }

    function addIngredient(ingredient) {
        var ingredientId = ingredient.data('ingredient-id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/ingredients/get-ingredient/' + ingredientId,
            type: 'POST',
            error: function (jqXhr) {
                // var errors = jqXhr.responseJSON.errors;
            },
            success: function (ingredient) {
                if ($('#ingredient-list-table').length >= 1) {
                    $('.recipe-info').children('.fieldset-next').remove();
                    $('#ingredient-list-table tbody').append(
                        partialIngredientsTableTemplate({
                            id: ingredient.id,
                            name: ingredient.name,
                            brand: ingredient.brand_name,
                            metric: ingredient.food_group.metric,
                        })
                    );
                } else {
                    // $('.instruction').addClass('d-none');
                    $("<div id='ingredient-list'></div>")
                        .html(
                            fullIngredientsTableTemplate({
                                id: ingredient.id,
                                name: ingredient.name,
                                brand: ingredient.brand_name,
                                metric: ingredient.food_group.metric,
                            })
                        )
                        .prependTo('.recipe-info');
                }
                resetIngredientSearch();
            },
        });
    }

    function showCalorieInfo() {
        var data = $('.ingredient-amount-input').serializeArray();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/recipes/get-nutrition-data',
            type: 'POST',
            data: data,
            error: function (jqXhr) {
                // var errors = jqXhr.responseJSON.errors;
            },
            success: function (data) {
                var nutritionData = JSON.parse(data);

                $('#calorie-info').remove();
                $("<div id='calorie-info'></div>").html(calorieInformationTemplate(nutritionData)).insertAfter('#ingredient-list');

                if ($('.recipe-info').children('.fieldset-next').length === 0) {
                    generateNextButton($('.recipe-info'));
                }

                $.each($('.ingredient-amount-input'), function () {
                    if ($(this).val() === '') {
                        $('.recipe-info').children('.fieldset-next').remove();
                    }
                });

                // Only remove calorie information if no other ingredient amount has a value
                if (fieldsHaveValue().length === 0) {
                    $('#calorie-info').remove();
                    $('.recipe-info').children('.fieldset-next').remove();
                }
            },
        });
    }

    function resetIngredientSearch() {
        $('#ingredient-searchbox').val('');
        $('#ingredient-listbox').remove();
    }

    function generateNextButton(selector) {
        $('<button />', {
            type: 'button',
            class: 'btn btn-primary fieldset-next',
            html: 'Next',
        }).appendTo(selector);
    }

    function fieldsHaveValue() {
        return $('.ingredient-amount-input').filter(function () {
            return $(this).val() !== '';
        });
    }

    function showFieldsetErrors() {
        if ($('fieldset').find('.input-error')) {
            $('fieldset').hide();
            $('.input-error').first().parents('fieldset').show();
        }
    }

    // Show loading-spinner
    function showLoadingSpinner() {
        $('.loading-spinner img').show();
    }

    // Hide loading-spinner
    function hideLoadingSpinner() {
        $('.loading-spinner img').hide();
    }
});
