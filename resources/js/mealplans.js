var mealPlansTemplate = require('./templates/meal-plans/mealPlans.handlebars');
var foodPageTemplate = require('./templates/meal-plans/foodPage.handlebars');
var recipeCardCalorieTemplate = require('./templates/meal-plans/recipeCardCalories.handlebars');
var showFoodItemTemplate = require('./templates/meal-plans/showFoodItem.handlebars');
var foodItemTemplate = require('./templates/meal-plans/foodItem.handlebars');
var mealPlanCalorieDataTemplate = require('./templates/meal-plans/mealPlanCalorieData.handlebars');

$(function () {
    var daysArray = [{}];
    var mealType;

    /*
     *
     * meal plan information fieldset events
     *
     */

    $('#meal-plan-days ~ ul li').on('click', function () {
        $('#meal-plan-days-wrapper select').trigger('change');
    });

    // When user selects how many days in meal plan, render the days header
    $('#meal-plan-days-wrapper select').on('change', function () {
        var days = $(this).val();

        $('#days-header ul').empty();
        daysArray.splice(days);

        for (var i = 1; i <= days; i++) {
            if (daysArray[i - 1] === undefined) {
                daysArray.push({});
            }

            $('#days-header ul').append('<li>Day ' + i + '</li>');
            if ($('#meal-plan-days-list .day-' + i).length === 0) {
                $('#meal-plan-days-list').append('<div class="day-' + i + ' meal-plan-list"></div>');
            }
        }

        $('#meal-plan-days-list .meal-plan-list').slice(days).remove();

        // Set day 1 to the active day
        $('#days-header ul li').first().addClass('active');
        $('.meal-plan-list').first().addClass('active');

        toggleSaveButton();
    });

    // Validate meal plan information data, then show next fieldset
    $('#meal-plan-information button').on('click', function (evt) {
        var mealPlanInfo = $('#meal-plan-information').serializeArray();
        var activeFieldset = $(this).parent('fieldset');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/validate-meal-plan-info',
            type: 'POST',
            data: mealPlanInfo,
            error: function (jqXhr) {
                var errors = jqXhr.responseJSON.errors;

                $('.error-feedback').remove();
                $('input').removeClass('input-error');

                $.each(errors, function (field, error) {
                    $('input[name="' + field + '"]').addClass('input-error');
                    $('input[name="' + field + '"]').after("<span class='error-feedback'>" + error + '</span>');
                });
            },
            success: function (data) {
                if (data === 'success') {
                    $('.error-feedback').remove();
                    $('input').removeClass('input-error');

                    activeFieldset.hide();
                    activeFieldset.next().show();
                    $('.fieldset-back').show();
                }
            },
        });
    });

    /*
     *
     * meal plan meals fieldset events
     *
     */

    // When user clicks on a different day update the active day
    $('#days-header ul').on('click', 'li', function () {
        var activeDay;

        $('#days-header ul li').removeClass('active');
        $(this).addClass('active');

        activeDay = $('#days-header ul li').index($('.active'));

        $('.meal-plan-list').removeClass('active');
        $('.meal-plan-list').eq(activeDay).addClass('active');
    });

    // Show all recipes by meal type
    $('#add-meal-btn ul li').on('click', function () {
        mealType = $(this).data('optionValue');

        $('#add-meal-plan-form').hide();
        $('#add-meal-plan-form').after(
            foodPageTemplate({
                mealType: $(this).text(),
            })
        );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/meal-plans/show-foods-by-meal/' + mealType,
            type: 'POST',
            error: function (jqXhr) {
                console.log(jqXhr.responseText);
            },
            success: function (data) {
                var recipeCards = $('.foods-list').children('.recipe-cards');

                $('.foods-list')
                    .children('.pagination-container')
                    .paginate({
                        dataObj: function (callback) {
                            callback(data);
                        },
                        pageSize: 16,
                        showFirst: true,
                        showLast: true,
                        displayData: function (data) {
                            $('.recipe-card').remove();

                            $.each(data, function (_key, item) {
                                recipeCards.append(
                                    recipeCardCalorieTemplate({
                                        id: item.id,
                                        image: item.image,
                                        name: item.name,
                                        calories: item.calories,
                                        food_type: item.pivot.taggable_type,
                                    })
                                );
                            });
                        },
                    });
            },
        });
    });

    /*
     *
     * Add meal interface events
     *
     */

    // Filter recipes and display results
    $(document).on('keyup', '#food-item-search', function (evt) {
        var query = $(this).val();

        if (query.length > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });

            $.ajax({
                url: '/filter-foods/' + query + '/' + mealType + '',
                type: 'POST',
                error: function (jqXhr) {
                    var error = jqXhr.responseJSON.error;

                    $('.error-feedback').remove();
                    $('<div class="error-feedback">' + error + '</div>').appendTo('#food-item-search-container');
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
                            recipeCardCalorieTemplate({
                                id: item.id,
                                image: item.image,
                                name: item.name,
                                calories: item.calories,
                                food_type: item.pivot.taggable_type,
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

    // Display food item
    $(document).on('click', '.foods-list .recipe-card', function () {
        var foodType = $(this).data('foodType');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });

        $.ajax({
            url: '/meal-plans/display-food/' + foodType + '/' + $(this).data('foodId'),
            type: 'POST',
            error: function (jqXhr) {
                console.log(jqXhr.responseText);
            },
            success: function (food) {
                $('#find-food').hide();

                $('#add-meal-interface').append(
                    showFoodItemTemplate({
                        food_type: foodType,
                        id: food.id,
                        name: food.name,
                        image: food.image,
                        time: food.time,
                        description: food.description,
                        calories: food.calories,
                        fat: food.fat,
                        carbs: food.carbohydrates,
                        fibre: food.fibre,
                        protein: food.protein,
                        ingredients: food.ingredients,
                        instructions: function () {
                            if (food.instructions === null || food.instructions === undefined) {
                                return '';
                            } else {
                                return food.instructions.split(/\n\s*\n/).join('</p><p>');
                            }
                        },
                    })
                );
            },
        });
    });

    // Add recipe to meal planner
    $(document).on('click', '#add-to-meal-plan-btn', function (evt) {
        evt.preventDefault();

        if ($('.meal-plan-list.active').find('.recipe-item').length === 0) {
            $('.meal-plan-list.active').append(mealPlansTemplate());
        }

        $('.meal-plan-list.active .' + mealType + '').append(
            foodItemTemplate({
                food_id: $('.food-container').data('foodId'),
                food_type: $('.food-container').data('foodType'),
                food_image: $('.recipe-image img').attr('src'),
                food_name: $('.recipe-container header h2').text(),
                calories: parseInt($('#calorie-data').text()),
                fat: parseInt($('#fat-data').text()),
                carbs: parseInt($('#carb-data').text()),
                protein: parseInt($('#protein-data').text()),
                meal_type: mealType,
            })
        );

        calculateCalorieData();

        $('.meal-plan-list.active .' + mealType + '').show();
        $('#add-meal-plan-form').show();
        $('#add-food-item-placeholder-wrapper').hide();
        $('#add-meal-interface').remove();

        toggleSaveButton();
    });

    // Delete recipe from meal planner
    $(document).on('click', '.delete-food-item button', function (evt) {
        var recipeItem = $(this).parents('.recipe-item');
        var recipeItemType = recipeItem.parent();

        evt.preventDefault();

        recipeItem.remove();

        calculateCalorieData();
        toggleSaveButton();

        if ($('.meal-plan-list.active .recipe-item').length === 0) {
            $('.meal-plan-list.active').children().remove();
        }

        if (recipeItemType.children('div').length === 0) {
            recipeItemType.hide();
        }

        if ($('.recipe-item').length === 0) {
            $('#add-food-item-placeholder-wrapper').show();
        }
    });

    // Save meal Plan
    $(document).on('click', '#save-meal-plan', function (evt) {
        evt.preventDefault();

        // Send meal plan information to 4 to store in database
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/meal-plans/',
            type: 'POST',
            data: serializeMealPlanData(),
            error: function (jqXhr) {
                console.log(jqXhr.responseText);
                // var errors = jqXhr.responseJSON.errors;

                // $('.error-feedback').remove();
                // $('input').removeClass('input-error');

                // $.each(errors, function (field, error) {
                //     $('input[name="' + field + '"]').addClass('input-error');
                //     $('input[name="' + field + '"]').after("<span class='error-feedback'>" + error + '</span>');
                // });

                // showFieldsetErrors();
            },
            success: function () {
                $(location).attr('href', '/meal-plans');
            },
        });
    });

    /*
     *
     * Back Buttons
     *
     */

    $('#meal-plan-meals .back').on('click', function () {
        $('#meal-plan-information').show();
        $('#meal-plan-meals').hide();
    });

    // Send user back to meal plan interface and remove the add meal interface from dom
    $(document).on('click', '#add-meal-interface-back', function () {
        $('#add-meal-plan-form').show();
        $('#add-meal-interface').remove();
    });

    // Send user back to recipe list from recipe information screen
    $(document).on('click', '#meal-planner-recipe-page .back', function () {
        $('#find-food').show();
        $('#meal-planner-recipe-page').remove();
    });

    /*
     *
     * functions
     *
     */

    function serializeMealPlanData() {
        var mealPlanInfo = $('#add-meal-plan-form').serialize();
        var foodItems = {};
        var day = {};

        // Gather food items in meal plan
        $.each($('.meal-plan-list'), function (index) {
            var dayNumber = index + 1;
            var dailyFoods = {};

            $.each($(this).find('.meal-type'), function () {
                if ($(this).css('display') === 'block') {
                    var mealType = $(this).data('mealType');
                    var foodItems = $(this).find($('.recipe-item'));
                    var foodInfo = [];

                    $.each(foodItems, function () {
                        var foodInfoObject = {
                            foodType: $(this).data('foodType'),
                            foodId: $(this).data('foodId'),
                        };

                        foodInfo.push(foodInfoObject);
                    });
                    dailyFoods[mealType] = foodInfo;
                }
            });
            day[dayNumber] = dailyFoods;
        });

        foodItems.day = day;

        return (mealPlanInfo += '&' + $.param(foodItems));
    }

    function toggleSaveButton() {
        var allDaysHaveRecipes = true;

        $('.meal-plan-list').each(function () {
            if ($(this).children('.day-recipe-list').length === 0) {
                allDaysHaveRecipes = false;

                return false;
            }
        });

        if (allDaysHaveRecipes === true) {
            if ($('#save-meal-plan').length === 0) {
                $('<div />', {
                    id: 'save-meal-plan',
                    html: $('<button />', {
                        class: 'btn btn-primary',
                        type: 'sumbit',
                        html: 'Save Meal Plan',
                    }),
                }).appendTo($('#meal-plan-meals'));
            }
        } else {
            $('#save-meal-plan').remove();
        }
    }

    function calculateCalorieData() {
        var activeDayIndex = $('#days-header ul .active').index();
        var totalCalories = $('#daily_calories').val();
        var nutritionValues = [];

        $.each($('.meal-plan-list.active .recipe-info'), function () {
            var macroInfo = {
                calories: $(this).data('calories'),
                fats: $(this).data('fat'),
                carbs: $(this).data('carbs'),
                protein: $(this).data('protein'),
            };

            nutritionValues.push(macroInfo);
        });

        if (nutritionValues.length !== 0) {
            var sumOfMacros = nutritionValues.reduce(function (prev, currentValue) {
                return {
                    calories: parseInt(prev.calories + currentValue.calories),
                    fats: parseInt(prev.fats + currentValue.fats),
                    carbs: parseInt(prev.carbs + currentValue.carbs),
                    protein: parseInt(prev.protein + currentValue.protein),
                };
            });

            daysArray[activeDayIndex] = sumOfMacros;

            $('.meal-plan-list.active .calorie-info').remove();
            $('.meal-plan-list.active').prepend(
                mealPlanCalorieDataTemplate({
                    calories: daysArray[activeDayIndex].calories,
                    totalCalories: totalCalories,
                    fat: daysArray[activeDayIndex].fats,
                    carbs: daysArray[activeDayIndex].carbs,
                    protein: daysArray[activeDayIndex].protein,
                })
            );
        } else {
            $('.meal-plan-list.active .calorie-info').remove();
        }
    }

    function showFieldsetErrors() {
        if ($('fieldset').find('.input-error')) {
            $('fieldset').hide();
            $('.input-error').first().parents('fieldset').show();
        }
    }
});
