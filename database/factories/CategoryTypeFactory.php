<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryType>
 */
class CategoryTypeFactory extends Factory
{

    protected $model = CategoryType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::whereHas('types')->inRandomOrder()->first();
        return [
            'category_id' => $category->id,
            'type_id' => $category->types()->inRandomOrder()->first()->id,
        ];
    }
}
