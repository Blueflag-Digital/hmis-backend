<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Investigation;
use Illuminate\Http\Request;
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
        $fullpath = asset($filePath);
        return response()->json(['file_path' => $fullpath],200);


    }
    /**
     * PATIENT INVESTIGATIONS :: Store
     */


    public function store(Request $request, $consultation_id)
    {

        // Validate the request
        // $request->validate([
        //     'investigation_ids' => 'required|array',
        //     'investigation_ids.*.id' => 'required|exists:investigations,id',
        //     'investigation_ids.*.results' => 'required|string',
        //     'investigation_ids.*.file' => 'nullable|file',
        // ]);


        $consultation = Consultation::findOrFail($consultation_id);

        $syncData = [];
        foreach ($request->investigations as $investigation) {
            $investigationData = [
                'results' => $investigation['results'],
            ];

            // Save the file if it exists
            if (isset($investigation['file']) && $investigation['file']->isValid()) {

                $filenamewithext = $investigation['file']->getClientOriginalName();
                $filename = pathinfo($filenamewithext, PATHINFO_FILENAME);
                $ext = $investigation['file']->getClientOriginalExtension();
                $filenametostore = $filename . '_' . time() . '.' . $ext;
                $path = $investigation['file']->storeAs('public/hmis_files', $filenametostore); // Ensure 'public/hmis_files' matches your filesystem disk setup
                $filePath = Storage::url($path);
                // $filePath = $investigation['file']->store('investigation_files');
                $investigationData['file_path'] = $filePath;
            }

            $syncData[$investigation['id']] = $investigationData;
        }

        // Sync the investigations with additional pivot data
        $consultation->investigations()->sync($syncData);
        return response()->json(['message' => 'Investigations added to consultation successfully'], 201);
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
}
