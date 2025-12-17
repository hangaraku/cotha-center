<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassroomSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'teacher_id',
        'type',
        'classroom_schedule_id',
        'start_time',
        'end_time',
        'session_date',
        'notes',
        'attendance_proof_photo',
        'status'
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroomSchedule()
    {
        return $this->belongsTo(ClassroomSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(ClassroomSessionAttendance::class);
    }

    // Check if session is valid (before midnight of the same day, regardless of end_time)
    public function isValid()
    {
        $today = Carbon::today();
        $midnight = Carbon::today()->endOfDay();
        
        return $this->session_date->isSameDay($today) && 
               Carbon::now()->isBefore($midnight) && 
               $this->status === 'active';
    }

    // Get formatted time range
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    // Get session type label
    public function getTypeLabelAttribute()
    {
        return ucfirst($this->type);
    }

    // Scope for active sessions
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for today's sessions
    public function scopeToday($query)
    {
        return $query->whereDate('session_date', Carbon::today());
    }

    // Scope for valid sessions (today and before midnight)
    public function scopeValid($query)
    {
        return $query->whereDate('session_date', Carbon::today())
                    ->where('status', 'active');
    }

    // Method to decrease credits for present students (for future implementation)
    public function decreaseCreditsForPresentStudents($presentStudentIds)
    {
        if ($this->type === 'official' && $this->status === 'completed') {
            // Decrease credits only for students who were marked present
            $this->classroom->students()
                ->whereIn('user_id', $presentStudentIds)
                ->update([
                    'credit_left' => DB::raw('credit_left - 1')
                ]);
        }
    }
}
