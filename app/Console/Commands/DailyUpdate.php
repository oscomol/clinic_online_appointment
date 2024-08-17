<?php

namespace App\Console\Commands;

use App\Mail\EmailNotification;
use App\Models\ClientSchedule;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending email update everyday';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $appointments = ClientSchedule::where('date', $tomorrow)->where('status', 1)->select('userId', 'patientName', 'doctorsId', 'expectedTime')->get();

        if($appointments){
            $completeDetails = $appointments->map(function($app) {
                $user = User::where('id', $app->userId)->select('name', 'email')->first();
                $doctor = Doctor::where('id', $app->doctorsId)->select('name', 'id')->first();
                $app->user = $user->name;
                $app->email = $user->email;
                $app->doctor = $doctor->name;
                return $app;
            });
           if($completeDetails){
            $subject = "Appointment update";
            foreach($completeDetails as $user){
                $message = "This is a friendly reminder for your appointment tomorrow at Cheche Eye Care near Jero's Vulcanizing Shop with Dr. " . $user->doctor . " at " . $user->expectedTime . ". Please arrive before the expected appointment time.";

            $name = $user->user;
            $closingMessage = "See you there!";
            Mail::to($user->email)->send(new EmailNotification($message, $subject, $name, $closingMessage));
            }
           }
        }
    }
}
