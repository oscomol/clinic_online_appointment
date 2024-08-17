<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCrtl extends Controller
{
    public function login(Request $request){
        $email = "";
    
        if (isset($_COOKIE["email"])) {
            $email = $_COOKIE["email"];
        }
    
        if ($request->isMethod('post')) {
            $user = [
                'email' => $request->email,
                'password' => $request->password
            ];
    
            $remember = $request->has('remember');

            if (Auth::attempt($user, $remember)) {
                if ($remember) {
                    setcookie("email", $request->email, time() + (86400 * 30), "/");
                } else {
                    setcookie("email", "", time() - 3600, "/");
                }
    
                return redirect('/checkRoute');
            }
    
    
            return back()->with('error', true);
        }
    
        return view('auth.login', compact('email'));
    }    
    
    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }
}
