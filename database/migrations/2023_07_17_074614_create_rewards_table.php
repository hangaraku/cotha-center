<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('shop_url');
            $table->integer('price');
            $table->string('img_url');
            $table->integer('stock');
            $table->integer('order_number');
            $table->boolean('is_pinned');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}
