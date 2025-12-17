<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add new status_int column
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->tinyInteger('status_int')->default(0)->after('reward_id')->comment('0=pending, 1=claimed, 2=cancelled');
        });
        // 2. Migrate data
        DB::table('user_rewards')->where('status', 'pending')->update(['status_int' => 0]);
        DB::table('user_rewards')->where('status', 'claimed')->update(['status_int' => 1]);
        DB::table('user_rewards')->where('status', 'cancelled')->update(['status_int' => 2]);
        // 3. Drop old status column
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        // 4. Rename status_int to status
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->renameColumn('status_int', 'status');
        });
    }

    public function down(): void
    {
        // 1. Add back enum status column
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->enum('status', ['pending', 'claimed', 'cancelled'])->after('reward_id');
        });
        // 2. Migrate data back
        DB::table('user_rewards')->where('status', 0)->update(['status' => 'pending']);
        DB::table('user_rewards')->where('status', 1)->update(['status' => 'claimed']);
        DB::table('user_rewards')->where('status', 2)->update(['status' => 'cancelled']);
        // 3. Drop int status column
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}; 