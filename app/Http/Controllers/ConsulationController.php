<?php

namespace App\Http\Controllers;

use App\Models\Consulation;
use Illuminate\Http\Request;

class ConsulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * CONSULTATION :: Store
     */
    public function store(Request $request)
    {
        $consultation = new Consulation();
        $consultation->patient_visit_id = $request->patient_visit_id;
        $consultation->height_cm = $request->height_cm;
        $consultation->weight_kg = $request->weight_kg;
        $consultation->allergies = $request->allergies;
        $consultation->current_medications = $request->current_medications;
        $consultation->past_medical_history = $request->past_medical_history;
        $consultation->family_medical_history = $request->family_medical_history;
        $consultation->immunization_history = $request->immunization_history;
        $consultation->reason_for_visit = $request->reason_for_visit;

        $consultation->blood_pressure = $request->blood_pressure;
        $consultation->heart_rate = $request->heart_rate;
        $consultation->temperature = $request->temperature;
        $consultation->respiratory_rate = $request->respiratory_rate;
        $consultation->oxygen_saturation = $request->oxygen_saturation;
        $consultation->doctors_notes = $request->doctors_notes;
        $consultation->custom_diagnosis = $request->custom_diagnosis;
        $consultation->next_appointment = $request->next_appointment;

        $consultation->save();

        return response()->json([
            'message' => 'Consultation saved successfully',
            'consultation' => $consultation,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $consultation = Consulation::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'patient_visit_id' => 'required|exists:patient_visits,id',
            'height_cm' => 'nullable|numeric',
            'weight_kg' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'family_medical_history' => 'nullable|string',
            'immunization_history' => 'nullable|string',
            'reason_for_visit' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'oxygen_saturation' => 'nullable|numeric',
            'doctors_notes' => 'nullable|string',
            'custom_diagnosis' => 'nullable|string',
            'next_appointment' => 'nullable|date',
        ]);

        $consultation->update($validatedData);

        return response()->json([
            'message' => 'Consultation updated successfully',
            'consultation' => $consultation,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
