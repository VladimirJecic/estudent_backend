<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;

class GetRemainingCourseExamsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "for-exam-period-id" => "required|exists:exam_periods,id"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }

    public function validationData()
    {
        return $this->all();
    }
}
