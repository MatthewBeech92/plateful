<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\FoodGroup;

class FoodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(FoodGroup::class, 100)->create();
    }
}
