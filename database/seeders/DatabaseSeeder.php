<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(1000)->make();

        User::query()->insert($users->toArray());

        $this->call([
            AreaOfInterestSeeder::class,
            NewspaperSeeder::class,
            TopicSeeder::class,
            ChapterSeeder::class,
            CaseStudySeeder::class,
            PageTrackerSeeder::class,
        ]);
    }
}
