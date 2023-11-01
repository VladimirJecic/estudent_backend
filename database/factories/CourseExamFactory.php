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
    public function definition(): array
    {
        $courses = Course::all();
        $examPeriods = ExamPeriod::all();

        
        
        foreach ($examPeriods as $examPeriod) {
             $halls = ['101', '102','103','104','105','201','202','203','204','205'];
            foreach ($courses as $course) {
                $this->courseExamAssociations[] = [
                    'course_id' => $course->id,
                    'exam_period_id' => $examPeriod->id,
                    'examDateTime' => $this->faker->dateTimeBetween($examPeriod->dateStart, $examPeriod->dateEnd),
                    'hall' => array_shift($halls), 
                ];
            }
        }
         return array_shift($this->courseExamAssociations);
    }
}
