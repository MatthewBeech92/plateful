@extends('layouts.dashboard')
@section('content')
<div class="main-content-wrapper">
    <header class="main-heading">
        <a class="btn-tertiary back-btn" href="/recipes">Recipes</a>
        <a class="btn-small" href="/recipes/{{ $recipe->id }}/edit"> Edit Recipe</a>
    </header>

    <div class="recipe-container"> 
        <div class="header-container">
            <header>
                <h2 title="{{ $recipe->name }}"> {{ $recipe->name }}</h2>
                <h3 class="recipe-time">{{ $recipe->time }}</h3>
            </header>

            <div class="recipe-image-container">
                <div class="recipe-image">
                    <div class="loading-spinner">
                        <img src="{{ asset('images/loading-spinner.gif') }}" alt="Loading Spinner">
                    </div>
                    @isset($recipe->image)
                        <img src="{{ asset('storage/'. $recipe->image) }}" alt="Recipe Image">
                        <div class="delete-recipe-image"></div>
                    @endisset
                </div>

                <form class="recipe-image-upload upload">
                    <input id="recipe-image-input" type="file" class="upload-file" name="file">
                    <label class="btn upload-btn" for="recipe-image-input">
                        <img src="{{ asset('images/icons/upload-btn.svg') }}" alt="Upload Icon">    
                        @if (isset($recipe->image))
                            <span class="upload-recipe-img-text">Change Recipe Image</span>
                        @else
                            <span class="upload-recipe-img-text">Upload Recipe Image</span>
                        @endif
                    </label>
                </form>
            </div>

            @isset($recipe->description)
                <div class="recipe-description">
                    {{ $recipe->description }}
                </div>
            @endisset
        </div>
        
        <hr>

        <div class="ingredient-info">
            <div id="nutrition-facts">
                <h2>Nutrition Facts</h2>
                <table class="two-column-table">
                    <tbody>
                        <tr>
                            <td>Calories</td>
                            <td>{{$recipe->calories}}kcal</td>
                        </tr>
                        <tr>
                            <td>Fat</td>
                            <td>{{$recipe->fat}}g</td>
                        </tr>
                        <tr>
                            <td>Carbohydrates</td>
                            <td>{{$recipe->carbohydrates}}g</td>
                        </tr>
                        <tr>
                            <td>Fibre</td>
                            <td>{{$recipe->fibre}}g</td>
                        </tr>
                        <tr>
                            <td>Protein</td>
                            <td>{{$recipe->protein}}g</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="recipe-ingredients">
                <h2>Ingredients</h2>
                <table class="two-column-table">
                    <tbody>
                        @foreach ($recipe->ingredients as $ingredient)
                            <tr>
                                <td>{{$ingredient->name}}</td>
                                <td>{{$ingredient->pivot->amount}}g</td>
                            </tr>   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        @isset($recipe->instructions)
            <div class="recipe-instructions">
                <h2>Instructions</h2>
                <p>
                    @php echo preg_replace('/[\r\n]+/', "</p>\n<p>", $recipe->instructions); @endphp
                </p>
            </div>
        @endisset
    </div>
</div>
@endsection