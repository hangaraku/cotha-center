<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserUnit;
use App\Models\UserReward;
use Illuminate\Support\Facades\DB;

class StudentPointService
{
    /**
     * Calculate total points for a student based on completed units and spent rewards
     *
     * @param User $user
     * @return array
     */
    public function calculateStudentPoints(User $user): array
    {
        // Get all completed units with their points
        $completedUnits = UserUnit::where('user_id', $user->id)
            ->with('unit')
            ->get();

        // Calculate points earned from completed units
        $pointsEarned = $completedUnits->sum('unit.point');

        // Calculate points spent on rewards (pending and claimed, but not cancelled)
        $pointsSpent = UserReward::where('user_id', $user->id)
            ->whereIn('status', [0, 1]) // 0 = pending, 1 = claimed (not cancelled)
            ->with('reward')
            ->get()
            ->sum('reward.price');

        // Calculate net points (earned - spent)
        $netPoints = $pointsEarned - $pointsSpent;

        // Note: Cancelled rewards (status = 2) are NOT deducted from points
        // They are already excluded from pointsSpent calculation above
        $pointsRefunded = UserReward::where('user_id', $user->id)
            ->where('status', 2) // cancelled rewards (for tracking purposes)
            ->with('reward')
            ->get()
            ->sum('reward.price');

        // Final net points (cancelled orders don't affect this as they're not in pointsSpent)
        $finalNetPoints = $netPoints;

        return [
            'points_earned' => $pointsEarned,
            'points_spent' => $pointsSpent,
            'points_refunded' => $pointsRefunded,
            'net_points' => $finalNetPoints,
            'completed_units' => $completedUnits->count(),
            'total_units_available' => $completedUnits->count(), // This could be enhanced to show total available units
            'rewards_redeemed' => UserReward::where('user_id', $user->id)
                ->whereIn('status', [0, 1]) // pending or claimed
                ->count(),
            'rewards_cancelled' => UserReward::where('user_id', $user->id)
                ->where('status', 2)
                ->count(),
        ];
    }

    /**
     * Get detailed breakdown of student's point history
     *
     * @param User $user
     * @return array
     */
    public function getPointHistory(User $user): array
    {
        // Get completed units with details
        $completedUnits = UserUnit::where('user_id', $user->id)
            ->with(['unit.module'])
            ->get()
            ->map(function ($userUnit) {
                return [
                    'type' => 'unit_completed',
                    'description' => "Completed unit: {$userUnit->unit->name}",
                    'module' => $userUnit->unit->module->name,
                    'points' => $userUnit->unit->point,
                    'date' => $userUnit->created_at,
                    'unit_id' => $userUnit->unit_id,
                ];
            });

        // Get reward redemptions
        $rewardRedemptions = UserReward::where('user_id', $user->id)
            ->with('reward')
            ->get()
            ->map(function ($userReward) {
                $status = match($userReward->status) {
                    0 => 'pending',
                    1 => 'claimed',
                    2 => 'cancelled',
                    default => 'unknown'
                };

                return [
                    'type' => 'reward_redemption',
                    'description' => "Redeemed reward: {$userReward->reward->name}",
                    'points' => -$userReward->reward->price, // Negative because points are spent
                    'status' => $status,
                    'date' => $userReward->created_at,
                    'reward_id' => $userReward->reward_id,
                ];
            });

        // Combine and sort by date
        $history = $completedUnits->concat($rewardRedemptions)
            ->sortBy('date')
            ->values()
            ->toArray();

        return $history;
    }

    /**
     * Update user's point field in database to match calculated points
     *
     * @param User $user
     * @return bool
     */
    public function syncUserPoints(User $user): bool
    {
        $pointCalculation = $this->calculateStudentPoints($user);
        
        $user->point = $pointCalculation['net_points'];
        return $user->save();
    }

    /**
     * Get point statistics for all students in a classroom
     *
     * @param int $classroomId
     * @return array
     */
    public function getClassroomPointStats(int $classroomId): array
    {
        $students = DB::table('student_classrooms')
            ->join('users', 'student_classrooms.user_id', '=', 'users.id')
            ->where('student_classrooms.classroom_id', $classroomId)
            ->select('users.id', 'users.name', 'users.point')
            ->get();

        $stats = [];
        foreach ($students as $student) {
            $user = User::find($student->id);
            $stats[] = [
                'user_id' => $student->id,
                'name' => $student->name,
                'current_points' => $student->point,
                'detailed_calculation' => $this->calculateStudentPoints($user),
            ];
        }

        return $stats;
    }

    /**
     * Award points to user for completing a unit
     *
     * @param User $user
     * @param int $unitId
     * @return bool
     */
    public function awardPointsForUnit(User $user, int $unitId): bool
    {
        $unit = \App\Models\Unit::find($unitId);
        if (!$unit) {
            return false;
        }

        // Check if user already has this unit
        $existingUserUnit = UserUnit::where('user_id', $user->id)
            ->where('unit_id', $unitId)
            ->first();

        if ($existingUserUnit) {
            return true; // Already completed
        }

        // Create user unit record
        UserUnit::create([
            'user_id' => $user->id,
            'unit_id' => $unitId,
        ]);

        // Update user's points
        $user->point += $unit->point;
        return $user->save();
    }

    /**
     * Get leaderboard data for a classroom
     *
     * @param int $classroomId
     * @return array
     */
    public function getClassroomLeaderboard(int $classroomId): array
    {
        $students = DB::table('student_classrooms')
            ->join('users', 'student_classrooms.user_id', '=', 'users.id')
            ->where('student_classrooms.classroom_id', $classroomId)
            ->select('users.id', 'users.name', 'users.point')
            ->orderBy('users.point', 'desc')
            ->get();

        return $students->map(function ($student, $index) {
            return [
                'rank' => $index + 1,
                'user_id' => $student->id,
                'name' => $student->name,
                'points' => $student->point,
            ];
        })->toArray();
    }
} 