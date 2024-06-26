<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
        'order_id' => $this->order_id,
        'supplier_id' => $this->supplier_id,
        'reference' => $this->reference,
        'amount' => $this->amount,
        'comment' => $this->comment,
    ];;
    }
}
