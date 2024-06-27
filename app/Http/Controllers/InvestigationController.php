<?php

namespace App\Http\Controllers;

use App\Models\Investigation;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    /**
     * INVESTIGATIONS :: List
     */
    public function index()
    {
        try {
            $investigations = Investigation::all();
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
        //
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
