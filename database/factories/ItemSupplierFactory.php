<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemSupplier;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemSupplier>
 */
class ItemSupplierFactory extends Factory
{

    protected $model = ItemSupplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'price' => fake()->numberBetween(5, 5000),
        ];
    }
}
