<?php

namespace App\Http\Controllers;

use App\Mail\AccountVerification;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ResetPassCtrl extends Controller
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
     
        return view('auth.resetPasswordForm', compact('email'));
    }
    public function resetPassword(Request $request)
    {
        try {
            // Validate the email
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $emailExists = User::where('email', $validated['email'])->first();
    
            if (!$emailExists) {
                return back()->withErrors(['email' => 'Email does not exist.'])->withInput();
            }
    
            $emailVerification = EmailVerification::where('email', $validated['email'])->first();
    
            $newToken = Str::random(50);
            $expires_at = Carbon::now()->addMinutes(30);
    
            if ($emailVerification) {
                $emailVerification->token = $newToken;
                $emailVerification->expires_at = $expires_at;
                $emailVerification->save();
            } else {
                EmailVerification::create([
                    'email' => $validated['email'],
                    'token' => $newToken,
                    'expires_at' => $expires_at,
                    'userType' => $emailExists->userType,
                ]);
            }
    
            $verificationLink = route('reset.password', ['token' => $newToken]);
    
            $subject = "Reset Password Request";
            $message = "Click the link below to reset your password:";
    
            Mail::to($validated['email'])->send(new AccountVerification($verificationLink, $subject, $message));

            return back()->with('sent', true);
    
        } catch (ValidationException $err) {
            return back()->withErrors($err->errors())->withInput();
        } catch (\Exception $e) {
            dd($e);
            return back()->with('error', 'There was an error sending the email.')->withInput();
        }
    }

    public function update(Request $request){
        try {
            $validated = $request->validate([
                'password' => 'required|confirmed',
                'email' => 'required|email'
            ]);

            $user = User::where('email', $validated['email'])->first();

           if($user){
            $validated['password'] = Hash::make($validated['password']);
            $user->password = $validated['password'];
            $user->save();

            $userUpdated = array(
                'email' =>  $validated['email'],
                'password' =>  $request->password
            );

            if(Auth::attempt($userUpdated)){
                $emailVerification = EmailVerification::where('email', $validated['email'])->first();

                if ($emailVerification) {
                    $emailVerification->delete();
                }
                return redirect('/checkRoute');
            }
           }
    
        } catch (ValidationException $err) {
            return back()->withErrors($err->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error sending the email.')->withInput();
        }
    }
}
