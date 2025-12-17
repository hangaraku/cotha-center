<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Exercise;
use App\Models\ExerciseQuestion;
use App\Models\UserExercise;
use App\Models\UserExerciseQuestion;
use App\Models\ClassroomTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    public function show($classroom, $id){
        $exercise = Exercise::with(['unit.module.level'])->findOrFail($id);
        $classroom = Classroom::active()->findOrFail($classroom);
        $module = $exercise->unit->module;
        $unit = $exercise->unit;
        
        // Check if user is a teacher for this classroom
        $isTeacher = ClassroomTeacher::where('user_id', Auth::user()->id)
            ->where('classroom_id', $classroom->id)
            ->exists();
        
        return view("exercises.exercise")
            ->with("exercise", $exercise)
            ->with("classroom", $classroom)
            ->with("module", $module)
            ->with("unit", $unit)
            ->with("isTeacher", $isTeacher);
    }

    public function development(){
        return UserExercise::where('exercise_id','=',18)->delete();
    }
    
    public function saveAnswer(Request $request) {
        $userExercise = UserExercise::create([
            "user_id" => Auth::user()->id,
            "exercise_id" => $request->exercise
        ]);
    
        $exercise = Exercise::find($request->exercise);
        foreach ($exercise->exerciseQuestions as $question) {
            UserExerciseQuestion::create([
                "user_exercise_id" => $userExercise->id,
                "answer_id" => $request->input("question" . $question->id),
                "exercise_question_id" => $question->id
            ]);
        }
    
        return redirect()->route('exercise', ["classroom" => 3, "id" => $exercise->id]);
    }
}
