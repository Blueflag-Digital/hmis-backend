<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    /**
     * DRUGS :: List drugs
     */
    public function index()
    {
        return response()->json(Drug::with('brand')->get(), 200);
    }

    /**
     * DRUGS :: Create drug
     */
    public function store(Request $request)
    {
        $drug = Drug::create($request->all());
        return response()->json($drug, 201);
    }

    /**
     * DRUGS :: View drug
     */
    public function show($id)
    {
        $drug = Drug::with('brand')->findOrFail($id);
        return response()->json($drug, 200);
    }

    /**
     * DRUGS :: Update drug
     */
    public function update(Request $request, $id)
    {
        $drug = Drug::findOrFail($id);
        $drug->update($request->all());
        return response()->json($drug, 200);
    }

    /**
     * DRUGS :: Delete drug
     */
    public function destroy($id)
    {
        Drug::destroy($id);
        return response()->json(null, 204);
    }
}
