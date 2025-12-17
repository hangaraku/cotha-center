<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortingQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('sorting_questions', function (Blueprint $table) {

            $table->id();
            $table->string('text');
            $table->string('img');
            $table->integer('score');
            $table->unsignedBigInteger('exercise_question_id');
            $table->foreign('exercise_question_id')->references('id')->on('exercise_questions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorting_questions');
    }
}
