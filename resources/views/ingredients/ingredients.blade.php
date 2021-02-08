@extends('layouts.dashboard')
@section('content')
    <div class="main-content-wrapper">
        <header class="main-heading">
            <h2>{{ $pageName }}</h2>
            <a class="btn-small" href="/ingredients/create">{{ __('Add Ingredient') }}</a>
        </header>

        @if (Session::has('success-msg'))
            <div class="alert alert-success">
                {{ Session::get('success-msg') }}
            </div>
        @endif

        @if ($foodInformation->isNotEmpty())
            <div class="responsive-table-layout">
                <table class="responsive-table ingredient-table">
                    <thead>
                        <tr>
                            <th scope="col">@sortablelink('name', 'Ingredient')</th>
                            <th scope="col">@sortablelink('brand_name', 'Brand')</th>
                            <th scope="col">@sortablelink('food_type', 'Food Type')</th>
                            <th scope="col" class="table-num">@sortablelink('calories', 'Calories')</th>
                            <th scope="col" class="table-num">@sortablelink('fat', 'Fat')</th>
                            <th scope="col" class="table-num">@sortablelink('carbohydrates', 'Carbs')</th>
                            <th scope="col" class="table-num">@sortablelink('protein', 'Protein')</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach ($foodInformation as $ingredient)
                            <tr>
                                <td data-label="Ingredient">{{ $ingredient->name }}</td>
                                <td data-label="Brand">{{ $ingredient->brand_name }}</td>
                                <td data-label="Food Type">{{ $ingredient->food_type }}</td>
                                <td data-label="Calories" class="table-num">{{ $ingredient->calories.'kcal'}}</td>
                                <td data-label="Fat" class="table-num">{{ $ingredient->fat.'g'}}</td>
                                <td data-label="Carbs" class="table-num">{{ $ingredient->carbohydrates.'g'}}</td>
                                <td data-label="Protein" class="table-num">{{ $ingredient->protein.'g'}}</td>
                                <td>
                                    <div class="ingredient-actions">
                                        <a class="btn-tertiary table-action edit-ingredient" href="/ingredients/{{$ingredient->id}}/edit"></a>
                                        <form action="/ingredients/{{$ingredient->id}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn-tertiary table-action delete-ingredient" data-ingredient-id="{{$ingredient->id}}"></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $foodInformation->appends(\Request::except('page'))->render() !!}
        @endif
    </div>
@endsection
