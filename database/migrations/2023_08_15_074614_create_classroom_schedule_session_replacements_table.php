<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomScheduleSessionReplacementsTable extends Migration
{
    public function up()
    {
        Schema::create('classroom_schedule_session_replacements', function (Blueprint $table) {

            $table->id();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('venue');
            $table->unsignedBigInteger('classroom_teacher_id');
            $table->unsignedBigInteger('classroom_schedule_session_id');
            $table->foreign('classroom_teacher_id','ctcssrid')->references('id')->on('classroom_teachers');
            $table->foreign('classroom_schedule_session_id','cssid')->references('id')->on('classroom_schedule_sessions');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_schedule_session_replacements');
    }
}
