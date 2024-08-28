<?php

namespace Database\Factories;

use App\Models\AreaOfInterest;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
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
        return $this->afterCreating(function (Topic $topic) {
            $areasOfInterest = AreaOfInterest::query()->inRandomOrder()->take(rand(1, 5))->get();

            $topic->areasOfInterest()->attach($areasOfInterest);
        });
    }
}
