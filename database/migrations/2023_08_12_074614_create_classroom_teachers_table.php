<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('classroom_teachers', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('classroom_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_teachers');
    }
}
