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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            "examRegistrationId" => "required|integer|exists:exam_registrations,id",
            'mark' => 'nullable|integer',
            'comment' => 'nullable|string',
            'hasAttended' => 'nullable|boolean',
        ];
    }
    public function toDTO(): ExamRegistrationUpdateDTO
    {
        return new ExamRegistrationUpdateDTO($this->validated());
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
    public function validationData()
    {
        return array_merge($this->all(), [
            'examRegistrationId' => $this->route('examRegistrationId'),
        ]);
    }
    
}
