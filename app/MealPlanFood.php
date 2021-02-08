<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MealPlanFood extends Model
{
    public $timestamps = false;
    protected $table = 'meal_plan_foods';
    protected $fillable = ['meal_plan_id','day', 'meal_type_id', 'recipe_type', 'food_id'];

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
