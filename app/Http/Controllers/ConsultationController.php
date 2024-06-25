<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\PatientVisit;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Const_;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getCounsulatationData(Request $request){
        $data = [
            'status' => false,
            'data' => null
        ];
        if($consultation = Consultation::find($request->consultation_id)){
            $data['status'] = true;
            $data['data'] = $consultation;
        }
        return response()->json($data);
    }
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
        $consultation = new Consultation();
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
        $consultation->diagnosis_ids = $request->diagnosis_ids;
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
        $consultation = Consultation::findOrFail($id);

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
            'diagnosis_ids' => 'nullable|array',
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

    /**
     * CONSULTATION :: Create a new consultation or return the existing consultation.
     */
    public function createOrRetrieve(Request $request)
    {

        $validatedData = $request->validate([
            'patient_visit_id' => 'required|exists:patient_visits,id',
        ]);


        $consultation = Consultation::where('patient_visit_id', $validatedData['patient_visit_id'])->first();

        if ($consultation) {
            // If a consultation exists, return it
            return response()->json([
                'consultation_id' => $consultation->id,
                'message' => 'Consultation already exists',
            ], 200);
        } else {
            // If no consultation exists, create a new one
            $consultation = new Consultation();
            $consultation->patient_visit_id = $validatedData['patient_visit_id'];
            $consultation->save();

            return response()->json([
                'consultation_id' => $consultation->id,
                'message' => 'Consultation created successfully',
            ], 200);
        }
    }

    /**
     * CONSULTATION :: Get Patient Details By Patient Visit Id
     */
    public function getPatientDetailsByVisit($patient_visit_id)
    {
        try {
            $patientVisit = PatientVisit::with(['patient.person'])->findOrFail($patient_visit_id);
            $person = $patientVisit->patient->person;

            if (!$person) {
                return response()->json(['message' => 'Patient not found'], 404);
            }

            $patientDetails = [
                'name' => $person->first_name . ' ' . $person->last_name,
                'date_of_birth' => $person->date_of_birth,
                'gender' => $person->gender,
                'phone' => $person->phone,
            ];

            return response()->json($patientDetails, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json(['message' => 'Failed to retrieve patient details'], 500);
        }
    }
}
