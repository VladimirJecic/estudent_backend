<?php

namespace App\estudent\controller\model\requests;

class GetRemainingCourseExamsRequest extends EStudentRequest
{

    public function rules(): array
    {
        return [
            "for-exam-period-id" => "required|exists:exam_periods,id"
        ];
    }


}
