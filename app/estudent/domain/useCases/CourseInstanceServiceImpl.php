<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\CourseInstanceService;
use App\estudent\domain\model\CourseInstance;
use App\estudent\domain\ports\input\model\CourseInstanceFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseInstanceServiceImpl implements CourseInstanceService
{
    public function getAllCourseInstancesWithFilters(CourseInstanceFilters $courseInstanceFilters): LengthAwarePaginator
    {
        $query = CourseInstance::with(['course', 'semester']);

        // Filters
        if ($courseInstanceFilters->searchText) {
            $searchText = $courseInstanceFilters->searchText;
            $query->where(function ($q) use ($searchText) {
                $q->whereHas('course', function ($courseQ) use ($searchText) {
                    $courseQ->where('name', 'like', '%' . $searchText . '%');
                })
                ->orWhereHas('semester', function ($semesterQ) use ($searchText) {
                    $semesterQ->where('academic_year', 'like', '%' . $searchText . '%');
                });
            });
        }

        if ($courseInstanceFilters->semesterId) {
            $query->where('semester_id', $courseInstanceFilters->semesterId);
        }

        $query->join('semesters', 'course_instances.semester_id', '=', 'semesters.id')
              ->orderBy('semesters.id', 'desc')
              ->select('course_instances.*');

        return $query->paginate(perPage: $courseInstanceFilters->pageSize, page: $courseInstanceFilters->page);
    }
}
