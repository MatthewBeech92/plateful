<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ingredient;
use App\FoodGroup;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;


$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'ingredient_name' => $faker->word,
        'brand_name' => $faker->word,
        'food_group_id' => factory(FoodGroup::class),
        'calories' => $faker->numberBetween($min = 100, $max = 200),
        'fat' => $faker->numberBetween($min = 5, $max = 20),
        'of_which_saturates' => $faker->numberBetween($min = 0, $max = 5),
        'carbohydrates' => $faker->numberBetween($min = 5, $max = 40),
        'of_which_sugars' => $faker->numberBetween($min = 1, $max = 5),
        'fibre' => $faker->numberBetween($min = 0, $max = 10),
        'protein' => $faker->numberBetween($min = 0, $max = 40),
    ];
});

