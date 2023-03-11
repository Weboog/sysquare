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
            'reference' => $this->reference,
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
            'suppliers' => $suppliers->get(['id', 'name']),
            'created' => $this->created_at,
            'deleted' => $this->deleted_at
        ];
    }
}
