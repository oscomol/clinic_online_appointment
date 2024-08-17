<?php

namespace App\Http\Controllers;

use App\Models\ClientSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientDashboardCtrl extends Controller
{
    public function index(Request $request){
        $overall = ClientSchedule::where('userId', $request->user)->count();
        $scheduled = ClientSchedule::where('userId', $request->user)->where('status', 1)->count();
        $overallMissed = ClientSchedule::where('userId', $request->user)->where('status', 2)->orWhere('status', 3)->count();
        $success =  ClientSchedule::where('userId', $request->user)->where('status', 5)->count();;
        $recent = ClientSchedule::where('userId', $request->user)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->join('doctors', 'client_schedule.doctorsId', '=', 'doctors.id')
        ->select('client_schedule.*', 'doctors.name as doctor_name')
        ->get();

        $weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->startOfWeek(); 
        
        $allReserve = 0;
        $unprocessedCancelled = 0;
        $unprocessedSuccess = 0;
        $unprocessed = 0;

        $weekDaysWithDates = [];
        foreach ($weekdays as $index => $day) {
            $date = $startOfWeek->copy()->addDays($index)->format('Y-m-d'); 

            $all = ClientSchedule::where('date', $date)
            ->where('userId', $request->user)
            ->count();

            $reserve = ClientSchedule::where('date', $date)->where('userId', $request->user)->where('status', 1)->count();
            
            $cancel = ClientSchedule::where('date', $date)
            ->where('userId', $request->user)
            ->where(function($query) {
                $query->where('status', 2)
                      ->orWhere('status', 3)
                      ->orWhere('status', '>', 5);
            })
            ->count();
            $done = ClientSchedule::where('date', $date)->where('userId', $request->user)->where('status', 5)->count();

            $allReserve += $reserve;
            $unprocessedSuccess += $done;
            $unprocessedCancelled += $cancel;
            $unprocessed += $all;
           
            $weekDaysWithDates[] = ["day" => $day, 'all' => $all, 'reserve' => $reserve, "date" => $date];
        }
    
        $startOfWeekFormatted = $currentDate->startOfWeek()->format('F j, Y');
        $endOfWeekFormatted = $currentDate->endOfWeek()->format('F j, Y');
        $currentWeek = "$startOfWeekFormatted to $endOfWeekFormatted";

        $weeklyStatusData = [$unprocessed, $allReserve, $unprocessedCancelled, $unprocessedSuccess];

        $data = [
            'all' => $overall,
            'scheduled' => $scheduled,
            'missed' => $overallMissed,
            'success' => $success,
            'recent' => $recent,
            'currentWeek' => $currentWeek,
            'weeksData' => $weekDaysWithDates,
            'weeklyStatusData' => $weeklyStatusData,
            "id" => $request->user
        ];

        return response()->json($data);
    }

    public function checkupStatus(Request $request){
        $byStatus = ClientSchedule::where('userId', $request->user)
        ->where('status', $request->status)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->join('doctors', 'client_schedule.doctorsId', '=', 'doctors.id')
        ->select('client_schedule.*', 'doctors.name as doctor_name')
        ->get();

        return response()->json($byStatus);
    }
}
