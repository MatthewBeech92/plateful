<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientRecipe extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'ingredient_recipe';
    protected $fillable = ['recipe_id', 'ingredient_id', 'ingredient_amount'];
}


