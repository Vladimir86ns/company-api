<?php

namespace App\Validators;

use Symfony\Component\HttpFoundation\Response;
use Validator;

class UserValidator
{
    /**
     * Validate create user
     *
     * @param array $data
     * @param $validator
     * @return mixed
     */
    public function userCreateValidator(array $data, $validator)
    {
        return $this->validateData($data, $validator);
    }

    /**
     * Validate data
     *
     * @param array $data
     * @param $validator
     * @return mixed
     */
    public function validateData(array $data, $validator)
    {
        $validator = Validator::make($data, $validator->rules(), $validator->messages());
        return $validator->fails() ? $validator->errors()->messages() : [];
    }
}
