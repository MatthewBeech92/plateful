@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <h2>Edit Recipe</h2>
        <form action="/recipes/{{$recipe->id}}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-small delete-btn">Delete Recipe</button>
        </form> 
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

            <form id="edit-recipe-form" action="/recipes/{{$recipe->id}}" autocomplete="off">
                @method('PUT')
                @csrf
                <fieldset>
                    <div>
                        <label for="recipe-name">Recipe Name</label>
                        <input class="input-box" id="recipe-name" name="recipe_name" type="text" value="{{$recipe->name}}">
                    </div>
                    <div>
                        <div class="multiple-select">
                            <label>Recipe Type</label>
                            <select name="meal_type[]" data-selected-option="{{$recipe->tags->implode('meal_type', '/')}}" multiple>
                                <option></option>
                                <option value="breakfast">Breakfast</option>
                                <option value="meal">Meal</option>
                                <option value="fruit">Fruit</option>
                                <option value="snack">Snack</option>
                                <option value="beverage">Beverage</option>
                            </select>
                        </div>  
                    </div>
                    <div>
                        <label for="recipe-desc">Recipe Description:</label>
                        <textarea class="" id="recipe-desc" name="recipe_description" rows="9">{{$recipe->description}}</textarea >
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
                        <div id="ingredient-list">
                            <h3>Ingredient List</h3>

                            <table id="ingredient-list-table">
                                <thead>
                                    <th scope="col">Ingredient</th>
                                    <th scope="col">Amount</th>
                                </thead>
                                <tbody>
                                    @foreach ($recipe->ingredients as $ingredient)
                                    <tr>
                                        <td>
                                            <div class="ingredient-name">
                                                {{$ingredient->name}}
                                            </div>
                                        </td>
                                        <td class="td-input">
                                            <div class="recipe-amount" data-metric="{{$ingredient->foodGroup->metric}}">
                                                <input type="text" class="ingredient-amount-input" name="ingredient[{{$ingredient->id}}]" value="{{$ingredient->pivot->amount}}">
                                            </div>
                                        </td>
                                        <td class="td-cross">
                                            <button class="cross-icon"> </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="calorie-info">
                            <h3>Calorie Information</h3>
                            <table class="two-column-table">
                                <tbody>
                                    <tr>
                                        <td data-macro-name="calories">Calories</td>
                                        <td data-macro-value="{{$recipe->calories}}">{{$recipe->calories}}<span>kcal</span></td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="fat">Fat</td>
                                        <td data-macro-value="{{$recipe->fat}}">{{$recipe->fat}}g</td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="of_which_saturates">of which saturates</td>
                                        <td data-macro-value="{{$recipe->of_which_saturates}}">{{$recipe->of_which_saturates}}g</td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="carbohydrates">Carbohydrates</td>
                                        <td data-macro-value="{{$recipe->carbohydrates}}"> {{$recipe->carbohydrates}}g</td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="of_which_sugars">of which sugars</td>
                                        <td data-macro-value="{{$recipe->of_which_sugars}}">{{$recipe->of_which_sugars}}g</td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="fibre">Fibre</td>
                                        <td data-macro-value="{{$recipe->fibre}}">{{$recipe->fibre}}g</td>
                                    </tr>
                                    <tr>
                                        <td data-macro-name="protein">Protein</td>
                                        <td data-macro-value="{{$recipe->protein}}">{{$recipe->protein}}g</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary fieldset-btn fieldset-next">Next</button>
                    </div>          
                </fieldset>

                <fieldset class="d-none">
                    <div class="back-btn-wrapper">
                        <button type="button" class="btn-tertiary fieldset-back back-btn">Add Ingredients</button> 
                    </div>
                    <div>
                        <label for="recipe-instruction">Recipe Instructions:</label>
                        <textarea id="recipe-instruction" name="recipe_instructions" rows="9">{{$recipe->instructions}}</textarea>
                    </div>
                    <div>
                        <div class="custom-select">
                            <label for="recipe-time">Recipe Time</label>
                            <select id="recipe-time" name="recipe_time" data-selected-option="{{$recipe->time}}">
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
                    <button type="button" class="btn btn-primary fieldset-btn fieldset-submit recipe-submit">Edit Recipe</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
@endsection




                                

                                        
