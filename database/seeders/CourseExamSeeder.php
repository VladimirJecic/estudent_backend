<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\estudent\domain\model\CourseExam;

class CourseExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         CourseExam::factory()->count(20)->create();
    }
}
