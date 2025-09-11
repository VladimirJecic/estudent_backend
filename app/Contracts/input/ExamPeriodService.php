<?php
namespace App\Contracts\input;
use Illuminate\Support\Collection;
use App\Contracts\input\model\ExamRegistrationFilters;

interface ExamPeriodService
{
    public function getAllExamPeriods(bool $onlyActive): Collection;

    public function active(): Collection;   
}