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

    public function withAreasOfInterest(int $count = 5): self
    {
        return $this->afterCreating(function (Topic $topic) use ($count) {
            $areasOfInterest = AreaOfInterest::query()->inRandomOrder()->take($count)->get();

            $topic->areasOfInterest()->attach($areasOfInterest);
        });
    }
}
