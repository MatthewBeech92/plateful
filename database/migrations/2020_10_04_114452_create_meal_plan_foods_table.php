<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlanFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan_foods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('meal_plan_id');
            $table->integer('day');
            $table->unsignedBigInteger('food_type_id');
            $table->morphs('food');	

            $table->foreign('meal_plan_id')->references('id')->on('meal_plans')->onDelete('cascade');
            $table->foreign('food_type_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_plan_foods');
    }
}
