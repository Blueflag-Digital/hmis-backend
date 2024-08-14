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

        $patientProcedure = PatientProcedure::create($validatedData);

        return response()->json($patientProcedure, 201);
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

        $patientProcedure->update($validatedData);

        return response()->json($patientProcedure);
    }

    // Remove the specified resource from storage.
    public function destroy(PatientProcedure $patientProcedure)
    {
        $patientProcedure->delete();

        return response()->json(null, 204);
    }
}
