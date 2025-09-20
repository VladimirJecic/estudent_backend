<?php

namespace App\estudent\controller\model\requests;

use Illuminate\Foundation\Http\FormRequest;
use App\estudent\domain\exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;


class CourseExamReportRequest extends EStudentRequest
{

    public function rules(): array
    {
        return [
            "course-exam-id"=> "required|integer|exists:course_exams,id",
        ];
    }
}
