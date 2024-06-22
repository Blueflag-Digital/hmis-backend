<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Email;
use App\Models\Patient;
use App\Models\Person;
use App\Models\Phone;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    //reusable functions
    function getInitials($firstName, $lastName)
    {
        $firstInitial = isset($firstName) ? strtoupper($firstName[0]) : '';
        $lastInitial = isset($lastName) ? strtoupper($lastName[0]) : '';
        return $firstInitial . $lastInitial;
    }


    //end reusbale code

    /**
     * PATIENTS :: List
     */
    public function index(Request $request)
    {

        if ($request->value) {
            //fetch all patients
            return Person::get()->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' =>   $patient->first_name . " " . $patient->last_name
                ];
            });
        }

        $pageNo = $request->pageNo;
        $limit = $request->limit;

        $data = [
            'patients' => [],
            'status' => false,
        ];


        try {
            $data['totalCount'] = Patient::count();
            $paginatedData = Patient::paginate($limit, ['*'], 'page', $pageNo);
            $patients = $paginatedData->getCollection()->map(function ($patient) {

                return [
                    'id' => $patient->id,
                    'initials' => isset($patient->person)  ?  $this->getInitials($patient->person->first_name, $patient->person->last_name)  : "",
                    'name' =>  isset($patient->person) ? $patient->person->first_name . " " . $patient->person->last_name : "",
                    'dob' => isset($patient->person) ? $patient->person->date_of_birth : "",
                    'gender' => isset($patient->person) ? $patient->person->gender : "",
                    'phone' => isset($patient->person) ? $patient->person->phone : " ",
                    'email' => isset($patient->person) ? $patient->person->email : "",
                    'blood_group' => isset($patient->person) ? $patient->person->blood_group : "",
                    'national_id' => isset($patient->person) ? $patient->person->identifier_no : "",
                    'national_id' => isset($patient->person) ? "23656524" : "",
                    'city' => isset($patient->person) && isset($patient->person->city) ? $patient->person->city->name : null,
                ];
            });
            $paginatedData->setCollection($patients);
            $data['patients'] = $paginatedData;
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
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
            'identifier_number' => 'string',
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
        $person->identifier_number = $validatedData['identifier_number'];
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

        if (!empty($validatedData['email_addresses'])) {
            foreach ($validatedData['email_addresses'] as $email_address) {
                $email = new Email();
                $email->person_id = $person->id;
                $email->email_address = $email_address; // Use the current email address in the loop
                $email->save();
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

                // Look up the city by name
                $city = City::where('name', $validatedData['City'])->first();

                if (!$city) {
                    $city_id = 1;
                    continue;
                } else {
                    $city_id = $city->id;
                }


                //save the data if the patient is not in the system
                if (!Person::where('email', $validatedData['Email'])
                    ->orWhere('phone', $validatedData['Phone'])->first()) {
                    $person = new Person();
                    $person->user_id = null;
                    $person->person_type_id = 1;
                    $person->first_name = $validatedData['First Name'];
                    $person->last_name = $validatedData['Last Name'];
                    // $person->date_of_birth = $validatedData['DOB'];
                    $person->gender = $validatedData['Gender'];
                    $person->phone = $validatedData['Phone'];
                    $person->email = $validatedData['Email'];
                    $person->city_id = $city_id;
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
        $person = Patient::find($request->patient_id);
        $patient = Person::find($person->person_id);
        $phones = Phone::where('person_id', $patient->id)->pluck('phone_number')->toArray();

        $patientData = [
            'id' => null,
            'name' => "",
            'dob' => "",
            'gender' => "",
            'phone' => " ",
            'email' => "",
            'blood_group' => "",
            'national_id' => "",
            'city' => null,
            'first_name' => "",
            'last_name' => ""
        ];


        if ($patient) {
            // If the patient exists, populate the response array
            $patientData = [
                'id' => $patient->id,
                'name' => $patient->first_name . " " . $patient->last_name,
                'dob' => $patient->date_of_birth,
                'gender' => $patient->gender,
                'phone' => $patient->phone,
                'email' => $patient->email,
                'blood_group' => $patient->blood_group,
                'city' => $patient->city ? $patient->city->name : null,
                'city_id' => $patient->city ? $patient->city->id : null,
                'first_name' =>  $patient->first_name,
                'last_name' => $patient->last_name,
                'national_id' =>  $patient->identifier_number
            ];
        }



        return response()->json([
            'patient' => $patientData,
            'phones' => $phones,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        info($request->all());

        $validatedData = $request->validate([
            'identifier_number' => 'string',
            'blood_group' => 'nullable|string|max:255',
            'city_id' => 'required|integer',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            // 'phone' => 'nullable|string|max:20|unique:phones,phone_number',
            'phone' => 'nullable|max:20',
        ]);

        $data = [
            'status' => false
        ];

        try {
            $patient = Patient::find($request->id);
            //find the person
            $person = Person::find($patient->person_id);
            //update with info

            $validatedData['email'] = $person->email;
            if (!Person::where('email', $request->email)->first()) {
                //update the email if its not the same
                $validatedData['email']  = $request->email;
            }



            $person->user_id = null;
            $person->person_type_id = 1;
            $person->city_id = $validatedData['city_id'];
            $person->first_name = $validatedData['first_name'];
            $person->last_name = $validatedData['last_name'];
            $person->date_of_birth = $validatedData['date_of_birth'];
            $person->gender = $validatedData['gender'];
            $person->phone = $validatedData['phone'];
            $person->identifier_number = $validatedData['identifier_number'];
            $person->email = $validatedData['email'];
            $person->blood_group = $validatedData['blood_group'];
            $person->save();
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patient = Patient::findOrFail($validatedData['patient_id']);

        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully',
        ], 200);
    }
}
