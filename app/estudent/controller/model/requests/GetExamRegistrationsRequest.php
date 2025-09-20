<?php

namespace App\estudent\controller\model\requests;

use App\estudent\domain\ports\input\model\ExamRegistrationFilters;
class GetExamRegistrationsRequest extends EStudentRequest
{

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer'],
            'page-size' => ['nullable', 'integer'],
            'search-text' => ['nullable', 'string'],
            'exclude-passed' => ['nullable', 'boolean'],
            'exclude-failed' => ['nullable', 'boolean'],
            'student-id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
    

    public function toDto(): ExamRegistrationFilters
    {
        return new ExamRegistrationFilters($this->all());
    }
   

}
