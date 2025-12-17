<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocabulariesTable extends Migration
{
    public function up()
    {
        Schema::create('vocabularies', function (Blueprint $table) {

            $table->id();
            $table->longText('word');
            $table->longText('meaning');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('vocabularies');
    }
}
