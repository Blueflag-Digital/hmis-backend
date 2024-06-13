<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PatientController::class)->group(function () {
    Route::post('add', 'store');
    Route::get('/', 'index');
    Route::get('/patient/{id}', 'show');
});
