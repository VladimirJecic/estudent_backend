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
       
        return array_merge($this->all(), $this->route()->parameters());
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
}
