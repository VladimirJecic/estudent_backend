<?php
namespace App\estudent\domain\ports\input;
use Illuminate\Support\Collection;
use App\estudent\domain\ports\input\model\ExamRegistrationFilters;

interface ExamPeriodService
{
    public function getAllExamPeriods(bool $onlyActive): Collection;

    public function active(): Collection;   
}