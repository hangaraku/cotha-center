<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomScheduleSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('classroom_schedule_sessions', function (Blueprint $table) {

            $table->id();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('venue');
            $table->unsignedBigInteger('classroom_teacher_id');
            $table->unsignedBigInteger('classroom_schedule_id');
            $table->foreign('classroom_teacher_id')->references('id')->on('classroom_teachers');
            $table->foreign('classroom_schedule_id')->references('id')->on('classroom_schedules');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_schedule_sessions');
    }
}
