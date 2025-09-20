<?php

namespace App\estudent\controller\model\requests;
use App\estudent\domain\ports\input\model\UpdateExamRegistrationDTO;

class UpdateExamRegistrationRequest extends EStudentRequest
{
    public function rules()
    {
        return [
            "exam-registration-id" => "required|integer|exists:exam_registrations,id",
            'mark' => 'nullable|integer',
            'comment' => 'nullable|string',
            'has-attended' => 'nullable|boolean',
        ];
    }
    public function toDTO(): UpdateExamRegistrationDTO
    {
        return new UpdateExamRegistrationDTO($this->all());
    }
}
