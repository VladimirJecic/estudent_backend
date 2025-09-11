<?php 
namespace App\Services;
use App\Models\ExamRegistration;
use App\Contracts\input\GetRegisterableCourseExams;
use App\Contracts\input\GetRemainingCourseExams;
use App\Contracts\input\ExamPeriodService;
use Illuminate\Support\Collection;
class GetRegisterableCourseExamsImpl implements GetRegisterableCourseExams
{

    private readonly ExamPeriodService $examPeriodService;
    private readonly GetRemainingCourseExams $getRemainingCourseExamsService;
    public function __construct(ExamPeriodService $examPeriodService, GetRemainingCourseExams $getRemainingCourseExamsService )
    {
        $this->examPeriodService = $examPeriodService;
        $this->getRemainingCourseExamsService = $getRemainingCourseExamsService;
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
            $remaining = $this->getRemainingCourseExamsService->getRemainingCourseExams($examPeriod->id);
            $allRemainingCourseExams = $allRemainingCourseExams->merge($remaining);
        }

        // Get all exam registrations for the student in any active exam period
        $activeExamPeriodIds = $activeExamPeriods->pluck('id')->toArray();
        $examRegistrationsForActivePeriods = ExamRegistration::where('student_id', $student->id)
            ->whereHas('courseExam.examPeriod', function ($query) use ($activeExamPeriodIds) {
                $query->whereIn('id', $activeExamPeriodIds);
            })->get();

        $registeredIds = $examRegistrationsForActivePeriods->pluck('course_exam_id')->toArray();
        $registerableCourseExams = $allRemainingCourseExams->reject(
            fn ($courseExam) => in_array($courseExam->id, $registeredIds)
        );

        return $registerableCourseExams;
    }
}
