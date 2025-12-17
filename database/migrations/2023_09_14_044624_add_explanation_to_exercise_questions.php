<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExplanationToExerciseQuestions extends Migration
{
    public function up()
    {
        Schema::table('exercise_questions', function (Blueprint $table) {
            $table->text('explanation')->nullable();
        });
    }

    public function down()
    {
        Schema::table('exercise_questions', function (Blueprint $table) {
            $table->dropColumn('explanation');
        });
    }
}

?>