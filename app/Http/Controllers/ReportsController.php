<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;


use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function patientsReport (Request $request){
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        //  $patients = Person::whereBetween('created_at', [$startDate, $endDate])->get();

        $paginatedData = Patient::whereBetween('created_at', [$startDate, $endDate])->latest()->get();
        $patients = $paginatedData->map(function ($patient) {
            return $patient->patientData();
        });

        if($patients->count() > 0){
             $pdf = app('dompdf.wrapper');
            $pdf->loadView('reports.patients',compact('patients'));
            return $pdf->download('report.pdf');
        }else{
            return response()->json($data,500);
        }





    }
}
