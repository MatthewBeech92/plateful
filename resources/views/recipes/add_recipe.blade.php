@extends('layouts.dashboard')
@section('content')
    <div class="main-content-wrapper">
        <header class="main-heading">
            <h2>Create Recipe</h2>
        </header>
    
        <div class="create-recipe-wrapper">
            <div class="create-recipe-interface">
                <div id="progress-bar-container">
                    <ul class="progress-bar">
                        <li class="active">Recipe Information</li>
                        <li>Add Ingredients</li>
                        <li>Recipe Instructions</li>
                    </ul>
                </div>

                <form id="add-recipe-form" method="POST" autocomplete="off">
                    <fieldset>
                        <div>
                            <label for="recipe-name">Recipe Name</label>
                            <input class="input-box" id="recipe-name" name="recipe_name" type="text">
                        </div>
                    
                        <div>
                            <div class="multiple-select">
                                <label>Recipe Type</label>
                                <select name="meal_type[]" multiple>
                                    <option></option>
                                    <option value="breakfast">Breakfast</option>
                                    <option value="meal">Meal</option>
                                    <option value="fruit">Fruit</option>
                                    <option value="snack">Snack</option>
                                    <option value="beverage">Beverage</option>
                                    <option value="pre-workout">Pre-Workout</option>
                                    <option value="post-workout">Post-Workout</option>
                                </select>
                            </div>  
                        </div>

                        <div>
                            <label for="recipe-desc">Recipe Description</label>
                            <textarea class="" id="recipe-desc" name="recipe_description" rows="9"> </textarea>
                        </div>
                        <button type="button" class="btn btn-primary fieldset-next">Next</button>
                    </fieldset>

                    <fieldset class="add-ingredients d-none">
                        <div class="back-btn-wrapper">
                            <button type="button" class="btn-tertiary fieldset-back back-btn">Recipe Information</button> 
                        </div>

                        <div id="find-ingredient" class="combobox" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-owns="ingredient-listbox">
                            <label id="ingredient-search" class="sr-only">Search for Ingredients</label>
                            <input id="ingredient-searchbox" class="input-box search combobox-search" type="text" placeholder="Search for Ingredients..." aria-autocomplete="list" aria-controls="ingredient-listbox"  aria-activedescendant>
                        </div>

                        <div class="recipe-info"> 
                        </div>          
                    </fieldset>

                    <fieldset class="d-none">

                        <div class="back-btn-wrapper">
                            <button type="button" class="btn-tertiary fieldset-back back-btn">Add Ingredients</button> 
                        </div>
                        
                        <div>                        
                            <div class="select custom-select">
                                <label id="recipe-time-label">Recipe Time</label>
                                <select class="mb-5" name="recipe_time">
                                    <option></option>
                                    <option value="5 mins">5 mins</option>
                                    <option value="10 mins">10 mins</option>
                                    <option value="15 mins">15 mins</option>
                                    <option value="20 mins">20 mins</option>
                                    <option value="25 mins">25 mins</option>
                                    <option value="30 mins">30 mins</option>
                                    <option value="30-45 mins">30-45 mins</option>
                                    <option value="45-60 mins">45-60 mins</option>
                                    <option value="60 mins+">60 mins +</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="recipe-instruction">Recipe Instructions</label>
                            <textarea id="recipe-instruction" name="recipe_instructions" rows="9"> </textarea>
                        </div>

                        <button type="button" class="btn btn-primary fieldset-submit recipe-submit">Add Recipe</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection