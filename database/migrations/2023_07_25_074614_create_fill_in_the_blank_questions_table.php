<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFillInTheBlankQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('fill_in_the_blank_questions', function (Blueprint $table) {

            $table->id();
            $table->string('question_text');
            $table->string('question_img');
            $table->unsignedBigInteger('exercise_question_id');
            $table->foreign('exercise_question_id')->references('id')->on('exercise_questions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('fill_in_the_blank_questions');
    }
}
