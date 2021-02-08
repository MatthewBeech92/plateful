<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    public $timestamps = false;
    protected $table = 'meal_plans';
    protected $fillable = ['name','daily_calories'];

    public function foods()
    {
        return $this->hasMany(MealPlanFood::class);
    }
}
