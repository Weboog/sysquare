<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return InvoiceResource::collection($invoices);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): InvoiceResource
    {

        $rules = [
            'order_id' => ['required', 'numeric', Rule::exists('orders', 'id')],
            'supplier_id' => ['required', 'numeric', Rule::exists('suppliers', 'id')],
            'reference' => ['required', 'string'],
            'comment' => ['nullable', 'string']
        ];
        $request->validate($rules);


        $invoice = Invoice::create($request->all());

        return new InvoiceResource($invoice);

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $rules = [
            'number' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
        ];

        $request->validate($rules);

         if ($request->number) $invoice->reference = $request->number;
        $invoice->comment = $request->comment;

        if ($invoice->save()) {
            $invoice->refresh();
            return new InvoiceResource($invoice);
        }

        return response()->json(['error' => 'CANNOT_UPDATE'], 402);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {

        $result = $invoice->forceDelete();
        if (!$result) return response()->json(['error' => 'ERROR_OCCURS'], 404);

        return new InvoiceResource($invoice);

    }

    /**
     * Relationships////////////////////////////////////////////////
     */

    public function inOrder(int $orderId): AnonymousResourceCollection
    {

        $invoices = Invoice::where('order_id', 428)->get();
        return InvoiceResource::collection($invoices);

    }

}
