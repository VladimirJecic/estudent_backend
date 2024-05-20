<?php
namespace Database\Factories;

use App\Models\ExamRegistration;
use App\Models\User;
use App\Models\Course;
use App\Models\ExamPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class ExamRegistrationFactory extends Factory
{
    public static $registrations = [];

    public function definition()
    {
        $registration = array_shift(self::$registrations);
        $admin = User::where('role', 'admin')->inRandomOrder()->first();
        $mark = $this->faker->numberBetween(5, 10);
        return [
            'course_id' => $registration[0],
            'exam_period_id' => $registration[1],
            'student_id' => $registration[2],
            'mark' => $mark,
            'signed_by_id' => $admin->id,
            'comment' => $mark > 5 ? 'položio' : 'pao',
        ];
    }
    private function beforeCreate(){
        $userIds = User::where('role','student')->pluck('id');
        $courseIds = Course::pluck('id');
        $examPeriodIds = ExamPeriod::pluck('id');
        !property_exists($this, 'count') && $this->count(count($examPeriodIds) * count($courseIds)*count($userIds));
        foreach( $courseIds as $c){
            foreach( $examPeriodIds as $e){
                foreach($userIds as $u){
                    self::$registrations[]= [$c,$e,$u];
                }
            }
        }


    }
    public function create($attributes = [], ?Model $parent = null){
        self::beforeCreate();
        parent::create($attributes, $parent);
    }
}
