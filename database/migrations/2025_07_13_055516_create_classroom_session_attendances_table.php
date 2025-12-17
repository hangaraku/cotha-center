<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_session_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_session_id');
            $table->unsignedBigInteger('student_id');
            $table->boolean('is_present')->default(false);
            $table->timestamps();

            $table->foreign('classroom_session_id')->references('id')->on('classroom_sessions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['classroom_session_id', 'student_id'], 'cs_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_session_attendances');
    }
};
