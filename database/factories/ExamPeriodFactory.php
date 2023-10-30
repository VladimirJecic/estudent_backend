<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamPeriodFactory extends Factory
{
    protected $examPeriods = [
    [
        'dateRegisterStart' => '2023-10-30',
        'dateRegisterEnd' => '2024-11-14',
        'dateStart' => '2023-11-15',
        'dateEnd' => '2023-11-29',
        'name' => 'novembar-decembar-apsolventski-2023'
    ],
    [
        'dateRegisterStart' => '2024-01-02',
        'dateRegisterEnd' => '2024-01-09',
        'dateStart' => '2024-01-09',
        'dateEnd' => '2024-01-24',
        'name' => 'januar-2024'
    ],
    [
        'dateRegisterStart' => '2024-01-18',
        'dateRegisterEnd' => '2024-01-24',
        'dateStart' => '2024-01-25',
        'dateEnd' => '2024-02-09',
        'name' => 'februar-2024'
    ],
    [
        'dateRegisterStart' => '2024-04-11',
        'dateRegisterEnd' => '2024-04-18',
        'dateStart' => '2024-04-19',
        'dateEnd' => '2025-05-06',
        'name' => 'mart-april-apsolventski-2024'
    ]
];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $exam_period = array_shift($this->examPeriods);
        return [
            'dateRegisterStart' => $exam_period['dateRegisterStart'],
            'dateRegisterEnd' => $exam_period['dateRegisterEnd'],
            'dateStart' => $exam_period['dateStart'],
            'dateEnd' => $exam_period['dateEnd'],
            'name' => $exam_period['name'],
        ];
    }
}
