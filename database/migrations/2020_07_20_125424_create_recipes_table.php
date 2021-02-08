<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->string('time');
            $table->text('instructions');
            $table->string('image');
            $table->integer('calories');
            $table->float('fat', 4, 1);
            $table->float('of_which_saturates', 4, 1);
            $table->float('carbohydrates', 4, 1);
            $table->float('of_which_sugars', 4, 1);
            $table->float('fibre', 4, 1);
            $table->float('protein', 4, 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
