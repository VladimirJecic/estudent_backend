<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\ExamPeriod;
use App\Models\CourseExam;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CourseExamFactory extends Factory
{
    protected $courseExamAssociations = [];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     private static $halls = range(101, 301);
     private static $courses = [];
     private  static $examPeriods = ExamPeriod::pluck('id');

     private static $currentExamPeriod;

    public function definition(): array
    {
        if(count(self::$courses)==0){
            self::$courses = Course::pluck('id');
            self::$currentExamPeriod = array_shift(self::$examPeriods);
        } 
                return [
                    'course_id' => array_shift(self::$courses),
                    'exam_period_id' => self::$currentExamPeriod->id,
                    'examDateTime' => $this->faker->dateTimeBetween(self::$currentExamPeriod->dateStart, self::$currentExamPeriod->dateEnd),
                    'hall' => array_shift(self::$halls), 
                ];
            
    }
}
