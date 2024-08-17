<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminWeekCtrl extends Controller
{

    public function index() {
        $doctors = Doctor::select('name', 'id')->get();
        $user = auth()->user();
        
        $weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        $statusData = [
            ['value' => 0, 'name' => 'All'],
            ['value' => 1, 'name' => 'Reserved'],
            ['value' => 2, 'name' => 'Cancelled'],
            ['value' => 4, 'name' => 'Undecided'],
            ['value' => 5, 'name' => 'Done'],
        ];
        
        
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->startOfWeek(); 

        $weekDaysWithDates = [];

        foreach ($weekdays as $index => $day) {
            $date = $startOfWeek->copy()->addDays($index)->format('Y-m-d'); 
            $weekDaysWithDates[] = [$day => $date];
        }
    
        $startOfWeekFormatted = $currentDate->startOfWeek()->format('F j');
        $endOfWeekFormatted = $currentDate->endOfWeek()->format('F j, Y');
        $currentWeek = "$startOfWeekFormatted to $endOfWeekFormatted";
    
        return view('admin.todaySched', compact('weekDaysWithDates', 'doctors', 'currentWeek', 'user', 'statusData'));
    }
    
    public function getIndexData(Request $request){
        return response()->json($request);
    }
}
