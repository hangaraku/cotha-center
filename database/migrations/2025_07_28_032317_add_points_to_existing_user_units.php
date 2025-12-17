<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add points to users who already have UserUnit records
        // This ensures existing users get points for units they already have access to
        $userUnits = DB::table('user_units')
            ->join('units', 'user_units.unit_id', '=', 'units.id')
            ->join('users', 'user_units.user_id', '=', 'users.id')
            ->select('user_units.user_id', 'units.point')
            ->where('units.point', '>', 0)
            ->get();

        // Group by user_id and sum points
        $userPoints = [];
        foreach ($userUnits as $userUnit) {
            if (!isset($userPoints[$userUnit->user_id])) {
                $userPoints[$userUnit->user_id] = 0;
            }
            $userPoints[$userUnit->user_id] += $userUnit->point;
        }

        // Update users with their accumulated points
        foreach ($userPoints as $userId => $points) {
            DB::table('users')
                ->where('id', $userId)
                ->increment('point', $points);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We cannot easily reverse this as we don't know the original point values
        // This migration is designed to be run once to initialize existing data
    }
};
