<?php
namespace App\estudent\domain\ports\input;
use Illuminate\Support\Collection;
use App\estudent\domain\ports\input\model\ExamPeriodFilters;

interface ExamPeriodService
{
    public function getAllExamPeriodsWithFilters(ExamPeriodFilters $filters): Collection;

    public function active(): Collection;   
}