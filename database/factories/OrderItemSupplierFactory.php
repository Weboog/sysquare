<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItemSupplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItemSupplier>
 */
class OrderItemSupplierFactory extends Factory
{

    protected $model = OrderItemSupplier::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::inRandomOrder()->first();
        $item = Item::whereHas('suppliers')->inRandomOrder()->first();
        $supplier = $item->suppliers()->inRandomOrder()->first();

        return [
            'order_id' => $order->id,
            'item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'quantity' => fake()->randomNumber(2)
        ];
    }
}
