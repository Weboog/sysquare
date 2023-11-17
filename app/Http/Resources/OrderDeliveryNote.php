<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDeliveryNote extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
//            'id' => $this->id,
//            'serial' => $this->serial,
//            'items' => OrderDeliveryNoteItem::collection($this->items),
//            'invoices' => $this->invoices()->get(['supplier_id', 'reference', 'comment']),
//            'created' => $this->created_at,
            'id' => $this->id,
            'serial' => $this->serial,
//            'status' => $this->status,
            'items' => OrderDeliveryNoteItem::collection($this->items),
            'invoices' => $this->invoices()->get(),
//            'validated' => $this->validated,
//            'delivered' => $this->delivered,
            'created' => $this->created_at,
        ];
    }

}
