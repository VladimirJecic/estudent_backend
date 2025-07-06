<?php
namespace App\Http\Requests;


use App\Contracts\input\model\ExamRegistrationStoreDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;
use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;
class StoreExamRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }   

    public function rules(): array
    {
        return [
            'course_exam_id' => 'required|integer|exists:course_exams,id',
            'student_id'     => 'required|integer|exists:users,id',
        ];
    }
    public function toDto(): ExamRegistrationStoreDTO
    {
        return new ExamRegistrationStoreDTO($this->validated());
    }


    public function passedValidation()
    {
        $user = $this->user();

        if ($user->role === 'student' && $this->student_id != $user->id) {
            $validator = ValidatorFacade::make([], []);
            $validator->errors()->add('student_id', 'As student, you can only register exams for yourself.');

            throw new ValidationException($validator);
        }
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
}
