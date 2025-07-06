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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "studentId" => "required|integer|exists:users,id"
        ];
    }

    
    public function passedValidation()
    {
            $user = $this->user();

            if( $user->role == 'student' && $this->studentId !== $user->id ){
                throw new UnauthorizedOperationException('Forbidden: You can only get your own not graded exam registrations.');
            }
    
    }
    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }

}
