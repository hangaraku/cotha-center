<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultipleChoiceAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('multiple_choice_answers', function (Blueprint $table) {

            $table->id();
            $table->string('img')->nullable();
            $table->string('text')->nullable();
            $table->string('order_number')->nullable();

            $table->boolean('is_correct_option');
            $table->unsignedBigInteger('exercise_question_id');
            $table->foreign('exercise_question_id')->references('id')->on('exercise_questions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('multiple_choice_answers');
    }
}
