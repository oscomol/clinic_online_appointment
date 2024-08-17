<?php

namespace App\Http\Controllers;

use App\Mail\AccountVerification;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerficationCtrl extends Controller
{
    public function index(Request $request){
     $token = $request->token;
     
     $verification = EmailVerification::where('token', $token)->first();

     if(!$verification){
        return view('auth.linkEpired');
     }

     if(Carbon::now() > $verification->expires_at){
        return view('auth.linkEpired');
     }

     $email = $verification->email;
     $userType = $verification->userType;
    
     return view('auth.register', compact('email', 'userType'));
     
    }
    public function verify(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
    
            $emailExists = User::where('email', $request->email)->exists();
    
            if ($emailExists) {
                return response()->json(['error' => 'Email already exist.'], 403);
            }
    
            $token = Str::random(50);
            $expires_at = Carbon::now()->addMinutes(30);
    
            EmailVerification::create([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => $expires_at,
                'userType' => $request->userType,
            ]);
    
            $verificationLink = route('email.verify', ['token' => $token]);
            $subject = "Account verification email";
            $message = "Verify your account by clicking the link below:";
    
           Mail::to($request->email)->send(new AccountVerification($verificationLink, $subject, $message));
    
           return response()->json(['success' => true]);
    
        } catch (\Illuminate\Validation\ValidationException $err) {
            return response()->json(['error' => 'Email required'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cannot use this email.'], 500);
        }
    }
}