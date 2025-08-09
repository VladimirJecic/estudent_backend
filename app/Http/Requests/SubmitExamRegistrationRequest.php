<?php
namespace App\Http\Requests;


use App\Contracts\input\model\SubmitExamRegistrationDTO;
use App\Exceptions\UnauthorizedOperationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;
use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;
class SubmitExamRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }   

    public function rules(): array
    {
        return [
            'courseExamId' => 'required|integer|exists:course_exams,id',
            'studentId'     => 'integer|exists:users,id',
        ];
    }
    public function validated($key = null, $default = null): array
{
    $data = parent::validated();

    // If studentId is not set, default to the authenticated user's ID
    if (!isset($data['studentId'])) {
        $data['studentId'] = $this->user()->id;
    }

    return $data;
}
    public function toDto(): SubmitExamRegistrationDTO
    {
        return new SubmitExamRegistrationDTO($this->validated());
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
    public function passedValidation()
    {
        $user = $this->user();

        if (null !== $this->studentId && $user->role === 'student' && $this->studentId != $user->id) {
            throw new UnauthorizedOperationException('As student, you can only register exams for yourself.');
        }
    }
    
}
