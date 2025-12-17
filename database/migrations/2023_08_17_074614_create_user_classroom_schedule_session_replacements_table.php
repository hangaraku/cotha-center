<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserClassroomScheduleSessionReplacementsTable extends Migration
{
    public function up()
    {
        Schema::create('user_classroom_schedule_session_replacements', function (Blueprint $table) {

            $table->id();
            $table->string('note');
            $table->integer('credit_used');
            $table->datetime('check_in_time');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('classroom_schedule_id');
            $table->unsignedBigInteger('user_classroom_schedule_session_status_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('classroom_schedule_id','csid')->references('id')->on('classroom_schedules');
            $table->foreign('user_classroom_schedule_session_status_id','ucsssid')->references('id')->on('user_classroom_schedule_session_statuses');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_classroom_schedule_session_replacements');
    }
}
