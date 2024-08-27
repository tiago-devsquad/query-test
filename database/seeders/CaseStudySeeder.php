<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        CaseStudy::factory()->count(1000)->withAreasOfInterest()->create();
    }
}
