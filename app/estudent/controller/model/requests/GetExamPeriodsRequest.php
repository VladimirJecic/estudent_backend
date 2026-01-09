<?php

namespace App\estudent\controller\model\requests;

use App\estudent\domain\ports\input\model\ExamPeriodFilters;

class GetExamPeriodsRequest extends EStudentRequest
{

    public function rules(): array
    {
        return [
            'only-active' => ['nullable', 'boolean'],
        ];
    }
    

    public function toDto(): ExamPeriodFilters
    {
        return new ExamPeriodFilters($this->all());
    }
   

}
