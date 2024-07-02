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
        // $brands = Brand::get()->map(function($brand){
        //     return $brand->brandData();
        // })
        return response()->json(Brand::all(), 200);
    }

    /**
     * BRANDS :: Create brand
     */
    public function store(Request $request)
    {
        $brand = Brand::create($request->all());
        return response()->json($brand, 201);
    }

    /**
     * BRANDS :: View brand
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand, 200);
    }

    /**
     * BRANDS :: Update brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);



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
