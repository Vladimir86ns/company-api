<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateCreateEmployee;
use App\Http\Requests\ValidateUpdateEmployee;
use App\Services\Employee\EmployeeService;
use App\Transformers\Employee\EmployeeTransformer;
use App\Validators\CompanyValidator;
use App\Validators\EmployeeValidator;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;

class EmployeeController extends Controller
{
    /**
     * @var \App\Validators\UserValidator
     */
    protected $validator;

    /**
     * @var \App\Validators\CompanyValidator
     */
    protected $companyValidator;

    /**
     * @var EmployeeService
     */
    protected $service;

    /**
     * @var EmployeeTransformer
     */
    protected $transformer;

    /**
     * EmployeeController constructor.
     *
     * @param EmployeeValidator   $validator
     * @param CompanyValidator    $companyValidator
     * @param EmployeeService     $employeeService
     * @param EmployeeTransformer $employeeTransformer
     */
    public function __construct(
        EmployeeValidator $validator,
        CompanyValidator $companyValidator,
        EmployeeService $employeeService,
        EmployeeTransformer $employeeTransformer
    ) {
        $this->validator = $validator;
        $this->companyValidator = $companyValidator;
        $this->service = $employeeService;
        $this->transformer = $employeeTransformer;
    }

    /**
     * Get employee by id and company id.
     *
     * @param $id
     * @param $companyId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getEmployee($id, $companyId)
    {
        $employee = $this->validator->getAndValidateEmployeeIdAndCompanyId($id, $companyId);

        return response([ 'data' => $this->transformer->transform($employee)],Response::HTTP_OK);
    }

    /**
     * Create employee.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->employeeValidator($inputs, new ValidateCreateEmployee($inputs));

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $this->companyValidator->getAndValidateCompanyAndAccountId($inputs['company_id'], $inputs['account_id']);

        try {
            $employee = $this->service->createEmployee($inputs);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Something went wrong, try again later!');
        }

        return response(['data' => $this->transformer->transform($employee)],Response::HTTP_OK);
    }

    /**
     * Update employee.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->employeeValidator($inputs, new ValidateUpdateEmployee($inputs));

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $this->companyValidator->getAndValidateCompanyAndAccountId($inputs['company_id'], $inputs['account_id']);
        $employee = $this->validator->getAndValidateEmployeeIdAndCompanyId($inputs['id'], $inputs['company_id']);

        try {
            $employee = $this->service->updateEmployee($inputs, $employee);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Something went wrong, try again later!');
        }

        return response(['data' => $this->transformer->transform($employee)],Response::HTTP_OK);
    }

    /**
     * Delete employee, with soft delete.
     *
     * @param $accountId
     * @param $companyId
     * @param $employeeId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete($accountId, $companyId, $employeeId)
    {
        $this->companyValidator->getAndValidateCompanyAndAccountId($companyId, $accountId);
        $employee = $this->validator->getAndValidateEmployeeIdAndCompanyId($employeeId, $companyId);
        $employee->delete();

        return response(['data' => []],Response::HTTP_OK);
    }
}
