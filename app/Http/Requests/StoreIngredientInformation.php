<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientInformation extends FormRequest
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
        $ingredientId = request()->route('ingredient');
        $brandName = $this->request->get('brand_name');

        return [
            'ingredient_name' => 'required|unique:ingredients,name,'. $ingredientId . ',id,brand_name,' . $brandName,
            'brand_name' => 'required',
            'serving_size' => 'nullable|numeric',
            'food_type' => 'required',
            'food_category' => 'required',
            'food_metric' => 'required',
            'calories' => 'required|numeric',
            'fat' => 'required|numeric',
            'saturated_fat' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'sugars' => 'required|numeric',
            'fibre' => 'required|numeric',
            'protein' => 'required|numeric'
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
            'name.unique' => 'This ingredient with the same brand name already exists.',
        ];
    }
}

