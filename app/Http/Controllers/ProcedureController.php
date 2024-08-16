<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    // Display a listing of the resource.
    public function index(Request $request)
    {
        $data = [
            'status'=>false,
            'data' =>[],
        ];

        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $data['data'] = Procedure::where('hospital_id',$hospital->id)->get()->map(function($procedure){
                return $procedure->procedureData();
            });
            $data['status'] = true;

        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'status'=>false,
        ];

        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            if(!$procedure = Procedure::create($validatedData)){
                throw new \Exception("Failed to store Procedure", 1);
            }
            $procedure->update([
                    'hospital_id' =>$hospital->id
                ]);
                $data['status'] = true;

        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json($data);
    }

    // Display the specified resource.
    public function show(Procedure $procedure)
    {
        return response()->json($procedure);
    }

    // Update the specified resource in storage.
    public function update(Request $request, Procedure $procedure)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $procedure->update($validatedData);

        return response()->json($procedure);
    }

    // Remove the specified resource from storage.
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();

        return response()->json(null, 204);
    }
}
