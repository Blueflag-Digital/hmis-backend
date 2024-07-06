<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DiagnosisCodeController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\PackSizeController;
use App\Http\Controllers\PatientInvestigationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientPrescriptionController;
use App\Http\Controllers\PatientVisitController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitOfMeasureController;
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
        Route::post('/update', 'update');
        Route::post('/get-consultation-data', 'getCounsulatationData');
        Route::post('/create-or-retrieve', 'createOrRetrieve');
        Route::get('patient-details/{patient_visit_id}', 'getPatientDetailsByVisit');
    });

    Route::controller(DiagnosisCodeController::class)->prefix('diagnosis')->group(function () {
        Route::post('diagnosis-codes', 'index');
        Route::post('save-diagnosis', 'store');
    });

    Route::get('investigations', [InvestigationController::class, 'index']);


    Route::controller(PatientInvestigationController::class)->prefix('patient-investigations')->group(function () {
        Route::get('patient-investigations/{consultation_id}/investigations', 'index');
        Route::post('patient-investigations/{consultation_id}/investigations', 'store');
        Route::put('patient-investigations/{consultation_id}/investigations', 'updateInvestigation');
        Route::post('patient-investigations/{consultation_id}/investigations/delete', 'destroy');
        Route::post('download-file', 'download');

    });



    Route::controller(DiagnosisCodeController::class)->prefix('diagnosis')->group(function () {
        Route::post('diagnosis-codes', 'index');
        Route::post('save-diagnosis', 'store');
    });

    Route::controller(SupplierController::class)->prefix('suppliers')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
    });

    Route::controller(BrandController::class)->prefix('brands')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
    });





    Route::controller(DrugController::class)->prefix('drugs')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
    });



    Route::controller(BatchController::class)->prefix('batches')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
        Route::get('available-drugs', 'availableDrugs');
    });

    // People routes
    Route::prefix('people')->group(function () {
        Route::post('people/{person_id}/phones', [PhoneController::class, 'index']);
        Route::post('people/{person_id}/phones', [PhoneController::class, 'store']);
    });

    Route::apiResource('pack-sizes', PackSizeController::class);
    Route::apiResource('units-of-measure', UnitOfMeasureController::class);

    Route::apiResource('patient-prescriptions', PatientPrescriptionController::class);

});
