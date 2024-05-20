<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseUser>
 */
class CourseUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static $courseUsers = [];

    public function definition(): array
    {
        $courseuser = array_shift(self::$courseUsers);

        return [
            'user_id' => $courseuser[0],
            'course_id' => $courseuser[1],
        ];
    }
        private  function beforeCreate(){
            $userIds = User::pluck('id');
            $courseIds = Course::pluck('id');
            !property_exists($this, 'count') && $this->count(count($userIds) * count($courseIds));
            foreach( $userIds as $u){
                foreach( $courseIds as $c){
                    self::$courseUsers[]=([$u,$c]);
                }
            }

        }
    public function create($attributes = [], ?Model $parent = null){
        self::beforeCreate();
        parent::create($attributes, $parent);
    }


}
