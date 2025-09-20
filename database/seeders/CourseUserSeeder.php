<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\estudent\domain\model\CourseUser;
use Database\Factories\CourseUserFactory;
use App\estudent\domain\model\User;
use App\estudent\domain\model\CourseInstance;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
      //  CourseUser::factory()->create();
      $userIds = User::pluck('id');
      $courseIds = CourseInstance::pluck('id');

      foreach ($userIds as $userId) {
          foreach ($courseIds as $courseId) {
              CourseUser::firstOrCreate([
                  'user_id' => $userId,
                  'course_instance_id' => $courseId,
              ]);
          }
      }
    }
}
