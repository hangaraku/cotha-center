<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('classroom_levels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('level_id');
            $table->boolean('is_active');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('level_id')->references('id')->on('levels');
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_levels');
    }
}
