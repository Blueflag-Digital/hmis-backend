<?php

namespace App\Http\Controllers;

use App\Models\Investigation;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    /**
     * INVESTIGATIONS :: List
     */
    public function index(Request $request)
    {
        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $investigations = Investigation::where('hospital_id', $hospital->id)->latest()->get()->map(function ($investigation) {
                return $investigation->investigationsData();
            });
            return response()->json($investigations, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json(['message' => 'Failed to retrieve investigations'], 500);
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
        $data = [
            'status' => false,
            'message' => 'Failed to add investigation',
        ];

        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $uniqueCode = Investigation::generateUniqueInvestigationCode();

            Investigation::create([
                'name' => $request->name,
                'code' => $uniqueCode,
                'type' => $request->type,
                'hospital_id' => $hospital->id
            ]);
            $data['status'] = true;
            $data['message'] = "Successfully added investigation";
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'status' => false,
            'message' => 'Failed to update investigation',
        ];

        try {
            if (!$inv = Investigation::find($id)) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $inv->name = $request->name;
            $inv->type = $request->type;
            $inv->update();
            $data['status'] = true;
            $data['message'] = "Successfully updated investigation";
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = [
            'status' => false,
            'message' => 'Failed to remove investigation'
        ];
        try {
            if (!$investigation =  Investigation::find($id)) {
                throw new \Exception("investigation does not exist", 1);
            }
            $investigation->delete();
            $data['status'] = false;
            $data['message'] =  'Successfully removed investigation';
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }
}
