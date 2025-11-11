<?php
namespace Database\Factories;

use App\estudent\domain\model\User;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\enums\SemesterSeason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class ExamRegistrationFactory extends Factory
{
    protected $model = \App\estudent\domain\model\ExamRegistration::class;
    public static $registrations = [];

    public function definition(): array
    {
        $registration = array_shift(self::$registrations);
        $admin = User::where('role', 'admin')->inRandomOrder()->first();

        $mark = $this->faker->numberBetween(5, 10);
        
        // If mark is 5, sometimes student didn't attend (20% chance)
        $hasAttended = $mark == 5 ? $this->faker->boolean(80) : true;

        return [
            'course_exam_id' => $registration[0],
            'student_id' => $registration[1],
            'mark' => $mark,
            'hasAttended' => $hasAttended,
            'signed_by_id' => $admin?->id,
            'comment' => !$hasAttended ? 'nije se pojavio' : ($mark > 5 ? 'položio' : 'pao'),
        ];
    }

    private function beforeCreate()
    {
        $students = User::where('role', 'student')->pluck('id');
        
        // Get winter 2025/2026 semester
        $winter20252026 = Semester::where('academic_year', '2025/2026')
            ->where('season', SemesterSeason::Winter)
            ->first();
        
        // Get the last 2 exam periods (winter 2025/2026: januar and februar 2026)
        $lastTwoExamPeriods = ExamPeriod::where('semester_id', $winter20252026->id)
            ->orderBy('dateStart', 'desc')
            ->take(2)
            ->pluck('id');
        
        // Get all course exams except those in the last 2 exam periods
        $courseExams = CourseExam::whereNotIn('exam_period_id', $lastTwoExamPeriods)->get();

        // For each course exam, randomly register 60-80% of students
        foreach ($courseExams as $courseExam) {
            $shuffledStudents = $students->shuffle();
            
            // Randomly select 60-80% of students to register
            $registrationRate = $this->faker->numberBetween(60, 80) / 100;
            $numStudentsToRegister = (int) ceil($students->count() * $registrationRate);
            
            $registeredStudents = $shuffledStudents->take($numStudentsToRegister);
            
            foreach ($registeredStudents as $studentId) {
                self::$registrations[] = [$courseExam->id, $studentId];
            }
        }
    }

    public function create($attributes = [], ?Model $parent = null) {
        self::beforeCreate();
        return parent::create($attributes, $parent);
    }
}

