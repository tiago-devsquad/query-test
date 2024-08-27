<?php

namespace Database\Factories;

use App\Models\AreaOfInterest;
use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseStudy>
 */
class CaseStudyFactory extends Factory
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
        return $this->afterCreating(function (CaseStudy $case) use ($count) {
            $areasOfInterest = AreaOfInterest::query()->inRandomOrder()->take($count)->get();

            $case->areasOfInterest()->attach($areasOfInterest);
        });
    }
}
