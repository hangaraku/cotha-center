<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('img_url');
            $table->integer('point');
            $table->integer('order_number');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}
