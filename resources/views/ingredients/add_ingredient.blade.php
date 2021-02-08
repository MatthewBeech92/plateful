@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <h2>Add Ingredient</h2>
    </header>

    <div class="form-container-center">
        <form id="add-ingredient-form" class="form-layout" method="POST" autocomplete="off">
            @csrf
            <fieldset class="form-step-1">
                <header>
                    <h3>Ingredient Information</h3>
                </header>
    
                <div>
                    <label for="ingredient-name">Ingredient Name</label>
                    <input class="input-box" type="text" id="ingredient-name" name="ingredient_name">
                </div>
    
                <div>
                    <label for="brand-name">Brand Name</label>
                    <input class="input-box" type="text" id="brand-name" name="brand_name">  
                </div>              
    
                <div>
                    <label for="food-type">Food Type</label>
                    <input class="input-box" type="text" id="food-type" name="food_type">      
                </div>         
                
                <div>
                    <label for="serving-size">Serving Size</label>
                    <div id="serving-size-input">
                        <input class="input-box" type="text" id="serving-size" name="serving_size">      
                    </div>
                </div>      
    
                <div class="custom-select">
                    <label>Food Category</label>
                    <select name="food_category">  
                        <option></option>
                        <option>Alcohol Beverages</option>
                        <option>Beverages</option>
                        <option>Cereal and Cereal Products</option>
                        <option>Eggs</option>
                        <option>Fats and Oils</option>
                        <option>Fish and Fish Products</option>
                        <option>Fruit</option>
                        <option>Herbs and Spices</option>
                        <option>Meat and Meat Products</option>
                        <option>Milk and Milk Products</option>
                        <option>Miscellaneous Foods</option>
                        <option>Nuts and Seeds</option>
                        <option>Soups and Sauces</option>
                        <option>Sugars, Preserves and Snacks</option>
                        <option>Vegetables</option>
                    </select>
                </div>
                
                <div class="multiple-select">
                    <label for="meal-type">Meal Type</label>
                    <select id="meal-type" name="meal_type[]" multiple>
                        <option></option>
                        <option value="breakfast">Breakfast</option>
                        <option value="meal">Meal</option>
                        <option value="fruit">Fruit</option>
                        <option value="snack">Snack</option>
                        <option value="beverage">Beverage</option>
                    </select>
                </div>  
    
                <div class="custom-select">
                    <label for="food_metric">Food Metric</label>
                    <select name="food_metric">
                        <option></option>
                        <option>g</option>
                        <option>kg</option>
                        <option>tsp</option>
                        <option>tbsp</option>
                        <option>spray</option>
                        <option>slice</option>
                        <option>--</option>
                    <select>
                </div>
                
                <div class="fieldset-next-div">
                    <button type="button" class="btn-tertiary fieldset-next">Add Nutritional Information</button> 
                </div>
            </fieldset>

            <fieldset class="form-step-2 d-none">
                <header>
                    <h3>Nutrition Information</h3>
                </header>

                <div class="back-btn-wrapper">
                    <button type="button" class="btn-tertiary fieldset-back back-btn">Back to ingredient information</button> 
                </div>

                <div class="responsive-input">
                    <div>
                        <label for="calories">Calories (100g/ml)</label>
                        <input class="input-box" type="text" id="calories" name="calories">
                    </div>
                    
                    <div>
                        <label for="fat">Fat (100g/ml)</label>
                        <input class="input-box" type="text" id="fat" name="fat">
                    </div>
            
                    <div>
                        <label for="saturated_fat">Saturated Fat (100g/ml)</label>
                        <input class="input-box" type="text" id="saturated-fat" name="saturated_fat">
                    </div>
                    
                    <div>
                        <label for="carbohydrates">Carbohydrates (100g/ml)</label>
                        <input class="input-box" type="text" id="carbohydrates" name="carbohydrates">
                    </div>
                    
                    <div>
                        <label for="sugars">Sugars (100g/ml)</label>
                        <input class="input-box" type="text" id="sugars" name="sugars">
                    </div>
                
                    <div>
                        <label for="fibre">Fibre (100g/ml)</label>
                        <input class="input-box" type="text" id="fibre" name="fibre">
                    </div>
                    
                    <div>
                        <label for="protein">Protein (100g/ml)</label>
                        <input class="input-box" type="text" id="protein" name="protein">
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary submit"> {{ __('Add Ingredient') }}</button>
        </form>
    </div>
</div>
@endsection