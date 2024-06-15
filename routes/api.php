<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PhoneController;
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

    Route::prefix('patients')->group(function () {
        Route::post('/', [PatientController::class, 'index']);
        Route::post('/add', [PatientController::class, 'store']);
        Route::post('/show', [PatientController::class, 'show']);
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout-user', 'logoutUser');
    });

    Route::prefix('cities')->group(function () {
        Route::controller(CityController::class)->group(function () {
            Route::post('/', 'index');
        });
    });

    // People routes
    // Route::prefix('people')->group(function () {
    //     Route::post('people/{person_id}/phones', [PhoneController::class, 'index']);
    //     Route::post('people/{person_id}/phones', [PhoneController::class, 'store']);
    // });
    // //cities routes
    // Route::prefix('cities')->group(function () {
    //     Route::controller(CityController::class)->group(function () {
    //         Route::post('/', 'index');
    //     });
    // });




});
