<?php
namespace App\Contracts\input;
use App\Contracts\input\model\CourseExamFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseExamService
{
    public function getAllCourseExamsWithFilters(CourseExamFilters $filters): LengthAwarePaginator;
}