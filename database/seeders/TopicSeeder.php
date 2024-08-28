<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        Topic::factory()->count(1000)->withAreasOfInterest()->create();
    }
}
