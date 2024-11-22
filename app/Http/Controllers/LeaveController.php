<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'hospital_id' => $hospital->id,
                'leave_type_id' =>$request->leaveTypeId
            ];

            $leave = Leave::create($data);
            if(!$leave){
                throw new \Exception("Leave not added", 1);
            }

            $pdf = app('dompdf.wrapper');
            if($leave->leave_type_id == 1){
                $leaveType =  LeaveType::find(1);
                $title = $leaveType->name;
                $pdf->loadView('reports.sick-leave', compact('leave','hospital','title'));
            }
            if($leave->leave_type_id == 2){
               $leaveType =  LeaveType::find(2);
               $title =  $leaveType->name;
               $pdf->loadView('reports.referral-leave', compact('leave','hospital','title'));
            }
             if($leave->leave_type_id == 3){
                // $title = "Medical Evacuation Leave";
                $leaveType =  LeaveType::find(3);
                $title =  $leaveType->name;

                $pdf->loadView('reports.medical-evacuation-leave', compact('leave','hospital','title'));
            }
            
            return $pdf->download('leave_form.pdf');

        } catch (\Throwable $th) {
           info($th->getMessage());
        }

    }
}
