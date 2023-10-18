<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
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
                    'Projektovanje Informacionih Sistema'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $semester = [1, 2, 3, 4, 5, 6, 7, 8];
        $espb = [4,5,6,7];
        return [
            'name' =>  array_shift($this->exams),
            'semester' => $this->faker->randomElement($semester),
            'espb' => $this->faker->randomElement($espb),
        ];
    }
}
