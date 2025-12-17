<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Level;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function show($id){
        $classroom = Classroom::active()->findOrFail($id);
        return view('course/course')->with("classroom", $classroom);
    }

    public function showLevel($classroomId, $levelId)
    {
        $classroom = Classroom::active()->findOrFail($classroomId);
        \Log::info('Classroom:', ['id' => $classroomId]);
        \Log::info('Level Query:', [
            'levels' => $classroom->levels()->where('levels.id', $levelId)->wherePivot('is_active', true)->toSql(),
            'bindings' => $classroom->levels()->where('levels.id', $levelId)->wherePivot('is_active', true)->getBindings(),
        ]);
        $level = $classroom->levels()->where('levels.id', $levelId)->wherePivot('is_active', true)->first();
        if (!$level) {
            abort(500, 'Level not found for classroom, but exists in DB. Debug needed.');
        }
        // Optionally, check user access here
        return view('course/course', compact('classroom', 'level'));
    }

    public function redirectToLatestLevel($classroomId)
    {
        $classroom = Classroom::active()->findOrFail($classroomId);
        $latestActiveLevel = $classroom->classroomLevels()->where('is_active', true)->orderByDesc('id')->first();
        if ($latestActiveLevel) {
            return redirect()->route('classroom.level', ['classroom' => $classroomId, 'level' => $latestActiveLevel->level_id]);
        }
        return redirect('/');
    }
}
