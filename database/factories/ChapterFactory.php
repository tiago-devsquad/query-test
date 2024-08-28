<?php

namespace Database\Factories;

use App\Models\AreaOfInterest;
use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapter>
 */
class ChapterFactory extends Factory
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
        return $this->afterCreating(function (Chapter $chapter) {
            $areasOfInterest = AreaOfInterest::query()->inRandomOrder()->take(rand(1, 5))->get();

            $chapter->areasOfInterest()->attach($areasOfInterest);
        });
    }
}
