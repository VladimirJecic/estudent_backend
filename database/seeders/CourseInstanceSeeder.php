<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\estudent\domain\model\Course;
use App\estudent\domain\model\CourseInstance;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\enums\SemesterSeason;

class CourseInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $winter20242025Semester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Winter,
        ]);

        $summer20242025Semester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Summer,
        ]);
        $winter20252026Semester = Semester::firstOrCreate([
            'academic_year' => '2025/2026',
            'season' => SemesterSeason::Winter,
        ]);

        $summer20252026Semester = Semester::firstOrCreate([
            'academic_year' => '2025/2026',
            'season' => SemesterSeason::Summer,
        ]);

        $courses = Course::all();

        // Shuffle courses randomly
        $courses = $courses->shuffle();
    
        // Split half for winter, half for summer
        $chunks = $courses->chunk($courses->count() / 2);
        foreach ($chunks[0] as $course) {
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $winter20242025Semester->id,
            ]);
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $winter20252026Semester->id,
            ]);
        }
    
        foreach ($chunks[1] as $course) {
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $summer20242025Semester->id,
            ]);
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $summer20252026Semester->id,
            ]);
        }
    }
}
