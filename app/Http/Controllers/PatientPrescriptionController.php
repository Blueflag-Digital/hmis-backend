<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BillingItem;
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

            DB::transaction(function () use ($request, $hospital) {
                $consultation_id = $request->consultation_id;
                $rows = json_decode($request['rows'], true);

                foreach($rows as $row) {
                    $brand = Brand::find($row['brandId']);
                    if (!$brand) {
                        throw new \Exception("No brand found : {$row['brandName']}");
                    }

                    $brand_id = $brand->id;
                    $drugId = isset($brand->drug) ? $brand->drug->id : null;

                    $batch = Batch::where('brand_id', $brand_id)
                        ->where('quantity_available', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($batch) {
                        if ($batch->quantity_available >= $row['noDispensed']) {
                            $rem = (float)$batch->quantity_available - (float)$row['noDispensed'];
                            $batch->quantity_available = $rem;
                            $batch->save();

                            // Create prescription
                            $prescription = PatientPrescription::create([
                                'consultation_id' => $consultation_id,
                                'drug_id' => $drugId,
                                'batch_id' => $batch->id,
                                'dosage' => $row['dosage'],
                                'number_dispensed' => $row['noDispensed'],
                                'hospital_id' => $hospital->id
                            ]);

                            // Create billing item
                            BillingItem::create([
                                'patient_visit_id' => $prescription->consultation->patient_visit_id,
                                'hospital_id' => $hospital->id,
                                'billable_type' => PatientPrescription::class,
                                'billable_id' => $prescription->id,
                                'quantity' => $row['noDispensed'],
                                'unit_price' => $batch->selling_price,
                                'amount' => $row['noDispensed'] * $batch->selling_price,
                                'status' => 'pending'
                            ]);
                        } else {
                            throw new \Exception("Not enough quantity available in batch for brand Name: {$brand->name}");
                        }
                    } else {
                        throw new \Exception("No batch found with sufficient quantity for brand ID: {$brand_id}");
                    }
                }
            });

            return response()->json(['status' => true, 'message' => 'Success']);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
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
