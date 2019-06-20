<?php

namespace App\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class EmployeeValidator
{
    /**
     * Validate create employee
     *
     * @param array $data
     * @param FormRequest $validator
     * @return mixed
     */
    public function employeeValidator(array $data, FormRequest $validator)
    {
        return $this->validateData($data, $validator);
    }

    /**
     * Validate data
     *
     * @param array $data
     * @param FormRequest $validator
     * @return array
     */
    public function validateData(array $data, FormRequest $validator)
    {
        $validator = Validator::make($data, $validator->rules(), $validator->messages());
        return $validator->fails() ? $validator->errors()->messages() : [];
    }
}
