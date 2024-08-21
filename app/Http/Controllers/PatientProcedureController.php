<?php

namespace App\Http\Controllers;

use App\Models\PatientProcedure;
use Illuminate\Http\Request;

class PatientProcedureController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $patientProcedures = PatientProcedure::with('procedure')->get();
        return response()->json($patientProcedures);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'procedure_id' => 'required|exists:procedures,id',
            'quantity' => 'required|integer',
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        $data = [
            'status' =>false,
            'message'=>'Failed'
        ];
        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            $patientProcedure = PatientProcedure::create([
                'procedure_id' =>$request->procedure_id,
                'consultation_id' => $request->consultation_id,
                'quantity' => $request->quantity,
                'description' =>$request->description,
                'hospital_id'=> $hospital->id
            ]);
            $data['status'] = true;
            $data['message'] = "success";
        } catch (\Throwable $th) {
            info($th->getMessage());
        }


        return response()->json($data);
    }

    // Display the specified resource.
    public function show(PatientProcedure $patientProcedure)
    {
        return response()->json($patientProcedure->load('procedure'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, PatientProcedure $patientProcedure)
    {
        $validatedData = $request->validate([
            'procedure_id' => 'required|exists:procedures,id',
            'quantity' => 'required|integer',
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        $data = [
            'status' =>false,
            'message'=>'Failed'
        ];

        try {
           $patientProcedure = $patientProcedure->update([
                'procedure_id' =>$request->procedure_id,
                'consultation_id' => $request->consultation_id,
                'quantity' => $request->quantity,
                'description' =>$request->description
            ]);
            $data['status'] = true;
            $data['message'] ="success";
        } catch (\Throwable $th) {
            info($th->getMessage());
        }


        return response()->json($data);
    }

    // Remove the specified resource from storage.
    public function destroy(PatientProcedure $patientProcedure)
    {
        $patientProcedure->delete();

        return response()->json(null, 204);
    }
}
