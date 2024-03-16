<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JobApplicationController extends Controller
{
    public function apply(Request $request, JobVacancy $jobVacancy)
    {
        $requestedJobVacancyId = $request->input('job_vacancy_id');

        // Memastikan apakah id lowongan pekerjaan di body request sama dengan id di url
        if ($requestedJobVacancyId != $jobVacancy->id) {
            return response()->json(['message' => 'Bad Request'], 400);
        }

        // Memeriksa apakah user benar melamar pada lowongan tersebut
        if ($request->user()->jobVacancies()->where('job_vacancy_id', $jobVacancy->id)->exists()) {
            return response()->json(['message' => 'User has already applied to this job vacancy'], 422);
        }

        // Menentukan tanggal dibuat sebagai tanggal melamar
        $createdAt = Carbon::now();

        // Menambahkan data ke table
        $request->user()->jobVacancies()->attach($jobVacancy, ['created_at' => $createdAt]);

        return response()->json(['message' => 'User applied successfully'], 200);
    }
}
