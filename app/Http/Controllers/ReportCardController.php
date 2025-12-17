<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomSessionAttendance;
use App\Models\Module;
use App\Models\StudentClassroom;
use App\Models\User;
use App\Models\UserUnit;

class ReportCardController extends Controller
{
    public function courseReportCard($userId, $classroomId)
    {
        // Get user and classroom with relationships
        $user = User::with('students')->findOrFail($userId);
        $classroom = Classroom::with(['sessions', 'projects', 'classroomLevels.level'])->findOrFail($classroomId);

        // Get student classroom relationship
        $studentClassroom = StudentClassroom::where('user_id', $userId)
            ->where('classroom_id', $classroomId)
            ->firstOrFail();

        // Get first classroom level name
        $firstClassroomLevel = $classroom->classroomLevels()->with('level')->first();
        $courseName = $firstClassroomLevel ? $firstClassroomLevel->level->name : 'N/A';

        // Calculate Project Achievement and get project list
        $projectData = $this->calculateProjectAchievement($userId, $classroomId);
        $projectAchievement = $projectData['count'];
        $projectsList = $projectData['projects'];

        // Calculate Learning Efficiency: user's units / class average units
        $learningEfficiency = $this->calculateLearningEfficiency($userId, $classroomId);

        // Get Learning Engagement score (1-10 from teacher)
        $learningEngagement = $studentClassroom->engagement_score ?? 'Not Set';

        // Calculate Attendance Consistency: attended / total official meetings
        $attendanceConsistency = $this->calculateAttendanceConsistency($userId, $classroomId);

        // Generate showcase URL for QR code
        $showcaseUrl = route('showcase.user-profile', ['slug' => $user->slug]);

        // Pass data to view
        return view('reports.course-report-card', compact(
            'user',
            'classroom',
            'courseName',
            'projectAchievement',
            'projectsList',
            'learningEfficiency',
            'learningEngagement',
            'attendanceConsistency',
            'showcaseUrl'
        ));
    }

    private function calculateProjectAchievement($userId, $classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);

        // Get all modules for this classroom through classroom_levels
        $moduleIds = $classroom->projects()->pluck('modules.id');

        $count = 0;
        $projects = [];

        foreach ($moduleIds as $moduleId) {
            $module = Module::with('units')->findOrFail($moduleId);

            // Count how many units the user has completed in this module
            $completedUnits = UserUnit::where('user_id', $userId)
                ->whereIn('unit_id', $module->units->pluck('id'))
                ->count();

            // If user completed >= 5 units in this module, count it
            if ($completedUnits >= 5) {
                $count++;
                $projects[] = $module->name;
            }
        }

        return [
            'count' => $count,
            'projects' => $projects,
        ];
    }

    private function calculateLearningEfficiency($userId, $classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);

        // Get all modules for this classroom
        $moduleIds = $classroom->projects()->pluck('modules.id');

        // Get all unit IDs for these modules
        $unitIds = Module::whereIn('id', $moduleIds)
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten()
            ->pluck('id');

        // Get user's completed units
        $userUnitsCount = UserUnit::where('user_id', $userId)
            ->whereIn('unit_id', $unitIds)
            ->count();

        // Get all students in this classroom
        $studentIds = StudentClassroom::where('classroom_id', $classroomId)
            ->pluck('user_id');

        if ($studentIds->isEmpty()) {
            return ['ratio' => '0/0', 'value' => 0];
        }

        // Calculate average units completed by all students
        $totalUnitsAllStudents = UserUnit::whereIn('user_id', $studentIds)
            ->whereIn('unit_id', $unitIds)
            ->count();

        $averageUnits = round($totalUnitsAllStudents / $studentIds->count());

        return [
            'ratio' => "{$userUnitsCount}/{$averageUnits}",
            'value' => $averageUnits > 0 ? round(($userUnitsCount / $averageUnits) * 100, 1) : 0,
        ];
    }

    private function calculateAttendanceConsistency($userId, $classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);

        // Get the StudentClassroom record for this user in this classroom
        $studentClassroom = StudentClassroom::where('user_id', $userId)
            ->where('classroom_id', $classroomId)
            ->first();

        if (! $studentClassroom) {
            return ['ratio' => '0/0', 'percentage' => 0];
        }

        // Get all OFFICIAL sessions for this classroom
        $officialSessions = $classroom->sessions()
            ->where('type', 'official')
            ->get();

        $totalOfficialSessions = $officialSessions->count();

        if ($totalOfficialSessions === 0) {
            return ['ratio' => '0/0', 'percentage' => 0];
        }

        // Count how many the student attended (using StudentClassroom ID, not User ID)
        $attendedCount = ClassroomSessionAttendance::whereIn('classroom_session_id', $officialSessions->pluck('id'))
            ->where('student_id', $studentClassroom->id)
            ->where('is_present', true)
            ->count();

        $percentage = round(($attendedCount / $totalOfficialSessions) * 100, 1);

        return [
            'ratio' => "{$attendedCount}/{$totalOfficialSessions}",
            'percentage' => $percentage,
        ];
    }
}
