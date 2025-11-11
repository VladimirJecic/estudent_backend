<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\estudent\domain\model\ExamRegistration;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\User;
use App\estudent\domain\model\enums\SemesterSeason;

class ExamRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get winter 2025/2026 semester
        $winter20252026 = Semester::where('academic_year', '2025/2026')
            ->where('season', SemesterSeason::Winter)
            ->first();
        
        // Get the last 2 exam periods to exclude
        $lastTwoExamPeriods = ExamPeriod::where('semester_id', $winter20252026->id)
            ->orderBy('dateStart', 'desc')
            ->take(2)
            ->pluck('id');
        
        // Get course exams excluding the last 2 exam periods
        // Total: 21 (winter 2024/2025) + 70 (summer 2024/2025) + 7 (first winter 2025/2026) = 98 course exams
        $courseExamsCount = CourseExam::whereNotIn('exam_period_id', $lastTwoExamPeriods)->count();
        
        // Get number of students
        $studentsCount = User::where('role', 'student')->count();
        
        // Estimate: ~70% average registration rate per course exam
        $estimatedRegistrations = (int) ($courseExamsCount * $studentsCount * 0.7);
        
        // Create the registrations (factory handles the actual count)
        ExamRegistration::factory()->count($estimatedRegistrations)->create();
    }
}
