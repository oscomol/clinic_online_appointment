<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSched;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrShedCtrl extends Controller
{

    public function indexView(){
        $user = auth()->user();

        return view('admin.doctors', compact('user'));   
    }
    public function index(Request $request){
        $doctor = Doctor::findOrFail($request->doctor);
        $user = auth()->user();

        $currentDate = Carbon::now();
        $today = Carbon::now()->format('Y-m-d');


        $schedules = [
            'Monday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Monday')->first(),
            'Tuesday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Tuesday')->first(),
            'Wednesday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Wednesday')->first(),
            'Thursday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Thursday')->first(),
            'Friday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Friday')->first(),
            'Saturday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Saturday')->first(),
            'Sunday' => DoctorSched::where('doctorsId', $doctor->id)->where('day', 'Sunday')->first(),
        ];

        return view('admin.schedule', compact('doctor', 'schedules', 'user', 'today'));
    }

    public function store(Request $request)
    {
    DB::beginTransaction();
    try {           
        $validated = $request->validate([
            'maxPatient' => 'required|numeric',
            'allotedTime' => 'required|numeric',
            'doctorsId' => 'required',
            'day' => 'required',
        ]);

        DoctorSched::create($validated);
        DB::commit();
        return response()->json($validated);
    } catch (\Illuminate\Validation\ValidationException $err) {
        DB::rollBack();
        return response()->json(['error' => $err->errors()]);
    }
    }

    public function update(Request $request){
        DB::beginTransaction();
    try {           
        $validated = $request->validate([
            'maxPatient' => 'required|numeric',
            'allotedTime' => 'required|numeric',
            'id' => 'required',
        ]);

        $doctor = DoctorSched::findOrFail($validated['id']);

        $doctor->update($validated);

        DB::commit();
        return response()->json($doctor);
    } catch (\Illuminate\Validation\ValidationException $err) {
        DB::rollBack();
        return response()->json(['error' => $err->errors()]);
    }
    }

    public function updateStatus(Request $request) {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'status' => 'required|numeric',
                'id' => 'required',
            ]);
    
            $doctor = DoctorSched::findOrFail($request->id);
            $doctor->update($validated);
    
            DB::commit();
            return response()->json(['success' => true, 'data' => $doctor]);
        } catch (\Illuminate\Validation\ValidationException $err) {
            DB::rollBack();
            return response()->json(['error' => $err->errors()], 422);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
    
}
