<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class DoctorCtrl extends Controller
{

    public function index(){
        $user = auth()->user();

        $specialties = [
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

        return view('admin.doctors', compact('user', 'specialties'));
    }
    public function adminIndex(){

        $data = Doctor::all();

        return response()->json($data);
    }

    public function store(Request $request){
        try {

            $file= $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('doctors/image'), $filename);

            $validated = $request->validate([
                'name' => 'required',
                'address' => 'required',
                'age' => 'required|numeric',
                'gender' => 'required',
                'yrsExp' => 'required|numeric',
                'specialty' => 'required',
                'checkupLimit' => 'required|numeric',              
            ]);

            $validated['isAvailable'] = 0;
            $validated['photo'] = $filename;
    
            return response()->json(Doctor::create($validated));
    
        } catch (\Illuminate\Validation\ValidationException $err) {
            return response()->json(['error' => $err->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    public function destroy(Request $request)
        {
            try {
                $doctor = Doctor::findOrFail($request->doctor);
                $doctor->delete();

                $path = public_path('doctors/image/' . $request->photo);

                if (File::exists($path)) {
                    File::delete($path);
                }

                return response()->json($doctor);
            } catch (\Exception $e) {

                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }
        }

        public function update(Request $request){
            try {
                $validated = $request->validate([
                    'name' => 'required',
                    'address' => 'required',
                    'age' => 'required|numeric',
                    'gender' => 'required',
                    'yrsExp' => 'required|numeric',
                    'specialty' => 'required',
                    'checkupLimit' => 'required|numeric',              
                ]);

                $file = $request->file('photo');
    
                if ($file) {
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('doctors/image'), $filename);
                    $validated['photo'] = $filename;
                    $path = public_path('doctors/image/' . $request->existingPhoto);

                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }

                $doctor = Doctor::findOrFail($request->id);

                $doctor->update($validated);
        
                return response()->json($doctor);
        
            } catch (\Exception $e) {
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }
        }
        
        public function statusUpdate(Request $request){
            try {

                $doctor = Doctor::findOrFail($request->id);

                $doctor->isAvailable = $request->stat;
                $doctor->update();
                
                return response()->json($doctor);
        
            } catch (\Exception $e) {
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }
        }
}
