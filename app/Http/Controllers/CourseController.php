<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\ClassroomTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(){
        if(Auth::check()){
            $user = Auth::user();
            
            // Get classrooms where user is a student (only active ones)
            $studentClassrooms = $user->studentClassrooms()
                ->with(['classroom' => function($query) {
                    $query->active();
                }])
                ->get()
                ->pluck('classroom')
                ->filter(); // Remove null values
            
            // Get classrooms where user is a teacher (only active ones)
            $teacherClassrooms = ClassroomTeacher::where('user_id', $user->id)
                ->with(['classroom' => function($query) {
                    $query->active();
                }])
                ->get()
                ->pluck('classroom')
                ->filter(); // Remove null values
            
            // Combine both collections and remove duplicates
            $allClassrooms = $studentClassrooms->merge($teacherClassrooms)->unique('id');
            
            // For teachers, group by level to avoid duplicates
            $groupedClassrooms = collect();
            
            foreach ($allClassrooms as $classroom) {
                $isStudent = $studentClassrooms->contains('id', $classroom->id);
                $isTeacher = ClassroomTeacher::where('user_id', $user->id)
                    ->where('classroom_id', $classroom->id)
                    ->exists();
                
                if ($isStudent) {
                    // For students, show all their classrooms
                    $classroom->user_role = 'student';
                    $groupedClassrooms->push($classroom);
                } elseif ($isTeacher) {
                    // For teachers, group by level
                    foreach ($classroom->classroomLevels->where('is_active', true) as $classroomLevel) {
                        $levelId = $classroomLevel->level->id;
                        
                        // Check if this level is already added for this teacher
                        $existingLevel = $groupedClassrooms->first(function($item) use ($levelId, $user) {
                            if ($item->user_role !== 'teacher') return false;
                            
                            // Check if this level exists in the item's classroom levels
                            return $item->classroomLevels->where('is_active', true)
                                ->contains('level_id', $levelId);
                        });
                        
                        if (!$existingLevel) {
                            // Create a new classroom object for this level
                            $levelClassroom = clone $classroom;
                            $levelClassroom->user_role = 'teacher';
                            $levelClassroom->display_level_id = $levelId;
                            $levelClassroom->display_level_name = $classroomLevel->level->name;
                            $levelClassroom->display_level_img_url = $classroomLevel->level->img_url;
                            $groupedClassrooms->push($levelClassroom);
                        }
                    }
                }
            }
            
            $classroomsWithRoles = $groupedClassrooms;
            
            return view('all_courses/all-courses',
            [
                "userClassroom" => $classroomsWithRoles,
                "levels" => Level::all()
            ]);
        }

        return view('all_courses/all-courses',
        [
            "levels" => Level::all()
        ]);
     
}
}
