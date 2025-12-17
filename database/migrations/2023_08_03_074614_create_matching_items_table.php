<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchingItemsTable extends Migration
{
    public function up()
    {
        Schema::create('matching_items', function (Blueprint $table) {

            $table->id();
            $table->string('text');
            $table->string('img');
            $table->unsignedBigInteger('matching_question_id');
            $table->unsignedBigInteger('matching_item_answer_id');
            $table->foreign('matching_question_id')->references('id')->on('matching_questions');
            $table->timestamps();
        
        });
        Schema::table('matching_items',function(Blueprint $table){
            $table->foreign('matching_item_answer_id')->references('id')->on('matching_items');

        });
    }

    public function down()
    {
        Schema::dropIfExists('matching_items');
    }
}
