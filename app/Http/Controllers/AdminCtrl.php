<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\ClientSchedule;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCtrl extends Controller
{

    public function index(){
        $user = auth()->user();

        return view('admin.administrator', compact('user'));
    }
    public function dashboardIndex(){
        $admins = User::where('userType', 2)->count();
        $users = User::where('userType', 1)->count();
        $doctors = Doctor::all()->count();
        $all = ClientSchedule::all()->count();

        $weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->startOfWeek(); 

        $weekDaysWithDates = [];
        
        foreach ($weekdays as $index => $day) {
            $date = $startOfWeek->copy()->addDays($index)->format('Y-m-d'); 
            $all = ClientSchedule::where('date', $date)->count();
            $reserved = ClientSchedule::where('date', $date)->where('status', 1)->count();
            $weekDaysWithDates[] = ['day' => $day, 'all' => $all, 'reserve' => $reserved];
        }
    
        $startOfWeekFormatted = $currentDate->startOfWeek()->format('F j');
        $endOfWeekFormatted = $currentDate->endOfWeek()->format('F j, Y');
        $currentWeek = "$startOfWeekFormatted to $endOfWeekFormatted";



        $sixMonthsAgo = $currentDate->subMonths(5)->startOfMonth();

        $months = [];
        $allCount = 0;
        $reserved = 0;
        $cancelled = 0;
        $undecided = 0;
        $done = 0;

        for ($i = 0; $i < 6; $i++) {
            $month = $sixMonthsAgo->copy()->addMonths($i);
            $date = $month->format('Y-m');
            $monthsReservationCount = ClientSchedule::where('date', 'like', "$date%")->count();
            $reservationsCount = ClientSchedule::where('date', 'like', "$date%")->where('status', 1)->count();
            $months[] = [
                'all' => $monthsReservationCount,
                'reserve' => $reservationsCount,
                'monthName' => $month->format('F'),
                'monthLabel' => $month->format('F, Y')
            ];

            $allCount += $monthsReservationCount;
            $reserved += ClientSchedule::where('date', 'like', "$date%")->where('status', 1)->count();
            $cancelled += ClientSchedule::where('date', 'like', "$date%")->where('status', 2)->orWhere('status', 3)->count();
            $undecided += ClientSchedule::where('date', 'like', "$date%")->where('status', 4)->count();
            $done += ClientSchedule::where('date', 'like', "$date%")->where('status', 5)->count();
        }

        $allReservationCount = [$allCount, $reserved, $cancelled, $undecided, $done];

        $topUser = User::where('userType', 1)->select('id', 'email', 'name')->get();

        $topUser = $topUser->map(function($user) {
            $reserveCount = ClientSchedule::where('userId', $user->id)->count();
            $user->reserveCount = $reserveCount;
            return $user;
        });

        $top4User = $topUser->sortByDesc('reserveCount')->take(4)->values();  

        $recentDoctor = Doctor::orderBy('created_at', 'desc')
        ->take(8)
        ->get();

        $recent = ClientSchedule::join('doctors', 'client_schedule.doctorsId', '=', 'doctors.id')
        ->select('client_schedule.*', 'doctors.name as doctor_name')
        ->orderBy('client_schedule.created_at', 'desc')
        ->take(10)
        ->get();

        $allDoctors = Doctor::select('id', 'name')->get();

        $doctorsWithReservation = $allDoctors->map(function($doc) {
            $doc->reservationsCount = ClientSchedule::where('doctorsId', $doc->id)->count();
            return $doc;
        });
        
        $top5 = $doctorsWithReservation->sortByDesc('reservationsCount')->take(5)->values();        

        $data = [
            'admins' => $admins,
            'users' => $users,
            'doctors' => $doctors,
            'all' => ClientSchedule::all()->count(),
            'recent' => $recent,
            'recentDoctor' => $recentDoctor,
            'currentWeek' => $currentWeek,
            'weekDaysWithDates' => $weekDaysWithDates,
            'doctorsWithReservation' => $top5,
            'monthsData' => $months,
            'allReservationCount' => $allReservationCount,
            'topUser' => $top4User,
        ];

        return response()->json($data);
    }
}
