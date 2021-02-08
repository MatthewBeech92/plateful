<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/sidebar', 'HomeController@showSidebar');

Route::resource('ingredients', 'IngredientController');
Route::post('ingredients/find/{query}', 'IngredientController@findIngredient');
Route::post('ingredients/get-ingredient/{id}', 'IngredientController@getIngredient');
Route::post('ingredients/validate-ingredient', 'IngredientController@validateIngredient');


Route::resource('recipes', 'RecipeController');
Route::post('recipes/filter-recipes/{query}', 'RecipeController@filterRecipes');
Route::post('recipes/get-nutrition-data', 'RecipeController@getNutritionData');
Route::post('recipes/upload-recipe-image', 'RecipeController@uploadRecipeImage');
Route::post('recipes/delete-recipe-image', 'RecipeController@deleteRecipeImage');

Route::resource('meal-plans', 'MealPlanController');
Route::post('meal-plans/show-foods-by-meal/{type}', 'MealPlanController@showFoodsByMeal');
Route::post('filter-foods/{foodName}/{recipeType}', 'MealPlanController@filterFoods');
Route::post('meal-plans/display-food/{foodType}/{id}', 'MealPlanController@displayFood');
Route::post('validate-meal-plan-info', 'MealPlanController@validateMealPlanInformation');





