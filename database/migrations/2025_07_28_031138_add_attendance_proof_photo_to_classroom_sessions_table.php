<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('classroom_sessions', function (Blueprint $table) {
            $table->string('attendance_proof_photo')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classroom_sessions', function (Blueprint $table) {
            $table->dropColumn('attendance_proof_photo');
        });
    }
};
