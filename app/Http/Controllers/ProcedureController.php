<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $procedures = Procedure::all();
        return response()->json($procedures);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $procedure = Procedure::create($validatedData);

        return response()->json($procedure, 201);
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
