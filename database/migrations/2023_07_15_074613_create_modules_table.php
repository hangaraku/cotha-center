<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->longText('img_url');
            $table->integer('order_number');
            $table->unsignedBigInteger('level_id');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
