<?php 
namespace App\Services;
use App\Models\ExamRegistration;
use App\Contracts\input\GetNotGradedExamRegistrations;
use App\Models\User;

use Illuminate\Support\Collection;
class GetNotGradedRegistrationsServiceImpl implements GetNotGradedExamRegistrations
{
    public function getAllForAdmin(): Collection
    {
        $admin= auth()->user();
        $adminCourses = $admin->courseIntances()->pluck('id')->toArray();
        $examRegistrations = ExamRegistration::with(
            'student',
            'courseExam.examPeriod',
            'signedBy',
            'courseExam.courseInstance'
        )
        ->whereNull('signed_by_id')
        ->whereHas('courseExam.courseInstance', function ($query) use ($adminCourses) {
            $query->whereIn('id', $adminCourses);
        })
        ->get();
        return $examRegistrations;
    }
    public function getAllForStudentId(int $studentId): Collection{
        $student = User::find($studentId);

        $userRegistrations = ExamRegistration::with('student','courseExam.examPeriod','signedBy')->where('student_id', $student->id)->get();
        $notGradedRegistrations = $userRegistrations->where('signed_by_id','=', null);

        return $notGradedRegistrations;
    }
}
