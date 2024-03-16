<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'address' => 'required',
            'cv' => 'mimes:pdf|max:5120', // Validate CV file
            'gender' => 'in:male,female',
            'birth_date' => 'nullable|date',
        ]);


        $fileName = "";

        if ($request->hasFile('cv')) {
            $fileName = time().'-'.$request->user()->id.'.'.$request->file('cv')->getClientOriginalExtension();
            $request->file('cv')->move(public_path('cv'), $fileName);
        }

        $userProfile = new UserProfile([
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'birth_date' => $request->input('birth_date'),
            'cv' => $fileName,
        ]);

        $userProfile->user()->associate($request->user());
        $userProfile->save();

        return response()->json(['message' => 'Profile created successfully'], 201);
    }
}
