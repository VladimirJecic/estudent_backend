<?php 
namespace App\estudent\domain\useCases;
use App\estudent\domain\ports\input\GetRemainingCourseExams;
use App\estudent\domain\model\ExamRegistration;
use App\estudent\domain\model\ExamPeriod;

use Illuminate\Support\Collection;
class GetRemainingCourseExamsImpl implements GetRemainingCourseExams
{
    public function getRemainingCourseExams(int $examPeriodId):  Collection
    {
        $examPeriod = ExamPeriod::with('exams.courseInstance')->findOrFail($examPeriodId);
        
        $student = auth()->user();
        
        $enrolledCourses = $student->courseInstances()->pluck('id')->toArray();

        $courseExams_where_enrolled = $examPeriod->exams()->get()->reject(fn ($courseExam)=>
            !in_array($courseExam->courseInstance->id,$enrolledCourses)
        );

        $examAttempts = ExamRegistration::with('student','courseExam')->where('student_id', $student->id)->get();

        $successfulAttempts = $examAttempts->count() > 0 ? $examAttempts->whereBetween('mark', [6, 10])->pluck('courseExam.course_instance_id')->toArray() : [];

        $courseExams_remaining =  $courseExams_where_enrolled->reject(fn ($courseExam) => 
            in_array($courseExam->course_instance_id, $successfulAttempts)
        );
        return $courseExams_remaining;
    }
}
