<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseExam;

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
