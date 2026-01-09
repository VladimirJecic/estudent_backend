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
            'include-passed' => ['nullable', 'boolean'],
            'include-failed' => ['nullable', 'boolean'],
            'include-current' => ['nullable', 'boolean'],
            'student-id' => ['nullable', 'integer', 'exists:users,id'],
            'exam-period-id' => ['nullable', 'integer', 'exists:exam_periods,id'],
            'course-exam-id' => ['nullable', 'integer', 'exists:course_exams,id'],
        ];
    }
    

    public function toDto(): ExamRegistrationFilters
    {
        return new ExamRegistrationFilters($this->all());
    }
   

}
