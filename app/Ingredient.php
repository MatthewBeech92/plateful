<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Ingredient extends Model
{
    use Sortable;

    protected $table = 'ingredients';
    public $timestamps = false;
    protected $fillable = ['name','brand_name', 'serving_size', 'food_group_id','calories','fat','of_which_saturates','carbohydrates','of_which_sugars','fibre','protein'];
    public $sortable = ['name', 'brand_name', 'calories', 'fat', 'carbohydrates', 'protein'];

    public function recipes(){
        return $this->belongsToMany(Recipe::class);
    }

    public function foodGroup()
    {
        return $this->belongsTo(FoodGroup::class);
    }    

    public function foodTypeSortable($query, $direction)
    {
        return $query->orderBy('food_type', $direction)
            ->select('food_groups.food_type', 'ingredients.*');
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
