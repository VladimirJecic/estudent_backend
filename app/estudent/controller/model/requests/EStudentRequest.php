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

    protected function prepareForValidation()
    {
        // Convert string "true"/"false" to boolean values for all request data
        $data = $this->all();
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                if (strtolower($value) === 'true') {
                    $data[$key] = true;
                } elseif (strtolower($value) === 'false') {
                    $data[$key] = false;
                }
            }
        }
        
        $this->merge($data);
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first(); 
        throw new BadRequestException($message);
    }
}

