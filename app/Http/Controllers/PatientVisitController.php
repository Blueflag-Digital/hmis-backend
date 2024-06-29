<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientVisitController extends Controller
{

    //reusable functions
    function getInitials($firstName, $lastName)
    {
        $firstInitial = isset($firstName) ? strtoupper($firstName[0]) : '';
        $lastInitial = isset($lastName) ? strtoupper($lastName[0]) : '';
        return $firstInitial . $lastInitial;
    }


    /**
     * PATIENT VISITS :: List
     */
    public function index(Request $request)
    {


        $pageNo = $request->pageNo ??  1;
        $limit = $request->limit ?? 10;

        $data = [
            'status' => false,
            'data' => [],
            'count' => 0
        ];
        //this block gets individuals patient visits
        if ($request->value && !empty($request->value)) {
            $patientId = $request->patientId;
            try {
                if ($patient = Patient::find($patientId)) {
                    $data['count'] = PatientVisit::where('patient_id', $patient->person_id)->count();
                    $paginatedData = PatientVisit::where('patient_id', $patient->person_id)->paginate($limit, ['*'], 'page', $pageNo);
                    $patientVisitsData = $paginatedData->getCollection()->map(function ($visit) use ($patientId) {
                        $initials = "";
                        if ($person =  Person::where('id', $patientId)->first()) {
                            $initials =  $this->getInitials($person->first_name, $person->last_name);
                        }
                        $dataToReturn =  $visit->visitData();
                        $dataToReturn['initials'] =  $initials;
                        return $dataToReturn;
                    });
                    $paginatedData->setCollection($patientVisitsData);
                    $data['data'] = $paginatedData;
                    $data['status'] = true;
                }
                return response()->json($data, 200);
            } catch (\Throwable $th) {
                info($th->getMessage());
                return response()->json($data, 500);
            }
        }


        //all visits data fetching
        try {
            $patientVisits = PatientVisit::latest()->paginate($limit, ['*'], 'page', $pageNo);

            $transformedVisits = $patientVisits->getCollection()->map(function ($visit) {

                $name = "";
                $initials = "";
                $email = "";
                $pid = " ";

                $patientId = $visit->patient_id; //this is person id not patient id.
                //get patient id from patients table where person_id matches  $patientId

                if ($person =  Person::where('id', $patientId)->first()) {

                    if ($pat = Patient::where('person_id', $patientId)->first()) {
                        $pid = $pat->id;
                    }

                    $name = $person->first_name . " " . $person->last_name;
                    $initials =  $this->getInitials($person->first_name, $person->last_name);
                    $email = $person->email;
                }
                $dataToReturn =  $visit->visitData();
                $dataToReturn['patient_id'] =  $pid;
                $dataToReturn['patient_name'] = $name;
                $dataToReturn['email'] =  $email;
                $dataToReturn['initials'] = $initials;
                return $dataToReturn;
            });
            $patientVisits->setCollection($transformedVisits);
            $data['data'] = $patientVisits;
            $data['count'] = PatientVisit::count();
            $data['status'] = true;
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
