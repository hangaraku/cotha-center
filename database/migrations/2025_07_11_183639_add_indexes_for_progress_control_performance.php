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
        Schema::table('user_units', function (Blueprint $table) {
            // Add composite index for user_id and unit_id
            $table->index(['user_id', 'unit_id'], 'user_units_user_unit_index');
        });

        Schema::table('units', function (Blueprint $table) {
            // Add index for module_id
            $table->index('module_id', 'units_module_id_index');
        });

        Schema::table('modules', function (Blueprint $table) {
            // Add index for order_number
            $table->index('order_number', 'modules_order_number_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_units', function (Blueprint $table) {
            $table->dropIndex('user_units_user_unit_index');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex('units_module_id_index');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropIndex('modules_order_number_index');
        });
    }
};
