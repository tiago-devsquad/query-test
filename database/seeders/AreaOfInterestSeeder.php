<?php

namespace Database\Seeders;

use App\Models\AreaOfInterest;
use Illuminate\Database\Seeder;

class AreaOfInterestSeeder extends Seeder
{
    public function run(): void
    {
        $interests = [
            "Cardiology",
            "Neurology",
            "Oncology",
            "Pediatrics",
            "Orthopedics",
            "Dermatology",
            "Radiology",
            "Surgery",
            "Endocrinology",
            "Psychiatry",
            "Gastroenterology",
            "Hematology",
            "Immunology",
            "Nephrology",
            "Obstetrics and Gynecology",
            "Ophthalmology",
            "Pathology",
            "Rheumatology",
            "Pulmonology",
            "Urology",
            "Infectious Diseases",
            "Emergency Medicine",
            "Anesthesiology",
            "Public Health",
            "Rehabilitation Medicine",
            "Geriatrics",
            "Genetics",
            "Allergy and Immunology",
            "Critical Care Medicine",
            "Sleep Medicine",
            "Occupational Medicine",
            "Palliative Care",
            "Sports Medicine",
            "Nuclear Medicine",
            "Vascular Medicine",
            "Pain Management",
            "Addiction Medicine",
            "Clinical Pharmacology",
            "Medical Toxicology",
            "Family Medicine",
            "Preventive Medicine",
            "Tropical Medicine",
            "Clinical Research",
            "Medical Education"
        ];

        $i = collect($interests)->map(fn (string $interest) => [
            'name' => $interest,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AreaOfInterest::query()->insert($i->all());
    }
}
