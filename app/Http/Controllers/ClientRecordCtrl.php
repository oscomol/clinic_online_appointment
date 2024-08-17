<?php

namespace App\Http\Controllers;

use App\Models\ClientSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientRecordCtrl extends Controller
{
    public function index(){
        $user = auth()->user();
        $specialties = [
            ['value' => 0, 'name' => 'All'],
            ['value' => 1, 'name' => 'Scheduled'],
            ['value' => 2, 'name' => 'Cancelled'],
            ['value' => 5, 'name' => 'Done'],
        ];


        $category = array_map(function($item) {
            return (object) $item;
        }, $specialties);


        return view('client.clientHistory', compact('user', 'category'));
    }
    public function getIndexData(Request $request){
        $user = auth()->user();

        $records = [];
       
        if($request->status > 0){
            $records = ClientSchedule::where('userId', $user->id)->where('status', $request->status)
            ->orderBy('created_at', 'desc')
            ->join('doctors', 'client_schedule.doctorsId', '=', 'doctors.id')
            ->select('client_schedule.*', 'doctors.name as doctor_name', 'doctors.checkupLimit as payment')
            ->get();
        }else{
            $records = ClientSchedule::where('userId', $user->id)
            ->orderBy('created_at', 'desc')
            ->join('doctors', 'client_schedule.doctorsId', '=', 'doctors.id')
            ->select('client_schedule.*', 'doctors.name as doctor_name', 'doctors.checkupLimit as payment')
            ->get();
        }

        $currentDate = Carbon::now()->startOfDay();

        $records->map(function($rec) use($currentDate) {
            $schedDate = Carbon::parse($rec->date);
            $hasPassed = $currentDate->diffInDays($schedDate, false);
            $rec->isPassed = $hasPassed;
            return $rec;
        });

        return response()->json($records);
    }
}
