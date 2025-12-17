<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('classroom_session_attendances');
    }

    public function down(): void
    {
        // No need to recreate the table here
    }
};
