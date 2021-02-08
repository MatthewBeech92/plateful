<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingredient;
use App\Recipe;
use App\MealPlan;
use App\MealPlanFood;
use App\Tag;
use App\Taggable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreMealPlan;



class MealPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mealPlans = MealPlan::all();

      
        return view('meal-plans/index', [
            'mealPlans' => $mealPlans
        ]);  
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meal-plans/add_meal_plan'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMealPlan $request)
    {
        DB::transaction(function () use ($request) {

            $mealPlanInformation = MealPlan::firstOrCreate([
                'name' => request('meal_plan_name'),
                'daily_calories' => request('daily_calories'),
            ]);

            foreach (request('day') as $day => $meals) {
                foreach ($meals as $mealType => $foods) {
                    foreach ($foods as $foodItem) {
                        $mealTypeId = Tag::where('meal_type', $mealType)->first()->id;

                        $mealPlanFoods = MealPlanFood::firstOrCreate([
                            'day' => $day,
                            'meal_plan_id' => $mealPlanInformation->id,
                            'meal_type_id' => $mealTypeId,
                            'recipe_type' => $foodItem['foodType'],
                            'food_id' => $foodItem['foodId']
                        ]);
                    }
                }
            }
        });

        $request->session()->flash('success-msg', 'Successfully added the "' . request('meal_plan_name') . '" meal plan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MealPlan $mealPlan)
    {
        dd($mealPlan::with('tags')->get());
        dd($mealPlan->foods::with('tags')->get());
        return view('meal-plans/meal_plan', [
            'mealPlan' => $mealPlan
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showFoodsByMeal(Request $request, $recipeType) 
    {
        $tag = Tag::with('recipeFoods', 'ingredientFoods')
            ->where('meal_type', $recipeType)
            ->get(); 
       
        $foods = $tag->map(function ($item){
            $allFoods = $item->recipeFoods->merge($item->ingredientFoods);

            return $allFoods;
        });

        return $foods->flatten();
    }

    public function filterFoods($foodName, $mealType){
        $tag = Tag::with(['recipeFoods' => function ($query) use ($foodName) {
            $query->where('name', 'like', '%'.$foodName.'%');
        }, 'ingredientFoods' => function ($query) use ($foodName) {
            $query->where('name', 'like', '%'.$foodName.'%');
        }])
        ->where('meal_type', $mealType)
        ->get();

        $foods = $tag->map(function ($item){
            return $item->recipeFoods->merge($item->ingredientFoods);
        })->flatten();

        if ($foods->isEmpty()) {
            return response()->json(['error' => 'No Recipes or Foods Found'], 422);
        } else {
            return json_encode($foods);         
        }
    }

    public function displayFood($foods, $id)
    {
        if ($foods === 'recipe') {
            $recipe = Recipe::find($id);

            foreach ($recipe->ingredients as $ingredient) {
                $ingredientInfo[$ingredient->ingredient_name] = $ingredient->pivot->ingredient_amount;
            }
    
            return $recipe;
        } else {
            return Ingredient::find($id);
        }
    }

    public function validateMealPlanInformation(StoreMealPlan $request) {
        return 'success';
    }
}
