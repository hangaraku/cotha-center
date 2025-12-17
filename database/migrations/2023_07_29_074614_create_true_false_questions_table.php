<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrueFalseQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('true_false_questions', function (Blueprint $table) {

            $table->id();
            $table->string('text');
            $table->string('img');
            $table->boolean('is_correct');
            $table->integer('score');
            $table->unsignedBigInteger('exercise_question_id');
            $table->foreign('exercise_question_id')->references('id')->on('exercise_questions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('true_false_questions');
    }
}
