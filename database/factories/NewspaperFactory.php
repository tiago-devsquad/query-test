<?php

namespace Database\Factories;

use App\Models\AreaOfInterest;
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

    public function withAreasOfInterest(): self
    {
        return $this->afterCreating(function (NewsPaper $newspaper) {
            $areasOfInterest = AreaOfInterest::query()->inRandomOrder()->take(rand(1, 5))->get();

            $newspaper->areasOfInterest()->attach($areasOfInterest);
        });
    }
}
