<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingredient;
use App\Recipe;
use App\IngredientRecipe;
use App\Tag;
use App\Taggable;
use App\Http\Requests\StoreRecipe;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $recipes = Recipe::all();

        return view('recipes/index', [
            'recipes' => $recipes
        ]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recipes/add_recipe'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreRecipe $request)
    {
        DB::transaction(function () use ($request) {
            $recipe = Recipe::firstOrCreate([
                'name' => request('recipe_name'),
                'description' => request('recipe_description'),
                'instructions' => request('recipe_instructions'),
                'time' => request('recipe_time'),
                'calories' => request('calories'),
                'fat' => request('fat'),
                'of_which_saturates' => request('of_which_saturates'),
                'carbohydrates' => request('carbohydrates'),
                'of_which_sugars' => request('of_which_sugars'),
                'fibre' => request('fibre'),
                'protein' => request('protein')
            ]);

            foreach ($request->ingredient as $ingredientId => $ingredientAmount) {
                $recipe->ingredients()->attach($ingredientId, ['amount' => $ingredientAmount]);
            }
            
            foreach($request->meal_type as $mealType) {
                $recipeTagId = Tag::where('meal_type', $mealType)->pluck('id')->first();

                $recipe->tags()->attach($recipeTagId);
            }            
        });

        $request->session()->flash('success-msg', 'Successfully added the "' . request('recipe_name') . '" recipe.');        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return view('recipes/recipe', [
            'recipe' => $recipe
        ]);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit_recipe', [
            'recipe' => $recipe
        ]);
    }

    /** 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRecipe $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $recipe = Recipe::find($id);
                
            $recipeDetails = Recipe::where('id', $id)->update([
                'name' => request('recipe_name'),
                'description' => request('recipe_description'),
                'instructions' => request('recipe_instructions'),
                'time' => request('recipe_time'),
                'calories' => request('calories'),
                'fat' => request('fat'),
                'of_which_saturates' => request('of_which_saturates'),
                'carbohydrates' => request('carbohydrates'),
                'of_which_sugars' => request('of_which_sugars'),
                'fibre' => request('fibre'),
                'protein' => request('protein')
            ]);

            $recipe->ingredients()->detach();
            foreach ($request->ingredient as $ingredientId => $ingredientAmount) {
            $existingIngredientIds = $recipe->ingredients->pluck('id');

                if ($existingIngredientIds) {
                    $recipe->ingredients()->attach($ingredientId, ['amount' => $ingredientAmount]);
                }        
            }
        
            $recipe->tags()->detach();
            foreach($request->meal_type as $mealType) {
                $recipeTagId = Tag::where('meal_type', $mealType)->pluck('id')->first();

                if($recipeTagId && !$recipe->tags->contains($recipeTagId)){
                    $recipe->tags()->attach($recipeTagId);
                }
            }
        });
    
        $request->session()->flash('success-msg', 'Successfully updated the "' . request('recipe_name') . '" recipe.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        Session::flash('success-msg', 'Successfully Deleted ' . $recipe->name . ' Recipe');

        return redirect()->action('RecipeController@index');
    }

    public function filterRecipes($query) 
    {
        $recipes = Recipe::where('name', 'like', '%'.$query.'%')->get();

        if ($recipes->isEmpty()) {
            return response()->json(['error' => 'No recipes found'], 422);
        } else {
            return json_encode($recipes); 
        }
    }

    public function getNutritionData(Request $request)
    {  
        foreach ($request->ingredient as $ingredientId => $ingredientAmount) {
            $nutritionValues = [];

            if(!isset($ingredientAmount)){
                $ingredientAmount = 0;
            }

            $nutritionData[$ingredientId][$ingredientAmount] = Arr::collapse(Ingredient::select('food_group_id', 'serving_size','calories', 'fat', 'of_which_saturates', 'carbohydrates', 'of_which_sugars', 'fibre', 'protein')->with('foodGroup:id,metric')->
            where('id', '=', $ingredientId)
            ->get()->toArray());
        }

        foreach ($nutritionData as $ingredientId => $ingredientInfo) {
            foreach ($ingredientInfo as $amount => $ingredient) {
                $macroArray = Arr::except($ingredient, ['food_group_id', 'serving_size', 'food_group']);
                $servingSize = $ingredient['serving_size'];

                foreach ($macroArray as $macro => $macroValue) {
                    switch ($ingredient['food_group']['metric']) {
                        case 'tbsp':
                            $calculatedNutritionValues[$macro] = round($macroValue /100 * ($amount * 15), 1);
                            break;
                        case 'tsp':
                            $calculatedNutritionValues[$macro] = round($macroValue /100 * ($amount * 5), 1);
                            break;
                        case 'spray':
                        case 'slice':
                        case '--':
                            $calculatedNutritionValues[$macro] = round($macroValue /100 * ($amount * $servingSize), 1);
                            break;
                        default:
                        $calculatedNutritionValues[$macro] = round($macroValue /100 * $amount, 1);
                    }
                }
            }
            array_push($nutritionValues, $calculatedNutritionValues);
        }

        //Add Macros together using array_sum with array_column method
        foreach($nutritionValues as $ingredientMacros) {
            foreach ($ingredientMacros as $macro => $value) {
                $macros[$macro] = round(array_sum(array_column($nutritionValues , $macro)), 1);
            }
        }

        //Round calories to whole number
        $macros['calories'] = round($macros['calories']);

        return json_encode($macros);  
    }

    /*
    public function getNutritionData(Request $request)
    {   
        return json_encode($this->calculateNutritionData($this->gatherNutritionData($request->ingredient)));
    }

    protected function gatherNutritionData($ingredient)
    {
        foreach ($ingredient as $ingredientId => $ingredientAmount) {

            if(!isset($ingredientAmount)){
                $ingredientAmount = 0;
            }

            $nutritionData[$ingredientId][$ingredientAmount] = Arr::collapse(Ingredient::select('calories', 'fat', 'of_which_saturates', 'carbohydrates', 'of_which_sugars', 'fibre', 'protein')->
            where('id', '=', $ingredientId)
            ->get()->toArray());
        }
        return $nutritionData;
    }   

    protected function calculateNutritionData($nutritionData) 
    {
        $nutritionValues = [];

        foreach ($nutritionData as $ingredientId => $ingredientInfo) {
            foreach ($ingredientInfo as $amount => $defaultMacros) {
                foreach ($defaultMacros as $macro => $value) {
                    $calculatedNutritionValues[$macro] = round($value /100 * $amount, 1);
                }
            }
            array_push($nutritionValues, $calculatedNutritionValues);
        }

        //Add Macros together using array_sum with array_column method
        foreach($nutritionValues as $ingredientMacros) {
            foreach ($ingredientMacros as $macro => $value) {
                $macros[$macro] = round(array_sum(array_column($nutritionValues , $macro)), 1);
            }
        }

        //Round calories to whole number
        $macros['calories'] = round($macros['calories']);

        return $macros;  
    }
    */
    protected function uploadRecipeImage(Request $request)
    {
        $recipeId = request('recipeId');
        
        $validateImage = Validator::make($request->all(), [
            'recipeImage' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ])->validate();
    
        $recipe = Recipe::find($recipeId);

        if ($recipe->image !== null) {
            $this->deleteRecipeImage(null, $recipeId);
        }

        $file = $request->file('recipeImage')->store('recipe-images');
        $recipe->image = $file;
        $recipe->save();

        return response()->json(['url' => Storage::url($file)]);
    }

    protected function deleteRecipeImage(Request $request = null, $recipeId = null) 
    {
        if (request('recipeId') !== null) {
            $recipeId = request('recipeId');
        } 
        
        $recipe = Recipe::find($recipeId);

        Storage::delete($recipe->image);
        $recipe->image = NULL;
        $recipe->save();
    }

}

