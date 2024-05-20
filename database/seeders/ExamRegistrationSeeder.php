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
        ExamRegistration::factory()->count(30)->create();

    }
}
