<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Person;
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
        try {
            $patientVisits = PatientVisit::paginate($limit, ['*'], 'page', $pageNo);
            $transformedVisits = $patientVisits->getCollection()->map(function ($visit) {
                $patientId = $visit->patient_id;
                $patient = Patient::where('id', $patientId)->first();
                $person = Person::where('id', $patient->id)->first('first_name');
                info($person);

                return [
                    'id' => $visit->id,
                    // 'patient_name' => $visit->patient->name ?? 'Unknown',
                    'patient_name' => $person,
                    'department_name' => $visit->department->name ?? 'Unknown', // Default to 'Unknown' if null
                    'status' => $visit->status,
                ];
            });
            $patientVisits->setCollection($transformedVisits);
            $count = PatientVisit::count();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json([
            'data' => $patientVisits,
            'totalCount' => $count
        ]);
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

        $patientVisit = PatientVisit::create([
            'patient_id' => $request->patient_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'checked_in_by' => $request->checked_in_by,
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
    public function destroy(PatientVisit $patientVisit)
    {
        //
    }
}
