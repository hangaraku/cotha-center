<?php

namespace App\Observers;

use App\Models\UserUnit;

class UserUnitObserver
{
    /**
     * Handle the UserUnit "created" event.
     */
    public function created(UserUnit $userUnit): void
    {
        // Get the unit to get its point value
        $unit = $userUnit->unit;
        $user = $userUnit->user;
        
        if ($unit && $user && $unit->point > 0) {
            // Add points to user
            $user->increment('point', $unit->point);
        }
    }

    /**
     * Handle the UserUnit "updated" event.
     */
    public function updated(UserUnit $userUnit): void
    {
        //
    }

    /**
     * Handle the UserUnit "deleted" event.
     */
    public function deleted(UserUnit $userUnit): void
    {
        // Get the unit to get its point value
        $unit = $userUnit->unit;
        $user = $userUnit->user;
        
        if ($unit && $user && $unit->point > 0) {
            // Subtract points from user (but don't go below 0)
            $newPoints = max(0, $user->point - $unit->point);
            $user->update(['point' => $newPoints]);
        }
    }

    /**
     * Handle the UserUnit "restored" event.
     */
    public function restored(UserUnit $userUnit): void
    {
        //
    }

    /**
     * Handle the UserUnit "force deleted" event.
     */
    public function forceDeleted(UserUnit $userUnit): void
    {
        //
    }
}
