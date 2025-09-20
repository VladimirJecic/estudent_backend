<?php
namespace App\estudent\controller\model\requests;


use App\estudent\domain\ports\input\model\SubmitExamRegistrationDTO;
use App\estudent\domain\exceptions\UnauthorizedOperationException;
class SubmitExamRegistrationRequest extends EStudentRequest
{
    public function rules(): array
    {
        return [
            'course-exam-id' => 'required|integer|exists:course_exams,id',
            'student-id'     => 'required|integer|exists:users,id',
        ];
    }
    public function passedValidation()
    {
        $user = $this->user();

        if (null !== $this->input('student-id') && $user->role === 'student' && $this->input('student-id') != $user->id) {
            throw new UnauthorizedOperationException('As student, you can only register exams for yourself.');
        }
    }

    public function toDto(): SubmitExamRegistrationDTO
    {
        return new SubmitExamRegistrationDTO($this->all());
    }




    
}
