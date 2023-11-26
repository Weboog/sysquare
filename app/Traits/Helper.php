<?php
namespace App\Traits;
use App\Http\Resources\OrderItem;
use App\Models\Order;
use App\Models\Supplier;

trait Helper {

    public function createPurchaseOrder(Order $order, Supplier $supplier): array
    {
        $reducedItems = [];
        foreach ($supplier->orderItems($order)->get() as $item) {
            $reducedItems[] = $item->pivot->missed ? 0 : ($item->pivot->price ?? $supplier->getItemPrice($item->id)) * $item->pivot->quantity;
        }
        $calculations = array_reduce($reducedItems, function ($carry, $price) {
            return $carry + $price ?? 0;
        }, 0);

        return [
            'reference' => $order->serial . '#' . $supplier->id,
            'supplier' => $supplier->sanitize(),
            'invoices' => $supplier->orderInvoices($order)->get(),
            'total' => round($calculations, 2),
            'items' => OrderItem::collection($supplier->orderItems($order)->get()),
            'created' => (string) $order->created_at
        ];
    }

}
