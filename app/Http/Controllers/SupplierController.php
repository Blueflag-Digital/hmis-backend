<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * SUPPLIERS :: List suppliers
     */
    public function index()
    {
        return response()->json(Supplier::all(), 200);
    }

    /**
     * SUPPLIERS :: Create supplier
     */
    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());
        return response()->json($supplier, 201);
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
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());
        return response()->json($supplier, 200);
    }

    /**
     * SUPPLIERS :: Delete supplier
     */
    public function destroy($id)
    {
        Supplier::destroy($id);
        return response()->json(null, 204);
    }
}

