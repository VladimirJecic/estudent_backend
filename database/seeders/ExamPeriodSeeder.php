<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamPeriod;
use App\Models\Semester;
use App\Enums\SemesterSeason;


class ExamPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $winterSemester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Winter,
        ]);

        $summerSemester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Summer,
        ]);

        $examPeriods = [
            [
                'semester_id' => $winterSemester->id,
                'dateRegisterStart' => '2024-10-30',
                'dateRegisterEnd' => '2024-11-14',
                'dateStart' => '2024-11-15',
                'dateEnd' => '2024-11-29',
                'name' => 'novembarsko-decembarski-apsolventski-2024'
            ],
            [
                'semester_id' => $winterSemester->id,
                'dateRegisterStart' => '2025-01-02',
                'dateRegisterEnd' => '2025-01-09',
                'dateStart' => '2025-01-09',
                'dateEnd' => '2025-01-24',
                'name' => 'januar-2025'
            ],
            [
                'semester_id' => $winterSemester->id,
                'dateRegisterStart' => '2025-01-18',
                'dateRegisterEnd' => '2025-01-24',
                'dateStart' => '2025-01-25',
                'dateEnd' => '2025-02-09',
                'name' => 'februar-2025'
            ],
            [
                'semester_id' => $summerSemester->id,
                'dateRegisterStart' => '2025-04-11',
                'dateRegisterEnd' => '2025-04-18',
                'dateStart' => '2025-04-19',
                'dateEnd' => '2025-05-06',
                'name' => 'martovsko-aprilski-apsolventski-2025'
            ],
            [
                'semester_id' => $summerSemester->id,
                'dateRegisterStart' => '2025-06-23',
                'dateRegisterEnd' => '2025-10-06',
                'dateStart' => '2025-07-06',
                'dateEnd' => '2025-10-20',
                'name' => 'jun-2025'
            ]
        ];

        foreach ($examPeriods as $period) {
            ExamPeriod::firstOrCreate([
                'name' => $period['name'],
            ], $period);
        }
    }
    
}


