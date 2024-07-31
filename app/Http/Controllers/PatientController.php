<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Email;
use App\Models\Patient;
use App\Models\Person;
use App\Models\Phone;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * PATIENTS :: List
     */
    public function index(Request $request)
    {

           if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }

        if ($request->value) {
            //fetch all patients
            return Person::where('hospital_id',$hospital->id)->get()->map(function ($patient) {
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
            $data['totalCount'] = Patient::where('hospital_id',$hospital->id)->count();
            $paginatedData = Patient::where('hospital_id',$hospital->id)->latest()->paginate($limit, ['*'], 'page', $pageNo);
            $patients = $paginatedData->getCollection()->map(function ($patient) {
                $dataToReturn = $patient->patientData();
                return $dataToReturn;
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
            'identifier_number' => 'string',
            'blood_group' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255|unique:people',
            'city_id' => 'required|integer',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'phones' => 'nullable|max:20',
        ]);

        $place_of_work_id = $request->place_of_work_id ?? null;

        $insuranceCard = $request->insurance_card_number ?? null;
        $medInsuranceCard = $request->med_Insurance_card_number ?? null;


        $data['status'] = false;
        $data['message'] = "";

        try {

            if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }
            //phone number validation
            $allPhones = [];
            if (!empty($validatedData['phones'])) {
                $validatedData['phones'] = array_filter($validatedData['phones'], function ($value) {
                    return $value !== NULL;
                });

                foreach ($validatedData['phones'] as $newPhoneNumber) {
                    // Validate phone number format
                    if (!preg_match('/^(2547||07)\d{8}$/', $newPhoneNumber)) {
                        throw new \Exception("{$newPhoneNumber} is invalid. It must start with '254' or '07' and be followed by 8 digits.");
                    }
                    if (strpos($newPhoneNumber, '07') === 0) {
                        $newPhoneNumber = '254' . substr($newPhoneNumber, 1);
                    }
                    //check phones table if this phone number exists
                    if ($number = Phone::where('phone_number', $newPhoneNumber)->exists()) {
                        throw new \Exception("Phone Number already exists", 1);
                    }
                    $allPhones[] =  $newPhoneNumber;
                }
            }


            // //add email
            // if (!empty($validatedData['email_addresses'])) {
            //     foreach ($validatedData['email_addresses'] as $email_address) {
            //         $email = new Email();
            //         $email->person_id = $person->id;
            //         $email->email_address = $email_address; // Use the current email address in the loop
            //         $email->save();
            //     }
            // }


            $person = new Person();
            $person->user_id = null;
            $person->person_type_id = 1;
            $person->city_id = $validatedData['city_id'];
            $person->first_name = $validatedData['first_name'];
            $person->last_name = $validatedData['last_name'];
            $person->date_of_birth = $validatedData['date_of_birth'];
            $person->gender = $validatedData['gender'];
            $person->identifier_number = $validatedData['identifier_number'];
            $person->email = $validatedData['email'];
            $person->blood_group = $validatedData['blood_group'];
            $person->work_place_id = $place_of_work_id;
            $person->insurance_card_number = $insuranceCard;
            $person->med_Insurance_card_number = $medInsuranceCard;
            $person->hospital_id = $hospital->id;
            $person->save();

            if ($person) {
                //Store this person as a patient
                $patient = new Patient();
                $patient->person_id = $person->id;
                $patient->hospital_id = $hospital->id;
                $patient->user_id = $request->user()->id;
                $patient->save();

                if (!empty($allPhones)) {
                    $person->phone = $allPhones[0];;
                    $person->update();
                    //store the patients phone numbers
                    foreach ($allPhones as $phoneNumber) {
                        $phone = new Phone;
                        $phone->person_id = $person->id;
                        $phone->phone_number = $phoneNumber;
                        $phone->save();
                    }
                }
            }

            $data['status'] = true;
            $data['message'] = "Patient created successfully";
        } catch (\Throwable $th) {
            // info($th->getMessage());
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    public function bulkUpload(Request $request)
    {

        $data['status'] = false;
        $data['message'] = "";

        $datas = $request->data;

        try {
            foreach ($datas as $key => $validatedData) {
                //remove the first row if email is 'test@gmail.com
                if (isset($validatedData['Email']) && $validatedData['Email'] === 'test@gmail.com') {
                    info("email exists");
                    unset($datas[$key]);
                }
            }
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

                // Validate phone number format
                $phone = null;
                if (!empty($validatedData['Phone'])) {
                    $phone = $validatedData['Phone'];

                    if (!preg_match('/^(2547||07)\d{8}$/', $phone)) {
                        throw new \Exception("{$phone} is invalid. It must start with '254' or '07', followed by 8 digits.");
                    }

                    if (strpos($phone, '07') === 0) {
                        $phone = '254' . substr($phone, 1);
                    }

                    $phone = $phone;
                }


                //save the data if the patient is not in the system
                if (!$personExists = Person::where('email', $validatedData['Email'])->first()) {
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

                    $newPhone = new Phone;
                    $newPhone->phone_number = $phone;
                    $newPhone->person_id = $person->id;
                    $newPhone->save();
                } else {

                    //patient already exists in the system
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
                    if ($personExists) {

                        $existingPhones = Phone::where('person_id', $personExists->id)->get();
                        if ($existingPhones->count() > 0) {
                            foreach ($existingPhones as $existingPhone) {
                                $existingPhone->delete();
                            }
                        }
                        $newPhone = new Phone;
                        $newPhone->phone_number = $phone;
                        $newPhone->person_id = $personExists->id;
                        $newPhone->save();
                    }
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

        $data = [
            'status' => false,
            'data' => [],
        ];

        try {
            $patient = Patient::find($request->patient_id);
            if (!$patient) {
                throw new \Exception("Patient does not exist", 1);
            }
            $person = Person::find($patient->person_id);
            if (!$person) {
                throw new \Exception("Person does not exist", 1);
            }
            $data['data'] = $person->personData();
            $data['status'] = true;
        } catch (\Throwable $th) {
            // info($th->getMessage());
        }
        return response()->json($data);
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
            'gender' => 'nullable|string|max:10',
            'phones' => 'nullable|max:20',
            'date_of_birth' => 'nullable|date'
        ]);

        // info($validatedData);
        $insuranceCard = $request->insurance_card_number ?? null;
        $medInsuranceCard = $request->med_Insurance_card_number ?? null;


        $data = [
            'status' => false,
            'message' => ""
        ];

        try {

            $validatedData['phones'] = array_filter($validatedData['phones'], function ($value) {
                return $value !== NULL;
            });

            // $formattedDate = "";
            // if (!empty($validatedData['date_of_birth'])) {

            //     if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $validatedData['date_of_birth'])) {
            //         // Create a DateTime object and format it to Y-m-d
            //         $dateTime = DateTime::createFromFormat('d/m/Y', $validatedData['date_of_birth']);
            //         if ($dateTime) {
            //             $formattedDate = $dateTime->format('Y-m-d');
            //         } else {
            //             throw new \Exception("Date of Birth {$validatedData['date_of_birth']} is invalid.");
            //         }
            //     } else {
            //         throw new \Exception("Date of Birth {$validatedData['date_of_birth']} is not in the correct format. use format (d/m/Y).");
            //     }
            // }


            $patient = Patient::find($request->id);
            //find the person
            $person = Person::find($patient->person_id);
            //update with info

            $validatedData['email'] = $person->email;
            if (!Person::where('email', $request->email)->first()) {
                //update the email if its not the same
                $validatedData['email']  = $request->email;
            }



            $validatedData['date_of_birth'] = Carbon::parse($request->date_of_birth)->format('Y-m-d');

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
            $person->work_place_id = $request->place_of_work_id ?? null;
            $person->insurance_card_number = $insuranceCard;
            $person->med_Insurance_card_number = $medInsuranceCard;
            $person->save();

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
                    if (!preg_match('/^(2547|07)\d{8}$/', $newPhoneNumber)) {
                        throw new \Exception("{$newPhoneNumber} is invalid. It must start with '254' or '07' and be followed by 8 digits.");
                    }
                    if (strpos($newPhoneNumber, '07') === 0) {
                        $newPhoneNumber = '254' . substr($newPhoneNumber, 1);
                    }

                    $formattedPhones[] = $newPhoneNumber;
                    $newPhone = new Phone();
                    $newPhone->person_id = $person->id;
                    $newPhone->phone_number = $newPhoneNumber; // Use the new phone number from validated data
                    $newPhone->save();
                }
                $person->phone = $formattedPhones[0];
                $person->update();
            } else {
                //delete every phone number if it comes empty
                $existingPhones = Phone::where('person_id', $person->id)->get();
                if ($existingPhones->count() > 0) {
                    foreach ($existingPhones as $existingPhone) {
                        $existingPhone->delete();
                    }
                }
                $person->phone = null;
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
