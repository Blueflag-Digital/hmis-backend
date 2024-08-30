<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * BRANDS :: List brands
     */
    public function index(Request $request)
    {

        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;

        $data = [
            'status' => false,
            'data' => [],
            'totalCount' => 0
        ];

        if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }


        if ($request->value) {
            try {
                $data['data'] = Brand::where('hospital_id',$hospital->id)->get()->map(function ($brand) {
                    return $brand->brandData3();
                });
                $data['status'] = true;
            } catch (\Throwable $th) {
                info($th->getMessage());
            }
            return response()->json($data);
        }

        try {
            $data['totalCount'] = Brand::count();
            $paginatedData = Brand::where('hospital_id',$hospital->id)->latest()->paginate($limit, ['*'], 'page', $pageNo);
            $brands = $paginatedData->getCollection()->map(function ($brand) {
                return $brand->brandData();
            });
            $paginatedData->setCollection($brands);
            $data['data'] = $paginatedData;
            $data['status'] = true;
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json($data, 500);
        }
    }

    /**
     * BRANDS :: Search brands
     */


    public function search(Request $request)
    {
        $search  = $request->search;
        $data['data'] = [];
        $data['status'] = false;

         if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }

        try {
            $data['data']  = Brand::where('hospital_id',$hospital->id)->where('name', 'like', '%' . $search . '%')->get()->map(function ($brand) {
                return $brand->brandData();
            });
            info($data['data']);
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }



    /**
     * BRANDS :: Create brand
     */
    public function store(Request $request)
    {
        if (!$hospital = $request->user()->getHospital()) {
                throw new \Exception("Hospital does not exist", 1);
            }


        $brand = Brand::create($request->all());
        $brand->update([
            'hospital_id'=>$hospital->id
        ]);
        return response()->json($brand, 201);
    }

    /**
     * BRANDS :: View brand
     */
    public function show($id)
    {
        $data = Brand::findOrFail($id);
        return response()->json($data->brandData(), 200);
    }

    /**
     * BRANDS :: Update brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        if (empty($request->brand_id)) {
            $request['drug_id'] = $brand->drug_id;
        }

        $brand->update($request->all());
        return response()->json($brand, 200);
    }

    /**
     * BRANDS :: Delete brand
     */
    public function destroy($id)
    {
        Brand::destroy($id);
        return response()->json(null, 204);
    }
}
