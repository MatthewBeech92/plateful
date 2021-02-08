@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <h2>Recipes</h2>
        <a class="btn-small" href="/recipes/create">{{ __('Add Recipe') }}</a>
    </header>

    @if (Session::has('success-msg'))
        <div class="alert alert-success">
            {{ Session::get('success-msg') }}
        </div>
    @endif

    <form id="recipe-search-form" class="search-form" action="" autocomplete="off">
        <label class="sr-only" for="recipe-search-all"></label>
        <input class="input-box search" id="recipe-search-all" type="text" placeholder="Search Recipes...">
    </form>

    <div class="recipe-cards">
        @foreach ($recipes as $recipe)  
            <a class="recipe-card card" href="/recipes/{{ $recipe->id }}">
                <div class="recipe-image">
                    @isset($recipe->image)
                        <img src="{{ asset('storage/'. $recipe->image) }}" alt="Recipe Image">
                    @endisset
                </div>
                <div class="card-content">
                    <h3 title="{{ $recipe->name }}"> {{ $recipe->name }} </h3>
                    <small class="recipe-time">{{ $recipe->time }}</small>
                </div>
            </a>
        @endforeach
    </div>
</div>

@endsection