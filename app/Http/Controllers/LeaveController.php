<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function generateSickLeavePDF(Request $request){

        // $data = $request->validate([
        //     'workingDays' => 'required|integer',
        //     'startDate' => 'required|date',
        //     'endDate' => 'required|date',
        //     'reason' => 'required|string',
        //     'patientId' => 'required|string',
        // ]);






        try {

            if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }
            $data = [
                'start_date'=>$request->startDate,
                'end_date' => $request->endDate,
                'reason' => $request->reason,
                'patient_id' => $request->patientId,
                'working_days' => $request->workingDays,
                'user_id' => $request->user()->id,
                'hospital_id' => $hospital->id
            ];

            $leave = Leave::create($data);
            if(!$leave){
                throw new \Exception("Leave not added", 1);
            }
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('reports.sick-leave', compact('leave','hospital'));
            return $pdf->download('sick_leave_form.pdf');
        } catch (\Throwable $th) {
           info($th->getMessage());
        }

    }
}
