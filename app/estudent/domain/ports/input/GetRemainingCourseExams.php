<?php
namespace App\estudent\domain\ports\input;
use Illuminate\Support\Collection;

interface GetRemainingCourseExams
{
    public function getRemainingCourseExams(int $examPeriodId): Collection;

}