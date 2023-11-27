<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderMode;
use App\Enums\OrderStatus;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\OrderDeliveryNote;
use App\Http\Resources\OrderInvoiceResource;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItem;
use App\Http\Resources\OrderSupplierResource;
use App\Models\Supplier;
use App\Rules\OrderStatusRule;
use App\Traits\Filters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    use Filters;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $orders = Order::OrderByDesc('id');
        $length = null;
        $mode = OrderMode::MODE_DEFAULT;

        foreach (request()->query() as $key => $value) {

            if ($key == 'mode') {
                if (in_array($value, OrderMode::getAllValues())) $mode = $value;
            }

            if ($key == 'length') {
                $length = $value;
            }

            if ($key == 'status' and in_array($value, array_map(fn($case) => $case->value, OrderStatus::cases()))) {
                $orders->where('status', $value);
            }

            if ($key == 'q' and $value != 'null') {

                if ($position = strpos($value, '#')) {
                    $value = substr($value, 0, $position);
                }
                $orders->where('serial', 'like', "%$value%");

            }

            if ($key == 'range') {
                $this->extractRange($value, function($v) use ($orders) {
                    $orders->whereBetween('created_at', $v);
                });
            }
        }

        if ($mode === OrderMode::MODE_DELIVERY_NOTE->value)
            return OrderDeliveryNote::collection(($orders->paginate($length == null ? 10 : $length)->withQueryString()));
        return OrderResource::collection(($orders->paginate($length == null ? 10 : $length)->withQueryString()));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'item' => ['required', 'array'],
            'item.*' => ['numeric', Rule::exists('items', 'id')],
            'supplier' => ['required', 'array'],
            'supplier.*' => ['numeric', Rule::exists('suppliers', 'id')],
            'price' => ['nullable', 'array'],
            'price.*' => 'numeric',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric',
        ];

        $request->validate($rules);

        return DB::transaction(function () use ($request){

            $order = Order::create([
                'serial' => Date::now()->format('Ym')
            ]);

            $orders = []; //[item_id => [supplier_id, quantity]]
            foreach ($request->item as $key => $value) {
                $orders[$value] = [
                    'supplier_id' => $request->supplier[$key],
                    'price' => $request->price[$key] !== '0' ? $request->price[$key] : null,
                    'quantity' => $request->quantity[$key]
                ];
            }

            $order->items()->attach($orders);
            $order->refresh();

            return new OrderResource($order);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

        /**
         * Specific update for item in the pivot table this logic is just for changing 'quantity' and 'missed' properties.
         *  To trigger this logic the missed property of request should be present otherwise it will skip to normal program flow
         */

        if ($request->has('missed') && $request->has('item') && $request->has('supplier')) {

            $rules_2 = [
                'missed' => 'numeric',
                'item' => ['numeric', Rule::in(Item::all()->pluck('id')->toArray())],
                'supplier' => ['numeric', Rule::in(Supplier::all()->pluck('id')->toArray())],
                'quantity' => 'numeric',
            ];

            $request->validate($rules_2);

            $updates = [];
            $updates['missed'] = $request->missed;
            if ($request->has('quantity')) $updates['quantity'] = $request->quantity;

            $int = $order->items()
                ->wherePivot('item_id', '=', $request->item)
                ->wherePivot('supplier_id', '=', $request->supplier)
                ->updateExistingPivot($request->item, $updates);

            return response()->json(['message' => $int == 1 ? 'UPDATED_ITEM' : 'NOTHING_CHANGED']);
        }

        $rules = [
            'status' => ['string', new OrderStatusRule],
            'item.*' => ['required', 'numeric', Rule::in(Item::all()->pluck('id')->toArray())],
            'supplier.*' => ['required', 'numeric', Rule::in(Supplier::all()->pluck('id')->toArray())],
            'quantity.*' => 'required|numeric',
        ];

        $request->validate($rules);

        return DB::transaction(function () use ($request, $order){

            if ($request->has('status')) $order->status = $request->status;
            $order->save();

            $orders = [];
            foreach ($request->item as $key => $value) {
                $orders[$value] = [
                    'supplier_id' => $request->supplier[$key],
                    'quantity' => $request->quantity[$key]
                ];
            }

            $order->items()->sync($orders);
            return new OrderResource($order);
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->deleteOrFail();
        return response()->json(['message' => 'DELETED'], 200);
    }

    /*
     * Relationships////////////////////////////////////////////////
     */

    public function items(Order $order) {

        $items = $order->items;
        return OrderItem::collection($items);

    }

    public function suppliers(Order $order) {

        $suppliers = $order->suppliers;
        return OrderSupplierResource::collection($suppliers);

    }

    public function invoices(Order $order) {

        $invoices = $order->invoices;
        return InvoiceResource::collection($invoices);

    }

    public function purchases(Order $order): JsonResponse
    {
        return $order->generatePurchaseOrders();

    }

    public function purchase(string $ref, Order $order) {
        return $order->generatePurchaseOrder(Supplier::find($ref));
    }

    /*
     * Special actions
     */

    public function setOrderStatus(Request $request, Order $order) {
        $rule = [
            'status' => ['required', 'string', new OrderStatusRule]
        ];
        $request->validate($rule);
        return new OrderResource($order->setStatus($request->status));
    }




}
