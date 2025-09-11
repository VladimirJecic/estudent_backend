<?php 
namespace App\Services;

use App\Contracts\input\model\ExamRegistrationFilters;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedOperationException;
use App\Exceptions\RegistrationNotInProgressException;
use App\Models\CourseExam;
use App\Models\ExamRegistration;
use App\Contracts\input\ExamRegistrationService;
use App\Contracts\input\model\SubmitExamRegistrationDTO;
use App\Contracts\input\model\UpdateExamRegistrationDTO;
use Illuminate\Support\Collection;
class ExamRegistrationServiceImpl implements ExamRegistrationService
{

    private readonly ExamPeriodServiceImpl $examPeriodService;
    public function __construct(ExamPeriodServiceImpl $examPeriodService )
    {
        $this->examPeriodService = $examPeriodService;   
    }
    public function getAllExamRegistrationsWithFilters(ExamRegistrationFilters $dto): Collection 
    {
        $userRegistrations = ExamRegistration::with('student','courseExam','signedBy')->where('student_id', $dto->studentId)->get();
        $signedUserRegistrations = $userRegistrations->where('signed_by_id','<>', null);
        $marks = [];
        if(!$dto->excludePassed){
            $marks = array_merge($marks, [6,7,8,9,10]);
        }
        if(!$dto->excludeFailed){
            $marks = array_merge($marks, [5]);
        }
        $wantedRegistrations = $signedUserRegistrations->count() > 0 ? $signedUserRegistrations->whereIn('mark', $marks) : [];

        foreach($wantedRegistrations as $er){
                $er->courseExam->load('examPeriod');
        }
        return $wantedRegistrations;
    }

    public function saveExamRegistration(SubmitExamRegistrationDTO $dto): ExamRegistration
    {
        $courseExam = CourseExam::with('examPeriod')->find($dto->courseExamId);
        $registerableExamPeriods = $this->examPeriodService->registerable();
        if (!$registerableExamPeriods->contains($courseExam->examPeriod)) {
            throw new RegistrationNotInProgressException('Exam registration is not in progress for this exam period.');
        }
        $exists = ExamRegistration::where([
            ['course_exam_id', '=', $dto->courseExamId],
            ['student_id', '=', $dto->studentId],
        ])->exists();

        if ($exists) {
            throw new  BadRequestException('Exam_registration already exists for this student and course_exam.');
        }
        return ExamRegistration::create([
            'course_exam_id' => $dto->courseExamId,
            'student_id'     => $dto->studentId,
            'hasAttended'    => false,
        ]);

       
    }

    public function updateExamRegistration(int $id, UpdateExamRegistrationDTO $dto): ExamRegistration
    {
        $examRegistration = ExamRegistration::find($id);

        if (!$examRegistration) {
            throw new NotFoundException('ExamRegistration with ID ' . $id . ' not found.');
        }

        $user = auth()->user();
        $examRegistration->mark = $dto->mark;
        $examRegistration->comment = $dto->comment;
        $examRegistration->signed_by_id = $user->id;
        $examRegistration->hasAttended = $dto->hasAttended;
        $examRegistration->save();
        return $examRegistration;
    }



    public function deleteExamRegistration(int $id): bool
    {
        $registration = ExamRegistration::find($id);
        if (!$registration) {
            throw new NotFoundException('ExamRegistration with ID ' . $id . ' not found.');
        }
        $user = auth()->user();
        if ($user->role === 'student' && $registration->student_id != $user->id) {
            throw new UnauthorizedOperationException('Forbidden: You can only delete your own exam registrations.');
        }

        return $registration->delete();
    }
}
