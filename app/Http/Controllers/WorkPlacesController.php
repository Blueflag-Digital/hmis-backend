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

         if(!$hospital = $request->user()->getHospital()){
            throw new \Exception("Hospital does not exist", 1);
        }

        try {

            $data['data'] =  WorkPlace::where('hospital_id',$hospital->id)->get()->map(function($workPlace){
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
