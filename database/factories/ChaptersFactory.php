<?php

namespace Database\Factories;

use App\Models\Chapters;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapters>
 */
class ChaptersFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->text(255),
            'description' => fake()->paragraph,
        ];
    }
}
