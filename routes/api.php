<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminJobVacancyController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User API
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/users/logout', [UserAuthController::class, 'logout'])->middleware('auth:api');
Route::post('/users/profiles', [UserProfileController::class, 'store'])->middleware('auth:api');
Route::post('/job-vacancies/{jobVacancy}/apply', [JobApplicationController::class, 'apply'])->middleware('auth:api');


// Admin API
Route::post('/admins/login', [AdminAuthController::class, 'login']);
Route::post('/admins/logout', [AdminAuthController::class, 'logout'])->middleware('auth:apiAdmin');
Route::post('/admins/job-vacancies', [AdminJobVacancyController::class, 'store'])->middleware('auth:apiAdmin');
Route::get('/job-vacancies/{jobVacancy}/applicants', [AdminJobVacancyController::class, 'getApplicants'])->middleware('auth:apiAdmin');
Route::get('/job-vacancies/{jobVacancy}/applicants/{user}/cv', [AdminJobVacancyController::class, 'downloadCV'])->middleware('auth:apiAdmin');


// Public API
Route::get('/job-vacancies', [AdminJobVacancyController::class, 'listPublished']);
