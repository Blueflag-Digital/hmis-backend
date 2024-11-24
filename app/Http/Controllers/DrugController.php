<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

        $data = [
            'data' => [],
            'status' => false,
            'totalCount' =>  0,
        ];

        if ($request->value) {
            //fetch all patients
            try {

                if (!$hospital = $request->user()->getHospital()) {
                    throw new \Exception("Hospital does not exist", 1);
                }

                $data['data'] =  Drug::where('hospital_id', $hospital->id)->get()->map(function ($drug) {
                    return $drug->drugData2();
                });
                $data['status'] = true;
            } catch (\Throwable $th) {
                //throw $th;
            }
            return response()->json($data);
        }


        try {

            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $data['totalCount'] = Drug::where('hospital_id', $hospital->id)->count();
            $paginatedData = Drug::where('hospital_id', $hospital->id)->latest()->paginate($limit, ['*'], 'page', $pageNo);
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
     * DRUGS :: Search drugs
     */

    public function search(Request $request)
    {
        $search  = $request->search;
        $data['data'] = [];
        $data['status'] = false;
        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $data['data']  = Drug::where('hospital_id', $hospital->id)->where('name', 'like', '%' . $search . '%')->get()->map(function ($drug) {
                return $drug->drugData();
            });
            info($data['data']);
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
        try {
            if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }
            $drug = Drug::create([
                'name' => $request->name,
                'hospital_id' => $hospital->id,
            ]);
            return response()->json($drug, 201);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    /**
     * DRUGS :: View drug
     */
    public function show(Request $request, $id)
    {

        if (!$hospital = $request->user()->getHospital()) {
            throw new \Exception("Hospital does not exist", 1);
        }
        $drug = Drug::where('id', $id)->where('hospital_id', $hospital->id)->first();
        $data['drug'] = $drug->drugData();
        $data['brands'] = isset($drug->brands) ?  $drug->brands->map(function ($brand) {
            $allData['data'] =  $brand->brandData2();
            $allData['batches'] = isset($brand->batches) ? $brand->batches->map(function ($batch) {
                return $batch->batchData();
            }) : [];
            return $allData;
        }) : [];
        return response()->json($data, 200);
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
        //delete
        Drug::destroy($id);
        return response()->json(null, 204);
    }
}
