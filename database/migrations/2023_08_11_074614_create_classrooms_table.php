<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_credit');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references("id")->on("centers");
            $table->unsignedBigInteger('classroom_type_id');
            $table->foreign('classroom_type_id')->references('id')->on('classroom_types');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
