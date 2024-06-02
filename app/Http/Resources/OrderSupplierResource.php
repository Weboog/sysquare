<?php

namespace App\Http\Resources;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $uri = explode('/', $request->getRequestUri());
        $param = $uri[3];
        $item = $param == $this->pivot->order_id
            ? Item::where('id', $this->pivot->item_id)
            : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'item' => $item->first(['items.id', 'ref']),
            'price' => (double) $item->first()->suppliers()->where('suppliers.id', $this->pivot->supplier_id)->get()->pluck('pivot.price')->first(),
            'quantity' => $item->first()->orders()->where('orders.id', $param)->get()->pluck('pivot.quantity')->first(),
            'created' => $this->created_at,
            'deleted' => $this->deleted_at,
        ];
    }
}
