<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ClassroomSession;
use App\Models\StudentClassroom;
use App\Models\ClassroomSessionAttendance;
use App\Models\User;
use Illuminate\Support\HtmlString;

class AttendanceManager extends Component
{
    public $classroomSessionId;
    public $present = [];
    public $absent = [];
    public $searchPresent = '';
    public $searchAbsent = '';
    public $finalized = false;

    public function mount($classroomSessionId)
    {
        $this->classroomSessionId = $classroomSessionId;
        $this->loadAttendance();
    }

    public function loadAttendance()
    {
        $session = ClassroomSession::with('classroom.students.user', 'attendances')->findOrFail($this->classroomSessionId);
        $this->finalized = $session->status === 'completed';
        $studentClassrooms = $session->classroom->students;
        $present = [];
        $absent = [];
        
        foreach ($studentClassrooms as $studentClassroom) {
            $user = $studentClassroom->user;
            if (!$user) continue;
            
            // Check for attendance using both user_id (old data) and student_classroom_id (new data)
            $attendance = $session->attendances->where('student_id', $user->id)->first();
            if (!$attendance) {
                $attendance = $session->attendances->where('student_id', $studentClassroom->id)->first();
            }
            
            if ($attendance && $attendance->is_present) {
                $present[] = ['id' => $studentClassroom->id, 'name' => $user->name];
            } else {
                $absent[] = ['id' => $studentClassroom->id, 'name' => $user->name];
            }
        }
        $this->present = $present;
        $this->absent = $absent;
    }

    public function toggleAttendance($studentClassroomId)
    {
        if ($this->finalized) return;
        $session = ClassroomSession::findOrFail($this->classroomSessionId);
        
        // Find the student classroom to get the user
        $studentClassroom = StudentClassroom::find($studentClassroomId);
        if (!$studentClassroom) return;
        
        // Try to find existing attendance record (check both user_id and student_classroom_id)
        $attendance = $session->attendances()->where('student_id', $studentClassroom->user_id)->first();
        if (!$attendance) {
            $attendance = $session->attendances()->where('student_id', $studentClassroomId)->first();
        }
        
        // If no existing record, create new one using student_classroom_id
        if (!$attendance) {
            $attendance = new ClassroomSessionAttendance();
            $attendance->classroom_session_id = $this->classroomSessionId;
            $attendance->student_id = $studentClassroomId;
            $attendance->is_present = true;
        } else {
            // Update existing record
            $attendance->is_present = !$attendance->is_present;
        }
        
        $attendance->save();
        $this->loadAttendance();
    }

    public function finalizeAttendance()
    {
        $session = ClassroomSession::findOrFail($this->classroomSessionId);
        if ($session->status === 'completed') return;
        
        // Deduct credits for present students
        foreach ($session->attendances()->where('is_present', true)->get() as $attendance) {
            // Handle both old data (user_id) and new data (student_classroom_id)
            $studentClassroom = null;
            
            // First try to find by student_classroom_id (new format)
            $studentClassroom = StudentClassroom::find($attendance->student_id);
            
            // If not found, try to find by user_id (old format)
            if (!$studentClassroom) {
                $studentClassroom = StudentClassroom::where('user_id', $attendance->student_id)
                    ->where('classroom_id', $session->classroom_id)
                    ->first();
            }
            
            if ($studentClassroom && $studentClassroom->credit_left > 0) {
                $studentClassroom->decrement('credit_left');
            }
        }
        
        $session->status = 'completed';
        $session->save();
        $this->finalized = true;
        $this->loadAttendance();
    }

    public function render()
    {
        return view('livewire.attendance-manager');
    }
}
