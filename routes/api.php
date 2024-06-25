<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DiagnosisCodeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ConsultationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'store');
    Route::post('login-user', 'loginUser');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(PatientController::class)->prefix('patients')->group(function () {
        Route::post('/', 'index');
        Route::post('/add', 'store');
        Route::post('/show', 'show');
        Route::post('bulk-upload-patients', 'bulkUpload');
        Route::post('/update', 'update');
        Route::post('/delete', 'destroy');
    });


    Route::controller(PatientVisitController::class)->prefix('patient-visits')->group(function () {

        Route::post('/', 'index');
        Route::post('/add', 'store');
        Route::post('/show', 'show');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout-user', 'logoutUser');
    });

    Route::controller(CityController::class)->prefix('cities')->group(function () {
        Route::post('/', 'index');
    });

    Route::controller(DepartmentController::class)->prefix('departments')->group(function () {
        Route::post('/', 'index');
    });

    Route::controller(ConsultationController::class)->prefix('consultation')->group(function () {
        Route::post('/', 'index');
        Route::post('/add', 'store');
        Route::post('/show', 'show');
        Route::post('create-or-retrieve', 'createOrRetrieve');

    });

    Route::get('diagnosis-codes', [DiagnosisCodeController::class, 'index']);


    // People routes
    Route::prefix('people')->group(function () {
        Route::post('people/{person_id}/phones', [PhoneController::class, 'index']);
        Route::post('people/{person_id}/phones', [PhoneController::class, 'store']);
    });
});
