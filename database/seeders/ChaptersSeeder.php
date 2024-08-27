<?php

namespace Database\Seeders;

use App\Models\Chapters;
use Illuminate\Database\Seeder;

class ChaptersSeeder extends Seeder
{
    public function run(): void
    {
        Chapters::factory()->count(1000)->create();
    }
}
