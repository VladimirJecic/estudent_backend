<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\enums\SemesterSeason;

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
        Semester::firstOrCreate([
            'academic_year' => '2025/2026',
            'season' => SemesterSeason::Winter,
        ]);
        Semester::firstOrCreate([
            'academic_year' => '2025/2026',
            'season' => SemesterSeason::Summer,
        ]);
    }
}
