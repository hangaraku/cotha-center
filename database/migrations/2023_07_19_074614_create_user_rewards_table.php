<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('user_rewards', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reward_id');
            $table->enum('status', ['pending', 'claimed', 'expired']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reward_id')->references('id')->on('rewards');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_rewards');
    }
}
