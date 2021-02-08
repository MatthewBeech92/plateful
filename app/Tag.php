<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $table = 'tags';

    protected $fillable = ['meal_type'];

    public function recipeFoods()
    {
        return $this->morphedByMany('App\Recipe', 'taggable');
    }

    public function ingredientFoods()
    {
        return $this->morphedByMany('App\Ingredient', 'taggable');
    }
}
