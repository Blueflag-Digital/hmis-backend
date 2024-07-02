<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class PatientInvestigationController extends Controller
{
    /**
     * PATIENT INVESTIGATIONS :: Store
     */

    public function store(Request $request, $consultation_id)
    {
        $request->validate([
            'investigation_ids' => 'required|array',
            'investigation_ids.*' => 'exists:investigations,id',
        ]);


        $investigationIds = [];
        foreach ($request->investigation_ids as $investigation) {
            $investigationIds[] = $investigation;
        }
        $consultation = Consultation::findOrFail($consultation_id);
        // $investigations = [];
        // foreach ($request->investigations as $investigation) {
        //     $investigations[$investigation['id']] = [
        //         'results' => $investigation['results'],
        //         'status' => 'ordered',
        //         'created_at' => now(),
        //     ];
        // }

        // $results = json_encode($investigations);
        // info($results);

        // $investigationIds = [];
        // foreach ($request->investigation_ids as $investigation) {
        //     $investigationIds[] = $investigation['id'];
        // }
        // $consultation = Consultation::findOrFail($consultation_id);

        $consultation->investigations()->sync($investigationIds);

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
        $investigations = $request->all();

        info($investigations);

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
