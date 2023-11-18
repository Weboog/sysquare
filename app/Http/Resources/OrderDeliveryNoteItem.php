<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDeliveryNoteItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $supplier = $this->orderSuppliers()->where('suppliers.id', $this->pivot->supplier_id)->get(['suppliers.id', 'code', 'name'])->first();

        return [
            'id' => $this->id,
            'reference' => $this->ref,
            'title' => $this->title,
            'condition' => $this->condition,
            'supplier' => [...$supplier->toArray(), 'price' => (double) $supplier->getItemPrice($this->id)],
            'orderPrice' => $this->pivot->price ?? null,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
