<?php

namespace App\Http\Controllers;

use App\Models\RaiseHand;
use App\Models\ClassroomTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaiseHandController extends Controller
{
    /**
     * Toggle raise hand status for a student
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'unit_id' => 'nullable|exists:units,id',
            'message' => 'nullable|string|max:500'
        ]);

        $userId = Auth::user()->id;
        $classroomId = $request->classroom_id;
        $unitId = $request->unit_id;
        $message = $request->message;

        // Find existing raise hand record
        $raiseHand = RaiseHand::where('user_id', $userId)
            ->where('classroom_id', $classroomId)
            ->first();

        if ($raiseHand) {
            // Toggle the status
            $raiseHand->update([
                'is_raised' => !$raiseHand->is_raised,
                'unit_id' => $unitId,
                'message' => $message,
                'raised_at' => !$raiseHand->is_raised ? now() : null,
                'lowered_at' => $raiseHand->is_raised ? now() : null,
            ]);
        } else {
            // Create new raise hand record
            $raiseHand = RaiseHand::create([
                'user_id' => $userId,
                'classroom_id' => $classroomId,
                'unit_id' => $unitId,
                'is_raised' => true,
                'message' => $message,
                'raised_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'is_raised' => $raiseHand->is_raised,
            'message' => $raiseHand->is_raised ? 'Tangan berhasil diangkat' : 'Tangan berhasil diturunkan'
        ]);
    }

    /**
     * Get all raised hands for a classroom (for teachers)
     */
    public function getClassroomRaisedHands(Request $request, $classroomId)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        // Check if user is a teacher for this classroom
        $isTeacher = ClassroomTeacher::where('user_id', Auth::user()->id)
            ->where('classroom_id', $classroomId)
            ->exists();

        if (!$isTeacher) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $raisedHands = RaiseHand::with(['user', 'unit'])
            ->where('classroom_id', $classroomId)
            ->where('is_raised', true)
            ->orderBy('raised_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'raised_hands' => $raisedHands
        ]);
    }

    /**
     * Lower a specific student's hand (for teachers)
     */
    public function lowerHand(Request $request, $raiseHandId)
    {
        $request->validate([
            'raise_hand_id' => 'required|exists:raise_hands,id'
        ]);

        $raiseHand = RaiseHand::findOrFail($raiseHandId);

        // Check if user is a teacher for this classroom
        $isTeacher = ClassroomTeacher::where('user_id', Auth::user()->id)
            ->where('classroom_id', $raiseHand->classroom_id)
            ->exists();

        if (!$isTeacher) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $raiseHand->update([
            'is_raised' => false,
            'lowered_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tangan siswa berhasil diturunkan'
        ]);
    }

    /**
     * Show teacher view for raise hands
     */
    public function teacherView($classroomId)
    {
        // Check if user is a teacher for this classroom
        $isTeacher = ClassroomTeacher::where('user_id', Auth::user()->id)
            ->where('classroom_id', $classroomId)
            ->exists();

        if (!$isTeacher) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $classroom = \App\Models\Classroom::active()->findOrFail($classroomId);
        
        return view('teacher.raise-hands', compact('classroom'));
    }
}
