<?php
namespace App\estudent\domain\ports\input;
use App\estudent\domain\ports\input\model\CourseExamFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourseExamService
{
    public function getCourseExamsByFilters(CourseExamFilters $filters): LengthAwarePaginator;
    
    public function calculateAttendancePercentage(int $courseExamId): float;
    
    public function calculateAverageScore(int $courseExamId): float;
    
    public function getRegisterableCourseExams(): Collection;
    
    public function getRemainingCourseExams(int $examPeriodId): Collection;
}