<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Brand;
use App\Models\PatientPrescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

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
        $rows = json_decode($request['rows'], true);
        // info($request->all());
        $data = [
            'status' => false,
            'message' => ''
        ];

        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            DB::transaction(function () use ($rows, $consultation_id, $hospital) {
                foreach($rows as $index => $row) {
                    
                    // Get the brand_id from the brands table using the provided drug_id
                    $brand = Brand::find($row['brandId']);
                    
                    if (!$brand) {
                        throw new \Exception("No brand found : {$row['brandName']}");
                    }

                    $brand_id = $brand->id;
                    $drugId = isset($brand->drug) ? $brand->drug->id : null;

                    // Find the batch that corresponds to the brand_id and has enough quantity
                    $batch = Batch::where('brand_id', $brand_id)
                        ->where('quantity_available', '>', 0)
                        ->orderBy('created_at', 'asc') // First, dispense from the oldest batch
                        ->first();
                    
                    if ($batch) {
                        // Check if there's enough quantity available
                        if ($batch->quantity_available >= $row['noDispensed']) {
                            $rem = (float)$batch->quantity_available - (float)$row['noDispensed'];
                            // Deduct the dispensed quantity from the available quantity
                            $batch->quantity_available = $rem; 
                            $batch->save();

                            // Create a patient prescription record
                            PatientPrescription::create([
                                'consultation_id' => $consultation_id,
                                'drug_id' => $drugId, // Saving drug_id for tracking purposes
                                'batch_id' => $batch->id,
                                'dosage' => $row['dosage'],
                                'number_dispensed' => $row['noDispensed'],
                                'hospital_id' => $hospital->id
                            ]);
                           
                        } else {
                            // Handle the case where the available quantity is less than the dispensed quantity
                            throw new \Exception("Not enough quantity available in batch for brand Name: {$brand->name}");
                        }
                    } else {
                        // Handle the case where no batch is found
                        throw new \Exception("No batch found with sufficient quantity for brand ID: {$brand_id}");
                    }
                }
            });
            $data['status'] = true;
            $data['message'] = 'Success';

        } catch (\Throwable $th) {
            info($th->getMessage());
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
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
