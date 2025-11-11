<?php

namespace Database\Factories;

use App\estudent\domain\model\CourseInstance;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\enums\SemesterSeason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class CourseExamFactory extends Factory
{
    protected $model = \App\estudent\domain\model\CourseExam::class;
    private static $courseExams = [];

    public function definition(): array
    {
        $courseExam = array_shift(self::$courseExams);
        $examPeriod = ExamPeriod::find($courseExam[1]);
        return [
            'course_instance_id' => $courseExam[0],
            'exam_period_id' => $courseExam[1],
            'examDateTime' => $this->faker->dateTimeBetween($examPeriod->dateStart, $examPeriod->dateEnd),
            'hall' => random_int(101, 501),
        ];
    }

    private function beforeCreate()
    {
        // Get winter 2024/2025 semester
        $winter20242025 = Semester::where('academic_year', '2024/2025')
            ->where('season', SemesterSeason::Winter)
            ->first();
        
        // Get summer 2024/2025 semester
        $summer20242025 = Semester::where('academic_year', '2024/2025')
            ->where('season', SemesterSeason::Summer)
            ->first();
        
        // Get winter 2025/2026 semester
        $winter20252026 = Semester::where('academic_year', '2025/2026')
            ->where('season', SemesterSeason::Winter)
            ->first();

        // Get exam periods for winter 2024/2025 (3 exam periods)
        $winterExamPeriods = ExamPeriod::where('semester_id', $winter20242025->id)->pluck('id');
        
        // Get exam periods for summer 2024/2025 (5 exam periods)
        $summerExamPeriods = ExamPeriod::where('semester_id', $summer20242025->id)->pluck('id');
        
        // Get exam periods for winter 2025/2026 (3 exam periods)
        $winter2026ExamPeriods = ExamPeriod::where('semester_id', $winter20252026->id)->pluck('id');

        // Get course instances for winter 2024/2025 (7 courses)
        $winterCourseInstances = CourseInstance::where('semester_id', $winter20242025->id)->pluck('id');
        
        // Get all course instances for academic year 2024/2025 (14 courses total)
        $allCourseInstances20242025 = CourseInstance::whereIn('semester_id', [$winter20242025->id, $summer20242025->id])->pluck('id');
        
        // Get course instances for winter 2025/2026 (7 courses)
        $winter2026CourseInstances = CourseInstance::where('semester_id', $winter20252026->id)->pluck('id');

        // Create course exams for winter 2024/2025: 3 exam periods * 7 courses = 21
        foreach ($winterExamPeriods as $examPeriodId) {
            foreach ($winterCourseInstances as $courseInstanceId) {
                self::$courseExams[] = [$courseInstanceId, $examPeriodId];
            }
        }

        // Create course exams for summer 2024/2025: 5 exam periods * 14 courses = 70
        // Students can take exams for both winter and summer courses during summer exam periods
        foreach ($summerExamPeriods as $examPeriodId) {
            foreach ($allCourseInstances20242025 as $courseInstanceId) {
                self::$courseExams[] = [$courseInstanceId, $examPeriodId];
            }
        }

        // Create course exams for winter 2025/2026: 3 exam periods * 7 courses = 21
        foreach ($winter2026ExamPeriods as $examPeriodId) {
            foreach ($winter2026CourseInstances as $courseInstanceId) {
                self::$courseExams[] = [$courseInstanceId, $examPeriodId];
            }
        }

        // Total: 21 + 70 + 21 = 112 course exams
    }

    public function create($attributes = [], ?Model $parent = null)
    {
        self::beforeCreate();
        return parent::create($attributes, $parent);
    }
}
