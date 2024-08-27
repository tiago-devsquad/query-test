<?php

namespace Database\Seeders;

use App\Models\PageTracker;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class PageTrackerSeeder extends Seeder
{
    public function run(): void
    {
        User::query()
            ->chunkById(50, function (Collection $users) {
                $users->each(function (User $user) {
                    PageTracker::factory()->count(rand(10, 30))->for($user)->randTopics()->create();
                    PageTracker::factory()->count(rand(10, 30))->for($user)->randChapters()->create();
                    PageTracker::factory()->count(rand(10, 30))->for($user)->randCases()->create();
                    PageTracker::factory()->count(rand(10, 30))->for($user)->randNewspapers()->create();
                });
            });
    }
}
