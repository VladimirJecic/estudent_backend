<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CourseUser;
use Database\Factories\CourseUserFactory;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
        CourseUser::factory()->prepare();
        CourseUser::factory()->create();
    }
}
