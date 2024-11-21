<?php

namespace App\Http\Controllers;

use App\Models\BillingItem;
use App\Models\Consultation;
use App\Models\Investigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientInvestigationController extends Controller
{

    /**
     * PATIENT DOWNLOAD HMIS DOCUMENT :: Store
     */

     public function download(Request $request)
    {
        $consultation_id = $request->consultationId;
        $investigation_id = $request->investigationId;

        $consultation = Consultation::with('investigations')->findOrFail($consultation_id);
        $filteredInvestigation = $consultation->investigations->where('id', $investigation_id)->first();
        $filePath = $filteredInvestigation->pivot->file_path;
        if($filePath){
            $fullpath = asset($filePath);
            return response()->json(['file_path' => $fullpath],200);
        }else{
            return response()->json(['file_path' => null],);
        }



    }
    /**
     * PATIENT INVESTIGATIONS :: Store
     */


    public function store(Request $request, $consultation_id)
    {
        $consultation = Consultation::findOrFail($consultation_id);
        $syncData = [];

        try {
            if(!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            DB::transaction(function () use ($request, $consultation_id, $hospital) {
                $consultation = Consultation::findOrFail($consultation_id);

                foreach ($request->investigations as $investigation) {
                    // Create investigation record
                    $investigationData = [
                        'results' => $investigation['results'],
                        'hospital_id' => $hospital->id,
                    ];

                    if (isset($investigation['file']) && $investigation['file']->isValid()) {
                        $filenamewithext = $investigation['file']->getClientOriginalName();
                        $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
                        $ext = $investigation['file']->getClientOriginalExtension();
                        $filenametostore = $filename . '_' . time() . '.' . $ext;
                        $path = $investigation['file']->storeAs('public/hmis_files', $filenametostore);
                        $filePath = Storage::url($path);
                        $investigationData['file_path'] = $filePath;
                    }

                    $consultation->investigations()->attach($investigation['id'], $investigationData);

                    // Create billing item
                    $investigationModel = Investigation::findOrFail($investigation['id']);
                    BillingItem::create([
                        'patient_visit_id' => $consultation->patient_visit_id,
                        'hospital_id' => $hospital->id,
                        'billable_type' => Investigation::class,
                        'billable_id' => $investigationModel->id,
                        'quantity' => 1,
                        'unit_price' => $investigationModel->price,
                        'amount' => $investigationModel->price,
                        'status' => 'pending'
                    ]);
                }
            });

            return response()->json(['message' => 'Investigations added successfully'], 201);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * PATIENT INVESTIGATIONS :: List
     */

    public function index($consultation_id)
    {
        $consultation = Consultation::with('investigations')->findOrFail($consultation_id);

        return response()->json($consultation->investigations, 200);
    }

    /**
     * PATIENT INVESTIGATIONS :: Update
     */
    public function updateInvestigation(Request $request, $consultation_id)
    {
        $investigationData = $request->investigations;
        $consultation = Consultation::findOrFail($consultation_id);

        foreach ($investigationData as $investigation) {
            $consultation->investigations()->updateExistingPivot($investigation['id'], [
                'status' => 'completed', // Assuming a single status for all investigations
                'results' => $investigation['results'], // Specific results for each investigation
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Investigation updated successfully'], 200);
    }

    /**
     * DELETE PATIENT INVESTIGATION
     */
    public function destroy(Request $request,$consultation_id){

        $investigation_id = $request->investigationId;

        $consultation = Consultation::with('investigations')->findOrFail($consultation_id);
        $filteredInvestigation = $consultation->investigations->where('id', $investigation_id)->first();

        if ($filteredInvestigation) {
            $filteredInvestigation->delete();
            return response()->json(['message' => 'Investigation deleted successfully'], 204);
        } else {
            return response()->json(['message' => 'Investigation not found'], 404);
        }
    }

}
