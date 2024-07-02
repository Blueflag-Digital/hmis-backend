<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    /**
     * DRUGS :: List drugs
     */
    public function index(Request $request)
    {
        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;

        if ($request->value) {
            //fetch all patients
            return Drug::get()->map(function ($drug) {
                return $drug->drugData();
            });
        }

        $data = [
            'data' => [],
            'status' => false,
            'totalCount' =>  0,
        ];

        try {
            $data['totalCount'] = Drug::count();
            $paginatedData = Drug::latest()->paginate($limit, ['*'], 'page', $pageNo);
            $drugs = $paginatedData->getCollection()->map(function ($drug) {
                $dataToReturn = $drug->drugData();
                return $dataToReturn;
            });
            $paginatedData->setCollection($drugs);
            $data['data'] = $paginatedData;
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
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

        if (empty($request->brand_id)) {
            $request['brand_id'] = $drug->brand_id;
        }

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
