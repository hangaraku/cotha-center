<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StudentPointService;
use Illuminate\Console\Command;

class SyncUserPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-points {--user-id= : Sync points for specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user points based on completed units and reward redemptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentPointService = new StudentPointService();
        
        if ($userId = $this->option('user-id')) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            
            $this->syncSingleUser($user, $studentPointService);
        } else {
            $this->syncAllUsers($studentPointService);
        }
        
        return 0;
    }
    
    private function syncSingleUser(User $user, StudentPointService $service)
    {
        $this->info("Syncing points for user: {$user->name} (ID: {$user->id})");
        
        $calculation = $service->calculateStudentPoints($user);
        $oldPoints = $user->point;
        
        $user->point = $calculation['net_points'];
        $user->save();
        
        $this->info("Points updated: {$oldPoints} → {$user->point}");
        $this->info("Details:");
        $this->info("  - Points earned: {$calculation['points_earned']}");
        $this->info("  - Points spent: {$calculation['points_spent']}");
        $this->info("  - Points refunded: {$calculation['points_refunded']}");
        $this->info("  - Completed units: {$calculation['completed_units']}");
        $this->info("  - Rewards redeemed: {$calculation['rewards_redeemed']}");
    }
    
    private function syncAllUsers(StudentPointService $service)
    {
        $users = User::all();
        $this->info("Syncing points for {$users->count()} users...");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        $updatedCount = 0;
        $totalPointsChanged = 0;
        
        foreach ($users as $user) {
            $calculation = $service->calculateStudentPoints($user);
            $oldPoints = $user->point;
            $newPoints = $calculation['net_points'];
            
            if ($oldPoints !== $newPoints) {
                $user->point = $newPoints;
                $user->save();
                $updatedCount++;
                $totalPointsChanged += abs($newPoints - $oldPoints);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Sync completed!");
        $this->info("  - Users updated: {$updatedCount}");
        $this->info("  - Total points adjusted: {$totalPointsChanged}");
    }
} 