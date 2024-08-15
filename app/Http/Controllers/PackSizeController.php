<?php

namespace App\Http\Controllers;

use App\Models\PackSize;
use Illuminate\Http\Request;

class PackSizeController extends Controller
{
    /**
     * PACK SIZES :: List pack sizes
     */
    public function index(Request $request)
    {
        if(!$hospital = $request->user()->getHospital()){
            throw new \Exception("Hospital does not exist", 1);
        }

        $packSizes = PackSize::where('hospital_id',$hospital->id)->latest()->get()->map(function($packsize){
            return [
                'id'=>$packsize->id,
                'name'=>$packsize->name
            ];
        });
        return response()->json($packSizes);
    }

    /**
     * PACK SIZES :: Create a new pack size
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $packSize = null;
        try {
            if(!$hospital = $request->user()->getHospital()){
                throw new \Exception("Hospital does not exist", 1);
            }
            $validatedData['hospital_id'] = $hospital->id;

            $packSize = PackSize::create($validatedData);
            return response()->json($packSize, 201);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json($packSize, 500);
        }




    }

    /**
     * PACK SIZES :: Show a specific pack size
     */
    public function show($id)
    {
        $packSize = PackSize::findOrFail($id);
        return response()->json($packSize);
    }

    /**
     * PACK SIZES :: Update a specific pack size
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $packSize = PackSize::findOrFail($id);
        $packSize->update($validatedData);

        return response()->json($packSize);
    }

    /**
     * PACK SIZES :: Delete a specific pack size
     */
    public function destroy($id)
    {
        $packSize = PackSize::findOrFail($id);
        $packSize->delete();

        return response()->json(null, 204);
    }
}
