<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\CourseExamService;
use App\estudent\domain\ports\input\ExamPeriodService;
use App\estudent\domain\ports\input\ExamRegistrationService;
use App\estudent\domain\ports\input\GetReportForCourseExam;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\model\ExamRegistration;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\ports\input\model\CourseExamFilters;
use App\estudent\domain\ports\output\GenerateCourseExamReport;
use App\estudent\domain\ports\output\model\CourseExamReportDTO;
use App\estudent\domain\ports\output\model\CourseExamReportItemDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourseExamServiceImpl implements CourseExamService, GetReportForCourseExam
{
    private readonly ExamPeriodService $examPeriodService;
    private readonly ExamRegistrationService $examRegistrationService;
    private readonly GenerateCourseExamReport $excelGeneratorService;

    public function __construct(ExamPeriodService $examPeriodService, ExamRegistrationService $examRegistrationService, GenerateCourseExamReport $excelGeneratorService)
    {
        $this->examPeriodService = $examPeriodService;
        $this->examRegistrationService = $examRegistrationService;
        $this->excelGeneratorService = $excelGeneratorService;
    }
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
    public function getCourseExamsByFilters(CourseExamFilters $courseExamFilters):LengthAwarePaginator
    {
        $query = CourseExam::with(['courseInstance', 'examPeriod']);
        $this->applyCourseExamFilters($query, $courseExamFilters);
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

    public function getRemainingCourseExams(int $examPeriodId): Collection
    {
        $examPeriod = ExamPeriod::with('exams.courseInstance')->findOrFail($examPeriodId);
        
        $student = auth()->user();
        
        $enrolledCourses = $student->courseInstances()->pluck('id')->toArray();

        $courseExams_where_enrolled = $examPeriod->exams()->get()->reject(fn ($courseExam)=>
            !in_array($courseExam->courseInstance->id,$enrolledCourses)
        );

        $examAttempts = ExamRegistration::with('student','courseExam.courseInstance')
        ->where('student_id', $student->id)->get();

        $successfulAttempts = $examAttempts->count() > 0 ? $examAttempts->whereBetween('mark', 
        [6, 10])->pluck('courseExam.courseInstance.course_id')->toArray() : [];

        $courseExams_remaining =  $courseExams_where_enrolled->reject(fn ($courseExam) => 
            in_array($courseExam->courseInstance->course_id, $successfulAttempts)
        );
        return $courseExams_remaining;
    }

    public function getRegisterableCourseExams(): Collection
    {
        $student = auth()->user();
        $activeExamPeriods = $this->examPeriodService->active();
        if ($activeExamPeriods->isEmpty()) {
            return collect();
        }

        $allRemainingCourseExams = collect();
        foreach ($activeExamPeriods as $examPeriod) {
            $remaining = $this->getRemainingCourseExams($examPeriod->id);
            $allRemainingCourseExams = $allRemainingCourseExams->merge($remaining);
        }

        $currentExamRegistrations = $this->examRegistrationService->getCurrentExamRegistrations();
        $currentExamRegistrationsIds = collect($currentExamRegistrations->items())
            ->pluck('course_exam_id')
            ->toArray();
        $registerableCourseExams = $allRemainingCourseExams->reject(
            fn ($courseExam) => in_array($courseExam->id, $currentExamRegistrationsIds)
        );

        return $registerableCourseExams;
    }

    public function getReportForCourseExam(int $courseExamId): BinaryFileResponse
    {
        $courseExam = CourseExam::with(['courseInstance.course','examRegistrations.student'])
            ->where('id', $courseExamId)
            ->firstOrFail();

        $courseExamReportDTO = new CourseExamReportDTO($courseExam);
        $courseExamReportDTO->reportItemList = array_map(
            fn(ExamRegistration $examRegistration) => new CourseExamReportItemDTO($examRegistration),
            $courseExam->examRegistrations->all()
        );

        $courseExamReportDTO->attendancePercentage = $this->calculateAttendancePercentage($courseExamId);
        $courseExamReportDTO->averageScore = $this->calculateAverageScore($courseExamId);
        
        return $this->excelGeneratorService->generateCourseExamReport($courseExamReportDTO);
    }

    /**
     * Applies filters to the CourseExam query based on the given filters object.
     */
    private function applyCourseExamFilters($query, CourseExamFilters $courseExamFilters): void
    {
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
    }
}