<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\instance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{

    public function __construct(private Model $instance)
    {
        $this->instance = $instance;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return match(preg_replace('/^App\\\\Models\\\\/', '', $this->instance::class)) {
            'Brand' => [
                'length' => $this->instance::all()->count()
            ],
            'Category' => [
                'length' => $this->instance::all()->count()
            ],
            'Type' => [
                'length' => $this->instance::all()->count()
            ],
            'Item' => [
                'length' => $this->instance::all()->count()
            ],
            'Order' => [
                'length' => $this->instance::all()->count(),
                'validated' => $this->instance::validated()->get()->count(),
                'rejected' => $this->instance::rejected()->get()->count(),
                'transit' => $this->instance::delivering()->get()->count(),
                'delivered' => $this->instance::delivered()->get()->count(),
            ],
            'Supplier' => [
                'length' => $this->instance::all()->count()
            ]
        };

    }
}
