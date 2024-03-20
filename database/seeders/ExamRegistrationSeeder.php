<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ExamRegistration;
use Database\Factories\ExamRegistrationFactory;

class ExamRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed the first 10 with random course_id
        ExamRegistration::factory()->count(10)->create();

        // Reset the counter with a random value
        ExamRegistrationFactory::$counter = rand(2, 10);

        // Seed the next 10, using the counter modulo 11
        ExamRegistration::factory()->count(10)->create();
    }
}
