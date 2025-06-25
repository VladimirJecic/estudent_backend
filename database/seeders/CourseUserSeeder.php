<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CourseUser;
use Database\Factories\CourseUserFactory;
use App\Models\User;
use App\Models\Course;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
      //  CourseUser::factory()->create();
      $userIds = User::pluck('id');
      $courseIds = Course::pluck('id');

      foreach ($userIds as $userId) {
          foreach ($courseIds as $courseId) {
              CourseUser::firstOrCreate([
                  'user_id' => $userId,
                  'course_id' => $courseId,
              ]);
          }
      }
    }
}
