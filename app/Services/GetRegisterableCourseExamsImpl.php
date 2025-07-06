<?php 
namespace App\Services;
use App\Models\ExamRegistration;
use App\Contracts\input\GetRegisterableCourseExams;
use App\Contracts\input\GetRemainingCourseExams;

use Illuminate\Support\Collection;
class GetRegisterableCourseExamsImpl implements GetRegisterableCourseExams
{

    private readonly GetRemainingCourseExams $getRemainingCourseExamsService;
    public function __construct(GetRemainingCourseExams $getRemainingCourseExamsService )
    {
        $this->getRemainingCourseExamsService = $getRemainingCourseExamsService;
    }
    public function getRegisterableCourseExams(int $examPeriodId): Collection
    {
        $student = auth()->user();
        $remainingCourseExams = $this->getRemainingCourseExamsService->getRemainingCourseExams($examPeriodId);
        $examRegistrationsForExamPeriod = ExamRegistration::where('student_id', $student->id)
        ->whereHas('courseExam', function ($query) use ($examPeriodId) {
            $query->where('exam_period_id', $examPeriodId);
        })->get();
        $registerableCourseExams  = $remainingCourseExams->reject(fn ($courseExam) =>
        in_array($courseExam->id,$examRegistrationsForExamPeriod->pluck('course_exam_id')->toArray())
        );
        return $registerableCourseExams;
    }
}
