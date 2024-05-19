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
    public static $userIds = [];
    public static $courseIds =[];
    public function definition(): array
    {
        return [
            'user_id' => array_shift(self::$userIds),
            'course_id' => array_shift(self::$courseIds),
        ];
    }
    private static function beforeCreate(){
        CourseUserFactory::$userIds = User::pluck('id');
        CourseUserFactory::$courseIds = Course::pluck('id');
        $createCount = count(self::$userIds) * count(self::$courseIds);
        self::count($createCount);
    }
    public function create($attributes = [], ?Model $parent = null){
        self::beforeCreate();
        parent::create($attributes, $parent);
    }


}
