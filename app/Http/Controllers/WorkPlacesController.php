<?php

namespace App\Http\Controllers;

use App\Models\WorkPlace;
use Illuminate\Http\Request;

class WorkPlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['data']  = [];
        $data['status']  = false;



        try {

            if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }

            $data['data'] =  WorkPlace::where('hospital_id',$hospital->id)->latest()->get()->map(function($workPlace){
                return $workPlace->workPlaceData();
            });
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json(['data'=> $data]);
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
            'status'=>false,
            'message'=> 'Failed creating Hopspital',
        ];
        if(!$hospital = $request->user()->getHospital()){
            throw new \Exception("Hospital does not exist", 1);
        }
        try {
            WorkPlace::create([
                'name' => $request->companyName,
                'description' => $request->companyDesc,
                'hospital_id' => $hospital->id
            ]);
            $data['status'] = true;
            $data['message'] = "Successfully added Company";
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
            'status'=>false,
            'message'=>'Failed to update Company'
        ];
        try {
            if(!$workPlace = WorkPlace::find($id)){
                throw new \Exception("Company not found", 1);
            }
            $workPlace->name = $request->companyName;
            $workPlace->description = $request->companyDesc;
            $workPlace->update();
            $data['status'] = true;
            $data['message'] = 'Successfully updated company';
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
            'status'=>false,
            'message'=>'Failed to update Company'
        ];
        try {
            if(!$workPlace = WorkPlace::find($id)){
                throw new \Exception("Company not found", 1);
            }
            $workPlace->delete();
            $data['status'] = true;
            $data['message'] = 'Successfully removed company';
        } catch (\Throwable $th) {
             info($th->getMessage());
        }
         return response()->json($data);
    }
}
