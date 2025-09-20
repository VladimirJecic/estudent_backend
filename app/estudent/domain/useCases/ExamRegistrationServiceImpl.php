<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\model\ExamRegistrationFilters;
use App\estudent\domain\exceptions\BadRequestException;
use App\estudent\domain\exceptions\NotFoundException;
use App\estudent\domain\exceptions\UnauthorizedOperationException;
use App\estudent\domain\exceptions\RegistrationNotInProgressException;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\model\ExamRegistration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\estudent\domain\ports\input\ExamRegistrationService;
use App\estudent\domain\ports\input\model\SubmitExamRegistrationDTO;
use App\estudent\domain\ports\input\model\UpdateExamRegistrationDTO;
class ExamRegistrationServiceImpl implements ExamRegistrationService
{

    private readonly ExamPeriodServiceImpl $examPeriodService;
    public function __construct(ExamPeriodServiceImpl $examPeriodService )
    {
        $this->examPeriodService = $examPeriodService;   
    }
    public function getAllExamRegistrationsWithFilters(ExamRegistrationFilters $examRegistrationFilters): LengthAwarePaginator
    {
        $query = ExamRegistration::with(['student', 'courseExam', 'signedBy']);
        $this->applyExamRegistrationFilters($query, $examRegistrationFilters);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(perPage: $examRegistrationFilters->pageSize, page: $examRegistrationFilters->page);
    }

    public function createExamRegistration(SubmitExamRegistrationDTO $dto): ExamRegistration
    {
        $courseExam = CourseExam::with('examPeriod')->find($dto->courseExamId);
        $registerableExamPeriods = $this->examPeriodService->registerable();
        if (!$registerableExamPeriods->contains($courseExam->examPeriod())) {
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

    /**
     * Applies filters to the ExamRegistration query based on the given filters object.
     */
    private function applyExamRegistrationFilters($query, ExamRegistrationFilters $examRegistrationFilters): void
    {
        if ($examRegistrationFilters->studentId) {
            $query->where('student_id', $examRegistrationFilters->studentId);
        }

        $marks = [];
        if ($examRegistrationFilters->includePassed) {
            $marks = array_merge($marks, [6,7,8,9,10]);
        }
        if ($examRegistrationFilters->includeFailed) {
            $marks = array_merge($marks, [5]);
        }
        $query->where(function($q) use ($marks, $examRegistrationFilters) {
            if ($examRegistrationFilters->includeNotGraded) {
                $q->whereIn('mark', $marks)
                  ->orWhereNull('mark');
            } else {
                $q->whereIn('mark', $marks);
            }
        });

        if ($examRegistrationFilters->searchText) {
            $searchText = $examRegistrationFilters->searchText;
            $query->where(function ($q) use ($searchText) {
                $q->whereHas('student', function ($studentQ) use ($searchText) {
                    $studentQ->where('name', 'like', "%$searchText%")
                             ->orWhere('indexNum', 'like', "%$searchText%");
                })
                ->orWhereHas('courseExam.courseInstance.course', function ($courseQ) use ($searchText) {
                    $courseQ->where('name', 'like', "%$searchText%");
                });
            });
        }
    }
}
