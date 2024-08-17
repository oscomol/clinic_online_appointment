<?php

namespace App\Http\Controllers;

use App\Mail\AccountVerification;
use App\Models\ClientSchedule;
use App\Models\Doctor;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserCtrl extends Controller
{

    public function index(){
        $user = auth()->user();

        return view('admin.users', compact('user'));
    }
    
    public function singleUser(Request $request){
        $user = auth()->user();

        $searchUser = User::findOrFail($request->id);

        $reservations = ClientSchedule::where('userId', $request->id)->get();

        $reservations->map(function($res) {
            $doctor = Doctor::where('id', $res->doctorsId)->first();
            $doctorsName = "Unknown";
            if($doctor){
                $doctorsName = $doctor->name;
            }
            $res->date = Carbon::parse($res->date)->format('F d, Y');
            $res->doctor_name = $doctorsName;
            return $res;
        });

        $scheduled = $reservations->filter(function($res){
            return $res->status == 1;
        })->count();

        $success = $reservations->filter(function($res){
            return $res->status == 5;
        })->count();

        $missed = $reservations->filter(function($res){
            return $res->status == 2;
        })->count();
        
        return view('admin.user.singleUser', compact('user', 'searchUser', 'reservations', 'scheduled', 'success', 'missed'));
    }
    public function store(Request $request){
        
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|confirmed',
                'name' => 'required',
                'userType' => 'required',
            ]);
            
            $validated['password'] = Hash::make($validated['password']);
    
            $newUser = User::create($validated);
    
            if($newUser){
                $user = array(
                    'email' =>  $validated['email'],
                    'password' =>  $request->password
                );
    
                if(Auth::attempt($user)){
                    $emailVerification = EmailVerification::where('email', $validated['email'])->first();
    
                    if ($emailVerification) {
                        $emailVerification->delete();
                    }
                    
                    return redirect('/checkRoute');
                }
            }

        } catch (ValidationException $err) {
            return back()->withErrors($err->errors())->withInput();
        }
       
    }

    
    public function adminStore(Request $request)
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

    public function getUser(Request $request){
        try{
            $data = User::where('userType', $request->userType)->get();
            
            return response()->json($data);

        }catch(\Illuminate\Validation\ValidationException $err){
             return response()->json('Error');
        }   
        }

        public function destroy(Request $request){
            
            $user = User::findOrFail($request->user);
            $user->delete();
            
            return redirect()->route('logout');

        }
        public function update(Request $request)
        {
            $request->validate(['email' => 'required|email']);

            $token = Str::random(50);
            $expires_at = Carbon::now()->addMinutes(30);
    
            EmailVerification::create([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => $expires_at,
                'userType' => $request->userType,
            ]);

            $verificationLink = route('account.update', ['token' => $token, 'user' => $request->id]);
            $subject = "Account update email";
            $message = "Update your account by clicking the link below:";

            Mail::to($request->email)->send(new AccountVerification($verificationLink, $subject, $message));

            return response()->json($verificationLink);
        }

        public function updateIndex(Request $request){
            $token = $request->token;
            $id = $request->user;

            $verification = EmailVerification::where('token', $token)->first();

            if(!$verification){
                return view('auth.linkEpired');
             }
        
             if(Carbon::now() > $verification->expires_at){
                return view('auth.linkEpired');
             }

             $user = User::where('id', $id)->first();
        
             $email = $verification->email;

             return view('auth.accountUpdateForm', compact('email', 'user'));

        }

        public function saveUpdate(Request $request)
{
    try {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'id' => 'required|integer'
        ]);

        if ($request->filled('password')) {
            $validated = $request->validate([
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required|confirmed',
                'id' => 'required|integer'
            ]);
        }

        $user = User::find($validated['id']);

        if (!$user) {
            return back()->withErrors(['id' => 'User not found.'])->withInput();
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->email = $validated['email'];
        $user->name = $validated['name'];

        $user->save();

        $emailVerification = EmailVerification::where('email', $validated['email'])->first();

        if ($emailVerification) {
            $emailVerification->delete();
        }

        return redirect()->route('login')->with('updatedMsg', "Account updated succesfully");

    } catch (ValidationException $err) {
        return back()->withErrors($err->errors())->withInput();
    }
}       
}
