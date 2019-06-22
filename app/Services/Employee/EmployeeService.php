<?php

namespace App\Services\Employee;


use App\Employee;
use App\UserInfo;
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
        return Employee::where('company_id', $companyID)->get();
    }
}
