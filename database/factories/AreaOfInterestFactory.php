<?php

namespace Database\Factories;

use App\Models\AreaOfInterest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AreaOfInterest>
 */
class AreaOfInterestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word,
        ];
    }
}
