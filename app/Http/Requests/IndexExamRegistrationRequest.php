<?php

namespace App\Http\Requests;

use App\Exceptions\BadRequestException;
use App\Contracts\input\model\ExamRegistrationFilters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
class IndexExamRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'excludePassed' => ['nullable', 'boolean'],
            'excludeFailed' => ['nullable', 'boolean'],
            'studentId' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
    public function passedValidation()
    {
            $user = $this->user();

            if ($user->role === 'admin' && !$this->studentId) {
                throw new BadRequestException('student_id is required for admins');
            }
    
    }
    
    public function validated($key = null, $default = null): array
    {
        return [
            'excludePassed' => $this->getExcludePassed(),
            'excludeFailed' => $this->getExcludeFailed(),
            'studentId' => $this->getStudentId(),
        ];
    }

    public function toDto(): ExamRegistrationFilters
    {
        return new ExamRegistrationFilters($this->validated());
    }


    private function getStudentId(): ?int
    {
        $user = $this->user();

        if ($user->role === 'student') {
            return $user->id;
        }

        return $this->input('studentId');
    }

    private function getExcludePassed(): bool
    {
        return filter_var($this->input('excludePassed', false), FILTER_VALIDATE_BOOLEAN);
    }

    private function getExcludeFailed(): bool
    {
        return filter_var($this->input('excludeFailed', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }


}
