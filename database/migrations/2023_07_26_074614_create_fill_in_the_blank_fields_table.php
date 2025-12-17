<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFillInTheBlankFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('fill_in_the_blank_fields', function (Blueprint $table) {

            $table->id();
            $table->integer('order_number');
            $table->unsignedBigInteger('fill_in_the_blank_question_id');
            $table->string('answer');
            $table->integer('score');
            $table->foreign('fill_in_the_blank_question_id')->references('id')->on('fill_in_the_blank_questions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('fill_in_the_blank_fields');
    }
}
