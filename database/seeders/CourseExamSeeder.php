<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\estudent\domain\model\CourseExam;

class CourseExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Total course exams: 21 (winter 2024/2025) + 70 (summer 2024/2025) + 21 (winter 2025/2026) = 112
        CourseExam::factory()->count(112)->create();
    }
}
