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
        if ($courseExamFilters->searchText) {
            $searchText = $courseExamFilters->searchText;
            $query->where(function ($q) use ($searchText) {
                $q->whereHas('courseInstance.course', function ($courseQ) use ($searchText) {
                    $courseQ->where('name', 'like', '%' . $searchText . '%');
                })
                ->orWhereHas('examPeriod', function ($periodQ) use ($searchText) {
                    $periodQ->where('name', 'like', '%' . $searchText . '%');
                });
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