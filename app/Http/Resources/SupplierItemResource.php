<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $suppliers = $this->suppliers;
        $req = $request->getRequestUri();
        $uri = explode('/', $req);
        $supplier_id = $uri[3];

        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'title' => $this->title,
            'condition' => $this->condition,
            'price' => $suppliers->where('id', $supplier_id)->pluck('pivot.price')->first(),
            'brand' => $this->brand()->first(['id', 'name']),
            'category' => $this->category()->first(['id', 'name']),
            'type' => $this->type()->first(['id', 'name']),
            'suppliers' => $this->suppliers()->where('id', '<>', $supplier_id)->get(['id', 'name']),
            'created' => $this->created_at,
            'deleted' => $this->deleted_at
        ];
    }
}
