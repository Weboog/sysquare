<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json(['data' => $invoices]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $rules = [
            'order_id' => ['required', 'numeric', Rule::exists('orders', 'id')],
            'supplier_id' => ['required', 'numeric', Rule::exists('suppliers', 'id')],
            'reference' => ['required', 'string'],
            'comment' => ['nullable', 'string']
        ];
        $request->validate($rules);


        $invoice = Invoice::create($request->all());

        return response()->json($invoice);

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}