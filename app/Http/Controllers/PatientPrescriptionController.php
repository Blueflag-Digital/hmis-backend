<?php

namespace App\Http\Controllers;

use App\Models\PatientPrescription;
use Illuminate\Http\Request;

class PatientPrescriptionController extends Controller
{
    /**
     * PATIENT PRESCRIPTIONS :: List all patient prescriptions
     */
    public function index()
    {
        $prescriptions = PatientPrescription::all();
        return response()->json($prescriptions, 200);
    }

    public function getSpecificPrescription(Request $request, $consultationId)
    {

        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;


        $data = [
            'data' => [],
            'status' => false,
        ];


        try {
            $data['totalCount'] = PatientPrescription::where('consultation_id', $consultationId)->count();
            $prescriptions = PatientPrescription::where('consultation_id', $consultationId)->latest()->paginate($limit, ['*'], 'page', $pageNo);
            $batches = $prescriptions->getCollection()->map(function ($prescription) {
                return $prescription->prescriptionData();
            });
            $prescriptions->setCollection($batches);
            $data['data'] = $prescriptions;
            $data['status'] = true;
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data, 500);
    }

    /**
     * PATIENT PRESCRIPTIONS :: Store a new patient prescription
     */
    public function store(Request $request)
    {

        $consultation_id = $request->consultation_id;

        // $request->validate([
        //     'consultation_id' => 'required|exists:consultations,id',
        //     'batch_id' => 'required|exists:batches,id',
        //     'dosage' => 'nullable|string',
        //     'number_dispensed' => 'nullable',
        //     'results' => 'nullable|string',
        // ]);




        $rows = json_decode($request['rows'], true);
        info($rows);

        try {
            foreach ($rows as $row) {
                PatientPrescription::create([
                    'consultation_id' => $consultation_id,
                    'drug_id' => $row['drugId'], // should be changed to batch id check to confirm
                    'dosage' => $row['dosage'],
                    'number_dispensed' => $row['noDispensed']
                ]);
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        // $patientPrescription = PatientPrescription::create($request->all());

        return response()->json([], 201);
    }

    /**
     * PATIENT PRESCRIPTIONS :: Show a specific patient prescription
     */
    public function show(PatientPrescription $patientPrescription)
    {
        return response()->json($patientPrescription, 200);
    }

    /**
     * PATIENT PRESCRIPTIONS :: Update a specific patient prescription
     */
    public function update(Request $request, PatientPrescription $patientPrescription)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'batch_id' => 'required|exists:batches,id',
            'dosage' => 'nullable|string',
            'number_dispensed' => 'nullable|integer',
            'results' => 'nullable|string',
        ]);

        $patientPrescription->update($request->all());

        return response()->json($patientPrescription, 200);
    }

    /**
     * PATIENT PRESCRIPTIONS :: Delete a specific patient prescription
     */
    public function destroy(PatientPrescription $patientPrescription)
    {
        $patientPrescription->delete();

        return response()->json(null, 204);
    }
}
