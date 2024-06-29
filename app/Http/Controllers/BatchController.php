<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * BATCHES :: List batches
     */
    public function index()
    {
        return response()->json(Batch::with(['drug', 'supplier'])->get(), 200);
    }

    /**
     * BATCHES :: Create batch
     */
    public function store(Request $request)
    {
        $batch = Batch::create($request->all());
        return response()->json($batch, 201);
    }

    /**
     * BATCHES :: View batch
     */
    public function show($id)
    {
        $batch = Batch::with(['drug', 'supplier'])->findOrFail($id);
        return response()->json($batch, 200);
    }

    /**
     * BATCHES :: Update batch
     */
    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $batch->update($request->all());
        return response()->json($batch, 200);
    }

    /**
     * BATCHES :: Delete batch
     */
    public function destroy($id)
    {
        Batch::destroy($id);
        return response()->json(null, 204);
    }
}
