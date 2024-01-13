<?php
namespace App\Traits;
use App\Http\Resources\OrderItem;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

trait Helper {

    public function createPurchaseOrder(Order $order, Supplier $supplier, bool $detailed = false): array
    {
        $reducedItems = [];
        foreach ($supplier->orderItems($order)->get() as $item) {
            $reducedItems[] = $item->pivot->missed ? 0 : ($item->pivot->price ?? $supplier->getItemPrice($item->id)) * $item->pivot->quantity;
        }
        $calculations = array_reduce($reducedItems, function ($carry, $price) {
            return $carry + $price ?? 0;
        }, 0);

        return !$detailed
        ? [
            'reference' => $order->serial . '#' . $supplier->id,
            'supplier' => ['id' => $supplier->id, 'name' => $supplier->name],
            'invoices' => $supplier->orderInvoices($order)->count(),
            'total' => round($calculations, 2),
            'created' => (string) $order->created_at
        ]
        : [
            'reference' => $order->serial . '#' . $supplier->id,
            'supplier' => $supplier->sanitize(),
            'invoices' => $supplier->orderInvoices($order)->get(),
            'invoiceCount' => $supplier->orderInvoices($order)->count(),
            'total' => round($calculations, 2),
            'items' => OrderItem::collection($supplier->orderItems($order)->orderBy('title')->get()),
            'created' => (string) $order->created_at
        ];
    }

    public function sanitize(Model $model): Model
    {
        $clone = clone($model);
        unset($clone->created_at);
        unset($clone->updated_at);
        unset($clone->deleted_at);
        return $clone;
    }

    public function calculateTotalAmount(Order $order): array
    {
        $arr = [];
        $items = $order->items;
        $arr['count_items'] = $order->items()->count();
        foreach ($items as $item) {
            //Exclude Missed items
            if ($item->pivot->missed) {
                $arr['total'][] = 0;
                continue;
            }
            $pivot = $item->pivot;

            $sp = $item->suppliers()->where('suppliers.id', $pivot->supplier_id)->first();

            $sp
            ? $price = $sp->pivot->price
            : $price = $pivot->price;

            $itemTotal = round(($pivot->quantity * (double) $price), 2);

            $arr['total'][] = $itemTotal;
        }

        $arr['total'] = array_reduce($arr['total'], function ($c, $t) { return $c + $t; });

        return $arr;

    }

}
