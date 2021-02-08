@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">

    <header class="main-heading">
        <h2>Meal Plans</h2>
        <a class="add-btn" href="/meal-plans/create">{{ __('Add Meal Plan') }}</a>
        <hr>
    </header>

    @if (Session::has('success-msg'))
    <div class="alert alert-success">
        {{ Session::get('success-msg') }}
    </div>
    @endif

    <div id="meal-plan-cards">
        @foreach ($mealPlans as $mealPlan)  
        <a class="meal-plan-card" href="/meal-plans/{{ $mealPlan->id }}">
            <h3>{{$mealPlan->name}}</h3>

            <div class="card-content">
                <p>{{$mealPlan->description}}</p>

                <div class="meal-plan-info">
                    <div class="meal-plan-calorie-info">
                        <img src="{{ asset('/images/icons/calories.svg') }}" alt="Daily Calories Icon">
                        <span>{{$mealPlan->daily_calories}} kcal</span>
                    </div>
                    <div class="meal-plan-days">
                        <img src="{{ asset('/images/icons/calendar-day.svg') }}" alt="Meal Plan Day Icon">
                        <span>{{$mealPlan->days}} Days</span>

                    </div>
                </div>
            </div>  
        </a>
        @endforeach
    </div>
</div>
@endsection 