<?php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;

class UnitOfMeasureController extends Controller
{
    /**
     * UNITS OF MEASURE :: List units of measure
     */
    public function index(Request $request)
    {
        if(!$hospital = $request->user()->getHospital()){
            throw new \Exception("Hospital does not exist", 1);
        }

        $unitsOfMeasure = UnitOfMeasure::where('hospital_id',$hospital->id)->latest()->get();
        return response()->json($unitsOfMeasure);
    }

    /**
     * UNITS OF MEASURE :: Create a new unit of measure
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $unitOfMeasure=null;
        try {
             if(!$hospital = $request->user()->getHospital()){
                 throw new \Exception("Hospital does not exist", 1);
            }
            $validatedData['hospital_id'] = $hospital->id;
            $unitOfMeasure = UnitOfMeasure::create($validatedData);
            return response()->json($unitOfMeasure, 201);
        } catch (\Throwable $th) {
            return response()->json($unitOfMeasure, 500);
        }

    }

    /**
     * UNITS OF MEASURE :: Show a specific unit of measure
     */
    public function show($id)
    {
        $unitOfMeasure = UnitOfMeasure::findOrFail($id);
        return response()->json($unitOfMeasure);
    }

    /**
     * UNITS OF MEASURE :: Update a specific unit of measure
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unitOfMeasure = UnitOfMeasure::findOrFail($id);
        $unitOfMeasure->update($validatedData);

        return response()->json($unitOfMeasure);
    }

    /**
     * UNITS OF MEASURE :: Delete a specific unit of measure
     */
    public function destroy($id)
    {
        $unitOfMeasure = UnitOfMeasure::findOrFail($id);
        $unitOfMeasure->delete();

        return response()->json(null, 204);
    }
}
