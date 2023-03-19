<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierItemResource;
use Exception;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderByDesc('id');
        return SupplierResource::collection($suppliers->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'code' => 'required|string',
            'name' => 'required|string',
            'phone' => ['required', 'string', 'regex:/^((\+(?!0)[\d]{1,3})|0)[5-7]{1}([0-9]{2}){4}$/i'],
            'email' => 'required|email',
            'address' => 'required|string'
        ];

        $request->validate($rules);

        $supplier = Supplier::create([
            'code' => $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json($supplier);

    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {

        $rules = [
            // 'code' => 'required|string',
            'name' => 'required|string',
            'phone' => ['required', 'string', 'regex:/^((\+(?!0)[\d]{1,3})|0)[5-7]{1}([0-9]{2}){4}$/i'],
            'email' => 'required|email',
            'address' => 'required|string'
        ];

        $request->validate($rules);
        $supplier->fill($request->all());

        if ($supplier->isDirty()) {

            $supplier->save();
            return response()->json($supplier);

        } else {

            return response()->json(['message' => 'NOTHING_TOUCHED'], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    /**
     * Relations
     */

    public function items(Supplier $supplier) {

        $items = $supplier->items;
        return SupplierItemResource::collection($items);

     }
}
