<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('exercise_questions', function (Blueprint $table) {

            $table->id();
            $table->integer('order_number');
            $table->unsignedBigInteger('exercise_id');
            $table->unsignedBigInteger('exercise_type_id');
            $table->longText('question');
            $table->integer('score');

            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->foreign('exercise_type_id')->references('id')->on('exercise_types');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercise_questions');
    }
}
