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
        // $request->validate([
        //     'investigation_ids' => 'required|array',
        //     'investigation_ids.*' => 'exists:investigations,id',
        // ]);

        // $investigationIds = [];
        // foreach ($request->investigations as $investigation) {
        //     $investigationIds[] = $investigation['id'];
        // }

        // info($investigationIds);

        $consultation = Consultation::findOrFail($consultation_id);
        $investigations = [];

        foreach ($request->investigations as $investigation) {
            $investigations[$investigation['id']] = [
                'results' => $investigation['results'],
                'status' => 'ordered',
                'created_at' => now(),
            ];
        }

        // $results = json_encode($investigations);
        // info($results);


        $consultation->investigations()->sync($investigations);

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
    public function updateInvestigation(Request $request, $consultation_id, $investigation_id)
    {
        $request->validate([
            'status' => 'required|string',
            'results' => 'nullable|string',
        ]);

        $consultation = Consultation::findOrFail($consultation_id);
        $consultation->investigations()->updateExistingPivot($investigation_id, [
            'status' => $request->status,
            'results' => $request->results,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Investigation updated successfully'], 200);
    }
}
