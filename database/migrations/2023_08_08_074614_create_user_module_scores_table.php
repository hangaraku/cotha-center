<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserModuleScoresTable extends Migration
{
    public function up()
    {
        Schema::create('user_module_scores', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_module_scores');
    }
}
