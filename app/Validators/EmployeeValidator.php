<?php

namespace App\Validators;

use App\Services\Employee\EmployeeService;
use Dingo\Api\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class EmployeeValidator
{
    /**
     * @var EmployeeService
     */
    protected $service;

    /**
     * EmployeeValidator constructor.
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->service = $employeeService;
    }

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

    /**
     * Validate and return employee.
     *
     * @param string $id
     * @param string $companyId
     *
     * @return mixed|void
     */
    public function getAndValidateEmployeeIdAndCompanyId(string $id, string $companyId)
    {
        $employee = $this->service->getEmployeeByIdAndCompanyId($id, $companyId);

        if (!$employee) {
            return abort(Response::HTTP_NOT_FOUND, 'User not found!');
        }

        return $employee;
    }
}
