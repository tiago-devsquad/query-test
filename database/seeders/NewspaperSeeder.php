<?php

namespace Database\Seeders;

use App\Models\Newspaper;
use Illuminate\Database\Seeder;

class NewspaperSeeder extends Seeder
{
    public function run(): void
    {
        Newspaper::factory()->count(250)->create();
    }
}
