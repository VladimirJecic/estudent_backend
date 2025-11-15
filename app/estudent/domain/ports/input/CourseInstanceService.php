<?php
namespace App\estudent\domain\ports\input;

use App\estudent\domain\ports\input\model\CourseInstanceFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseInstanceService
{
    public function getAllCourseInstancesWithFilters(CourseInstanceFilters $filters): LengthAwarePaginator;
}
