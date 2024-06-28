<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Email;
use App\Models\Patient;
use App\Models\Person;
use App\Models\Phone;
use DateTime;
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
                    'national_id' => isset($patient->person) ? $patient->person->identifier_number : "",
                    // 'national_id' => isset($patient->person) ? "23656524" : "",
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
        $person->phone = null;
        $person->identifier_number = $validatedData['identifier_number'];
        $person->email = $validatedData['email'];
        $person->blood_group = $validatedData['blood_group'];
        $person->save();

        // if (!empty($validatedData['phones'])) {
        //     foreach ($validatedData['phones'] as $phoneNumber) {
        //         $phone = new Phone();
        //         $phone->person_id = $person->id;
        //         $phone->phone_number = $phoneNumber; // Use the current phone number in the loop
        //         $phone->save();
        //     }
        // }

        $formattedPhones = [];
        if (!empty($validatedData['Phones'])) {
             foreach ($validatedData['phones'] as $phone) {
                // Transform phone numbers starting with '07' to '254'
                if (strpos($phone, '07') === 0) {
                    $phone = '254' . substr($phone, 1);
                }

                // Validate phone number format
                if (!preg_match('/^254\d{9}$/', $phone)) {
                    throw new \Exception("{$phone} is invalid for email: {$validatedData['Email']}.It must start with '254' or '07' and be followed by 9 digits.");
                }
                $formattedPhones[] = $phone;

                $phone = new Phone();
                $phone->person_id = $person->id;
                $phone->phone_number = $phone; // Use the current phone number in the loop
                $phone->save();
            }
            //update phone
            $person->phone = $formattedPhones[0];
            $person->update();

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

                $formattedDate = "";
                if (!empty($validatedData['DOB'])) {

                    if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $validatedData['DOB'])) {
                        // Create a DateTime object and format it to Y-m-d
                        $dateTime = DateTime::createFromFormat('d/m/Y', $validatedData['DOB']);
                        if ($dateTime) {
                            $formattedDate = $dateTime->format('Y-m-d');
                        } else {
                            throw new \Exception("Date of Birth {$validatedData['DOB']} is invalid.");
                        }
                    } else {
                        throw new \Exception("Date of Birth {$validatedData['DOB']} is not in the correct format. use format (d/m/Y).");
                    }
                }

                $city_id = null;
                if (!empty($validatedData['City'])) {
                    if ($city = City::where('name', 'LIKE', '%' . $validatedData['City'] . '%')->first()) {
                        $city_id = $city->id;
                    }
                }

                $phone = null;
                if (!empty($validatedData['Phone'])) {
                    $phone = $validatedData['Phone'];
                    // Transform phone numbers starting with '07' to '254'
                    if (strpos($phone, '07') === 0) {
                        $phone = '254' . substr($phone, 1);
                    }

                    // Validate phone number format
                    if (!preg_match('/^254\d{9}$/', $phone)) {
                        throw new \Exception("{$phone} is invalid for email: {$validatedData['Email']}.It must start with '254' or '07' and be followed by 9 digits.");
                    }
                }


                //save the data if the patient is not in the system
                if (!$personExists = Person::where('email', $validatedData['Email'])
                    ->orWhere('phone', $validatedData['Phone'])->first()) {

                    $person = new Person();
                    $person->user_id = null;
                    $person->person_type_id = 1;
                    $person->first_name = $validatedData['First Name'];
                    $person->last_name = $validatedData['Last Name'];
                    $person->date_of_birth = $formattedDate;
                    $person->gender = $validatedData['Gender'];
                    $person->phone = $phone;
                    $person->email = $validatedData['Email'];
                    $person->city_id = $city_id;
                    $person->blood_group = $validatedData['Blood Group'];
                    $person->identifier_number = $validatedData['National ID'];
                    $person->save();

                    $patient = new Patient();
                    $patient->person_id = $person->id;
                    $patient->save();
                } else {
                    $personExists->user_id = null;
                    $personExists->person_type_id = 1;
                    $personExists->first_name = $validatedData['First Name'];
                    $personExists->last_name = $validatedData['Last Name'];
                    $personExists->date_of_birth = $formattedDate;
                    $personExists->gender = $validatedData['Gender'];
                    $personExists->phone = $phone;
                    $personExists->email = $validatedData['Email'];
                    $personExists->city_id = $city_id;
                    $personExists->blood_group = $validatedData['Blood Group'];
                    $personExists->identifier_number = $validatedData['National ID'];
                    $personExists->update();
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
        $person = Patient::find($request->patient_id);

        $patient = Person::find($person->person_id);
        $phones = Phone::where('person_id', $patient->id)->pluck('phone_number')->toArray();

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

        $validatedData = $request->validate([
            'identifier_number' => 'string',
            'blood_group' => 'nullable|string|max:255',
            'city_id' => 'required|integer',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'phones' => 'nullable|max:20',
        ]);

        $data = [
            'status' => false,
            'message' => ""
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

            $phone = "";
            if (!empty($validatedData['phones'])) {
                $phone  = $validatedData['phones'][0];
            }

            $person->user_id = null;
            $person->person_type_id = 1;
            $person->city_id = $validatedData['city_id'];
            $person->first_name = $validatedData['first_name'];
            $person->last_name = $validatedData['last_name'];
            $person->date_of_birth = $validatedData['date_of_birth'];
            $person->gender = $validatedData['gender'];
            $person->phone = null;
            $person->identifier_number = $validatedData['identifier_number'];
            $person->email = $validatedData['email'];
            $person->blood_group = $validatedData['blood_group'];
            $person->save();

            $validatedData['phones'] = array_filter($validatedData['phones'], function ($value) {
                        return $value !== NULL;
                    });

            if (!empty($validatedData['phones'])) {
                $formattedPhones = [];
                // Delete all existing phone numbers for the person
                $existingPhones = Phone::where('person_id', $person->id)->get();
                if ($existingPhones->count() > 0) {
                    foreach ($existingPhones as $existingPhone) {
                        $existingPhone->delete();
                    }
                }
                // Insert new phone numbers
                foreach ($validatedData['phones'] as $newPhoneNumber) {
                    // Transform phone numbers starting with '07' to '254'
                    if (strpos($newPhoneNumber, '07') === 0) {
                        $newPhoneNumber = '254' . substr($newPhoneNumber, 1);
                    }
                    // Validate phone number format
                    if (!preg_match('/^254\d{9}$/', $newPhoneNumber)) {
                        throw new \Exception("{$newPhoneNumber} is not valid.It must start with '254' or '07' and be followed by 9 digits.");
                    }

                    $formattedPhones[] = $newPhoneNumber ;

                    $newPhone = new Phone();
                    $newPhone->person_id = $person->id;
                    $newPhone->phone_number = $newPhoneNumber; // Use the new phone number from validated data
                    $newPhone->save();
                }
                $person->phone = $formattedPhones[0];
                $person->update();

            }

            $data['status'] = true;
        } catch (\Throwable $th) {
            // info($th->getMessage());
            $data['message'] = $th->getMessage();
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

        $data['status'] = false;
        $data['message'] = "";

        try {
            if (!$patient = Patient::findOrFail($validatedData['patient_id'])) {
                throw new \Exception("Patient not found", 1);
            }
            if (!$person = Person::find($patient->person_id)) {
                throw new \Exception("person not found", 1);
            }
            $person->delete();
            $patient->delete();
            $data['message'] = 'Patient deleted successfully';
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data, 200);
    }
}
