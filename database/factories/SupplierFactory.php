<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => $phone =  fake()->unique()->phoneNumber(),
            'email' => $email =  fake()->unique()->safeEmail(),
            'code' => $phone.$email,
            'address' => fake()->address(),
        ];
    }
}
