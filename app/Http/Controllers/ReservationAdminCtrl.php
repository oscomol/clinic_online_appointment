<?php

namespace App\Http\Controllers;

use App\Mail\EmailNotification;
use App\Models\ClientSchedule;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReservationAdminCtrl extends Controller
{
    public function index(){
        $doctors = Doctor::all();
        $user = auth()->user();

        $currentDate = Carbon::now();
        $today = Carbon::now()->format('Y-m-d');

        $statusData = [
            (object) ['value' => 0, 'name' => 'All'],
            (object) ['value' => 1, 'name' => 'Reserved'],
            (object) ['value' => 2, 'name' => 'Cancelled'],
            (object) ['value' => 4, 'name' => 'Undecided'],
            (object) ['value' => 5, 'name' => 'Done']
        ];

        return view('admin.history', compact('doctors', 'today', 'user', 'statusData'));
    }
    public function getdoctorsReservation(Request $request){

        $currentDate = Carbon::now()->startOfDay();

        $schedDate = Carbon::parse($request->date);

        $hasPassed = $currentDate->diffInDays($schedDate, false);

        $doctorsPrice = Doctor::where('id',  $request->doctor)->select("checkUpLimit")->first();

        $reservations = ClientSchedule::where('doctorsId', $request->doctor)->where('date', $request->date)->get();

        $data = [
            "reservations" => $reservations,
            'hasPassed' => $hasPassed,
            'price' => $doctorsPrice->checkUpLimit,
        ];
        
        return response()->json($data);
    }

    public function cancelReservation(Request $request)
    {
        $userIds = ClientSchedule::where('doctorsId', $request->doctorsId)
            ->where('date', $request->date)
            ->where('status', 1)
            ->select('userId', 'patientName', 'doctorsId')
            ->get();
    
        $updateStatus = ClientSchedule::where('doctorsId', $request->doctorsId)
            ->where('date', $request->date)
            ->where('status', 1)
            ->update(['status' => $request->status]);
    
        if ($updateStatus) {
            $emails = $userIds->map(function ($user) {
                $userFound = User::where('id', $user->userId)->select('email', 'name')->first();
                $doctor = Doctor::where('id', $user->doctorsId)->select('name')->first();
                $user->email = $userFound->email;
                $user->name = $userFound->name;
                $user->doctor = $doctor->name;
                return $user;
            })->filter();
            
            if ($emails->isNotEmpty()) {
                $subject = "Cancellation of Appointment";
                $closingMessage = "Thank you for understanding.";
                $formattedDate = Carbon::parse($request->date)->format('F d, Y');
            
                $emails->each(function ($user) use ($formattedDate, $subject, $closingMessage) {
                    $message = "Sorry for the inconvenience. Your reservation on
                    ".$formattedDate." for patient, " . $user->patientName . " for Dr. ".$user->doctor. " has been cancelled! We would like you to make another appointment and will do our best to cater to you.";
                    $name = $user->name;
            
                    Mail::to($user->email)->send(new EmailNotification($message, $subject, $name, $closingMessage));
                });
            }            
        
            
            return response()->json(['message' => 'Reservation cancelled and emails sent.', 'status' => $updateStatus, 'emails' => $emails]);
        }
    
        return response()->json(['message' => 'No reservations found or already cancelled.'], 404);
    }
    

    public function statusUpdate(Request $request){
        $status = $request->status;

        $user = ClientSchedule::where('id', $request->id)->select('userId', 'doctorsId', 'date', 'patientName')->first();

        $name = "";
        $email = "";
        $doctor = "";
        $patient = "";
        $date = "";
        $message = "";
        $subject = "Appointment update";
        $closingMessage = "";

        if($user){
            $date = Carbon::parse($user->date)->format('F d, Y');
            $patient = $user->patientName;
            $userFound = User::where('id', $user->userId)->select('email', 'name')->first();
            if($userFound){
                $name = $userFound->name;
                $email = $userFound->email;
            }
            $doctor = Doctor::where('id', $user->doctorsId)->select('name')->first()->name;
        }

       switch ($status){
        case 5:
            $message = "Your appointent on ".$date. " for Dr. ".$doctor." was marked DONE for patient ".$patient.". Please follow the docto's advice. We're rooting to your good healt.";
            $closingMessage = "Thank you for choosing us.";
            break;
            case 6:
                $message = "Your appointent on ".$date. " for patient. ".$patient." was cancelled for reason: Doctor (".$doctor.") was not available. We apologize for any inconvenience it may caused.";
                $closingMessage = "Thank you for your understanding.";
                break;
                case 7: $message = "Patient, ".$patient. " were not present during the day of his/her appointment. This is a friendly reminder that we can ban you with the clinic. Please be responsible next time.";
                 $closingMessage = "Thank you for your understanding.";
                break;
                case 8: 
                $message = "Your appointent on ".$date. " for patient, ".$patient." was cancelled for reason: System error. We apologize for any inconvenience it may caused.";
                 $closingMessage = "Thank you for your understanding.";
       }

       $reservation = ClientSchedule::where('id', $request->id)
       ->update(['status' => $request->status]);

       if($reservation){
        $mailResponse = Mail::to($email)->send(new EmailNotification($message, $subject, $name, $closingMessage));

        if($mailResponse){
             return response()->json(['message' => 'Email not sent.', 'status' => $status, 'emails' => $email]);
        } 

        return response()->json(['message' => 'Reservation cancelled and emails sent.', 'status' => $status, 'emails' => $email]);
           
       }


       return response()->json(['message' => 'No reservations found or already cancelled.'], 404);
    }
}
