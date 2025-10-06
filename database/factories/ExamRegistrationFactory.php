<?php
namespace Database\Factories;

use App\estudent\domain\model\User;
use Illuminate\Database\Eloquent\Factories\Factory;

namespace Database\Factories;

use App\estudent\domain\model\User;
use App\estudent\domain\model\CourseExam;
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

        return [
            'course_exam_id' => $registration[0],
            'student_id' => $registration[1],
            'mark' => $mark,
            'hasAttended' => true,
            'signed_by_id' => $admin?->id,
            'comment' => $mark > 5 ? 'položio' : 'pao',
        ];
    }

    private function beforeCreate()
    {
        $userIds = User::where('role', 'student')->pluck('id');
        $courseExamIds = CourseExam::pluck('id');

        if (!property_exists($this, 'count')) {
            $this->count(count($courseExamIds) * count($userIds));
        }

        foreach ($courseExamIds as $courseExamId) {
            foreach ($userIds as $studentId) {
                self::$registrations[] = [$courseExamId, $studentId];
            }
        }
    }

    public function create($attributes = [], ?Model $parent = null) {
        self::beforeCreate();
        parent::create($attributes, $parent);
    }
}

