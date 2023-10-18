<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CourseUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
        ];
    }
   public function enrollAllUsersInAllCourses()
    {
        $enrollments = [];
        $userIds = User::pluck('id');
        $courseIds = Course::pluck('id');

        foreach ($userIds as $userId) {
            foreach ($courseIds as $courseId) {
                // Check if this user is already enrolled in this course
                if (!isset($enrollments[$userId][$courseId])) {
                    $enrollments[$userId][$courseId] = true;

                    $this->create([
                        'user_id' => $userId,
                        'course_id' => $courseId,
                    ]);
                }
            }
        }
    }
}
