<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Module;
use App\Models\Unit;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function show($classroom,$id, $unitId){
        return view('lesson/lesson')
        ->with("classroom", Classroom::active()->findOrFail($classroom))
        ->with("module",Module::find($id))
        ->with("unit",Unit::find($unitId));
    }
}
