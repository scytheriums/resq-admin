<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function driverRegistration(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'sim_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'stnk_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'unit_photos' => 'required|array',
            'unit_photos.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'equipment_photos' => 'required|array',
            'equipment_photos.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'evoc_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'medical_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        return redirect()->route('registration-complete');
    }

    public function registrationComplete()
    {
        return view('enduser.registration_complete');
    }
}
