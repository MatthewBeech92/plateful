<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('brand_name');
            $table->unsignedBigInteger('food_group_id');
            $table->float('serving_size')->nullable();
            $table->float('calories')->nullable();
            $table->float('fat', 4, 1)->nullable();
            $table->float('of_which_saturates', 4, 1)->nullable();
            $table->float('carbohydrates', 4, 1)->nullable();
            $table->float('of_which_sugars', 4, 1)->nullable();
            $table->float('fibre', 4, 1)->nullable();
            $table->float('protein', 4, 1)->nullable();

            $table->foreign('food_group_id')->references('id')->on('food_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
