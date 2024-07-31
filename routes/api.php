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
use App\Http\Controllers\HospitalsController;
use App\Http\Controllers\ReportDataController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitOfMeasureController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkPlacesController;
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
    Route::controller(AuthController::class)->group(function () {
         Route::post('update-password','updatePassword');
    });



    Route::apiResource('hospitals', HospitalsController::class);
    Route::apiResource('work-places', WorkPlacesController::class);



    Route::controller(RolesPermissionsController::class)->prefix('roles-permissions')->group(function () {
        Route::get('get-role-permissions/{roleId}', 'getRolePermissions');
        Route::get('/get-user-permissions', 'getUserPermissions');
        Route::post('/update-permissions', 'updatePermissions');
        Route::post('update-roles', 'updateRoles');
        Route::post('/add', 'store');
        Route::post('/show', 'show');

        Route::post('/update-role-permissions', 'updateRolePermissions');
    });

    Route::controller(DepartmentController::class)->prefix('departments')->group(function () {
        Route::post('/', 'index');
        Route::post('/store', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::controller(InvestigationController::class)->prefix('investigations')->group(function () {
        Route::get('/', 'index');
        Route::post('/',  'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


    Route::controller(SettingsController::class)->prefix('settings')->group(function () {
        Route::get('/', 'index');
        Route::put('{id}', 'update');
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
        Route::post('search-supplier', 'search');
    });

    Route::controller(BrandController::class)->prefix('brands')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
        Route::post('search-brands', 'search');
    });





    Route::controller(DrugController::class)->prefix('drugs')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
        Route::post('search-drugs', 'search');
    });



    Route::controller(BatchController::class)->prefix('batches')->group(function () {
        Route::post('list', 'index');
        Route::post('create', 'store');
        Route::post('view/{id}', 'show');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'destroy');
        Route::get('available-drugs', 'availableDrugs');
        Route::post('search-batches', 'search');
    });

    // People routes
    Route::prefix('people')->group(function () {
        Route::post('people/{person_id}/phones', [PhoneController::class, 'index']);
        Route::post('people/{person_id}/phones', [PhoneController::class, 'store']);
    });

    Route::apiResource('pack-sizes', PackSizeController::class);
    Route::apiResource('units-of-measure', UnitOfMeasureController::class);

    //reports

    Route::prefix('reports')->group(function () {
        Route::post('load-patients-report', [ReportsController::class, 'patientsReport']);
        // Route::post('people/{person_id}/phones', [PhoneController::class, 'store']);
        Route::post('report-data', [ReportDataController::class, 'getReportData']);
    });



    Route::prefix('users')->group(function () {
        Route::post('/get-users', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'store']);
        Route::get('/{userId}', [UsersController::class, 'show']);
        Route::post('/update', [UsersController::class, 'update']);
        Route::post('/delete', [UsersController::class, 'delete']);
    });



    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'index']);
        Route::post('/', [RolesController::class, 'store']);
        Route::post('/update', [RolesController::class, 'update']);
        Route::post('/delete', [RolesController::class, 'delete']);
    });







    Route::apiResource('patient-prescriptions', PatientPrescriptionController::class);
    Route::post('patient-prescriptions/consultations/{consultationId}', [PatientPrescriptionController::class, 'getSpecificPrescription']);
});
