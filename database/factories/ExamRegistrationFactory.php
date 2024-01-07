<?php
namespace Database\Factories;

use App\Models\ExamRegistration;
use App\Models\User;
use App\Models\CourseExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamRegistrationFactory extends Factory
{
    protected $model = ExamRegistration::class;

    public static $counter = 1;

    public function definition()
    {
        $admin = User::where('role', 'admin')->inRandomOrder()->first();
        $student = User::inRandomOrder()->where('role', 'student')->first();
        $courseExam = CourseExam::inRandomOrder()->first();
        $mark = $this->faker->numberBetween(5, 10);

        return [
            'student_id' => $student->id,
            'course_id' => (($courseExam->course_id + self::$counter) % 10)+1,
            'exam_period_id' => $courseExam->exam_period_id,
            'mark' => $mark,
            'signed_by_id' => $admin->id,
            'comment' => $mark > 5 ? 'položio' : 'pao',
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function () {
            self::$counter++;
        });
    }
}
