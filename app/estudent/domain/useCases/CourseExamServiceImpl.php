<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\CourseExamService;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\ports\input\model\CourseExamFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseExamServiceImpl implements CourseExamService
{
    public function calculateAttendancePercentageForRegistrations($registrations): float
    {
        $total = $registrations->count();
        if ($total == 0) return 0.0;
        $attended = $registrations->filter(fn ($r) => $r->hasAttended)->count();
        return round((doubleval($attended) / $total) * 100, 2);
    }

    public function calculateAverageScoreForRegistrations($registrations): float
    {
        if ($registrations->isEmpty()) return 0.0;
        $attended = $registrations->filter(fn ($r) => $r->hasAttended);
        if ($attended->isEmpty()) return 0.0;
        return round($attended->avg('mark'), 2);
    }
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

    public function calculateAttendancePercentage(int $courseExamId): float
    {
        $courseExam = CourseExam::with('examRegistrations')->findOrFail($courseExamId);
        $registrations = $courseExam->examRegistrations;
        
        $total = $registrations->count();
        if ($total == 0) {
            return 0.0;
        }
        
        $attended = $registrations->filter(fn ($r) => $r->hasAttended)->count();
        return round((doubleval($attended) / $total) * 100, 2);
    }

    public function calculateAverageScore(int $courseExamId): float
    {
        $courseExam = CourseExam::with('examRegistrations')->findOrFail($courseExamId);
        $registrations = $courseExam->examRegistrations;
        $attendedRegistrations = $registrations->filter(fn ($r) => $r->hasAttended);
        
        if ($attendedRegistrations->isEmpty()) {
            return 0.0;
        }
        
        return round($attendedRegistrations->avg('mark') ?? 0.0, 2);
    }
}