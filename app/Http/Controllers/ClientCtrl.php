<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientCtrl extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        return view('client.dashboard', compact('user'));
    }
    public function adminIndex(Request $request){
        $user = auth()->user();
        return view('admin.adminDashboard', compact('user'));
    }
}
