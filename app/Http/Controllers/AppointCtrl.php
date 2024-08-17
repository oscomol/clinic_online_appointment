<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointCtrl extends Controller
{
    public function index(){
        $user = auth()->user();

        $specialties = [
            "",
            'Allergy and Immunology',
            'Anesthesiology',
            'Cardiology',
            'Dermatology',
            'Emergency Medicine',
            'Endocrinology',
            'Family Medicine',
            'Gastroenterology',
            'Geriatrics',
            'Hematology',
            'Infectious Disease',
            'Internal Medicine',
            'Nephrology',
            'Neurology',
            'Nuclear Medicine',
            'Obstetrics and Gynecology',
            'Oncology',
            'Ophthalmology',
            'Orthopedic Surgery',
            'Otolaryngology',
            'Pediatrics',
            'Physical Medicine and Rehabilitation',
            'Plastic Surgery',
            'Podiatry',
            'Preventive Medicine',
            'Psychiatry',
            'Pulmonology',
            'Rheumatology',
            'Sleep Medicine',
            'Surgery',
            'Thoracic Surgery',
            'Urology',
            'Vascular Surgery'
        ];

        
        return view('client.appoint', compact('user', 'specialties'));
    }

    public function indexData(Request $request){
        $doctors = Doctor::all();

        return response()->json($doctors);
    }
}
