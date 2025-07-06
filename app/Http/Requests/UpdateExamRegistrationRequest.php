<?php

namespace App\Http\Requests;
use App\Contracts\input\model\ExamRegistrationUpdateDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;

class UpdateExamRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            "id"=> "required|integer|exists:exam_registrations,id",
            'mark' => 'nullable|integer',
            'comment' => 'nullable|string',
            'hasAttended' => 'required|boolean',
        ];
    }
    public function toDTO()
    {
        return new ExamRegistrationUpdateDTO($this->validated());
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
}
