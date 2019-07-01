<?php

namespace App\Services\Employee;


use App\Employee;
use App\UserInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Create employee.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function createEmployee(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $userInfo = UserInfo::create($attributes);
            $userInfo->employee()->create($attributes);

            return $userInfo->employee;
        });
    }

    /**
     * Update employee info and user info.
     *
     * @param array    $attributes
     * @param Employee $employee
     *
     * @return mixed
     */
    public function updateEmployee(array $attributes, Employee $employee)
    {
        return DB::transaction(function () use ($attributes, $employee) {
            $filtered = Arr::except($attributes, ['account_id', 'company_id', 'id']);
            $employee->update($filtered);
            $employee->userInfo()->update($filtered);

            return $employee;
        });
    }

    /**
     * Get employee by id and company id.
     *
     * @param string $id
     * @param string $companyID
     *ap
     * @return mixed
     */
    public function getEmployeeByIdAndCompanyId(string $id, string $companyID)
    {
        return Employee::where(['id' => $id, 'company_id' => $companyID])->first();
    }

    /**
     * Get all employees by company id.
     *
     * @param string $companyID
     *
     * @return mixed
     */
    public function getEmployeesByCompanyId(string $companyID)
    {
        return Employee::where('company_id', $companyID)->paginate(10);
    }
}
