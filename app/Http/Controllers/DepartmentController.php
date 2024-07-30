<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * DEPARTMENTS :: List
     */
    public function index(Request $request)
    {
        if(!$hospital = $request->user()->getHospital()){
            throw new \Exception("Hospital does not exist", 1);
        }

        $departments = Department::where('hospital_id',$hospital->id)->all();

        return response()->json($departments);
    }

    /**
     * DEPARTMENTS :: Store
     */
    public function store(Request $request)
    {
        $data = [
            'status'=> false,
            'message'=> 'Failed to add department',
        ];

        try {

            if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }

            Department::create([
                'name' => $request->name,
                'hospital_id'=> $hospital->id
            ]);
            $data['status'] = true;
            $data['message'] = "Successfully added department";
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
