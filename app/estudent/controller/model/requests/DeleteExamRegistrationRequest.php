<?php
namespace App\estudent\controller\model\requests;

use App\estudent\domain\exceptions\BadRequestException;
use App\estudent\domain\exceptions\UnauthorizedOperationException;
use App\estudent\domain\exceptions\ExamRegistrationNotFoundException;
use App\estudent\domain\model\ExamRegistration;

class DeleteExamRegistrationRequest extends EStudentRequest
{
    public function rules(): array
    {
        return [
            'examRegistrationId' => 'required|integer|exists:exam_registrations,id',
        ];
    }

}