@extends('layouts.dashboard')

@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <h2>Ingredients</h2>
        <a class="btn-small" href="/ingredients/create">{{ __('Add Ingredient') }}</a>
    </header>

    <div class="ingredients-container">
        <div class="ingredient-categories">
            @foreach ($ingredientCategories as $ingredientCategory => $formattedCategory)   
            <div class="ingredient-card-container">
                <a class="ingredient-card card" href="/ingredients/{{$formattedCategory}}">
                    <img src="{{ asset('images/icons/icon-placeholder.svg') }}">
                    <h3 class="ingredient-type">{{$ingredientCategory}}</h3>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection