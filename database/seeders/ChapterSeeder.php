<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        Chapter::factory()->count(1000)->withAreasOfInterest()->create();
    }
}
