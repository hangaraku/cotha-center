<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('img_url');
            $table->longText('description');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references("id")->on("centers");
            $table->integer('order_number');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
