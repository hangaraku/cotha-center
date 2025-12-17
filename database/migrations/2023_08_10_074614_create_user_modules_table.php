<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserModulesTable extends Migration
{
    public function up()
    {
        Schema::create('user_modules', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_module_score_id');
            $table->unsignedBigInteger('user_module_status_id');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_module_score_id')->references('id')->on('user_module_scores');
            $table->foreign('user_module_status_id')->references('id')->on('user_module_statuses');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_modules');
    }
}
