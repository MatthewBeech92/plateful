<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class FoodGroup extends Model
{
    use Sortable;

    protected $table = 'food_groups';
    protected $fillable = ['food_type','food_category','metric'];
    public $timestamps = false;
    public $sortable = ['food_type'];


    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
