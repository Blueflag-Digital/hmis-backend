<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Brand;
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

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            $data['totalCount'] = Batch::where('hospital_id', $hospital->id)->count();
            $paginatedData = Batch::where('hospital_id', $hospital->id)->latest()->paginate($limit, ['*'], 'page', $pageNo);
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

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            $data['data']  = Batch::where('hospital_id', $hospital->id)->where('name', 'like', '%' . $search . '%')->get()->map(function ($batch) {
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
        $batch  = null;
        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

            $batchData = $request->all();
            $batchData['hospital_id'] = $hospital->id;
            $batchData['quantity_available'] = $batchData['quantity_received'];

            $batch = Batch::create([
                'brand_id' =>$batchData['brand_id'],
                'quantity_received' =>$batchData['quantity_received'],
                'supplier_id' =>$batchData['supplier_id'] ? $batchData['supplier_id'] : null,
                'lpo' =>$batchData['lpo'],
                'buying_price' =>$batchData['buying_price'],
                'selling_price' =>$batchData['selling_price'],
                'pack_size_id' =>$batchData['pack_size_id'],
                'unit_of_measure_id' =>$batchData['unit_of_measure_id'],
                'hospital_id' =>$batchData['hospital_id'],
                'quantity_available' =>$batchData['quantity_available'],
            ]);

            return response()->json($batch, 201);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json($batch, 500);
        }
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
    public function availableBrands()
    {

        $data = [
          'status'=>false,
          'data' =>[]
        ];
        try {
            if(!$hospital = request()->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $response = Brand::with(['drug','batches'])
                            ->where('hospital_id',$hospital->id)
                            ->whereHas('batches',function($query){
                                $query->where('quantity_available', '>', 0);
                            })->get();

            if(count($response) > 0 ){
                $data['data'] = $response->map(function($brand){
                    $brandData = $brand->brandData3();
                    $brandData['drug_name'] = $brand->drug->name;
                    $brandData['quantity_available'] = $brand->batches->sum('quantity_available');
                    return $brandData;
                });
            }
            $data['status'] = true;
        } catch (\Throwable $th) {
           info($th->getMessage());
        }

        return response()->json($data);
    }
}
