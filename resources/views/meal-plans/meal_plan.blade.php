@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <h2>{{$mealPlan->name}}</h2>
        @admin
            <a class="btn-small" href="/meal-plan/{{ $mealPlan->id }}/edit"> Edit Meal Plan</a>
        @endadmin    
        <hr>
    </header>

    <div class="back-btn-wrapper">
        <a class="btn-tertiary back-btn" href="/meal-plans">Back to Meal Plans</a>
    </div>

    <div id="days-header">
        <ul>       
            <li class="active">Day 1</li>
            @for ($i = 2; $i <= $mealPlan->days; $i++)
                <li>Day {{$i}}</li>
            @endfor                
        </ul>
    </div>

    <div class="calorie-info">
        {{$mealPlan->foods}}
        <div class="calorie-data">
            <h4>Calories</h4>
            <div>1000</div>
        </div>

        <div class="macro-info">
            <div class="fat">
                <h4>Fat</h4>
                <div>10g</div>
            </div>
            <div class="carbs">
                <h4>Carbs</h4>
                <div>25g</div>
            </div>
            <div class="protein">
                <h4>Protein</h4>
                <div>40g</div>
            </div>
        </div>

    </div>
         
    <div id="meal-plan-days-list">
        <div class="day-1 meal-plan-list active"></div>
    
    </div>
</div>
@endsection