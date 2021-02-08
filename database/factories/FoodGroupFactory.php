<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FoodGroup;
use App\IngredientCategory;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;




$factory->define(FoodGroup::class, function (Faker $faker) {
    $ingredientCategoryArr = IngredientCategory::get('food_category')->toArray();
    $ingredientCategory = Arr::flatten($ingredientCategoryArr);

    return [
        'food_type' => $faker->word,
        'food_category' => Arr::random($ingredientCategory),
        'metric' => 'g'
    ];
});
