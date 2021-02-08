@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <div id="create-meal-plan">
        <form id="add-meal-plan-form" method="POST" autocomplete="off">
            <fieldset id="meal-plan-information" class="d-none">
                <header class="main-heading">
                    <h2>Create Meal Plan</h2>
                    <hr>
                </header>

                <div>
                    <label for="meal-plan-name">Meal Plan Name*</label>
                    <input class="input-box" id="meal-plan-name" name="meal_plan_name" type="text">
                </div>

                <div id="meal-plan-days-wrapper" class="select custom-select">
                    <label id="meal-plan-days-label">Number of Days in Meal plan</label>
                    <select name="meal_plan_days">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                    </select>
                </div>

                <div>
                    <label for="daily_calories">Choose Daily Calorie Intake*</label>
                    <input class="input-box" id="daily_calories" name="daily_calories" type="text">
                </div>
                <button type="button" class="btn btn-primary fieldset-btn">Next</button>
            </fieldset>

            <fieldset id="meal-plan-meals" >
                <header class="main-heading">
                    <h2>Create </h2>
                    <hr>
                </header>

                <div class="back-btn-wrapper">
                    <button type="button" class="btn-tertiary back back-btn">Back to Meal Plan Information</button> 
                </div>

                <div id="days-header">
                    <ul>
                        <li class="active">Day 1</li>
                    </ul>
                </div>

                <div id="add-meal-btn-wrapper">
                    <div id="add-meal-btn" class="select btn-select">
                        <label class="sr-only" id="meal-select-label"></label>
                        <select name="meal_select">
                            <option></option>
                            <option value="breakfast">Breakfast</option>
                            <option value="meal">Meal</option>
                            <option value="fruit">Fruit</option>
                            <option value="snack">Snack</option>
                            <option value="pre-workout">Pre-Workout Meal</option>
                            <option value="post-workout">Post-Workout Meal</option>
                            <option value="beverage">Beverage</option>
                            <option value="alcoholic-beverage">Alcoholic Beverage</option>
                        </select>
                    </div>
                </div>

                <div id="add-food-item-placeholder-wrapper">
                    <div class="add-food-item-placeholder">
                        <div class="placeholder-text">Add a Recipe or Food Item to Your Meal Plan</div> 
                    </div>      
                </div>

                <div id="meal-plan-days-list">
                    <div class="day-1 meal-plan-list active"></div>
                </div>
            </fieldset> 
        </form>
    </div>
</div>
@endsection