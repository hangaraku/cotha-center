<?php

namespace App\Http\Middleware;

use App\Models\Unit;
use App\Models\UserModule;
use App\Models\UserUnit;
use App\Models\ClassroomTeacher;
use Closure;

class CheckUserAccess
{
    public function handle($request, Closure $next)
    {
        $moduleId = $request->route('id');
        $unitId = $request->route('unitId');
        $classroom = $request->route('classroom');

        // Check if user is a teacher assigned to this classroom
        $isTeacher = ClassroomTeacher::where('user_id', auth()->user()->id)
            ->where('classroom_id', $classroom)
            ->exists();

        // If user is a teacher, grant full access to all modules and units
        if ($isTeacher) {
            return $next($request);
        }

        // For students, check module access    
        if (!auth()->user()->userModules->contains('module_id', $moduleId)) {
            return redirect()->back()->with('error', 'You do not have access to this module.');
        }

        // For students, check unit access
        if (auth()->user()->userUnits->where('unit_id', $unitId)->isEmpty()) {
            return redirect()->back()->with('error', 'You do not have access to this unit.');
        }

        return $next($request);
    }
}
