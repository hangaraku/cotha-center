<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use App\Services\StudentPointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPointController extends Controller
{
    protected $studentPointService;

    public function __construct(StudentPointService $studentPointService)
    {
        $this->studentPointService = $studentPointService;
    }

    /**
     * Display student's own point information
     */
    public function myPoints()
    {
        $user = Auth::user();
        $pointCalculation = $this->studentPointService->calculateStudentPoints($user);
        $pointHistory = $this->studentPointService->getPointHistory($user);

        return view('student-points.my-points', compact('pointCalculation', 'pointHistory'));
    }

    /**
     * Display point leaderboard for a classroom
     */
    public function classroomLeaderboard($classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        $leaderboard = $this->studentPointService->getClassroomLeaderboard($classroomId);

        return view('student-points.leaderboard', compact('classroom', 'leaderboard'));
    }

    /**
     * Display detailed point statistics for a classroom (admin/teacher view)
     */
    public function classroomStats($classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        $stats = $this->studentPointService->getClassroomPointStats($classroomId);

        return view('student-points.classroom-stats', compact('classroom', 'stats'));
    }

    /**
     * API endpoint to get student's point calculation
     */
    public function apiMyPoints()
    {
        $user = Auth::user();
        $pointCalculation = $this->studentPointService->calculateStudentPoints($user);
        
        return response()->json([
            'success' => true,
            'data' => $pointCalculation
        ]);
    }

    /**
     * API endpoint to sync user points
     */
    public function apiSyncPoints()
    {
        $user = Auth::user();
        $success = $this->studentPointService->syncUserPoints($user);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Points synced successfully',
                'new_points' => $user->point
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to sync points'
        ], 500);
    }

    /**
     * API endpoint to get point history
     */
    public function apiPointHistory()
    {
        $user = Auth::user();
        $pointHistory = $this->studentPointService->getPointHistory($user);
        
        return response()->json([
            'success' => true,
            'data' => $pointHistory
        ]);
    }
} 