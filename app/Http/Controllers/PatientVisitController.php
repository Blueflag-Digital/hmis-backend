<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientVisitController extends Controller
{



    /**
     * PATIENT VISITS :: List
     */
    public function index(Request $request)
    {

        $pageNo = $request->pageNo;
        $limit = $request->limit;
        $count = 0;
        $patientVisits = [];

        $data = [
            'count' => 0,
            'patientVisits' => [],
        ];
        try {
            $patientVisits = PatientVisit::with(['patient', 'department'])->paginate($limit, ['*'], 'page', $pageNo);

            $transformedVisits = $patientVisits->getCollection()->map(function ($visit) {


                return [
                    'id' => $visit->id,
                    'patient_name' => $visit->patient->name ?? 'Unknown', // Default to 'Unknown' if null
                    'department_name' => $visit->department->name ?? 'Unknown', // Default to 'Unknown' if null
                    'status' => $visit->status,
                    'formatted_visit_date' => Carbon::parse($visit->created_at)->format('Y-m-d H:i:s'),

                ];
            });
            $patientVisits->setCollection($transformedVisits);
            $data['patientVisits'] = $patientVisits;
            $data['count'] = PatientVisit::count();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json($data);
    }

    /**
     * PATIENT VISITS :: Store
     */
    public function store(Request $request)
    {
        // Validate the request data
        /*
        $validator = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|string|max:20',
            'checked_in_by' => 'required|exists:users,id',
        ]);
        */

        $user = $request->user();

        $patientVisit = PatientVisit::create([
            'patient_id' => $request->patient_id,
            'department_id' => $request->department_id,
            'checked_in_by' => $user->id,
        ]);

        return response()->json($patientVisit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientVisit $patientVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientVisit $patientVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $patientVisit = PatientVisit::findOrFail($id);
            $patientVisit->delete();

            return response()->json(['message' => 'Patient visit deleted successfully'], 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json(['message' => 'Failed to delete patient visit'], 500);
        }
    }
}
