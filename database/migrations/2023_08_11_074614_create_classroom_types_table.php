<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomTypesTable extends Migration
{
    public function up()
    {
        Schema::create('classroom_types', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->integer('max_member')->default(0);

        
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_types');
    }
}
