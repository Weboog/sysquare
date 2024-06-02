<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'reference' => $this->ref,
            'title' => $this->title,
            'condition' => $this->condition,
            'brand' => $this->brand()->first(['id', 'name']),
            'category' => $this->category()->first(['id', 'name']),
            'type' => $this->type()->first(['id', 'name']),
            'supplier' => $supplier = $this->orderSuppliers()->where('suppliers.id', $this->pivot->supplier_id)->get(['suppliers.id', 'code', 'name'])->first(),
            'price' => (double)  $supplier->getItemPrice($this->id),
            'order_price' => (double) $this->pivot->price,
            'quantity' => $this->pivot->quantity,
            'missed' => $this->pivot->missed,
            'created' => (string) $this->created_at,
            'deleted' => $this->deleted_at,
        ];
    }
}
