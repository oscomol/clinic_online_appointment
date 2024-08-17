<?php

namespace App\Http\Controllers;

use App\Models\ClientSchedule;
use App\Models\Doctor;
use App\Models\DoctorSched;
use Carbon\Carbon;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentCtrl extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        $doctors = Doctor::all();
        $doctorsId = $request->default;

        if (is_numeric($doctorsId)) {
            $doctors->map(function($doc) use ($doctorsId) {
                $doc->isSelected = ($doctorsId == $doc->id);
                return $doc;
            });
        }

        return view('client.appointment', compact('user', 'doctors', 'doctorsId'));
    }

    public function indexData(Request $request){

        return response()->json($request->user);
    }

    public function availability(Request $request) {
        try {
            $schedules = DoctorSched::where('doctorsId', $request->doctorsId)
                                    ->where('day', $request->day)
                                    ->where('status', 1)
                                    ->first();
            
            if (!$schedules) {
                return response()->json([
                    'error' => 'Doctor not available for the selected day.'
                ], 404);
            }

            $currentDate = Carbon::now();

            $clientSched = ClientSchedule::where('doctorsId', $request->doctorsId)
            ->where('date', $request->choosenDate)
            ->get()
            ->map(function($sched) use ($currentDate) {
                $schedDate = Carbon::parse($sched->date);
                
                $daysDifference = $currentDate->diffInDays($schedDate, false);

                if ($daysDifference > 1) {
                    $sched->isCancellable = false;
                } else {
                    $sched->isCancellable = true;
                }

                return $sched;
            });

            // if ($clientSched->count() >= $schedules->maxPatient) {
            //     return response()->json([
            //         'error' => 'No available slot on the selected day.'
            //     ], 404);
            // }
    
            $data = [
                'schedules' => $schedules,
                'clientSched' => $clientSched
            ];
    
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred. Please try again later.',
                'message' => $e->getMessage()  
            ], 500);
        }
    }    

    public function store(Request $request){
        DB::beginTransaction();
    try {           
        $validated = $request->validate([
            'patientName' => 'required',
            'age' => 'required|numeric',
            'gender' => 'required',
            'address' => 'required',
            'concern' => 'required',
            'severity' => 'required',
        ]);

        $validated['doctorsId'] = $request->doctorsId;
        $validated['expectedTime'] = $request->expectedTime;
        $validated['date'] = $request->date;
        $validated['number'] = $request->number;
        $validated['userId'] = $request->userId;


        $schedules = DoctorSched::where('doctorsId', $validated['doctorsId'])
        ->where('day', $request->day)
        ->where('status', 1)
        ->first();

        if (!$schedules) {
            return response()->json(['error' => 'Doctor is not available on the selected day.'], 422);
        }

        $dayAvailable = ClientSchedule::where('doctorsId', $validated['doctorsId'])
        ->where('date', $validated['date'])
        ->where('status', 1)
        ->where('number', $validated['number'])
        ->first();

        if ($dayAvailable) {
            return response()->json(['error' => 'We cannot reserve this day for you!'], 422);
        }

        $schedule = ClientSchedule::create($validated);

       
         $clientSched = ClientSchedule::where('doctorsId', $validated['doctorsId'])
                        ->where('date', $validated['date'])
                        ->get();

         $data = [
                'schedules' => $schedules,
                'clientSched' => $clientSched
                ];

        DB::commit();
        return response()->json($data);
    } catch (\Illuminate\Validation\ValidationException $err) {
        DB::rollBack();
        return response()->json(['error' => $err->errors()], 403);
    }
    }

    public function destroy(Request $request) {
        $cancelData = [
            "status" => 2
        ];
        ClientSchedule::findOrFail($request->id)->update($cancelData);

        $schedules = DoctorSched::where('doctorsId', $request->doctorsId)
            ->where('day', $request->day)
            ->where('status', 1)
            ->first();
    
        $clientSched = ClientSchedule::where('doctorsId', $request->doctorsId)
            ->where('date', $request->choosenDate)
            ->get();
    
        $data = [
            'schedules' => $schedules,
            'clientSched' => $clientSched
        ];
    
        return response()->json($data);
    }

    public function show(Request $request){
        $reservation = ClientSchedule::findOrFail($request->patient);
        return response()->json($reservation);
    }

    public function update(Request $request){
        DB::beginTransaction();
        try {           
            $validated = $request->validate([
                'patientName' => 'required',
                'age' => 'required|numeric',
                'gender' => 'required',
                'address' => 'required',
                'concern' => 'required',
                'severity' => 'required',
                'id' => 'required',
            ]);

            $reservation = ClientSchedule::findOrFail($validated['id']);

            $reservation->update($validated);
    
            DB::commit();
            return response()->json($reservation);
        } catch (\Illuminate\Validation\ValidationException $err) {
            DB::rollBack();
            return response()->json(['error' => $err->errors()], 403);
        }
    }
}
