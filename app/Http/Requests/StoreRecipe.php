<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Ingredient;

class StoreRecipe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $recipeId = request()->route('recipe');
        
        return [
            'recipe_name' => 'required|unique:recipes,name,'.$recipeId .'',
            'meal_type' => 'required',
            'recipe_time' => 'required',
            'ingredient.*' => 'required|numeric'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ingredient.*.required' => 'The :attribute amount field is required',
            'ingredient.*.numeric' => 'The :attribute amount field must be a number'
        ];
    }    


    public function attributes()
    {
        foreach ($this->request->get('ingredient') as $ingredientId => $ingredientAmount){
            $ingredients = Ingredient::select('name', 'brand_name')
                    ->where('id', $ingredientId)
                    ->first();
    
            $attribute['ingredient.' . $ingredientId] = $ingredients['name'] . ' - ' . $ingredients['brand_name'];
        }

        return $attribute;
    }

  
}
