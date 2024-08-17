<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class ScheduleCtrl extends Controller
{
    public function index(Request $request){
        $doctor = Doctor::findOrFail($request->doctor);
        
        return view('admin.schedule', compact('doctor'));
    }

    public function indexData(Request $request){
        return response()->json($request->doctor);
    }
}
