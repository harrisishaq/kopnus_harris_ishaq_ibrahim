<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminJobVacancyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'publish' => 'required',
        ]);

        $jobVacancy = JobVacancy::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $jobVacancy->admin()->associate($request->user());
        $jobVacancy->save();

        if ($request->publish) {
            $jobVacancy->published_at = now();
            $jobVacancy->save();
        }

        return response()->json(['message' => 'success'], 201);
    }

    public function listPublished()
    {
        $publishedVacancies = JobVacancy::whereNotNull('published_at')
            ->select('id', 'title', 'description', 'published_at', 'created_at', 'admin_id')
            ->with('admin')
            ->get()
            ->map(function ($vacancy) {
                return [
                    'id' => $vacancy->id,
                    'title' => $vacancy->title,
                    'description' => $vacancy->description,
                    'published_at' => $vacancy->published_at ? $vacancy->published_at->format('d-M-Y') : null,
                    'created_at' => $vacancy->created_at->format('d-M-Y'),
                    'created_by' => $vacancy->admin ? $vacancy->admin->name : 'Unknown'
                ];
            });

        return response()->json($publishedVacancies);
    }

    public function getApplicants(JobVacancy $jobVacancy)
    {
        // Memastikan hanya admin yang membuat lowongan pekerjaan yang dapat mengakses list pelamar
        if (Auth::guard('apiAdmin')->user()->id !== $jobVacancy->admin_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Mengambil data pelamar bersama dengan informasi detail profil-nya
        $applicants = $jobVacancy->users()->with('profile')->get();

        return response()->json(['applicants' => $applicants], 200);
    }

    public function downloadCV(JobVacancy $jobVacancy, User $user)
    {
        // Memastikan hanya admin yang membuat lowongan pekerjaan yang dapat mengakses list pelamar
        if (Auth::guard('apiAdmin')->user()->id !== $jobVacancy->admin_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Memastikan apakah user tersebut benar pelamar di lowongan pekerjaan tersebut
        if (!$jobVacancy->users->contains($user)) {
            return response()->json(['message' => 'User has not applied to this job vacancy'], 404);
        }

        // Mendapatkan filename untuk mengunduh cv
        $fileName = $user->profile->cv;

        // Menyusun path file cv
        $filePath = public_path('cv/' . $fileName);

        // Memastikan apakah file tersebut ada
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'CV file not found'], 404);
        }

        // Mengunduh file cv
        return response()->download($filePath, $fileName);
    }
}
