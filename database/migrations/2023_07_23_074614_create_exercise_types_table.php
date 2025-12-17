<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseTypesTable extends Migration
{
    public function up()
    {
        Schema::create('exercise_types', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercise_types');
    }
}
