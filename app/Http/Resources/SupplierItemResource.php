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
            'reference' => $this->ref,
            'title' => $this->title,
            'condition' => $this->condition,
            'price' => $suppliers->where('suppliers.id', $supplier_id)->pluck('pivot.price')->first(),
            'brand' => $this->brand()->first(['brands.id', 'name']),
            'category' => $this->category()->first(['categories.id', 'name']),
            'type' => $this->type()->first(['types.id', 'name']),
            'suppliers' => $this->suppliers()->where('suppliers.id', '<>', $supplier_id)->get(['suppliers.id', 'name']),
            'created' => $this->created_at,
            'deleted' => $this->deleted_at
        ];
    }
}
