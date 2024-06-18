<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use App\Models\Phone;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * PATIENTS :: List
     */
    public function index(Request $request)
    {
        $pageNo = $request->pageNo;
        $limit = $request->limit;

        $count = Patient::count();

        $patients = Patient::with(['person', 'person.phones'])
            ->paginate($limit, ['*'], 'page', $pageNo);

        return response()->json([
            'patients' => $patients,
            'totalCount' => $count
        ]);
    }

    /**
     * PATIENTS :: Store
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'user_id' => 'nullable|string|max:255|unique:people',
            // 'person_type_id' => 'required|integer|exists:person_types,id',
            // 'city_id' => 'required|integer|exists:cities,id',
            'blood_group' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255|unique:people',
            'city_id' => 'required|integer',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            // 'phone' => 'nullable|string|max:20|unique:phones,phone_number',
            'phones' => 'nullable|max:20',
        ]);

        $phone = "";

        if (!empty($validatedData['phones'])) {
            $phone  = $validatedData['phones'][0];
        }



        $person = new Person();
        $person->user_id = null;
        $person->person_type_id = 1;
        $person->city_id = $validatedData['city_id'];
        $person->first_name = $validatedData['first_name'];
        $person->last_name = $validatedData['last_name'];
        $person->date_of_birth = $validatedData['date_of_birth'];
        $person->gender = $validatedData['gender'];
        $person->phone = $phone;
        $person->email = $validatedData['email'];
        $person->blood_group = $validatedData['blood_group'];
        $person->save();

        if (!empty($validatedData['phones'])) {
            foreach ($validatedData['phones'] as $phoneNumber) {
                $phone = new Phone();
                $phone->person_id = $person->id;
                $phone->phone_number = $phoneNumber; // Use the current phone number in the loop
                $phone->save();
            }
        }

        $patient = new Patient();
        $patient->person_id = $person->id;
        $patient->save();

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }

    public function bulkUpload(Request $request)
    {
        $data['status'] = false;
        $data['message'] = "";

        $datas = $request->data;
        try {
            foreach ($datas as $key => $validatedData) {
                //save the data if the patient is not in the system
                if (!Person::where('email', $validatedData['Email'])
                    ->orWhere('phone', $validatedData['Phone'])->first()) {
                    $person = new Person();
                    $person->user_id = null;
                    $person->person_type_id = 1;
                    // $person->city_id = $validatedData['city_id'];
                    $person->first_name = $validatedData['First Name'];
                    $person->last_name = $validatedData['Last Name'];
                    // $person->date_of_birth = $validatedData['DOB'];
                    $person->gender = $validatedData['Gender'];
                    $person->phone = $validatedData['Phone'];
                    $person->email = $validatedData['Email'];
                    $person->city_id = $validatedData['City'];
                    $person->blood_group = $validatedData['Blood Group'];
                    $person->save();
                    // $person->identifier_number = $validatedData['NationalID'];
                    $patient = new Patient();
                    $patient->person_id = $person->id;
                    $patient->save();
                }
            }
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] =  $th->getMessage();
        }
        return response()->json($data);
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $patient = Person::find($request->patient_id);
        $phones = Phone::where('person_id', $patient->id)->pluck('phone_number')->toArray();

        return response()->json([
            'patient' => $patient,
            'phones' => $phones,
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
