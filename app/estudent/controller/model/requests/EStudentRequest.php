<?php

namespace App\estudent\controller\model\requests;

use Illuminate\Foundation\Http\FormRequest;
use App\estudent\domain\exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Validator;


abstract class EStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        return $this->all();
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
}
