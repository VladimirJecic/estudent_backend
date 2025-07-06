<?php
namespace App\Contracts\input;
use Illuminate\Support\Collection;

interface GetRemainingCourseExams
{
    public function getRemainingCourseExams(int $examPeriodId): Collection;

}