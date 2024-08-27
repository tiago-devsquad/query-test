<?php

namespace Database\Factories;

use App\Models\Newspaper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Newspaper>
 */
class NewspaperFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->text(255),
            'description' => fake()->paragraph,
        ];
    }
}
