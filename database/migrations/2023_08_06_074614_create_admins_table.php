<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('role_id')->references('id')->on('admin_roles');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
