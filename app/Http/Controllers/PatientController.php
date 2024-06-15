<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::all();

        return response()->json([
            'patients' => $patients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'user_id' => 'nullable|string|max:255|unique:people',
            // 'person_type_id' => 'required|integer|exists:person_types,id',
            // 'city_id' => 'required|integer|exists:cities,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20|unique:people',
        ]);

        $user_id =  $request->user()->id; //logged in user 

        $person = new Person();
        // $person->user_id = $validatedData['user_id'];
        $person->user_id = $user_id;
        $person->person_type_id = 1;
        // $person->city_id = $validatedData['city_id'];
        $person->first_name = $validatedData['first_name'];
        $person->last_name = $validatedData['last_name'];
        $person->date_of_birth = $validatedData['date_of_birth'];
        $person->gender = $validatedData['gender'];
        $person->phone = $validatedData['phone'];
        $person->save();

        $patient = new Patient();
        $patient->person_id = $person->id;
        $patient->save();

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient = Patient::findOrFail($patient->id);

        return response()->json([
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
