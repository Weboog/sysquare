<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'ice' => $this->ice,
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'fax' => $this->fax,
            'created' => $this->created_at
        ];
    }
}
