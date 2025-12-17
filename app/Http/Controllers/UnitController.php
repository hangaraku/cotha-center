<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function show($id){
        return view('lesson/lesson')->with('unit',Unit::find($id));
    }
}
