<?php

namespace Database\Factories;

use App\estudent\domain\model\CourseInstance;
use App\estudent\domain\model\ExamPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class CourseExamFactory extends Factory
{
    protected $courseExamAssociations = [];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
     private static $courseExams;

    public function definition(): array
    {
        $courseExam = array_shift(self::$courseExams);
        $examPeriod = ExamPeriod::find($courseExam[1]);
                return [
                    'course_instance_id' => $courseExam[0],
                    'exam_period_id' => $courseExam[1],
                    'examDateTime' => $this->faker->dateTimeBetween($examPeriod->dateStart, $examPeriod->dateEnd),
                    'hall' => random_int(101,501), 
                ];
            
    }
    private function beforeCreate(){
        $courseIds = CourseInstance::pluck('id');
        $examPeriodIds = ExamPeriod::pluck('id');
        !property_exists($this, 'count') && $this->count(count($examPeriodIds) * count($courseIds));
        foreach( $courseIds as $c){
            foreach( $examPeriodIds as $e){
                self::$courseExams[]= [$c,$e];
            }
        }


    }
    public function create($attributes = [], ?Model $parent = null){
        self::beforeCreate();
        parent::create($attributes, $parent);
    }
}
