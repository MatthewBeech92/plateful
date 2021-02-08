<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientCategory extends Model
{
    public $timestamps = false;
    protected $table = 'ingredient_categories';
    protected $keyType = 'string';
}
