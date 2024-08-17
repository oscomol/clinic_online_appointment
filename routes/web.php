<?php

use App\Http\Controllers\AdminCtrl;
use App\Http\Controllers\AdminWeekCtrl;
use App\Http\Controllers\AppointCtrl;
use App\Http\Controllers\AppointmentCtrl;
use App\Http\Controllers\AuthCrtl;
use App\Http\Controllers\ClientCtrl;
use App\Http\Controllers\ClientDashboardCtrl;
use App\Http\Controllers\ClientRecordCtrl;
use App\Http\Controllers\DoctorCtrl;
use App\Http\Controllers\DrShedCtrl;
use App\Http\Controllers\EmailVerficationCtrl;
use App\Http\Controllers\ReservationAdminCtrl;
use App\Http\Controllers\ResetPassCtrl;
use App\Http\Controllers\ScheduleCtrl;
use App\Http\Controllers\UserCtrl;
use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//LOGIN USER

Route::get('/checkRoute', function(){
    if(auth()->user()->userType === "1"){
        return redirect('/dashboard');
    }else{
        return redirect('/admin/dashboard');
    }
});

Route::match(['GET', 'POST'],'/login', [AuthCrtl::class,'login'])->name('login');

Route::group(['middleware' => 'auth'], function() {

    Route::middleware(['checkRole:1'])->group(function() {
        Route::get('/dashboard', [ClientCtrl::class, 'index']);
        Route::get('/getData/dashboard/{user}', [ClientDashboardCtrl::class, 'index']);
        Route::get('/getStatus/dashboard/{status}{user}', [ClientDashboardCtrl::class, 'checkupStatus']);
        Route::get('/appoint', [AppointCtrl::class, 'index']);
        Route::get('/appointment/{default}', [AppointmentCtrl::class, 'index']);
        Route::get('/getData/appointment', [AppointmentCtrl::class, 'indexData']);
        Route::post('/client-appointment/create/{userId}', [AppointmentCtrl::class, 'store']);
        Route::post('/client-appointment/cancel', [AppointmentCtrl::class, 'destroy']);
        Route::get('/doctor/view/{patient}', [AppointmentCtrl::class, 'show']);
        Route::put('/client-appointment/edit', [AppointmentCtrl::class, 'update']);

        Route::post('/doctor/availability', [AppointmentCtrl::class, 'availability']);

        Route::get('/getData/appoint/{user}', [AppointCtrl::class, 'indexData']);

        Route::get('/records', [ClientRecordCtrl::class, 'index']);
        Route::get('/getData/records/{status}', [ClientRecordCtrl::class, 'getIndexData']);
    });

    Route::middleware(['checkRole:2'])->group(function() {
        Route::get('/admin/dashboard', [ClientCtrl::class, 'adminIndex']);
        Route::get('/data/dashboard', [AdminCtrl::class, 'dashboardIndex']);

        Route::get('/admin/today-schedule', [AdminWeekCtrl::class, 'index']);
        Route::get('/week/admin/reserve/{day}{doctor}', [ReservationAdminCtrl::class, 'getdoctorsReservation']);


        Route::get('/admin/history', [ReservationAdminCtrl::class, 'index']);

        Route::get('/data/admin/reserve', [ReservationAdminCtrl::class, 'getIndexData']);
        
        Route::get('/admin/doctors', [DoctorCtrl::class, 'index']);

        Route::get('/admin/doctors/view/{doctor}', [DrShedCtrl::class, 'index']);

        Route::post('/doctorsSched/create', [DrShedCtrl::class, 'store']);
        Route::put('/doctorsSched/edit', [DrShedCtrl::class, 'update']);
        Route::put('/schedule/updateStatus', [DrShedCtrl::class, 'updateStatus']);

        Route::get('/get/doctor/reservation/{date}{doctor}', [ReservationAdminCtrl::class, 'getdoctorsReservation']);

        Route::post('/admin/cancel/reservation', [ReservationAdminCtrl::class, 'cancelReservation']);
        Route::post('/admin/status/update', [ReservationAdminCtrl::class, 'statusUpdate']);

        Route::get('/getDoctor', [DoctorCtrl::class, 'adminIndex']);
        Route::post('/addDoctor', [DoctorCtrl::class, 'store']);
        Route::delete('/deleteDoctor/{doctor}', [DoctorCtrl::class, 'destroy']);
        Route::post('/editDoctor', [DoctorCtrl::class, 'update']);
        Route::post('/updateStatus', [DoctorCtrl::class, 'statusUpdate']);



        Route::get('/user/{id}', [UserCtrl::class, 'singleUser']);


        
        //ADMINISTRATOR
        Route::get('/admin/list', function(){
            $user = auth()->user();

            return view('admin.administrator', compact('user'));
        });

        // deleteDoctor
       

        // Route::delete('/deleteAdmin/{admin}', [UserCtrl::class, 'destroy'])->name('deleteAdmin');

        Route::get('/admin/users', function(){
            $user = auth()->user();

            return view('admin.users', compact('user'));
        });
    });

    Route::get('/getAdmins/{userType}', [UserCtrl::class, 'getUser']);
    
});

//LOGOUT
Route::get('/logout', [AuthCrtl::class, 'logout'])->name('logout');

Route::delete('/deleteAccount/{user}', [UserCtrl::class, 'destroy'])->name('deleteAccount');

Route::put('/updateAccount', [UserCtrl::class, 'update'])->name('updateAccount');


Route::put('/updateAccount/saveUpdate', [UserCtrl::class, 'saveUpdate'])->name('saveUpdate');

Route::get('/updateAccount/{token}/{user}', [UserCtrl::class, 'updateIndex'])->name('account.update');

Route::post('/reset-password', [ResetPassCtrl::class, 'resetPassword']);
Route::get('/reset-password/{token}', [ResetPassCtrl::class, 'index'])->name('reset.password');;
Route::post('/password/update', [ResetPassCtrl::class, 'update'])->name('reset-password');;

//REGISTER USER
Route::get('/register/account', function () {
    return view('auth.register');
})->name('account.setup');

Route::get('/account-setup/{token}', [EmailVerficationCtrl::class, 'index'])->name('email.verify');



Route::post('/verify-email', [EmailVerficationCtrl::class, 'verify'])->name('verify.email');

Route::get('/verify', function () {
    return view('auth.emailVerification');
});

Route::get('/page-expired', function () {
    return view('auth.linkEpired');
});

Route::post('/register', [UserCtrl::class, 'store'])->name('register');
Route::post('/addUser', [EmailVerficationCtrl::class, 'verify'])->name('adminRegister');
Route::post('/editAdmin', [UserCtrl::class, 'update']);

//RESET PASSWORD
Route::get('/forgot-password', function () {
    return view('auth.forgotPassword');
});


