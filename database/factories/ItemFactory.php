<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $brand = Brand::whereHas('categories')->inRandomOrder()->first();
        $category = $brand->categories()->with('types')->inRandomOrder()->first();
        $type = $category->types()->inRandomOrder()->get()->first();

        return [
            'title' => $title = fake()->word(),
            'condition' => fake()->word(),
            'brand_id' => $b = $brand->id,
            'category_id' => $c = $category->id,
            'type_id' => $t = $type->id,
            'reference' => [$b, $c, $t, $title]
        ];
    }
}
