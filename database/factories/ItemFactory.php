<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
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
        $category = $brand->categories()->whereHas('types')->inRandomOrder()->first();
        if (!$category) $category = Category::find(1);
        $type = $category->types()->inRandomOrder()->get()->first();

        return [
            'title' => $title = fake()->word(),
            'condition' => fake()->word(),
            'brand_id' => $b = $brand->id,
            'category_id' => $c = $category->id,
            'type_id' => $t = $type->id,
            'ref' => [$b, $c, $t, $title]
        ];
    }
}
