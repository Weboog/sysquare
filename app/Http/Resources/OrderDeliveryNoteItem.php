<?php

namespace App\Http\Resources;

use App\Models\Supplier;
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

        $supplier = $this->orderSuppliers()->where('suppliers.id', $this->pivot->supplier_id)->get()->first();

        return [
            'id' => $this->id,
            'reference' => $this->ref,
            'title' => $this->title,
            'condition' => $this->condition,
            'supplier' => [...$this->sanitize($supplier)->toArray(), 'price' => (double) $supplier->getItemPrice($this->id)],
            'orderPrice' => $this->pivot->price ?? null,
            'quantity' => $this->pivot->quantity,
            'missed' => $this->pivot->missed
        ];
    }

    private function sanitize(Supplier $supplier)
    {
        unset($supplier->created_at);
        unset($supplier->updated_at);
        unset($supplier->deleted_at);

        return $supplier;
    }
}
