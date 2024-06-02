<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Resources\OrderResource;
use App\Http\Resources\SupplierResource;
use App\Models\Order;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierItemResource;
use App\Traits\Randomize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    use Randomize;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name');
        $length = null;
        $paginate = true;

        foreach (request()->query() as $key => $value) {

            if ($key == 'length') {
                $length = $value;
            }

            if ($key == 'paginate') {
                $paginate = (bool) (int) $value;
            }

            if ($key == 'q' and $value != 'null') {
                $suppliers
                ->where(DB::raw('lower(name)'), 'ilike', strtolower("%$value%"))
                ->orWhere('code', 'ilike', "%$value%")
                ->orWhere(DB::raw('lower(email)'), 'ilike', strtolower("%$value%"))
                ->orWhere('phone', 'ilike', "%$value%");
            }
        }

        return SupplierResource::collection(
            $paginate
            ? $suppliers->paginate($length == null ? 10 : $length)->withQueryString()
            : $suppliers->get()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'phone' => ['required', 'string', 'regex:/^((\+(?!0)[\d]{1,3})|0)[5-7]{1}(\s)?([0-9]{2}(\s)?){4}$/'],
            'email' => 'required|email',
            'address' => 'required|string'
        ];

        $request->validate($rules);

        $supplier = Supplier::create([
            'code' =>  $request->email,
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

     public function orders(Supplier $supplier) {
        $orders = $supplier->orders;
        return OrderResource::collection($orders);
     }

     public function orderItems(Supplier $supplier, int $orderId = 0) {
        $order = Order::findOrFail($orderId);
        $items = $supplier->orderItems($order)->get();
        return response()->json(['data' => $items]);
     }
}
