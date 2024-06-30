<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * SUPPLIERS :: List suppliers
     */
    public function index(Request $request)
    {

        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;

        $data['status'] = false;
        $data['data'] = [];
        $data['count'] = 0;

        try {
            $paginatedData = Supplier::latest()->paginate($limit, ['*'], 'page', $pageNo);
            $suppliers = $paginatedData->getCollection()->map(function ($supplier) {
                $dataToReturn = $supplier->supplierData();
                return $dataToReturn;
            });
            $paginatedData->setCollection($suppliers);
            $data['data'] = $paginatedData;
            $data['status'] = true;
            $data['count']  = Supplier::count();
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data, 500);
    }

    /**
     * SUPPLIERS :: Create supplier
     */
    public function store(Request $request)
    {
        $data['status'] = false;
        $data['message'] = "";

        try {
            if (Supplier::where('email', $request->email)->first()) {
                throw new \Exception("Supplier with {$request->email} already exists", 1);
            }
            $data = Helper::validPhone($request->phone);
            if (!$data['status']) {
                throw new \Exception($data['message']);
            }
            $request['phone'] = $data['phoneNumber'];
            Supplier::create($request->all());
            $data['status'] = true;
            $data['message'] = "Sucess";
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }

    /**
     * SUPPLIERS :: View supplier
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier, 200);
    }

    /**
     * SUPPLIERS :: Update supplier
     */
    public function update(Request $request, $id)
    {

        $data['status'] = false;
        $data['message'] = "";
        try {
            $data = Helper::validPhone($request->phone);
            if (!$data['status']) {
                throw new \Exception($data['message']);
            }
            $request['phone'] = $data['phoneNumber'];


            $supplier = Supplier::findOrFail($id);
            $supplier->update($request->all());
            $data['status'] = true;
            $data['message'] = "Sucess";
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    /**
     * SUPPLIERS :: Delete supplier
     */
    public function destroy($id)
    {
        try {
            Supplier::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }
}
