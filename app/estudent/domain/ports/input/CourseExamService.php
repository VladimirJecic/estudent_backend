<?php
namespace App\estudent\domain\ports\input;
use App\estudent\domain\ports\input\model\CourseExamFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseExamService
{
    public function getAllCourseExamsWithFilters(CourseExamFilters $filters): LengthAwarePaginator;
    
    public function calculateAttendancePercentage(int $courseExamId): float;
    
    public function calculateAverageScore(int $courseExamId): float;
}