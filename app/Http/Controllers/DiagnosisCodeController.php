<?php

namespace App\Http\Controllers;

use App\Models\DiagnosisCode;
use Illuminate\Http\Request;

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
