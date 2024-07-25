<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Drug;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * BATCHES :: List batches
     */
    public function index(Request $request)
    {

        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;

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

    public function search(Request $request)
    {
        $search = $request->search;
        $data['data'] = [];
        $data['status'] = false;
        try {
            $data['data']  = Batch::where('name', 'like', '%' . $search . '%')->get()->map(function ($batch) {
                return $batch->batchData();
            });

            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    /**
     * BATCHES :: Create batch
     */
    public function store(Request $request)
    {
        info($request->all());
        $batch = Batch::create($request->all());
        return response()->json($batch, 201);
    }

    /**
     * BATCHES :: View batch
     */
    public function show($id)
    {
        $batch = Batch::findOrFail($id);
        return response()->json($batch->batchData(), 200);
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
        if (empty($request->brand_id)) {
            $request['brand_id'] = $batch->brand_id;
        }
        if (empty($request->unit_of_measure_id)) {
            $request['unit_of_measure_id'] = $batch->unit_of_measure_id;
        }
        if (empty($request->pack_size_id)) {
            $request['pack_size_id'] = $batch->pack_size_id;
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

    /**
     * BATCHES :: Get Available Drugs
     */
    public function availableDrugs()
    {


        // $response = $drugs->map(function ($drug) {
        //     return [
        //         'id' => $drug->id,
        //         'drug_name' => $drug->name,
        //         'brands' => $drug->brands->map(function ($brand) {
        //             return [
        //                 'id' => $brand->id,
        //                 'brand_name' => $brand->name,
        //                 'quantity_available' => $brand->batches->sum('quantity_available'),
        //             ];
        //         })
        //     ];
        // });

        // $drugs = Drug::whereHas('brands', function ($query) {
        //     $query->whereHas('batches', function ($query) {
        //         $query->where('quantity_available', '>', 0);
        //     });
        // })->get();

        // $drugss = Drug::whereHas('brands', function ($query) {
        //     $query->whereHas('batches', function ($query) {
        //         $query->where('quantity_available', '>', 0);
        //     });
        // });


        // info($drugss->count());

        $drugs = Drug::with(['brands' => function ($query) {
            $query->whereHas('batches', function ($query) {
                $query->where('quantity_available', '>', 0);
            });
        }])->get();

        $response = $drugs->map(function ($drug) {
            return [
                'id' => $drug->id,
                'drug_name' => $drug->name,
                'brands' => $drug->brands->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'brand_name' => $brand->name,
                        'quantity_available' => $brand->batches->sum('quantity_available'),
                    ];
                })
            ];
        });

        return response()->json($response);
    }
}
