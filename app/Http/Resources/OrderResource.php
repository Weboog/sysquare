<?php

namespace App\Http\Resources;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'serial' => $this->serial,
            'status' => $this->status,
//            'items' => ItemResource::collection($this->items),
//            'invoices' => $this->invoices()->get(),
            'validated' => $this->validated,
            'delivered' => $this->delivered,
            'created' => $this->created_at,
            'deleted' => $this->deleted_at,
        ];
    }
}
