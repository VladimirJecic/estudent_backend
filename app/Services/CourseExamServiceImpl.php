<?php 
namespace App\Services;

use App\Contracts\input\CourseExamService;
use App\Models\CourseExam;
use App\Contracts\input\model\CourseExamFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class CourseExamServiceImpl implements CourseExamService
{
    public function getAllCourseExamsWithFilters(CourseExamFilters $courseExamFilters):LengthAwarePaginator
    {
        $query = CourseExam::with(['courseInstance', 'examPeriod']);

        // Filters
        if ($courseExamFilters->courseName) {
            $courseName = $courseExamFilters->courseName;
            $query->whereHas('courseInstance.course', function ($q) use ($courseName) {
                $q->where('name', 'like', '%' . $courseName . '%');
            });
        }

        if ($courseExamFilters->dateFrom) {
            $query->whereDate('examDateTime', '>=', $courseExamFilters->dateFrom);
        }
        
        if ($courseExamFilters->dateTo) {
            $query->whereDate('examDateTime', '<=', $courseExamFilters->dateTo);
        }

        $query->orderBy('examDateTime', 'desc');

        return $query->paginate(perPage: $courseExamFilters->pageSize, page: $courseExamFilters->page);
        
    }
}