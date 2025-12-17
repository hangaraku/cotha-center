<?php

namespace App\Filament\Widgets;

use App\Models\ClassroomSession;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActiveSessionsStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public function getHeading(): string
    {
        return 'Active Sessions Overview';
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get active sessions for today
        $activeSessions = ClassroomSession::where('teacher_id', $user->id)
            ->where('status', 'active')
            ->whereDate('session_date', $today)
            ->count();
            
        // Get total sessions for today (including completed/cancelled)
        $totalSessions = ClassroomSession::where('teacher_id', $user->id)
            ->whereDate('session_date', $today)
            ->count();
            
        // Get official sessions count
        $officialSessions = ClassroomSession::where('teacher_id', $user->id)
            ->where('type', 'official')
            ->where('status', 'active')
            ->whereDate('session_date', $today)
            ->count();

        $stats = [];
        
        $stats[] = Stat::make(
            'Active Sessions Today',
            $activeSessions
        )
        ->description('Currently active class sessions')
        ->descriptionIcon('heroicon-m-clock')
        ->color($activeSessions > 0 ? 'success' : 'gray');

        $stats[] = Stat::make(
            'Total Sessions Today',
            $totalSessions
        )
        ->description('All sessions created today')
        ->descriptionIcon('heroicon-m-calendar')
        ->color('info');

        $stats[] = Stat::make(
            'Official Sessions',
            $officialSessions
        )
        ->description('Active official sessions')
        ->descriptionIcon('heroicon-m-academic-cap')
        ->color($officialSessions > 0 ? 'warning' : 'gray');
        
        return $stats;
    }
} 