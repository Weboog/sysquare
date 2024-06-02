<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemSupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $req = $request->getRequestUri();
        $uri = explode('/', $req);
        $item_id = $uri[3];

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'price' => $this->items->where('id', $item_id)->pluck('pivot.price')->first()
        ];
    }
}
