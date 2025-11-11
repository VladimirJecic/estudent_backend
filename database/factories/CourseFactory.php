<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\estudent\domain\model\Semester;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\estudent\domain\model\CourseInstance>
 */
class CourseFactory extends Factory
{
    protected $model = \App\estudent\domain\model\Course::class;
    protected $exams = [ 'Matematika1',
                    'Matematika2', 
                    'Informacioni Sistemi i Tehnologije',
                    'Osnovi Organizacije',
                    'Marketing',
                    'Menadzment',
                    'Inteligentni Sistemi',
                    'Statistika',
                    'Verovatnoca',
                    'Programiranje 1',
                    'Programiranje 2',
                    'Strukture Podataka i Algoritmi',
                    'Internet Tehnologije',
                    'Projektovanje Softvera',
                   ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $espb = [4,5,6,7];
        return [
            'name' =>  array_shift($this->exams),
            'espb' => $this->faker->randomElement($espb),
        ];
    }
}
