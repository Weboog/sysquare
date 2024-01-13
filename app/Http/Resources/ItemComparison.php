<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemComparison extends JsonResource
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
            'title' => $this->title,
            'condition' => $this->condition,
            'suppliers' => $this->suppliers->map(function (Supplier $sp) {
                return [
                    'itemId' => $this->id,
                    'itemCondition' => $this->condition,
                    'id' => $sp->id,
                    'name' => $sp->name,
                    'price' => (double) $sp->pivot->price,
                ];
            }),
        ];
    }
}
