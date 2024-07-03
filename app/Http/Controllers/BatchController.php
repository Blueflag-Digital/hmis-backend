<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * BATCHES :: List batches
     */
    public function index(Request $request)
    {

        $pageNo = $request->pageNo;
        $limit = $request->limit;

        $data = [
            'data' => [],
            'status' => false,
        ];


        try {
            $data['totalCount'] = Batch::count();
            $paginatedData = Batch::latest()->paginate($limit, ['*'], 'page', $pageNo);
            $batches = $paginatedData->getCollection()->map(function ($batch) {
                return $batch->batchData();
            });
            $paginatedData->setCollection($batches);
            $data['data'] = $paginatedData;
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data, 500);

        // return response()->json(Batch::with(['drug', 'supplier'])->get(), 200);
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

        if (empty($request->supplier_id)) {
            $request['supplier_id'] = $batch->supplier_id;
        }
        if (empty($request->drug_id)) {
            $request['drug_id'] = $batch->drug_id;
        }
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
