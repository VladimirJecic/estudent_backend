<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Enums\SemesterSeason;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Winter,
        ]);

        Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Summer,
        ]);
    }
}
