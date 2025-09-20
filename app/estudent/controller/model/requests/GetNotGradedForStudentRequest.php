<?php

namespace App\estudent\controller\model\requests;
use App\estudent\domain\exceptions\UnauthorizedOperationException;
class GetNotGradedForStudentRequest extends EStudentRequest
{

    public function rules(): array
    {
        return [
                "student-id" => "required|integer|exists:users,id"
        ];
    }

    
    public function passedValidation()
    {
            $user = $this->user();
            $studentId = $this->input("student-id");
            if( $user->role == 'student' && $studentId != $user->id ){
                throw new UnauthorizedOperationException('Forbidden: You can only get your own not graded exam registrations.');
            }
    
    }
    public function getStudentId(): int
    {
        return $this->input('student-id');
    }

}
