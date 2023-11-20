<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $suppliers = $this->suppliers();
        return [
            'id' => $this->id,
            'reference' => $this->ref,
            'title' => $this->title,
            'price' => $suppliers
                ->where('item_id', $this->id)
                ->get()
                ->pluck('pivot.price')
                ->first(),
            'condition' => $this->condition,
            'brand' => $this->brand()->first(['id', 'name']),
            'category' => $this->category()->first(['id', 'name']),
            'type' => $this->type()->first(['id', 'name']),
            'suppliers' => $this->suppliers()->get(['suppliers.id', 'code', 'name', 'phone', 'email', 'address', 'item_supplier.price']),
            'created' => $this->created_at,
            'deleted' => $this->deleted_at
        ];
    }
}
