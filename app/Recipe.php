<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $timestamps = false;
    protected $table = 'recipes';
    protected $fillable = ['name', 'description', 'instructions', 'time', 'image', 'calories','fat','of_which_saturates','carbohydrates','of_which_sugars','fibre','protein'];

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');;
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
