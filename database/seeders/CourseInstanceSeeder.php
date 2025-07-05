<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseInstance;
use App\Models\Semester;
use App\Enums\SemesterSeason;

class CourseInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $winterSemester = Semester::where([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Winter,
        ])->first();
        $summerSemester = Semester::where([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Summer,
        ])->first();
        $courses = Course::all();

        // Shuffle courses randomly
        $courses = $courses->shuffle();
    
        // Split half for winter, half for summer
        $chunks = $courses->chunk($courses->count() / 2);
        foreach ($chunks[0] as $course) {
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $winterSemester->id,
            ]);
        }
    
        foreach ($chunks[1] as $course) {
            CourseInstance::firstOrCreate([
                'course_id' => $course->id,
                'semester_id' => $summerSemester->id,
            ]);
        }
    }
}
