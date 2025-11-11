<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\model\Semester;
use App\estudent\domain\model\enums\SemesterSeason;


class ExamPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $winter20242025Semester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Winter,
        ]);

        $summer20242025Semester = Semester::firstOrCreate([
            'academic_year' => '2024/2025',
            'season' => SemesterSeason::Summer,
        ]);
        $winter20252026Semester = Semester::firstOrCreate([
            'academic_year' => '2025/2026',
            'season' => SemesterSeason::Winter,
        ]);


        $examPeriods20242025 = [
            [
                'semester_id' => $winter20242025Semester->id,
                'dateRegisterStart' => '2024-10-30',
                'dateRegisterEnd' => '2024-11-14',
                'dateStart' => '2024-11-15',
                'dateEnd' => '2024-11-29',
                'name' => 'novembarsko-decembarski-apsolventski-2024'
            ],
            [
                'semester_id' => $winter20242025Semester->id,
                'dateRegisterStart' => '2025-01-02',
                'dateRegisterEnd' => '2025-01-09',
                'dateStart' => '2025-01-09',
                'dateEnd' => '2025-01-24',
                'name' => 'januar-2025'
            ],
            [
                'semester_id' => $winter20242025Semester->id,
                'dateRegisterStart' => '2025-01-18',
                'dateRegisterEnd' => '2025-01-24',
                'dateStart' => '2025-01-25',
                'dateEnd' => '2025-02-09',
                'name' => 'februar-2025'
            ],
            [
                'semester_id' => $summer20242025Semester->id,
                'dateRegisterStart' => '2025-04-11',
                'dateRegisterEnd' => '2025-04-18',
                'dateStart' => '2025-04-19',
                'dateEnd' => '2025-05-06',
                'name' => 'martovsko-aprilski-apsolventski-2025'
            ],
            [
                'semester_id' => $summer20242025Semester->id,
                'dateRegisterStart' => '2025-05-23',
                'dateRegisterEnd' => '2025-05-30',
                'dateStart' => '2025-06-06',
                'dateEnd' => '2025-06-20',
                'name' => 'jun-2025'
            ]
            ,
            [
                'semester_id' => $summer20242025Semester->id,
                'dateRegisterStart' => '2025-06-23',
                'dateRegisterEnd' => '2025-06-30',
                'dateStart' => '2025-07-06',
                'dateEnd' => '2025-07-20',
                'name' => 'jul-2025'
            ],
            [
                'semester_id' => $summer20242025Semester->id,
                'dateRegisterStart' => '2025-08-15',
                'dateRegisterEnd' => '2025-08-22',
                'dateStart' => '2025-09-01',
                'dateEnd' => '2025-09-14',
                'name' => 'septembar-2025'
            ],
            [
                'semester_id' => $summer20242025Semester->id,
                'dateRegisterStart' => '2025-09-01',
                'dateRegisterEnd' => '2025-09-14',
                'dateStart' => '2025-09-15',
                'dateEnd' => '2025-09-29',
                'name' => 'oktobar-2025'
            ]
        ];
        $examPeriods20252026 = [
            [
                'semester_id' => $winter20252026Semester->id,
                'dateRegisterStart' => '2025-10-30',
                'dateRegisterEnd' => '2025-11-14',
                'dateStart' => '2025-11-15',
                'dateEnd' => '2025-11-29',
                'name' => 'novembarsko-decembarski-apsolventski-2025'
            ],
            [
                'semester_id' => $winter20252026Semester->id,
                'dateRegisterStart' => '2026-01-02',
                'dateRegisterEnd' => '2026-01-09',
                'dateStart' => '2026-01-09',
                'dateEnd' => '2026-01-24',
                'name' => 'januar-2026'
            ],
            [
                'semester_id' => $winter20252026Semester->id,
                'dateRegisterStart' => '2026-01-18',
                'dateRegisterEnd' => '2026-01-24',
                'dateStart' => '2026-01-25',
                'dateEnd' => '2026-02-09',
                'name' => 'februar-2026'
            ]
        ];

        foreach ($examPeriods20242025 as $period) {
            ExamPeriod::firstOrCreate([
                'name' => $period['name'],
            ], $period);
        }
        foreach ($examPeriods20252026 as $period) {
            ExamPeriod::firstOrCreate([
                'name' => $period['name'],
            ], $period);
        }
    }
    
}


