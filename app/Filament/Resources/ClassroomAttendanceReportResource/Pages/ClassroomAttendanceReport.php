<?php

namespace App\Filament\Resources\ClassroomAttendanceReportResource\Pages;

use App\Filament\Resources\ClassroomAttendanceReportResource;
use App\Models\Classroom;
use App\Models\ClassroomSession;
use App\Models\ClassroomSessionAttendance;
use App\Models\StudentClassroom;
use App\Models\ClassroomTeacher;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ClassroomAttendanceReport extends Page
{
    protected static string $resource = ClassroomAttendanceReportResource::class;

    protected static string $view = 'filament.resources.classroom-attendance-report-resource.pages.classroom-attendance-report';

    public $classroom;
    public $attendanceData = [];
    public $sessions = [];
    public $students = [];

    public function mount($record): void
    {
        $classroom = Classroom::with(['students.user', 'sessions.attendances'])->findOrFail($record);
        
        // Check if user has access to this classroom
        if (!$this->canAccessClassroom($classroom)) {
            abort(403, 'You do not have permission to access this classroom.');
        }
        
        $this->classroom = $classroom;
        $this->loadAttendanceData();
    }

    private function canAccessClassroom($classroom): bool
    {
        // Super admin can access all classrooms
        if (Auth::user()->hasRole('super_admin')) {
            return true;
        }
        
        // Teacher can only access their own classrooms
        if (Auth::user()->hasRole('Teacher')) {
            return $classroom->teachers()->where('user_id', Auth::id())->exists();
        }
        
        // Other users can only access classrooms from their center
        return $classroom->center_id === Auth::user()->center_id;
    }

    public function getTitle(): string
    {
        return 'Laporan Absensi - ' . $this->classroom->name;
    }

    public function loadAttendanceData()
    {
        if (!$this->classroom) return;

        // Get all sessions for this classroom, ordered by date
        $this->sessions = $this->classroom->sessions()
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        // Get all students in this classroom
        $this->students = $this->classroom->students()
            ->with('user')
            ->get();

        // Initialize attendance data
        $this->attendanceData = [];

        foreach ($this->students as $student) {
            $studentData = [
                'id' => $student->id,
                'name' => $student->user->name,
                'sessions' => []
            ];

            foreach ($this->sessions as $session) {
                // Check if student has attendance record for this session
                // Try to find attendance using user_id first, then fallback to student_classroom_id
                $attendance = $session->attendances()
                    ->where('student_id', $student->user->id)
                    ->first();

                // If not found, try with student_classroom_id (for backward compatibility)
                if (!$attendance) {
                    $attendance = $session->attendances()
                        ->where('student_id', $student->id)
                        ->first();
                }

                $studentData['sessions'][] = [
                    'session_id' => $session->id,
                    'session_date' => $session->session_date,
                    'is_present' => $attendance ? $attendance->is_present : false,
                    'status' => $attendance ? ($attendance->is_present ? 'Present' : 'Absent') : 'Not Marked'
                ];
            }

            $this->attendanceData[] = $studentData;
        }
    }

    public function getAttendanceStatusColor($status)
    {
        return match($status) {
            'Present' => 'success',
            'Absent' => 'danger',
            'Not Marked' => 'gray',
            default => 'gray'
        };
    }

    public function getAttendanceStatusIcon($status)
    {
        return match($status) {
            'Present' => 'heroicon-o-check-circle',
            'Absent' => 'heroicon-o-x-circle',
            'Not Marked' => 'heroicon-o-minus-circle',
            default => 'heroicon-o-minus-circle'
        };
    }

    public function getOfficialSessionsCount()
    {
        if (!$this->sessions) return 0;
        return $this->sessions->where('type', 'official')->count();
    }

    public function getCompletedOfficialSessionsCount()
    {
        if (!$this->sessions) return 0;
        return $this->sessions->where('type', 'official')
                             ->where('status', 'completed')
                             ->count();
    }

    public function getUnofficialSessionsCount()
    {
        if (!$this->sessions) return 0;
        return $this->sessions->where('type', 'unofficial')->count();
    }


}
