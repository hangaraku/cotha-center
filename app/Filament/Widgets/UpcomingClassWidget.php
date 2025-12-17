<?php

namespace App\Filament\Widgets;

use App\Models\ClassroomSchedule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpcomingClassWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public function getHeading(): string
    {
        return 'Upcoming Classes';
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $today = Carbon::today();
        $currentTime = Carbon::now();
        
        // Get the current day name
        $currentDay = $today->format('l'); // Monday, Tuesday, etc.
        
        // Find upcoming classes for today
        $upcomingClasses = ClassroomSchedule::whereHas('classroom.classroomTeachers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('day', $currentDay)
        ->where('start_time', '>', $currentTime->format('H:i:s'))
        ->with(['classroom', 'classroom.classroomType'])
        ->orderBy('start_time')
        ->limit(3)
        ->get();

        $stats = [];
        
        if ($upcomingClasses->count() > 0) {
            foreach ($upcomingClasses as $index => $class) {
                $startTime = Carbon::parse($class->start_time)->format('H:i');
                $endTime = Carbon::parse($class->end_time)->format('H:i');
                
                $stats[] = Stat::make(
                    "Next Class: {$class->classroom->name}",
                    "{$startTime} - {$endTime}"
                )
                ->description("{$class->classroom->classroomType->name}")
                ->descriptionIcon('heroicon-m-clock')
                ->color('success')
                ->url(\App\Filament\Resources\ClassroomSessionResource::getUrl('create', ['classroom_id' => $class->classroom->id]))
                ->openUrlInNewTab();
            }
        } else {
            // Check if there are any classes today that have already passed
            $todayClasses = ClassroomSchedule::whereHas('classroom.classroomTeachers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('day', $currentDay)
            ->count();
            
            if ($todayClasses > 0) {
                $stats[] = Stat::make(
                    'No More Classes Today',
                    'All classes completed'
                )
                ->description("You had {$todayClasses} class(es) today")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('gray');
            } else {
                $stats[] = Stat::make(
                    'No Classes Today',
                    'Enjoy your day off!'
                )
                ->description('No classes scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('gray');
            }
        }
        
        return $stats;
    }
} 