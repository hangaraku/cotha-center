<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('phone')->nullable();
            $table->dateTime('birthdate')->nullable();
            $table->string('city')->nullable();
            $table->string('school')->nullable();
            
            $table->string('status')->default("Active");
            $table->text('note')->nullable();

            $table->timestamps();   
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
