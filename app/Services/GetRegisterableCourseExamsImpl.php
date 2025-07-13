<?php 
namespace App\Services;
use App\Models\ExamRegistration;
use App\Contracts\input\GetRegisterableCourseExams;
use App\Contracts\input\GetRemainingCourseExams;
use App\Services\ExamPeriodServiceImpl;
use Illuminate\Support\Collection;
class GetRegisterableCourseExamsImpl implements GetRegisterableCourseExams
{

    private readonly ExamPeriodServiceImpl $examPeriodService;
    private readonly GetRemainingCourseExams $getRemainingCourseExamsService;
    public function __construct(ExamPeriodServiceImpl $examPeriodService, GetRemainingCourseExams $getRemainingCourseExamsService )
    {
        $this->examPeriodService = $examPeriodService;
        $this->getRemainingCourseExamsService = $getRemainingCourseExamsService;
    }
    public function getRegisterableCourseExams(int $examPeriodId): Collection
    {
        $student = auth()->user();
        $registerableCourseExams = $this->examPeriodService->registerable();
        if($registerableCourseExams->isEmpty()) {
            return collect();
        }
        $remainingCourseExams = $this->getRemainingCourseExamsService->getRemainingCourseExams($examPeriodId);
        $examRegistrationsForExamPeriod = ExamRegistration::where('student_id', $student->id)
        ->whereHas('courseExam.examPeriod', function ($query) use ($examPeriodId) {
            $query->where([
                            ['id', '=', $examPeriodId],
                        ]);

        })->get();
         $registeredIds = $examRegistrationsForExamPeriod->pluck('course_exam_id')->toArray();
         $registerableCourseExams = $remainingCourseExams->reject(
        fn ($courseExam) => in_array($courseExam->id, $registeredIds)
    );

        return $registerableCourseExams;
    }
}
