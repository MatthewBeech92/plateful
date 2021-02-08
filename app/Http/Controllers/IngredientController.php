<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use App\Ingredient;
use App\FoodGroup;
use App\IngredientCategory;
use App\Tag;
use App\Taggable;
use App\Http\Requests\StoreIngredientInformation;


class IngredientController extends Controller
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
        $ingredientCategories = IngredientCategory::all()->pluck('food_category')->toArray();
        $formattedCategories = [];

        foreach ($ingredientCategories as $ingredientCategory) {
            array_push($formattedCategories, Str::slug($ingredientCategory, '-'));
        }

        return view('ingredients/index', [
            'ingredientCategories' => array_combine($ingredientCategories, $formattedCategories)
        ]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ingredients/add_ingredient'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIngredientInformation $request)
    {    
        DB::transaction(function () {
            $ingredient = Ingredient::firstOrNew([
                'name' => request('ingredient_name'),
                'brand_name' => request('brand_name'),
                'serving_size' => request('serving_size'),
                'calories' => request('calories'),
                'fat' => request('fat'),
                'of_which_saturates' => request('saturated_fat'),
                'carbohydrates' => request('carbohydrates'),
                'of_which_sugars' => request('sugars'),
                'fibre' => request('fibre'),
                'protein' => request('protein')
            ]);

            $foodInformation = FoodGroup::firstOrCreate([
                'food_type' => request('food_type'),
                'food_category' => request('food_category'),
                'metric' => request('food_metric')
            ]);
        
            $food = FoodGroup::find($foodInformation->id);
            $food->ingredients()->save($ingredient);
            
            if (request('meal_type') !== null) {
                foreach(request('meal_type') as $mealType){
                    $mealTag = Tag::where('meal_type', $mealType)->first();
                    $ingredient->tags()->save($mealTag);
                }
            }            
        });

        $request->session()->flash('success-msg', 'Successfully added the "' . request('ingredient_name') . '" ingredient');
        
        return request('food_category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        $formattedCategory = str_replace('And', 'and', ucwords(str_replace('-', ' ', $category)));

        $ingredientInfo = Ingredient::join('food_groups', 'ingredients.food_group_id', '=', 'food_groups.id')
            ->where('food_groups.food_category',$formattedCategory)
            ->select('ingredients.id', 'name', 'brand_name', 'calories', 'fat', 'of_which_saturates', 'carbohydrates', 'of_which_sugars', 'fibre', 'protein', 'food_type', 'food_category')
            ->sortable('name')
            ->paginate(15);

        return view('ingredients.ingredients', [
            'pageName' => $formattedCategory,
            'foodInformation' => $ingredientInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ingredient = Ingredient::with('foodGroup')->where('id', $id)->get();
        $tags = Ingredient::find($id)->tags;

        return view('ingredients.edit_ingredient', [
            'ingredient' => $ingredient,
            'tags' => $tags->implode('meal_type', '/'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreIngredientInformation $request, $id)
    {        
        DB::transaction(function () use ($request, $id) {
            $ingredient = Ingredient::find($id);

            $ingredientDetails = Ingredient::where('id', $id)->update([
                'name' => request('ingredient_name'),
                'brand_name' => request('brand_name'),
                'serving_size' => request('serving_size'),
                'calories' => request('calories'),
                'fat' => request('fat'),
                'of_which_saturates' => request('saturated_fat'),
                'carbohydrates' => request('carbohydrates'),
                'of_which_sugars' => request('sugars'),
                'fibre' => request('fibre'),
                'protein' => request('protein')
            ]);

            $foodInformation = FoodGroup::updateOrCreate([
                'food_type' => request('food_type'),
                'food_category' => request('food_category'),
                'metric' => request('food_metric')
            ]);
            
            $food = FoodGroup::find($foodInformation->id);
            $food->ingredients()->save($ingredient);

            $ingredient->tags()->detach();

            if ($request->meal_type) {
                foreach($request->meal_type as $mealType) {
                    $ingredientTagId = Tag::where('meal_type', $mealType)->pluck('id')->first();
        
                    if($ingredientTagId){
                        $ingredient->tags()->attach($ingredientTagId);
                    }
                }
            }
        });
        
        $request->session()->flash('success-msg', 'Successfully updated "' . request('ingredient_name') . '" ingredient');
        
        return request('food_category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {        
        $ingredient->delete();

        Session::flash('success-msg', 'Successfully Deleted ' . $ingredient->name);

        return redirect()->action(
            'IngredientController@show', [$this->getPreviousCategory()]
        );    
    }

    public function findIngredient(Request $request, $query)
    {
        $ingredients = Ingredient::select('id', 'name', 'brand_name')
            ->whereRaw("concat(name, ' - ', brand_name) LIKE ?", "%".$query."%")
            ->orderBy('name', 'asc')
            ->get();

        // if ingredient has already been added, remove from search result
        if (isset($request['currentIngredients'])) {
            foreach ($request['currentIngredients'] as $currentIngredient) {
                preg_match_all("/\\[(.*?)\\]/", $currentIngredient, $ingredientId); 
                $ingredientId =  $ingredientId[1][0];

                foreach ($ingredients as $key => $ingredient) {
                    if ($ingredient['id'] == $ingredientId) {
                        unset($ingredients[$key]);
                    }
                }
            }
        }
        
        if ($ingredients->isEmpty()) {
            return response()->json(['errors' => 'No ingredients found'], 422);
        } else {
            return json_encode($ingredients); 
        }
    }

    public function getIngredient($id) 
    {
        $ingredients = Ingredient::select('id','name', 'brand_name', 'food_group_id')
        ->with('foodGroup:id,metric')->where('id', $id)
        ->get()
        ->first();
       
        return $ingredients;
    }

    public function validateIngredient(Request $request){       
        foreach ($request->get('ingredient') as $ingredientId => $ingredientAmount) {
            $ingredients = Ingredient::select('name', 'brand_name')
            ->where('id', $ingredientId)
            ->first();
            
            $attribute['ingredient.' . $ingredientId] = $ingredients['name'] . ' - ' . $ingredients['brand_name'];

            $validator = Validator::make($request->all(), [
                'ingredient.*' => 'numeric'
            ],[
                'ingredient.*.numeric' => 'The :attribute amount field must be a number.'
            ], $attribute);
    
            if ($ingredientAmount !== null && $validator->fails()) {
                return $validator->validate();
            } else {
                return 'ingredient[' . $ingredientId . ']';
            }
        }
    }

    protected function getPreviousCategory(){
        return basename(url()->previous());
    }
}
