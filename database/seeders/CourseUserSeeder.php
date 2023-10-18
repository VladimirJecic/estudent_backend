<?php

namespace Database\Seeders;
use App\Models\CourseUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\CourseUserFactory;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
     CourseUserFactory::new()->enrollAllUsersInAllCourses();
    }
}
