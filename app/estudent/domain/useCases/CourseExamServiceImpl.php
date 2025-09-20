<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\CourseExamService;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\ports\input\model\CourseExamFilters;
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