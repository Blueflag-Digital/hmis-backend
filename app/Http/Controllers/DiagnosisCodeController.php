<?php

namespace App\Http\Controllers;

use App\Models\DiagnosisCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiagnosisCodeController extends Controller
{
    /**
     * DIAGNOSIS CODES :: List
     */
    public function index()
    {
        $data = [
            'status'=>true,
            'data' =>[]
        ];
        try {
            if (!$hospital = request()->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $data['data'] = DiagnosisCode::where('hospital_id',$hospital->id)->get()->map(function($diagnosis){
                return $diagnosis->diagnosisData();
            });
            $data['status'] = true;

        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json($data);
    }


    public function getICD10Codes(Request $request)
    {
        // // Define the base URL for the WHO ICD-10 API
        $baseUrl = 'https://id.who.int/icd/release/10/';
        $authUrl = 'https://icdaccessmanagement.who.int/connect/token';

        // // Your Client ID and Client Secret
        $clientId = env('ICD10_CLIENT_ID'); // Store this in your .env file
        $clientSecret = env('ICD10_CLIENT_SECRET'); // Store this in your .env file

        try {

            $tokenResponse = Http::asForm()->post($authUrl, [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials',
            ]);
            if ($tokenResponse->failed()) {
                return response()->json(['error' => 'Authentication failed'], 401);
            }

            $accessToken = $tokenResponse->json()['access_token'];
             // Prepare the request with client credentials
            $response = Http::withToken($accessToken)->withHeaders([
                'API-Version' => 'v2', // Set the appropriate API version
                'Accept' => 'application/json',
                'Accept-Language' => 'en',
            ])->timeout(10)->get($baseUrl);

            // Check if the response was successful
            if ($response->successful()) {
                // Return the data to the view or as JSON
                info("successful fetch");
                return response()->json($response->json());
            } else {
                // Log the error response
                info('Failed to fetch ICD-10 codes', ['response' => $response->body()]);
                return response()->json(['error' => 'Unable to fetch ICD-10 codes'], 500);
            }

        } catch (\Exception $e) {
            // Log the exception
            info('Error fetching ICD-10 codes', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching ICD-10 codes'], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        info($request->all());
        $data['status'] = false;

        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            if (!empty($request->customDiagnosis)) {
                //save the custom diagnosis
                DiagnosisCode::create([
                    'diagnosis' =>  $request->customDiagnosis,
                    'code' => rand(123, 1000),
                    'hospital_id'=>$hospital->id
                ]);
                $data['status'] = true;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(DiagnosisCode $diagnosisCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiagnosisCode $diagnosisCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiagnosisCode $diagnosisCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiagnosisCode $diagnosisCode)
    {
        //
    }
}
