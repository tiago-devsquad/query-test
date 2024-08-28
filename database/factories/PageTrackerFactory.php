<?php

namespace Database\Factories;

use App\Models\CaseStudy;
use App\Models\Chapter;
use App\Models\Newspaper;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageTrackerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'time_spent' => fake()->numberBetween(10, 1000),
            'url'        => fake()->url,
        ];
    }

    public function withMetadata(array $metadata): self
    {
        return $this->state(fn (array $attributes) => ['metadata' => $metadata]);
    }

    public function randTopics(): self
    {
        return $this->state(function () {
            $topic = Topic::query()->inRandomOrder()->first();

            return [
                'trackable_type' => Topic::class,
                'trackable_id'   => $topic->id,
                'url'            => route('topic', $topic),
            ];
        });
    }

    public function randChapters(): self
    {
        return $this->state(function () {
            $chapter = Chapter::query()->inRandomOrder()->first();

            return [
                'trackable_type' => Chapter::class,
                'trackable_id'   => $chapter->id,
                'url'           => route('chapter', $chapter),
            ];
        });
    }

    public function randCases(): self
    {
        return $this->state(function () {
            $case = CaseStudy::query()->inRandomOrder()->first();

            return [
                'trackable_type' => CaseStudy::class,
                'trackable_id'   => $case->id,
                'url'            => route('case-study', $case),
            ];
        });
    }

    public function randNewspapers(): self
    {
        return $this->state(function () {
            $newspaper = Newspaper::query()->inRandomOrder()->first();

            return [
                'trackable_type' => Newspaper::class,
                'trackable_id'   => $newspaper->id,
                'url'            => route('newspaper', $newspaper),
            ];
        });
    }
}
