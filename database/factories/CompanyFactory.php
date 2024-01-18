<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ice' => '000189568000063',
            'title' => 'Hôtel Université Mohammed VI Polytechnique Rabat',
            'address' => 'Rocade Salé',
            'phone' => '0530221420',
            'email' => 'um6photel@phl.ma',
            'fax' => null,
            'logo' => 'logo.png',
            'colors' => '{ "primary": "rgba(220, 20, 60, 1)", "secondary": "rgba(220, 20, 60, 0.3)" }'
        ];
    }
}
