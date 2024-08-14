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
        $diagnosisCodes = DiagnosisCode::all();

        return response()->json($diagnosisCodes);
    }

    public function getICD10Codes(Request $request)
    {
        // Define the base URL for the WHO ICD-10 API
        $baseUrl = 'https://id.who.int/icd/release/10/';

        // Your Client ID and Client Secret
        $clientId = env('ICD10_CLIENT_ID'); // Store this in your .env file
        $clientSecret = env('ICD10_CLIENT_SECRET'); // Store this in your .env file

        // Prepare the request with client credentials
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret)
        ])->get($baseUrl . 'categories');

        // Check if the response was successful
        if ($response->successful()) {
            // Return the data to the view or as JSON
            return response()->json($response->json());
        } else {
            // Handle the error
            return response()->json(['error' => 'Unable to fetch ICD-10 codes'], 500);
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
        if (!empty($request->customDiagnosis)) {
            //save the custom diagnosis
            DiagnosisCode::create([
                'diagnosis' =>  $request->customDiagnosis,
                'code' => rand(123, 1000)
            ]);
            $data['status'] = true;
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
