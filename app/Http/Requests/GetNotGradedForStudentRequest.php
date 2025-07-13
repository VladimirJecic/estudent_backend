<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\UnauthorizedOperationException;
use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;
class GetNotGradedForStudentRequest extends FormRequest
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
                "studentId" => "nullable|integer|exists:users,id"
        ];
    }

    
    public function passedValidation()
    {
            $user = $this->user();
            $studentIdProvided = $this->has('studentId');
            if( $studentIdProvided && $user->role == 'student' && $this->studentId != $user->id ){
                throw new UnauthorizedOperationException('Forbidden: You can only get your own not graded exam registrations.');
            }
            if( $studentIdProvided && $user->role == 'admin' ){
                throw new BadRequestException('Student ID is required for admin requests.');
            }
    
    }
    public function getStudentId(): ?int
    {
        return $this->input('studentId') ?? $this->user()->id;
    }
    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
    public function validationData()
    {
        return array_merge($this->all(), [
            'studentId' => $this->route('studentId'),
        ]);
    }

}
